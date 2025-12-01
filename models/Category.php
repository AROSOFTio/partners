<?php
require_once __DIR__ . '/../config/database.php';

class Category
{
    public static function allActive(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT * FROM categories WHERE is_active = 1 ORDER BY name');
        return $stmt->fetchAll();
    }
}
