<?php
require_once __DIR__ . '/../config/database.php';

class OrderItem
{
    public static function create(array $data): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('INSERT INTO order_items (order_id, package_id, package_name_snapshot, unit_price, quantity, line_total) VALUES (:order_id, :package_id, :package_name_snapshot, :unit_price, :quantity, :line_total)');
        return $stmt->execute([
            ':order_id' => $data['order_id'],
            ':package_id' => $data['package_id'],
            ':package_name_snapshot' => $data['package_name_snapshot'],
            ':unit_price' => $data['unit_price'],
            ':quantity' => $data['quantity'],
            ':line_total' => $data['line_total'],
        ]);
    }
}
