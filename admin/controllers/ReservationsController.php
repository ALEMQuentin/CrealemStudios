<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use PDO;

final class ReservationsController
{
    private PDO $pdo;
    private array $settings;

    private array $allowedStatuses = [
        'a_confirmer',
        'confirmee',
        'en_cours',
        'terminee',
        'annulee',
    ];

    public function __construct(PDO $pdo, array $settings = [])
    {
        $this->pdo = $pdo;
        $this->settings = $settings;
    }

    private function render(string $pageTitle, string $view, array $data = []): void
    {
        $currentModule = 'booking';
        $currentAction = (string)($_GET['action'] ?? 'index');
        $settings = $this->settings;

        $viewPath = $view;
        if (!str_starts_with($viewPath, '/')) {
            $viewPath = dirname(__DIR__) . '/views/' . ltrim($viewPath, '/');
        }

        if (!str_ends_with($viewPath, '.php')) {
            $viewPath .= '.php';
        }

        extract($data, EXTR_SKIP);

        require dirname(__DIR__) . '/views/layouts/admin.php';
    }

    
    public function index(): void
    {
        $status = trim((string)($_GET['status'] ?? 'active'));

        if ($status === 'archived') {
            $stmt = $this->pdo->query("SELECT * FROM reservations WHERE is_archived = 1 ORDER BY pickup_datetime DESC, id DESC");
        } elseif (in_array($status, $this->allowedStatuses, true)) {
            $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE is_archived = 0 AND status = :status ORDER BY pickup_datetime DESC, id DESC");
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM reservations WHERE is_archived = 0 ORDER BY pickup_datetime DESC, id DESC");
        }

        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('Réservations', 'reservations/index', compact('reservations'));
    }

    public function create(): void
    {
        $reservation = $this->emptyReservation();
        $this->render('Nouvelle réservation', 'reservations/create', compact('reservation'));
    }

    public function store(): void
    {
        $data = $this->validatedPayload();

        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (
                client_name,
                client_phone,
                client_email,
                pickup_address,
                dropoff_address,
                pickup_datetime,
                passengers,
                luggage,
                vehicle_type,
                payment_method,
                price,
                distance_meters,
                duration_seconds,
                routing_provider,
                customer_note,
                internal_note,
                status,
                is_archived,
                created_at,
                updated_at
            ) VALUES (
                :client_name,
                :client_phone,
                :client_email,
                :pickup_address,
                :dropoff_address,
                :pickup_datetime,
                :passengers,
                :luggage,
                :vehicle_type,
                :payment_method,
                :price,
                :distance_meters,
                :duration_seconds,
                :routing_provider,
                :customer_note,
                :internal_note,
                :status,
                0,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            )
        ");

        $stmt->execute($data);

        $this->redirect('/admin.php?module=reservations&success=Réservation créée');
    }

    public function edit(int $id): void
    {
        $reservation = $this->findOrFail($id);
        $this->render('Modifier réservation', 'reservations/edit', compact('reservation'));
    }

    public function update(int $id): void
    {
        $this->findOrFail($id);
        $data = $this->validatedPayload();

        $data['id'] = $id;

        $stmt = $this->pdo->prepare("
            UPDATE reservations SET
                client_name = :client_name,
                client_phone = :client_phone,
                client_email = :client_email,
                pickup_address = :pickup_address,
                dropoff_address = :dropoff_address,
                pickup_datetime = :pickup_datetime,
                passengers = :passengers,
                luggage = :luggage,
                vehicle_type = :vehicle_type,
                payment_method = :payment_method,
                price = :price,
                distance_meters = :distance_meters,
                duration_seconds = :duration_seconds,
                routing_provider = :routing_provider,
                customer_note = :customer_note,
                internal_note = :internal_note,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute($data);

        $this->redirect('/admin.php?module=reservations&success=Réservation mise à jour');
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id);

        $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $this->redirect('/admin.php?module=reservations&success=Réservation supprimée');
    }

    public function archive(int $id): void
    {
        $this->findOrFail($id);

        $stmt = $this->pdo->prepare("UPDATE reservations SET is_archived = 1, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $this->redirect('/admin.php?module=reservations&success=Réservation archivée');
    }

    public function unarchive(int $id): void
    {
        $this->findOrFail($id);

        $stmt = $this->pdo->prepare("UPDATE reservations SET is_archived = 0, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $this->redirect('/admin.php?module=reservations&status=archived&success=Réservation restaurée');
    }

    private function validatedPayload(): array
    {
        $clientName = trim((string)($_POST['client_name'] ?? ''));
        $clientPhone = trim((string)($_POST['client_phone'] ?? ''));
        $pickupAddress = trim((string)($_POST['pickup_address'] ?? ''));
        $dropoffAddress = trim((string)($_POST['dropoff_address'] ?? ''));
        $pickupDatetime = trim((string)($_POST['pickup_datetime'] ?? ''));

        if ($clientName === '' || $clientPhone === '' || $pickupAddress === '' || $dropoffAddress === '' || $pickupDatetime === '') {
            $this->redirect('/admin.php?module=reservations&error=Champs obligatoires manquants');
        }

        $status = trim((string)($_POST['status'] ?? 'a_confirmer'));
        if (!in_array($status, $this->allowedStatuses, true)) {
            $status = 'a_confirmer';
        }

        return [
            'client_name' => $clientName,
            'client_phone' => $clientPhone,
            'client_email' => $this->nullableString($_POST['client_email'] ?? null),
            'pickup_address' => $pickupAddress,
            'dropoff_address' => $dropoffAddress,
            'pickup_datetime' => $pickupDatetime,
            'passengers' => max(1, (int)($_POST['passengers'] ?? 1)),
            'luggage' => max(0, (int)($_POST['luggage'] ?? 0)),
            'vehicle_type' => $this->nullableString($_POST['vehicle_type'] ?? 'berline') ?? 'berline',
            'payment_method' => $this->nullableString($_POST['payment_method'] ?? null),
            'price' => $this->nullableFloat($_POST['price'] ?? null),
            'distance_meters' => $this->nullableInt($_POST['distance_meters'] ?? null),
            'duration_seconds' => $this->nullableInt($_POST['duration_seconds'] ?? null),
            'routing_provider' => $this->nullableString($_POST['routing_provider'] ?? null),
            'customer_note' => $this->nullableString($_POST['customer_note'] ?? null),
            'internal_note' => $this->nullableString($_POST['internal_note'] ?? null),
            'status' => $status,
        ];
    }

    private function emptyReservation(): array
    {
        return [
            'client_name' => '',
            'client_phone' => '',
            'client_email' => '',
            'pickup_address' => '',
            'dropoff_address' => '',
            'pickup_datetime' => '',
            'passengers' => 1,
            'luggage' => 0,
            'vehicle_type' => 'berline',
            'payment_method' => '',
            'price' => '',
            'distance_meters' => '',
            'duration_seconds' => '',
            'routing_provider' => '',
            'customer_note' => '',
            'internal_note' => '',
            'status' => 'a_confirmer',
        ];
    }

    private function findOrFail(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            $this->redirect('/admin.php?module=reservations&error=Réservation introuvable');
        }

        return $reservation;
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int)$value;
    }

    private function nullableFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float)str_replace(',', '.', (string)$value);
    }

    private function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}
