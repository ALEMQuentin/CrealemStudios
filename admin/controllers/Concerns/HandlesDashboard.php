<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

use PDO;

trait HandlesDashboard
{
    private function handleDashboard(): void
    {
        $today = date('Y-m-d');

        // Stats principales
        $stats = [
            'bookings_today' => $this->countQuery("
                SELECT COUNT(*) FROM bookings 
                WHERE DATE(pickup_date) = :today
            ", ['today' => $today]),

            'bookings_upcoming' => $this->countQuery("
                SELECT COUNT(*) FROM bookings 
                WHERE pickup_date > NOW()
            "),

            'clients' => $this->safeCount('clients'),
            'drivers' => $this->safeCount('drivers'),
        ];

        // Dernières réservations
        $stmt = $this->pdo->query("
            SELECT 
                b.id,
                b.pickup_date AS date,
                CONCAT(c.first_name, ' ', c.last_name) AS client,
                CONCAT(b.pickup_address, ' → ', b.dropoff_address) AS route,
                b.status
            FROM bookings b
            LEFT JOIN clients c ON c.id = b.client_id
            ORDER BY b.pickup_date DESC
            LIMIT 10
        ");

        $recentBookings = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

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
