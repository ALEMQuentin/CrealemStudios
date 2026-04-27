<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Concerns\HandlesContentHelpers;

use App\Controllers\Admin\Concerns\HandlesDashboard;

use App\Controllers\Admin\Concerns\HandlesSubscriptions;

use App\Controllers\Admin\Concerns\HandlesBooking;

use App\Controllers\Admin\Concerns\HandlesClients;

use App\Controllers\Admin\Concerns\HandlesTestimonials;

use App\Controllers\Admin\Concerns\HandlesGallery;

use App\Controllers\Admin\Concerns\HandlesForms;

use App\Controllers\Admin\Concerns\HandlesMedia;

use App\Controllers\Admin\Concerns\HandlesProducts;

use App\Controllers\Admin\Concerns\HandlesBlog;

use App\Controllers\Admin\Concerns\HandlesUsers;

use App\Controllers\Admin\Concerns\HandlesSettings;

use App\Controllers\Admin\Concerns\HandlesMenus;

use App\Controllers\Admin\Concerns\HandlesPages;

use App\Models\Content;

use PDO;

use App\Controllers\Admin\Concerns\HandlesReservationForms;

class Kernel
{

    use HandlesContentHelpers;

    use HandlesDashboard;

    use HandlesSubscriptions;

    use HandlesBooking;

    use HandlesClients;

    use HandlesTestimonials;

    use HandlesGallery;

    use HandlesForms;

    use HandlesMedia;

    use HandlesProducts;

    use HandlesBlog;

    use HandlesUsers;

    use HandlesSettings;

    use HandlesMenus;

    use HandlesReservationForms;

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
                return;

            case 'gallery':
                $this->handleGallery($action);
                return;

            case 'testimonials':
                $this->handleTestimonials($action);
                return;

            case 'clients':
                $this->handleClients($action);
                return;
case 'subscriptions':
                $this->handleSubscriptions($action);
                return;

            case 'reservation_forms':
                $this->handleReservationForms($action);
                break;

            case 'booking':
            case 'booking_forms':
                $this->handleBooking($action);
                break;

            case 'booking':
                $this->handleBooking($action);
                return;

default:
                if (!headers_sent()) {
                    http_response_code(404);
                }
                echo 'Module introuvable';
                return;
        }
    }






























}
