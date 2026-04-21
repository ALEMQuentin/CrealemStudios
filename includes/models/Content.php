<?php

namespace App\Models;

use PDO;

class Content
{
    public static function allByType(PDO $pdo, string $type, ?string $status = null): array
    {
        if ($status !== null) {
            $stmt = $pdo->prepare("
                SELECT *
                FROM content
                WHERE type = :type
                  AND status = :status
                ORDER BY created_at DESC, id DESC
            ");
            $stmt->execute([
                'type' => $type,
                'status' => $status,
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $stmt = $pdo->prepare("
            SELECT *
            FROM content
            WHERE type = :type
            ORDER BY created_at DESC, id DESC
        ");
        $stmt->execute(['type' => $type]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM content WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public static function findByTypeAndSlug(PDO $pdo, string $type, string $slug, ?string $status = 'published'): ?array
    {
        if ($status !== null) {
            $stmt = $pdo->prepare("
                SELECT *
                FROM content
                WHERE type = :type
                  AND slug = :slug
                  AND status = :status
                LIMIT 1
            ");
            $stmt->execute([
                'type' => $type,
                'slug' => $slug,
                'status' => $status,
            ]);
        } else {
            $stmt = $pdo->prepare("
                SELECT *
                FROM content
                WHERE type = :type
                  AND slug = :slug
                LIMIT 1
            ");
            $stmt->execute([
                'type' => $type,
                'slug' => $slug,
            ]);
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function slugExists(PDO $pdo, string $type, string $slug, int $excludeId = 0): bool
    {
        $stmt = $pdo->prepare("
            SELECT id
            FROM content
            WHERE type = :type
              AND slug = :slug
              AND id != :exclude_id
            LIMIT 1
        ");

        $stmt->execute([
            'type' => $type,
            'slug' => $slug,
            'exclude_id' => $excludeId,
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create(PDO $pdo, array $data): int
    {
        $stmt = $pdo->prepare("
            INSERT INTO content (
                type, title, slug, excerpt, content, status,
                author_id, parent_id, menu_order, created_at, updated_at
            ) VALUES (
                :type, :title, :slug, :excerpt, :content, :status,
                :author_id, :parent_id, :menu_order, :created_at, :updated_at
            )
        ");

        $stmt->execute([
            'type' => $data['type'],
            'title' => $data['title'] ?? '',
            'slug' => $data['slug'] ?? '',
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'author_id' => $data['author_id'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'menu_order' => $data['menu_order'] ?? 0,
            'created_at' => $data['created_at'] ?? null,
            'updated_at' => $data['updated_at'] ?? null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function update(PDO $pdo, int $id, array $data): void
    {
        $stmt = $pdo->prepare("
            UPDATE content
            SET title = :title,
                slug = :slug,
                excerpt = :excerpt,
                content = :content,
                status = :status,
                author_id = :author_id,
                parent_id = :parent_id,
                menu_order = :menu_order,
                updated_at = :updated_at
            WHERE id = :id
        ");

        $stmt->execute([
            'title' => $data['title'] ?? '',
            'slug' => $data['slug'] ?? '',
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'author_id' => $data['author_id'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'menu_order' => $data['menu_order'] ?? 0,
            'updated_at' => $data['updated_at'] ?? null,
            'id' => $id,
        ]);
    }

    public static function delete(PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare("DELETE FROM content WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $stmt = $pdo->prepare("DELETE FROM content_meta WHERE content_id = :content_id");
        $stmt->execute(['content_id' => $id]);

        if (self::tableExists($pdo, 'content_blocks')) {
            $stmt = $pdo->prepare("DELETE FROM content_blocks WHERE content_id = :content_id");
            $stmt->execute(['content_id' => $id]);
        }

        if (self::tableExists($pdo, 'post_category_relations')) {
            $stmt = $pdo->prepare("DELETE FROM post_category_relations WHERE post_id = :post_id");
            $stmt->execute(['post_id' => $id]);
        }
    }

    public static function meta(PDO $pdo, int $contentId): array
    {
        $stmt = $pdo->prepare("
            SELECT meta_key, meta_value
            FROM content_meta
            WHERE content_id = :content_id
        ");
        $stmt->execute(['content_id' => $contentId]);

        $meta = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $meta[$row['meta_key']] = $row['meta_value'];
        }

        return $meta;
    }

    public static function setMeta(PDO $pdo, int $contentId, string $key, ?string $value): void
    {
        $now = date('Y-m-d H:i:s');

        $check = $pdo->prepare("
            SELECT id
            FROM content_meta
            WHERE content_id = :content_id
              AND meta_key = :meta_key
            LIMIT 1
        ");
        $check->execute([
            'content_id' => $contentId,
            'meta_key' => $key,
        ]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $stmt = $pdo->prepare("
                UPDATE content_meta
                SET meta_value = :meta_value,
                    updated_at = :updated_at
                WHERE content_id = :content_id
                  AND meta_key = :meta_key
            ");
            $stmt->execute([
                'meta_value' => $value,
                'updated_at' => $now,
                'content_id' => $contentId,
                'meta_key' => $key,
            ]);
            return;
        }

        $stmt = $pdo->prepare("
            INSERT INTO content_meta (content_id, meta_key, meta_value, created_at, updated_at)
            VALUES (:content_id, :meta_key, :meta_value, :created_at, :updated_at)
        ");
        $stmt->execute([
            'content_id' => $contentId,
            'meta_key' => $key,
            'meta_value' => $value,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public static function deleteMeta(PDO $pdo, int $contentId, string $key): void
    {
        $stmt = $pdo->prepare("
            DELETE FROM content_meta
            WHERE content_id = :content_id
              AND meta_key = :meta_key
        ");
        $stmt->execute([
            'content_id' => $contentId,
            'meta_key' => $key,
        ]);
    }

    private static function tableExists(PDO $pdo, string $table): bool
    {
        $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type = 'table' AND name = :name LIMIT 1");
        $stmt->execute(['name' => $table]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
