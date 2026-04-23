<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Concerns\HandlesMedia;

use App\Controllers\Admin\Concerns\HandlesProducts;

use App\Controllers\Admin\Concerns\HandlesBlog;

use App\Controllers\Admin\Concerns\HandlesPages;

use App\Models\Content;
use PDO;

class Kernel
{
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

    private function handleForms(string $action): void
    {
        if ($action === 'index') {
            $forms = $this->fetchAllSafe("SELECT * FROM forms ORDER BY id DESC");
            $this->render('Formulaires', $this->resolveView(['modules/forms-list.php']), compact('forms'));
            return;
        }

        if ($action === 'create') {
            $form = ['title' => '', 'slug' => '', 'description' => '', 'form_schema_json' => '[]', 'status' => 'draft'];
            $isEdit = false;
            $this->render('Ajouter un formulaire', $this->resolveView(['modules/forms-form.php']), compact('form', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $form = $this->fetchOne("SELECT * FROM forms WHERE id = :id", ['id' => $id]);
            if (!$form) redirectTo('/admin.php?module=forms&error=Formulaire introuvable');
            $isEdit = true;
            $this->render('Modifier un formulaire', $this->resolveView(['modules/forms-form.php']), compact('form', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'form_schema_json' => trim($_POST['form_schema_json'] ?? '[]'),
                'status' => trim($_POST['status'] ?? 'draft'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('forms', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('forms', $data);
            }
            redirectTo('/admin.php?module=forms&success=Formulaire enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('forms', $id);
            redirectTo('/admin.php?module=forms&success=Formulaire supprimé');
        }

        redirectTo('/admin.php?module=forms');
    }

    private function handleGallery(string $action): void
    {
        if ($action === 'index') {
            $galleryItems = $this->fetchAllSafe("SELECT * FROM gallery_items ORDER BY sort_order ASC, id ASC");
            $this->render('Galerie', $this->resolveView(['modules/gallery-list.php']), compact('galleryItems'));
            return;
        }

        if ($action === 'create') {
            $galleryItem = ['title' => '', 'image_media_id' => '', 'caption' => '', 'sort_order' => 0];
            $isEdit = false;
            $this->render('Ajouter un élément de galerie', $this->resolveView(['modules/gallery-form.php']), compact('galleryItem', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $galleryItem = $this->fetchOne("SELECT * FROM gallery_items WHERE id = :id", ['id' => $id]);
            if (!$galleryItem) redirectTo('/admin.php?module=gallery&error=Élément introuvable');
            $isEdit = true;
            $this->render('Modifier un élément de galerie', $this->resolveView(['modules/gallery-form.php']), compact('galleryItem', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'image_media_id' => ($_POST['image_media_id'] ?? '') !== '' ? (int)$_POST['image_media_id'] : null,
                'caption' => trim($_POST['caption'] ?? ''),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('gallery_items', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('gallery_items', $data);
            }
            redirectTo('/admin.php?module=gallery&success=Élément enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('gallery_items', $id);
            redirectTo('/admin.php?module=gallery&success=Élément supprimé');
        }

        redirectTo('/admin.php?module=gallery');
    }

    private function handleTestimonials(string $action): void
    {
        if ($action === 'index') {
            $testimonials = $this->fetchAllSafe("SELECT * FROM testimonials ORDER BY id DESC");
            $this->render('Avis', $this->resolveView(['modules/testimonials-list.php']), compact('testimonials'));
            return;
        }

        if ($action === 'create') {
            $testimonial = ['author_name' => '', 'company' => '', 'content' => '', 'rating' => 5, 'status' => 'published'];
            $isEdit = false;
            $this->render('Ajouter un avis', $this->resolveView(['modules/testimonials-form.php']), compact('testimonial', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $testimonial = $this->fetchOne("SELECT * FROM testimonials WHERE id = :id", ['id' => $id]);
            if (!$testimonial) redirectTo('/admin.php?module=testimonials&error=Avis introuvable');
            $isEdit = true;
            $this->render('Modifier un avis', $this->resolveView(['modules/testimonials-form.php']), compact('testimonial', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'author_name' => trim($_POST['author_name'] ?? ''),
                'company' => trim($_POST['company'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'rating' => (int)($_POST['rating'] ?? 5),
                'status' => trim($_POST['status'] ?? 'published'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('testimonials', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('testimonials', $data);
            }
            redirectTo('/admin.php?module=testimonials&success=Avis enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('testimonials', $id);
            redirectTo('/admin.php?module=testimonials&success=Avis supprimé');
        }

        redirectTo('/admin.php?module=testimonials');
    }

    private function handleClients(string $action): void
    {
        if ($action === 'index') {
            $clients = $this->fetchAllSafe("SELECT * FROM clients ORDER BY id DESC");
            $this->render('Clients', $this->resolveView(['modules/clients-list.php']), compact('clients'));
            return;
        }

        if ($action === 'create') {
            $client = ['first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '', 'company' => '', 'notes' => ''];
            $isEdit = false;
            $this->render('Ajouter un client', $this->resolveView(['modules/clients-form.php']), compact('client', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $client = $this->fetchOne("SELECT * FROM clients WHERE id = :id", ['id' => $id]);
            if (!$client) redirectTo('/admin.php?module=clients&error=Client introuvable');
            $isEdit = true;
            $this->render('Modifier un client', $this->resolveView(['modules/clients-form.php']), compact('client', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'company' => trim($_POST['company'] ?? ''),
                'notes' => trim($_POST['notes'] ?? ''),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('clients', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('clients', $data);
            }
            redirectTo('/admin.php?module=clients&success=Client enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('clients', $id);
            redirectTo('/admin.php?module=clients&success=Client supprimé');
        }

        redirectTo('/admin.php?module=clients');
    }

    private function handleBooking(string $action): void
    {
        if ($action === 'index') {
            $bookings = $this->fetchAllSafe("SELECT * FROM bookings ORDER BY id DESC");
            $this->render('Réservations', $this->resolveView(['modules/booking-list.php']), compact('bookings'));
            return;
        }

        if ($action === 'create') {
            $booking = ['client_id' => '', 'title' => '', 'booking_date' => '', 'booking_time' => '', 'status' => 'pending', 'amount' => '', 'notes' => ''];
            $isEdit = false;
            $this->render('Ajouter une réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $booking = $this->fetchOne("SELECT * FROM bookings WHERE id = :id", ['id' => $id]);
            if (!$booking) redirectTo('/admin.php?module=booking&error=Réservation introuvable');
            $isEdit = true;
            $this->render('Modifier une réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'client_id' => ($_POST['client_id'] ?? '') !== '' ? (int)$_POST['client_id'] : null,
                'title' => trim($_POST['title'] ?? ''),
                'booking_date' => trim($_POST['booking_date'] ?? ''),
                'booking_time' => trim($_POST['booking_time'] ?? ''),
                'status' => trim($_POST['status'] ?? 'pending'),
                'amount' => ($_POST['amount'] ?? '') !== '' ? (float)$_POST['amount'] : null,
                'notes' => trim($_POST['notes'] ?? ''),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('bookings', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('bookings', $data);
            }
            redirectTo('/admin.php?module=booking&success=Réservation enregistrée');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('bookings', $id);
            redirectTo('/admin.php?module=booking&success=Réservation supprimée');
        }

        redirectTo('/admin.php?module=booking');
    }

    private function handleSubscriptions(string $action): void
    {
        if ($action === 'index') {
            $subscriptions = $this->fetchAllSafe("SELECT * FROM subscriptions ORDER BY id DESC");
            $this->render('Abonnements', $this->resolveView(['modules/subscriptions-list.php']), compact('subscriptions'));
            return;
        }

        if ($action === 'create') {
            $subscription = ['title' => '', 'description' => '', 'price' => '', 'billing_cycle' => 'monthly', 'status' => 'active'];
            $isEdit = false;
            $this->render('Ajouter un abonnement', $this->resolveView(['modules/subscriptions-form.php']), compact('subscription', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $subscription = $this->fetchOne("SELECT * FROM subscriptions WHERE id = :id", ['id' => $id]);
            if (!$subscription) redirectTo('/admin.php?module=subscriptions&error=Abonnement introuvable');
            $isEdit = true;
            $this->render('Modifier un abonnement', $this->resolveView(['modules/subscriptions-form.php']), compact('subscription', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => ($_POST['price'] ?? '') !== '' ? (float)$_POST['price'] : null,
                'billing_cycle' => trim($_POST['billing_cycle'] ?? 'monthly'),
                'status' => trim($_POST['status'] ?? 'active'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('subscriptions', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('subscriptions', $data);
            }
            redirectTo('/admin.php?module=subscriptions&success=Abonnement enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('subscriptions', $id);
            redirectTo('/admin.php?module=subscriptions&success=Abonnement supprimé');
        }

        redirectTo('/admin.php?module=subscriptions');
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
