<?php

namespace App\Controllers\Admin;

class Kernel
{
    public function handle()
    {
        $module = $_GET['module'] ?? 'dashboard';

        switch ($module) {
            case 'dashboard':
            default:
                return $this->render('modules/dashboard');
        }
    }

    protected function render(string $view, array $data = [])
    {
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue introuvable : " . $view);
        }

        require __DIR__ . '/../views/layouts/admin.php';
    }
}
