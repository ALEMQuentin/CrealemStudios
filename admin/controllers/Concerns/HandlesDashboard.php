<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesDashboard
{
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
}
