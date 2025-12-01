<?php
require_once __DIR__ . '/../config/database.php';

class Payment
{
    public static function create(array $data): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('INSERT INTO payments (order_id, amount, currency, payment_type, provider, status, pesapal_transaction_tracking_id, pesapal_payment_method, created_at, updated_at) VALUES (:order_id, :amount, :currency, :payment_type, :provider, :status, :tracking_id, :payment_method, NOW(), NOW())');
        return $stmt->execute([
            ':order_id' => $data['order_id'],
            ':amount' => $data['amount'],
            ':currency' => $data['currency'],
            ':payment_type' => $data['payment_type'],
            ':provider' => $data['provider'] ?? 'pesapal',
            ':status' => $data['status'] ?? 'pending',
            ':tracking_id' => $data['pesapal_transaction_tracking_id'] ?? null,
            ':payment_method' => $data['pesapal_payment_method'] ?? null,
        ]);
    }

    public static function findByOrder(int $orderId): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM payments WHERE order_id = ? ORDER BY created_at DESC');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
}
