<?php
require_once __DIR__ . '/../config/database.php';

class PortfolioItem
{
    public static function all(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT * FROM portfolio_items ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function featured(int $limit = 6): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM portfolio_items WHERE is_featured = 1 ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM portfolio_items WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('INSERT INTO portfolio_items (title, brand_name, youtube_url, collab_type, short_description, is_featured, created_at, updated_at) VALUES (:title, :brand_name, :youtube_url, :collab_type, :short_description, :is_featured, NOW(), NOW())');
        $stmt->execute([
            ':title' => $data['title'],
            ':brand_name' => $data['brand_name'],
            ':youtube_url' => $data['youtube_url'],
            ':collab_type' => $data['collab_type'],
            ':short_description' => $data['short_description'],
            ':is_featured' => $data['is_featured'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE portfolio_items SET title=:title, brand_name=:brand_name, youtube_url=:youtube_url, collab_type=:collab_type, short_description=:short_description, is_featured=:is_featured, updated_at=NOW() WHERE id=:id');
        return $stmt->execute([
            ':title' => $data['title'],
            ':brand_name' => $data['brand_name'],
            ':youtube_url' => $data['youtube_url'],
            ':collab_type' => $data['collab_type'],
            ':short_description' => $data['short_description'],
            ':is_featured' => $data['is_featured'],
            ':id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('DELETE FROM portfolio_items WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
