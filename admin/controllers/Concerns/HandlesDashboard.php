<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesDashboard
{
    private function handleDashboard(): void
    {
        $today = date('Y-m-d');
        $month = date('Y-m');

        $stats = [

            'bookings_today' => $this->countQuery("
                SELECT COUNT(*) FROM reservations
                WHERE DATE(pickup_datetime) = :today
            ", ['today' => $today]),

            'bookings_upcoming' => $this->countQuery("
                SELECT COUNT(*) FROM reservations
                WHERE pickup_datetime > NOW()
            "),

            'clients' => $this->safeCount('clients'),
            'drivers' => $this->safeCount('chauffeurs'),

            'revenue_today' => $this->sumQuery("
                SELECT SUM(price) FROM reservations
                WHERE DATE(pickup_datetime) = :today
                AND status = 'completed'
            ", ['today' => $today]),

            'revenue_month' => $this->sumQuery("
                SELECT SUM(price) FROM reservations
                WHERE DATE_FORMAT(pickup_datetime, '%Y-%m') = :month
                AND status = 'completed'
            ", ['month' => $month]),

            'unassigned' => $this->countQuery("
                SELECT COUNT(*) FROM reservations
                WHERE driver_id IS NULL
            "),
        ];

        $stmt = $this->pdo->query("
            SELECT
                id,
                pickup_datetime AS date,
                client_name AS client,
                CONCAT(pickup_address, ' → ', dropoff_address) AS route,
                status
            FROM reservations
            ORDER BY pickup_datetime DESC
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

    private function sumQuery(string $sql, array $params = []): float
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (float)($stmt->fetchColumn() ?? 0);
    }
}
