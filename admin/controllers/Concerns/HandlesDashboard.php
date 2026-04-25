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
                WHERE datetime(pickup_datetime) > datetime('now')
            "),

            'clients' => $this->safeCount('clients'),
            'drivers' => $this->safeCount('chauffeurs'),

            'revenue_today' => $this->sumQuery("
                SELECT SUM(price) FROM reservations
                WHERE DATE(pickup_datetime) = :today
                AND status = 'terminee'
            ", ['today' => $today]),

            'revenue_month' => $this->sumQuery("
                SELECT SUM(price) FROM reservations
                WHERE strftime('%Y-%m', pickup_datetime) = :month
                AND status = 'terminee'
            ", ['month' => $month]),

            'unassigned' => $this->countQuery("
                SELECT COUNT(*) FROM reservations
                WHERE chauffeur_id IS NULL
            "),
        ];

        $recentBookings = $this->fetchAll("
            SELECT
                id,
                pickup_datetime AS date,
                client_name AS client,
                pickup_address || ' → ' || dropoff_address AS route,
                status
            FROM reservations
            ORDER BY datetime(pickup_datetime) DESC
            LIMIT 10
        ");

        $unassignedBookings = $this->fetchAll("
            SELECT
                id,
                pickup_datetime AS date,
                client_name AS client,
                pickup_address || ' → ' || dropoff_address AS route
            FROM reservations
            WHERE chauffeur_id IS NULL
            ORDER BY datetime(pickup_datetime) ASC
            LIMIT 5
        ");

        $this->render(
            'Dashboard',
            $this->resolveView([
                'modules/dashboard.php',
                'admin/dashboard.php',
            ]),
            compact('stats', 'recentBookings', 'unassignedBookings')
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

    private function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
