<?php
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/Validator.php';

class OrderController
{
    public function request()
    {
        $allPackages = Package::allActive();
        $selectedIds = $_POST['packages'] ?? ($_GET['packages'] ?? []);
        if (!is_array($selectedIds)) {
            $selectedIds = [$selectedIds];
        }
        $selectedIds = array_filter(array_map('intval', $selectedIds));
        $isPost = is_post();
        if ($isPost) {
            csrf_verify();
        }

        // Handle custom package creation
        if ($isPost) {
            $customName = Validator::sanitize($_POST['custom_name'] ?? '');
            $customPrice = (float)($_POST['custom_price'] ?? 0);
            $customDescription = Validator::sanitize($_POST['custom_description'] ?? '');
            $customDelivery = Validator::sanitize($_POST['custom_delivery_time'] ?? '');
            $customDuration = (int)($_POST['custom_duration'] ?? 0);
            if ($customName && $customPrice > 0) {
                $categoryId = !empty($allPackages) ? (int)$allPackages[0]['category_id'] : 1;
                $slug = $this->slugify($customName . '-' . uniqid());
                $displayCurrency = get_display_currency();
                $baseCurrency = config_value('currency.base', 'UGX');
                $customBasePrice = convert_amount($customPrice, $displayCurrency, $baseCurrency);
                $packageId = Package::create([
                    'category_id' => $categoryId,
                    'name' => $customName,
                    'slug' => $slug,
                    'short_description' => $customDescription ?: 'Custom collaboration package.',
                    'full_description' => $customDescription ?: 'Custom collaboration package tailored to your needs.',
                    'base_price' => $customBasePrice,
                    'currency' => $baseCurrency,
                    'duration_minutes' => $customDuration ?: 5,
                    'allow_deposit' => 0,
                    'deposit_percentage' => 0,
                    'delivery_time_text' => $customDelivery ?: 'As agreed',
                    'is_active' => 0, // hide from public list, tied to this order
                ]);
                $selectedIds[] = $packageId;
                // Refresh packages list to include the custom one (inactive but selected)
                $allPackages = Package::all(); // include inactive
            }
        }

        $selectedPackages = Package::findByIds($selectedIds, true);
        $errors = [];

        if ($isPost) {
            $data = [
                'customer_name' => Validator::sanitize($_POST['customer_name'] ?? ''),
                'customer_email' => Validator::sanitize($_POST['customer_email'] ?? ''),
                'company_name' => Validator::sanitize($_POST['company_name'] ?? ''),
                'website_url' => Validator::sanitize($_POST['website_url'] ?? ''),
                'brief' => Validator::sanitize($_POST['brief'] ?? ''),
                'preferred_timeline' => Validator::sanitize($_POST['preferred_timeline'] ?? ''),
                'payment_type' => $_POST['payment_type'] ?? 'full',
            ];

            $errors = Validator::validateOrderRequest($data, $selectedPackages);

            $totalAmount = 0;
            $depositAmount = 0;
            $currency = $selectedPackages ? $selectedPackages[0]['currency'] : 'UGX';
            $depositAllowed = true;
            foreach ($selectedPackages as $pkg) {
                $totalAmount += (float)$pkg['base_price'];
                if (empty($pkg['allow_deposit'])) {
                    $depositAllowed = false;
                } else {
                    $depositAmount += (float)$pkg['base_price'] * ((float)$pkg['deposit_percentage'] / 100);
                }
            }

            if ($data['payment_type'] === 'deposit' && !$depositAllowed) {
                $data['payment_type'] = 'full';
                $errors[] = 'Deposit not available for the selected combination of packages.';
            }

            $amountDue = $data['payment_type'] === 'deposit' ? $depositAmount : $totalAmount;
            $items = [];
            foreach ($selectedPackages as $pkg) {
                $items[] = [
                    'package_id' => $pkg['id'],
                    'package_name_snapshot' => $pkg['name'],
                    'unit_price' => $pkg['base_price'],
                    'quantity' => 1,
                    'line_total' => $pkg['base_price'],
                ];
            }

            if (empty($errors)) {
                $order = Order::create([
                    'customer_name' => $data['customer_name'],
                    'customer_email' => $data['customer_email'],
                    'company_name' => $data['company_name'],
                    'website_url' => $data['website_url'],
                    'brief' => $data['brief'],
                    'preferred_timeline' => $data['preferred_timeline'],
                    'payment_type' => $data['payment_type'],
                    'total_amount' => $totalAmount,
                    'amount_due_now' => $amountDue,
                    'currency' => $currency,
                ], $items);

                if ($order) {
                    redirect('/checkout?order=' . urlencode($order['order_code']));
                } else {
                    $errors[] = 'Unable to create your order. Please try again later.';
                }
            }
        }

        view('orders/request', [
            'allPackages' => $allPackages,
            'selectedPackages' => $selectedPackages,
            'selectedIds' => $selectedIds,
            'errors' => $errors,
        ]);
    }

    public function checkout()
    {
        $orderCode = $_GET['order'] ?? null;
        if (!$orderCode) {
            redirect('/packages');
        }
        $order = Order::findByCode($orderCode);
        if (!$order) {
            redirect('/packages');
        }
        $items = Order::findItems((int)$order['id']);
        view('orders/checkout', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}
