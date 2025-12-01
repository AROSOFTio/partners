<?php
require_once __DIR__ . '/../config/database.php';

class Package
{
    public static function allActive(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT * FROM packages WHERE is_active = 1 ORDER BY name');
        return $stmt->fetchAll();
    }

    public static function all(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT * FROM packages ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM packages WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        $pkg = $stmt->fetch();
        return $pkg ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM packages WHERE id = ?');
        $stmt->execute([$id]);
        $pkg = $stmt->fetch();
        return $pkg ?: null;
    }

    public static function findByIds(array $ids): array
    {
        $ids = array_filter(array_map('intval', $ids));
        if (empty($ids)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM packages WHERE id IN ($placeholders) AND is_active = 1");
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('INSERT INTO packages (category_id, name, slug, short_description, full_description, base_price, currency, duration_minutes, allow_deposit, deposit_percentage, delivery_time_text, is_active, created_at, updated_at) VALUES (:category_id, :name, :slug, :short_description, :full_description, :base_price, :currency, :duration_minutes, :allow_deposit, :deposit_percentage, :delivery_time_text, :is_active, NOW(), NOW())');
        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':short_description' => $data['short_description'],
            ':full_description' => $data['full_description'],
            ':base_price' => $data['base_price'],
            ':currency' => $data['currency'],
            ':duration_minutes' => $data['duration_minutes'],
            ':allow_deposit' => $data['allow_deposit'],
            ':deposit_percentage' => $data['deposit_percentage'],
            ':delivery_time_text' => $data['delivery_time_text'],
            ':is_active' => $data['is_active'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE packages SET category_id=:category_id, name=:name, slug=:slug, short_description=:short_description, full_description=:full_description, base_price=:base_price, currency=:currency, duration_minutes=:duration_minutes, allow_deposit=:allow_deposit, deposit_percentage=:deposit_percentage, delivery_time_text=:delivery_time_text, is_active=:is_active, updated_at=NOW() WHERE id=:id');
        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':short_description' => $data['short_description'],
            ':full_description' => $data['full_description'],
            ':base_price' => $data['base_price'],
            ':currency' => $data['currency'],
            ':duration_minutes' => $data['duration_minutes'],
            ':allow_deposit' => $data['allow_deposit'],
            ':deposit_percentage' => $data['deposit_percentage'],
            ':delivery_time_text' => $data['delivery_time_text'],
            ':is_active' => $data['is_active'],
            ':id' => $id,
        ]);
    }
}
