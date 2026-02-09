<?php
require_once __DIR__ . '/../config/database.php';

class Package
{
    private static function activeWithMeta(string $orderClause = 'ORDER BY p.name', ?int $limit = null): array
    {
        $pdo = getPDO();
        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                       COALESCE(req.request_count, 0) AS request_count
                FROM packages p
                JOIN categories c ON c.id = p.category_id AND c.is_active = 1
                LEFT JOIN (
                    SELECT oi.package_id, SUM(oi.quantity) AS request_count
                    FROM order_items oi
                    JOIN orders o ON o.id = oi.order_id AND o.status <> 'cancelled'
                    GROUP BY oi.package_id
                ) req ON req.package_id = p.id
                WHERE p.is_active = 1
                $orderClause";

        if ($limit !== null) {
            $sql .= ' LIMIT :limit';
        }

        $stmt = $pdo->prepare($sql);
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function allActive(): array
    {
        return self::activeWithMeta('ORDER BY p.name');
    }

    public static function allActiveWithMeta(): array
    {
        return self::activeWithMeta('ORDER BY p.name');
    }

    public static function popular(int $limit = 3): array
    {
        return self::activeWithMeta('ORDER BY request_count DESC, p.name ASC', $limit);
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

    public static function findByIds(array $ids, bool $includeInactive = false): array
    {
        $ids = array_filter(array_map('intval', $ids));
        if (empty($ids)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $pdo = getPDO();
        $sql = "SELECT * FROM packages WHERE id IN ($placeholders)";
        if (!$includeInactive) {
            $sql .= ' AND is_active = 1';
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    public static function splitByType(array $packages): array
    {
        $video = [];
        $combo = [];
        $other = [];

        foreach ($packages as $pkg) {
            $slug = strtolower($pkg['category_slug'] ?? '');
            $name = strtolower($pkg['category_name'] ?? '');
            $isCombo = (strpos($slug, 'combo') !== false) || (strpos($name, 'combo') !== false) || (strpos($slug, 'bundle') !== false);
            $isVideo = (strpos($slug, 'video') !== false) || (strpos($name, 'video') !== false);

            if ($isCombo) {
                $combo[] = $pkg;
                continue;
            }

            if ($isVideo) {
                $video[] = $pkg;
                continue;
            }

            $video[] = $pkg;
            $other[] = $pkg;
        }

        return [
            'video' => $video,
            'combo' => $combo,
            'other' => $other,
        ];
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
