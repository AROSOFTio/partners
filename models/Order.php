<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/OrderItem.php';

class Order
{
    public static function generateOrderCode(): string
    {
        $seed = microtime(true) * 10000;
        return 'COLL' . date('Y') . '-' . str_pad((string)($seed % 10000), 4, '0', STR_PAD_LEFT);
    }

    public static function create(array $data, array $items): ?array
    {
        $pdo = getPDO();
        try {
            $pdo->beginTransaction();
            $orderCode = self::generateOrderCode();
            $stmt = $pdo->prepare('INSERT INTO orders (order_code, customer_name, customer_email, company_name, website_url, brief, preferred_timeline, payment_type, total_amount, amount_due_now, currency, status, created_at, updated_at) VALUES (:order_code, :customer_name, :customer_email, :company_name, :website_url, :brief, :preferred_timeline, :payment_type, :total_amount, :amount_due_now, :currency, :status, NOW(), NOW())');
            $stmt->execute([
                ':order_code' => $orderCode,
                ':customer_name' => $data['customer_name'],
                ':customer_email' => $data['customer_email'],
                ':company_name' => $data['company_name'] ?? null,
                ':website_url' => $data['website_url'] ?? null,
                ':brief' => $data['brief'],
                ':preferred_timeline' => $data['preferred_timeline'] ?? null,
                ':payment_type' => $data['payment_type'],
                ':total_amount' => $data['total_amount'],
                ':amount_due_now' => $data['amount_due_now'],
                ':currency' => $data['currency'],
                ':status' => 'pending_payment',
            ]);
            $orderId = (int)$pdo->lastInsertId();

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $orderId,
                    'package_id' => $item['package_id'],
                    'package_name_snapshot' => $item['package_name_snapshot'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }
            $pdo->commit();
            return self::findById($orderId);
        } catch (Exception $e) {
            $pdo->rollBack();
            return null;
        }
    }

    public static function findByCode(string $code): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE order_code = ? LIMIT 1');
        $stmt->execute([$code]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public static function updateStatus(int $orderId, string $status): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$status, $orderId]);
    }

    public static function updatePesapalReferences(int $orderId, string $reference, string $trackingId): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE orders SET pesapal_merchant_reference = ?, pesapal_transaction_tracking_id = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$reference, $trackingId, $orderId]);
    }

    public static function listAll(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function findItems(int $orderId): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
}
