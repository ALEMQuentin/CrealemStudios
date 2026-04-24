<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesBooking
{
    private array $bookingStatuses = [
        'a_confirmer',
        'confirmee',
        'en_cours',
        'terminee',
        'annulee',
    ];

    private function handleBooking(string $action): void
    {
        if ($action === 'create') {
            $booking = $this->emptyBooking();
            $isEdit = false;

            $this->render('Nouvelle réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $booking = $this->findReservation($id);

            if (!$booking) {
                redirectTo('/admin.php?module=booking&error=Réservation introuvable');
            }

            $isEdit = true;
            $this->render('Modifier une réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = $this->reservationPayload();

            if ($id > 0) {
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
            } else {
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
            }

            redirectTo('/admin.php?module=booking&success=Réservation enregistrée');
        }

        if ($action === 'archive') {
            $id = (int)($_GET['id'] ?? 0);
            $this->pdo->prepare("UPDATE reservations SET is_archived = 1, updated_at = CURRENT_TIMESTAMP WHERE id = :id")
                ->execute(['id' => $id]);

            redirectTo('/admin.php?module=booking&success=Réservation archivée');
        }

        if ($action === 'unarchive') {
            $id = (int)($_GET['id'] ?? 0);
            $this->pdo->prepare("UPDATE reservations SET is_archived = 0, updated_at = CURRENT_TIMESTAMP WHERE id = :id")
                ->execute(['id' => $id]);

            redirectTo('/admin.php?module=booking&status=archived&success=Réservation restaurée');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->pdo->prepare("DELETE FROM reservations WHERE id = :id")->execute(['id' => $id]);

            redirectTo('/admin.php?module=booking&success=Réservation supprimée');
        }

        $status = trim((string)($_GET['status'] ?? 'active'));

        if ($status === 'archived') {
            $stmt = $this->pdo->query("SELECT * FROM reservations WHERE is_archived = 1 ORDER BY pickup_datetime DESC, id DESC");
        } elseif (in_array($status, $this->bookingStatuses, true)) {
            $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE is_archived = 0 AND status = :status ORDER BY pickup_datetime DESC, id DESC");
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM reservations WHERE is_archived = 0 ORDER BY pickup_datetime DESC, id DESC");
        }

        $bookings = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('Réservations', $this->resolveView(['modules/booking-list.php']), compact('bookings'));
    }

    private function reservationPayload(): array
    {
        $clientName = trim((string)($_POST['client_name'] ?? ''));
        $clientPhone = trim((string)($_POST['client_phone'] ?? ''));
        $pickupAddress = trim((string)($_POST['pickup_address'] ?? ''));
        $dropoffAddress = trim((string)($_POST['dropoff_address'] ?? ''));
        $pickupDatetime = trim((string)($_POST['pickup_datetime'] ?? ''));

        if ($clientName === '' || $clientPhone === '' || $pickupAddress === '' || $dropoffAddress === '' || $pickupDatetime === '') {
            redirectTo('/admin.php?module=booking&error=Champs obligatoires manquants');
        }

        $status = trim((string)($_POST['status'] ?? 'a_confirmer'));
        if (!in_array($status, $this->bookingStatuses, true)) {
            $status = 'a_confirmer';
        }

        return [
            'client_name' => $clientName,
            'client_phone' => $clientPhone,
            'client_email' => $this->nullableReservationString($_POST['client_email'] ?? null),
            'pickup_address' => $pickupAddress,
            'dropoff_address' => $dropoffAddress,
            'pickup_datetime' => $pickupDatetime,
            'passengers' => max(1, (int)($_POST['passengers'] ?? 1)),
            'luggage' => max(0, (int)($_POST['luggage'] ?? 0)),
            'vehicle_type' => $this->nullableReservationString($_POST['vehicle_type'] ?? 'berline') ?? 'berline',
            'payment_method' => $this->nullableReservationString($_POST['payment_method'] ?? null),
            'price' => $this->nullableReservationFloat($_POST['price'] ?? null),
            'distance_meters' => $this->nullableReservationInt($_POST['distance_meters'] ?? null),
            'duration_seconds' => $this->nullableReservationInt($_POST['duration_seconds'] ?? null),
            'routing_provider' => $this->nullableReservationString($_POST['routing_provider'] ?? null),
            'customer_note' => $this->nullableReservationString($_POST['customer_note'] ?? null),
            'internal_note' => $this->nullableReservationString($_POST['internal_note'] ?? null),
            'status' => $status,
        ];
    }

    private function emptyBooking(): array
    {
        return [
            'id' => null,
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
            'is_archived' => 0,
        ];
    }

    private function findReservation(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $booking = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $booking ?: null;
    }

    private function nullableReservationString(mixed $value): ?string
    {
        $value = trim((string)$value);

        return $value === '' ? null : $value;
    }

    private function nullableReservationInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int)$value;
    }

    private function nullableReservationFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float)str_replace(',', '.', (string)$value);
    }
}
