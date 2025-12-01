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
        if (!$orderCode) {
            view('payments/error', ['message' => 'Missing order reference.']);
            return;
        }

        $order = Order::findByCode($orderCode);
        if (!$order) {
            view('payments/error', ['message' => 'Order not found.']);
            return;
        }

        try {
            $verification = verifyPesapalPayment($trackingId, $orderCode);
        } catch (Exception $e) {
            view('payments/error', ['message' => 'Payment verification failed: ' . $e->getMessage()]);
            return;
        }

        $status = strtolower($verification['status'] ?? 'failed');
        $paymentMethod = $verification['payment_method'] ?? null;

        $paymentType = $order['payment_type'] === 'deposit' ? 'deposit' : 'full';
        $amount = $order['amount_due_now'];
        $paymentStatus = $status === 'completed' || $status === 'successful' ? 'successful' : 'failed';

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
            $newStatus = $order['payment_type'] === 'deposit' ? 'deposit_paid' : 'paid_full';
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
        $status = $_GET['status'] ?? $order['status'];
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

        $adminEmail = config_value('mail.from_email', 'admin@example.com');
        $adminBody = '<p>New payment received for order ' . e($order['order_code']) . '.</p>';
        $adminBody .= '<p>Client: ' . e($order['customer_name']) . ' (' . e($order['customer_email']) . ')</p>';
        $adminBody .= '<p>Amount: ' . format_money($amount, $order['currency']) . ' (' . $paymentType . ')</p>';
        $adminBody .= '<p>Packages:</p><ul>' . $listItems . '</ul>';
        $adminBody .= '<p>View order: ' . base_url('admin/orders/view?id=' . urlencode($order['id'])) . '</p>';

        $mailer->send($adminEmail, 'New Collaboration Payment - ' . $order['order_code'], $adminBody);
    }
}
