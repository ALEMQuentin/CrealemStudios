<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Admin\Modules\UserModule;
use PDO;

final class Kernel
{
    public function __construct(
        private PDO $pdo,
        private array $config,
        private string $module,
        private string $action
    ) {
    }

    public function handle(): void
    {
        switch ($this->module) {
            case 'dashboard':
                $this->dashboard();
                return;

            case 'users':
                (new UserModule($this->pdo))->handleUsers(
                    $this->action,
                    fn (string $title, string $view, array $data = []) => $this->render($title, $view, $data)
                );
                return;

            case 'roles':
                (new UserModule($this->pdo))->handleRoles(
                    $this->action,
                    fn (string $title, string $view, array $data = []) => $this->render($title, $view, $data)
                );
                return;

            default:
                http_response_code(404);
                echo 'Module introuvable';
                return;
        }
    }

    private function dashboard(): void
    {
        $stats = [
            'database' => 'connectée',
            'module' => 'dashboard',
        ];

        $this->render('Dashboard', 'modules/dashboard.php', compact('stats'));
    }

    private function render(string $pageTitle, string $view, array $data = []): void
    {
        $viewPath = $this->resolveView($view);

        extract($data);

        require $this->resolveView('layouts/admin.php');
    }

    private function resolveView(string $view): string
    {
        $path = dirname(__DIR__) . '/views/' . ltrim($view, '/');

        if (!is_file($path)) {
            throw new \RuntimeException('Vue introuvable : ' . $view);
        }

        return $path;
    }
}
