<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesDashboard
{
    private function handleDashboard(): void
    {
        $today = date('Y-m-d');

        $stats = [
            'bookings_today' => $this->countQuery("
                SELECT COUNT(*)
                FROM reservations
                WHERE DATE(pickup_datetime) = :today
            ", ['today' => $today]),

            'bookings_upcoming' => $this->countQuery("
                SELECT COUNT(*)
                FROM reservations
                WHERE datetime(pickup_datetime) >= datetime('now')
            "),

            'clients' => $this->safeCount('clients'),
            'drivers' => $this->safeCount('chauffeurs'),
        ];

        $stmt = $this->pdo->query("
            SELECT
                id,
                pickup_datetime AS date,
                client_name AS client,
                pickup_address || ' → ' || dropoff_address AS route,
                status
            FROM reservations
            ORDER BY datetime(pickup_datetime) DESC, id DESC
            LIMIT 10
        ");

        $recentBookings = $stmt ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

        $this->render(
            'Dashboard',
            $this->resolveView([
                'modules/dashboard.php',
                'admin/dashboard.php',
            ]),
            compact('stats', 'recentBookings')
        );
    }

    private function countQuery(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    }
}
