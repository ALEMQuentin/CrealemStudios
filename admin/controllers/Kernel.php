<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Concerns\HandlesSubscriptions;

use App\Controllers\Admin\Concerns\HandlesBooking;

use App\Controllers\Admin\Concerns\HandlesClients;

use App\Controllers\Admin\Concerns\HandlesTestimonials;

use App\Controllers\Admin\Concerns\HandlesGallery;

use App\Controllers\Admin\Concerns\HandlesForms;

use App\Controllers\Admin\Concerns\HandlesMedia;

use App\Controllers\Admin\Concerns\HandlesProducts;

use App\Controllers\Admin\Concerns\HandlesBlog;

use App\Controllers\Admin\Concerns\HandlesPages;

use App\Models\Content;
use PDO;

class Kernel
{
    use HandlesSubscriptions;

    use HandlesBooking;

    use HandlesClients;

    use HandlesTestimonials;

    use HandlesGallery;

    use HandlesForms;

    use HandlesMedia;

    use HandlesProducts;

    use HandlesBlog;

    use HandlesPages;

    private PDO $pdo;
    private array $config;
    private array $settings;
    private string $module;
    private string $action;

    public function __construct(PDO $pdo, array $config, string $module, string $action)
    {
        $this->pdo = $pdo;
        $this->config = $config;
        $this->module = $module;
        $this->action = $action;
        $this->settings = getSettings($pdo);
    }

    public function handle(): void
    {
        $module = $this->module;
        $action = $this->action;

        if (!isModuleEnabled($this->settings, $module)) {
            redirectTo('/admin.php?module=dashboard&error=Module désactivé');
        }

        switch ($module) {
            case 'dashboard':
                $this->handleDashboard();
                return;
            case 'pages':
                $this->handlePages($action);
                return;
            case 'blog':
                $this->handleBlog($action);
                return;
            case 'media':
                $this->handleMedia($action);
                return;
            case 'menus':
                $this->handleMenus($action);
                return;
            case 'users':
                $this->handleUsers($action);
                return;
            case 'settings':
                $this->handleSettings($action);
                return;
            case 'products':
                $this->handleProducts($action);
                return;
        
            case 'forms':
                $this->handleForms($action);
                break;

            case 'gallery':
                $this->handleGallery($action);
                break;

            case 'testimonials':
                $this->handleTestimonials($action);
                break;

            case 'clients':
                $this->handleClients($action);
                break;

            case 'booking':
                $this->handleBooking($action);
                break;

            case 'subscriptions':
                $this->handleSubscriptions($action);
                break;
}

        http_response_code(404);
        echo 'Module introuvable';
    }

    private function handleDashboard(): void
    {
        $stats = [
            'pages' => count(Content::allByType($this->pdo, 'page')),
            'users' => $this->safeCount('users'),
            'media' => $this->safeCount('media'),
            'posts' => count(Content::allByType($this->pdo, 'post')),
        ];

        $this->render(
            'Dashboard',
            $this->resolveView([
                'modules/dashboard.php',
                'admin/dashboard.php',
            ]),
            compact('stats')
        );
    }














    private function syncCommonMeta(int $contentId): void
    {
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDescription = trim($_POST['meta_description'] ?? '');
        $featuredMediaId = trim((string)($_POST['featured_media_id'] ?? ''));

        if ($metaTitle !== '') {
            Content::setMeta($this->pdo, $contentId, 'meta_title', $metaTitle);
        } else {
            Content::deleteMeta($this->pdo, $contentId, 'meta_title');
        }

        if ($metaDescription !== '') {
            Content::setMeta($this->pdo, $contentId, 'meta_description', $metaDescription);
        } else {
            Content::deleteMeta($this->pdo, $contentId, 'meta_description');
        }

        if ($featuredMediaId !== '') {
            Content::setMeta($this->pdo, $contentId, 'featured_media_id', $featuredMediaId);
        } else {
            Content::deleteMeta($this->pdo, $contentId, 'featured_media_id');
        }
    }

    private function fetchBlocks(int $contentId): array
    {
        return $this->fetchAllSafe("SELECT * FROM content_blocks WHERE content_id = " . (int)$contentId . " ORDER BY sort_order ASC, id ASC");
    }

    private function moveBlock(int $contentId, int $blockId, string $direction): void
    {
        $blocks = $this->fetchBlocks($contentId);
        $index = null;

        foreach ($blocks as $i => $block) {
            if ((int)$block['id'] === $blockId) {
                $index = $i;
                break;
}
        }

        if ($index === null) {
            return;
        }

        if ($direction === 'up' && $index > 0) {
            $other = $blocks[$index - 1];
            $current = $blocks[$index];
            $this->swapBlockOrder((int)$current['id'], (int)$other['id'], (int)$current['sort_order'], (int)$other['sort_order']);
        }

        if ($direction === 'down' && $index < count($blocks) - 1) {
            $other = $blocks[$index + 1];
            $current = $blocks[$index];
            $this->swapBlockOrder((int)$current['id'], (int)$other['id'], (int)$current['sort_order'], (int)$other['sort_order']);
        }
    }

    private function swapBlockOrder(int $firstId, int $secondId, int $firstOrder, int $secondOrder): void
    {
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("UPDATE content_blocks SET sort_order = :sort_order, updated_at = :updated_at WHERE id = :id");
        $stmt->execute([
            'sort_order' => $secondOrder,
            'updated_at' => $now,
            'id' => $firstId,
        ]);

        $stmt->execute([
            'sort_order' => $firstOrder,
            'updated_at' => $now,
            'id' => $secondId,
        ]);
    }

    private function extractBlockSettings(string $blockType): array
    {
        return match ($blockType) {
            'hero' => [
                'title' => trim($_POST['hero_title'] ?? ''),
                'subtitle' => trim($_POST['hero_subtitle'] ?? ''),
                'button_text' => trim($_POST['hero_button_text'] ?? ''),
                'button_url' => trim($_POST['hero_button_url'] ?? ''),
            ],
            'rich-text' => [
                'title' => trim($_POST['rich_text_title'] ?: ($_POST['hero_title'] ?? '')),
                'content' => trim($_POST['rich_text_content'] ?? ''),
            ],
            'menu' => [
                'menu_location' => trim($_POST['menu_location'] ?? 'main'),
            ],
            'cta' => [
                'title' => trim($_POST['cta_title'] ?? ''),
                'text' => trim($_POST['cta_text'] ?? ''),
                'button_text' => trim($_POST['cta_button_text'] ?? ''),
                'button_url' => trim($_POST['cta_button_url'] ?? ''),
            ],
            'posts-list' => [
                'title' => trim($_POST['posts_list_title'] ?? ''),
                'limit' => (int)($_POST['posts_list_limit'] ?? 3),
            ],
            default => [],
        };
    }

    private function render(string $pageTitle, string $viewPath, array $data = []): void
    {
        extract($data);
        $module = $this->module;
        $action = $this->action;
        $config = $this->config;
        $settings = $this->settings;

        require ADMIN_VIEWS_PATH . '/layouts/admin.php';
    }

    private function resolveView(array $candidates): string
    {
        $base = ADMIN_VIEWS_PATH . '/';

        foreach ($candidates as $candidate) {
            $path = $base . $candidate;
            if (file_exists($path)) {
                return $path;
            }
        }

        return $base . 'admin/module-placeholder.php';
    }

    private function titleFor(string $module): string
    {
        return match ($module) {
            'products' => 'Produits',
            'forms' => 'Formulaires',
            'booking' => 'Réservations',
            'clients' => 'Clients',
            'testimonials' => 'Avis',
            'gallery' => 'Galerie',
            'subscriptions' => 'Abonnements',
            default => ucfirst($module),
        };
    }

    private function tableExists(string $table): bool
    {
        $stmt = $this->pdo->prepare("SELECT name FROM sqlite_master WHERE type = 'table' AND name = :name LIMIT 1");
        $stmt->execute(['name' => $table]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function insertRow(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);

        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return (int)$this->pdo->lastInsertId();
    }

    private function updateRow(string $table, int $id, array $data): void
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = $column . ' = :' . $column;
        }

        $data['id'] = $id;

        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    private function deleteById(string $table, int $id): void
    {
        if (!$this->tableExists($table)) {
            return;
        }

        $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    private function fetchAllSafe(string $sql): array
    {
        try {
            return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function fetchOne(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function safeCount(string $table): int
    {
        if (!$this->tableExists($table)) {
            return 0;
        }

        try {
            return (int)$this->pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
