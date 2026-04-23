<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

use App\Controllers\Admin\ReservationsController;

trait HandlesReservations
{
    private function handleReservations(string $action): void
    {
        $controller = new ReservationsController($this->pdo);
        $id = (int)($_GET['id'] ?? 0);

        match ($action) {
            'create' => $controller->create(),
            'store' => $controller->store(),
            'edit' => $controller->edit($id),
            'update' => $controller->update($id),
            'delete' => $controller->delete($id),
            'archive' => $controller->archive($id),
            'unarchive' => $controller->unarchive($id),
            default => $controller->index(),
        };
    }
}
