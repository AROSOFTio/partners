<?php
require_once __DIR__ . '/../config/database.php';

class Admin
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('INSERT INTO admins (name, email, password_hash, created_at, updated_at) VALUES (:name, :email, :password_hash, NOW(), NOW())');
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function count(): int
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT COUNT(*) as total FROM admins');
        $row = $stmt->fetch();
        return (int)($row['total'] ?? 0);
    }
}
