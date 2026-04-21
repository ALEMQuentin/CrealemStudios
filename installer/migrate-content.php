<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);
$config = require $basePath . '/config/config.php';

$pdo = new PDO('sqlite:' . $config['db']['database']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

function tableExists(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type = 'table' AND name = :name LIMIT 1");
    $stmt->execute(['name' => $table]);
    return (bool) $stmt->fetch();
}

function columnExists(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->query("PRAGMA table_info({$table})");
    foreach ($stmt->fetchAll() as $col) {
        if (($col['name'] ?? '') === $column) {
            return true;
        }
    }
    return false;
}

function insertContent(PDO $pdo, array $data): int
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

    $stmt->execute($data);
    return (int) $pdo->lastInsertId();
}

function insertMeta(PDO $pdo, int $contentId, string $key, ?string $value): void
{
    $now = date('Y-m-d H:i:s');

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

echo "=== Migration pages/posts vers content ===\n";

if (tableExists($pdo, 'pages')) {
    $pages = $pdo->query("SELECT * FROM pages")->fetchAll();

    foreach ($pages as $page) {
        $check = $pdo->prepare("SELECT id FROM content WHERE type = 'page' AND slug = :slug LIMIT 1");
        $check->execute(['slug' => $page['slug']]);
        if ($check->fetch()) {
            continue;
        }

        $contentId = insertContent($pdo, [
            'type' => 'page',
            'title' => $page['title'] ?? '',
            'slug' => $page['slug'] ?? '',
            'excerpt' => null,
            'content' => $page['content'] ?? '',
            'status' => $page['status'] ?? 'draft',
            'author_id' => null,
            'parent_id' => null,
            'menu_order' => 0,
            'created_at' => $page['created_at'] ?? null,
            'updated_at' => $page['updated_at'] ?? null,
        ]);

        if (columnExists($pdo, 'pages', 'meta_title') && !empty($page['meta_title'])) {
            insertMeta($pdo, $contentId, 'meta_title', $page['meta_title']);
        }

        if (columnExists($pdo, 'pages', 'meta_description') && !empty($page['meta_description'])) {
            insertMeta($pdo, $contentId, 'meta_description', $page['meta_description']);
        }

        if (columnExists($pdo, 'pages', 'featured_media_id') && !empty($page['featured_media_id'])) {
            insertMeta($pdo, $contentId, 'featured_media_id', (string) $page['featured_media_id']);
        }
    }

    echo count($pages) . " page(s) traitée(s)\n";
}

if (tableExists($pdo, 'posts')) {
    $posts = $pdo->query("SELECT * FROM posts")->fetchAll();

    foreach ($posts as $post) {
        $check = $pdo->prepare("SELECT id FROM content WHERE type = 'post' AND slug = :slug LIMIT 1");
        $check->execute(['slug' => $post['slug']]);
        if ($check->fetch()) {
            continue;
        }

        $contentId = insertContent($pdo, [
            'type' => 'post',
            'title' => $post['title'] ?? '',
            'slug' => $post['slug'] ?? '',
            'excerpt' => $post['excerpt'] ?? null,
            'content' => $post['content'] ?? '',
            'status' => $post['status'] ?? 'draft',
            'author_id' => null,
            'parent_id' => null,
            'menu_order' => 0,
            'created_at' => $post['created_at'] ?? null,
            'updated_at' => $post['updated_at'] ?? null,
        ]);

        if (columnExists($pdo, 'posts', 'meta_title') && !empty($post['meta_title'])) {
            insertMeta($pdo, $contentId, 'meta_title', $post['meta_title']);
        }

        if (columnExists($pdo, 'posts', 'meta_description') && !empty($post['meta_description'])) {
            insertMeta($pdo, $contentId, 'meta_description', $post['meta_description']);
        }

        if (columnExists($pdo, 'posts', 'featured_media_id') && !empty($post['featured_media_id'])) {
            insertMeta($pdo, $contentId, 'featured_media_id', (string) $post['featured_media_id']);
        }
    }

    echo count($posts) . " article(s) traité(s)\n";
}

echo "Migration content terminée.\n";
