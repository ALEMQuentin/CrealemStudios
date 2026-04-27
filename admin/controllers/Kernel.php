<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Admin\Concerns\HandlesBlog;
use App\Controllers\Admin\Concerns\HandlesBooking;
use App\Controllers\Admin\Concerns\HandlesClients;
use App\Controllers\Admin\Concerns\HandlesContentHelpers;
use App\Controllers\Admin\Concerns\HandlesDashboard;
use App\Controllers\Admin\Concerns\HandlesForms;
use App\Controllers\Admin\Concerns\HandlesGallery;
use App\Controllers\Admin\Concerns\HandlesMedia;
use App\Controllers\Admin\Concerns\HandlesMenus;
use App\Controllers\Admin\Concerns\HandlesPages;
use App\Controllers\Admin\Concerns\HandlesProducts;
use App\Controllers\Admin\Concerns\HandlesReservationForms;
use App\Controllers\Admin\Concerns\HandlesSettings;
use App\Controllers\Admin\Concerns\HandlesSubscriptions;
use App\Controllers\Admin\Concerns\HandlesTestimonials;
use App\Controllers\Admin\Concerns\HandlesUsers;
use PDO;

class Kernel
{
    use HandlesBlog;
    use HandlesBooking;
    use HandlesClients;
    use HandlesContentHelpers;
    use HandlesDashboard;
    use HandlesForms;
    use HandlesGallery;
    use HandlesMedia;
    use HandlesMenus;
    use HandlesPages;
    use HandlesProducts;
    use HandlesReservationForms;
    use HandlesSettings;
    use HandlesSubscriptions;
    use HandlesTestimonials;
    use HandlesUsers;

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
                return;

            case 'gallery':
                $this->handleGallery($action);
                return;

            case 'testimonials':
                $this->handleTestimonials($action);
                return;

            case 'subscriptions':
                $this->handleSubscriptions($action);
                return;

            case 'clients':
                $this->handleClients($action);
                return;

            case 'booking':
                $this->handleBooking($action);
                return;

            case 'reservation_forms':
                $this->handleReservationForms($action);
                return;

            default:
                if (!headers_sent()) {
                    http_response_code(404);
                }

                echo 'Module introuvable';
                return;
        }
    }

    private function render(string $pageTitle, string $viewPath, array $data = []): void
    {
        extract($data);

        require $this->resolveView([
            'layouts/admin.php',
            'admin/layout.php',
        ]);
    }

    private function resolveView(array $candidates): string
    {
        $viewsRoot = dirname(__DIR__, 2) . '/views';

        foreach ($candidates as $candidate) {
            $candidate = ltrim($candidate, '/');
            $path = $viewsRoot . '/' . $candidate;

            if (is_file($path)) {
                return $path;
            }
        }

        throw new \RuntimeException('Vue introuvable : ' . implode(', ', $candidates));
    }
}
