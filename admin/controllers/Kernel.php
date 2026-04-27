<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Admin\Modules\UserModule;
use App\Controllers\Admin\Modules\ClientModule;
use App\Controllers\Admin\Modules\DriverModule;
use PDO;

final class Kernel
{
    public function __construct(
        private PDO $pdo,
        private array $config,
        private string $module,
        private string $action
    ) {}

    public function handle(): void
    {
        switch ($this->module) {

            case 'dashboard':
                $this->render('Dashboard','modules/dashboard.php',['stats'=>['ok'=>true]]);
                return;

            case 'users':
                (new UserModule($this->pdo))->handleUsers(
                    $this->action,
                    fn($t,$v,$d=[]) => $this->render($t,$v,$d)
                );
                return;

            case 'roles':
                (new UserModule($this->pdo))->handleRoles(
                    $this->action,
                    fn($t,$v,$d=[]) => $this->render($t,$v,$d)
                );
                return;

            case 'clients':
                (new ClientModule($this->pdo))->handle(
                    $this->action,
                    fn($t,$v,$d=[]) => $this->render($t,$v,$d)
                );
                return;

            case 'drivers':
                (new DriverModule($this->pdo))->handle(
                    $this->action,
                    fn($t,$v,$d=[]) => $this->render($t,$v,$d)
                );
                return;

            default:
                http_response_code(404);
                echo 'Module introuvable';
                return;
        }
    }

    private function render(string $title,string $view,array $data=[]): void
    {
        $viewPath = dirname(__DIR__).'/views/'.$view;
        extract($data);
        require dirname(__DIR__).'/views/layouts/admin.php';
    }
}
