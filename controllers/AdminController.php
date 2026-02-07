<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/Validator.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/PortfolioItem.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../config/database.php';

class AdminController
{
    public function login()
    {
        if (isset($_SESSION['admin_user'])) {
            redirect('/admin');
        }

        $error = null;
        if (is_post()) {
            csrf_verify();
            $email = Validator::sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $admin = Admin::findByEmail($email);
            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_user'] = [
                    'id' => $admin['id'],
                    'name' => $admin['name'],
                    'email' => $admin['email'],
                ];
                redirect('/admin');
            } else {
                $error = 'Invalid credentials';
            }
        }

        view('admin/login', ['error' => $error]);
    }

    public function logout()
    {
        session_destroy();
        redirect('/admin/login');
    }

    public function dashboard()
    {
        require_admin_auth();
        $pdo = getPDO();
        $stats = [
            'orders' => (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'paid' => (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status IN ('deposit_paid','paid_full')")->fetchColumn(),
            'pending' => (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending_payment'")->fetchColumn(),
            'packages' => (int)$pdo->query("SELECT COUNT(*) FROM packages WHERE is_active = 1")->fetchColumn(),
        ];
        $recentOrders = $pdo->query('SELECT id, order_code, customer_name, status, total_amount, currency, created_at FROM orders ORDER BY created_at DESC LIMIT 5')->fetchAll();
        view('admin/dashboard', ['stats' => $stats, 'recentOrders' => $recentOrders]);
    }

    public function packages()
    {
        require_admin_auth();
        $packages = Package::all();
        view('admin/packages', ['packages' => $packages]);
    }

    public function editPackage()
    {
        require_admin_auth();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $package = $id ? Package::findById($id) : null;
        $categories = Category::allActive();
        $error = null;

        if (is_post()) {
            csrf_verify();
            $data = [
                'category_id' => (int)($_POST['category_id'] ?? 1),
                'name' => Validator::sanitize($_POST['name'] ?? ''),
                'slug' => Validator::sanitize($_POST['slug'] ?? ''),
                'short_description' => Validator::sanitize($_POST['short_description'] ?? ''),
                'full_description' => Validator::sanitize($_POST['full_description'] ?? ''),
                'base_price' => (float)($_POST['base_price'] ?? 0),
                'currency' => Validator::sanitize($_POST['currency'] ?? 'UGX'),
                'duration_minutes' => (int)($_POST['duration_minutes'] ?? 0),
                'allow_deposit' => isset($_POST['allow_deposit']) ? 1 : 0,
                'deposit_percentage' => (float)($_POST['deposit_percentage'] ?? 0),
                'delivery_time_text' => Validator::sanitize($_POST['delivery_time_text'] ?? ''),
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
            ];
            if (!$data['slug']) {
                $data['slug'] = $this->slugify($data['name']);
            }
            if (!$data['name'] || !$data['base_price']) {
                $error = 'Name and price are required.';
            } else {
                if ($package) {
                    Package::update($package['id'], $data);
                } else {
                    Package::create($data);
                }
                redirect('/admin/packages');
            }
        }

        view('admin/edit_package', [
            'package' => $package,
            'categories' => $categories,
            'error' => $error,
        ]);
    }

    public function orders()
    {
        require_admin_auth();
        $status = $_GET['status'] ?? null;
        $orders = Order::listAll();
        if ($status) {
            $orders = array_filter($orders, fn($o) => $o['status'] === $status);
        }
        view('admin/orders', ['orders' => $orders, 'status' => $status]);
    }

    public function viewOrder()
    {
        require_admin_auth();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $order = Order::findById($id);
        if (!$order) {
            redirect('/admin/orders');
        }
        $items = Order::findItems($id);
        $payments = Payment::findByOrder($id);
        view('admin/view_order', [
            'order' => $order,
            'items' => $items,
            'payments' => $payments,
        ]);
    }

    public function portfolio()
    {
        require_admin_auth();
        $items = PortfolioItem::all();
        $error = null;

        if (is_post()) {
            csrf_verify();
            if (!empty($_POST['delete_id'])) {
                $deleteId = (int)$_POST['delete_id'];
                PortfolioItem::delete($deleteId);
                redirect('/admin/portfolio');
            }
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $data = [
                'title' => Validator::sanitize($_POST['title'] ?? ''),
                'brand_name' => Validator::sanitize($_POST['brand_name'] ?? ''),
                'youtube_url' => Validator::sanitize($_POST['youtube_url'] ?? ''),
                'collab_type' => Validator::sanitize($_POST['collab_type'] ?? ''),
                'short_description' => Validator::sanitize($_POST['short_description'] ?? ''),
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            ];

            if (!$data['title'] || !$data['youtube_url']) {
                $error = 'Title and YouTube URL are required.';
            } else {
                if ($id) {
                    PortfolioItem::update($id, $data);
                } else {
                    PortfolioItem::create($data);
                }
                redirect('/admin/portfolio');
            }
        }

        view('admin/portfolio', ['items' => $items, 'error' => $error]);
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}
