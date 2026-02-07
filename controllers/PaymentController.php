<?php
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/Mailer.php';
require_once __DIR__ . '/../config/pesapal.php';

class PaymentController
{
    public function create()
    {
        if (is_post()) {
            csrf_verify();
        }
        $orderCode = $_POST['order_code'] ?? ($_GET['order'] ?? null);
        if (!$orderCode) {
            redirect('/packages');
        }
        $order = Order::findByCode($orderCode);
        if (!$order) {
            view('payments/error', ['message' => 'Order not found.']);
            return;
        }
        if ($order['status'] !== 'pending_payment') {
            redirect('/payment/complete?order=' . urlencode($order['order_code']));
        }

        try {
            $paymentRequest = createPesapalPaymentRequest($order);
            Order::updatePesapalReferences((int)$order['id'], $paymentRequest['merchant_reference'], $paymentRequest['tracking_id']);
            $iframeUrl = $paymentRequest['iframe_url'];

            if (config_value('pesapal.demo', false)) {
                // In demo mode, simulate an instant callback
                $iframeUrl = base_url('payment/callback') . '?order=' . urlencode($order['order_code']) . '&pesapal_transaction_tracking_id=' . urlencode($paymentRequest['tracking_id']) . '&reference=' . urlencode($paymentRequest['merchant_reference']) . '&status=COMPLETED';
            }

            redirect($iframeUrl ?: '/payments/error');
        } catch (Exception $e) {
            view('payments/error', ['message' => 'Payment initialization failed: ' . $e->getMessage()]);
        }
    }

    public function callback()
    {
        $trackingId = $_REQUEST['pesapal_transaction_tracking_id'] ?? ($_REQUEST['OrderTrackingId'] ?? ($_REQUEST['orderTrackingId'] ?? ''));
        $reference = $_REQUEST['reference'] ?? ($_REQUEST['pesapal_merchant_reference'] ?? ($_REQUEST['OrderMerchantReference'] ?? ($_REQUEST['orderMerchantReference'] ?? '')));
        $orderCode = $_REQUEST['order'] ?? $reference;
        $demo = (bool)config_value('pesapal.demo', true);
        if (!$orderCode) {
            view('payments/error', ['message' => 'Missing order reference.']);
            return;
        }
        if (!$demo && !$trackingId) {
            view('payments/error', ['message' => 'Missing payment tracking ID.']);
            return;
        }

        $order = Order::findByCode($orderCode);
        if (!$order) {
            view('payments/error', ['message' => 'Order not found.']);
            return;
        }
        if (!$demo) {
            if (empty($order['pesapal_transaction_tracking_id'])) {
                view('payments/error', ['message' => 'Payment tracking not initialized.']);
                return;
            }
            if (!hash_equals((string)$order['pesapal_transaction_tracking_id'], (string)$trackingId)) {
                view('payments/error', ['message' => 'Payment tracking mismatch.']);
                return;
            }
        }

        try {
            $verification = verifyPesapalPayment($trackingId, $orderCode);
        } catch (Exception $e) {
            $verification = ['status' => 'pending'];
        }

        // Normalize status codes returned by Pesapal or callback
        $statusRaw = strtolower($verification['status'] ?? ($verification['payment_status_description'] ?? 'pending'));
        $statusCode = strtolower((string)($verification['status_code'] ?? $verification['response_code'] ?? ''));
        $paymentMethod = $verification['payment_method'] ?? null;

        $paymentType = $order['payment_type'] === 'deposit' ? 'deposit' : 'full';
        $amount = $order['amount_due_now'];
        $successStatuses = ['completed', 'successful', 'success', 'paid', 'payment_completed', 'complete'];
        $failureStatuses = ['failed', 'cancelled', 'canceled', 'denied', 'error'];

        if (in_array($statusRaw, $successStatuses, true) || $statusCode === '00') {
            $paymentStatus = 'successful';
        } elseif (in_array($statusRaw, $failureStatuses, true)) {
            $paymentStatus = 'failed';
        } else {
            $paymentStatus = 'pending';
        }

        // Prefer Pesapal reported amount/currency if provided
        if (!empty($verification['amount'])) {
            $amount = (float)$verification['amount'];
        }
        if (!empty($verification['currency'])) {
            $order['currency'] = $verification['currency'];
        }

        Payment::create([
            'order_id' => (int)$order['id'],
            'amount' => $amount,
            'currency' => $order['currency'],
            'payment_type' => $paymentType,
            'provider' => 'pesapal',
            'status' => $paymentStatus,
            'pesapal_transaction_tracking_id' => $trackingId,
            'pesapal_payment_method' => $paymentMethod,
        ]);

        if ($paymentStatus === 'successful') {
            // If the amount covers full total, mark paid_full even for deposit orders
            $newStatus = ($amount + 0.0001) >= (float)$order['total_amount'] ? 'paid_full' : ($order['payment_type'] === 'deposit' ? 'deposit_paid' : 'paid_full');
            Order::updateStatus((int)$order['id'], $newStatus);
            $this->sendPaymentEmails($order, $amount, $paymentType);
        }

        redirect('/payment/complete?order=' . urlencode($order['order_code']) . '&status=' . $paymentStatus);
    }

    public function complete()
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
        $status = $order['status'];
        view('orders/complete', [
            'order' => $order,
            'items' => $items,
            'status' => $status,
        ]);
    }

    private function sendPaymentEmails(array $order, float $amount, string $paymentType): void
    {
        $mailer = new Mailer(config_value('mail', []));
        $items = Order::findItems((int)$order['id']);

        $listItems = '';
        foreach ($items as $item) {
            $listItems .= '<li>' . e($item['package_name_snapshot']) . ' - ' . format_money($item['unit_price'], $order['currency']) . '</li>';
        }

        $clientBody = '<p>Hi ' . e($order['customer_name']) . ',</p>';
        $clientBody .= '<p>We have received your payment for collaboration request ' . e($order['order_code']) . '.</p>';
        $clientBody .= '<p>Amount paid: ' . format_money($amount, $order['currency']) . ' (' . ucfirst($paymentType) . ')</p>';
        $clientBody .= '<p>Packages:</p><ul>' . $listItems . '</ul>';
        $clientBody .= '<p>We will review your brief and get back to you with next steps.</p>';
        $clientBody .= '<p>- BenTech / AROSOFT Innovations Ltd</p>';

        $mailer->send($order['customer_email'], 'Your BenTech Collaboration Payment', $clientBody);

        $adminEmail = config_value('mail.admin_to', config_value('mail.from_email', 'admin@example.com'));
        $adminBody = '<p>New payment received for order ' . e($order['order_code']) . '.</p>';
        $adminBody .= '<p>Client: ' . e($order['customer_name']) . ' (' . e($order['customer_email']) . ')</p>';
        $adminBody .= '<p>Amount: ' . format_money($amount, $order['currency']) . ' (' . $paymentType . ')</p>';
        $adminBody .= '<p>Packages:</p><ul>' . $listItems . '</ul>';
        $adminBody .= '<p>View order: ' . base_url('admin/orders/view?id=' . urlencode($order['id'])) . '</p>';

        $mailer->send($adminEmail, 'New Collaboration Payment - ' . $order['order_code'], $adminBody);
    }
}
