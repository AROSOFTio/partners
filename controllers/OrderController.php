<?php
require_once __DIR__ . '/../models/Package.php';
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
        $selectedPackages = Package::findByIds($selectedIds);
        $errors = [];

        if (is_post()) {
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
}
