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
        if ($action === 'tariffs') {
            $tariffs = $this->pdo->query("SELECT * FROM booking_tariffs ORDER BY id ASC")->fetchAll(\PDO::FETCH_ASSOC);
            $this->render('Tarifs de réservation', $this->resolveView(['modules/booking-tariffs.php']), compact('tariffs'));
            return;
        }

        if ($action === 'save_tariffs' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveBookingTariffs();
            redirectTo('/admin.php?module=booking&action=tariffs&success=Tarifs enregistrés');
        }

        if ($action === 'chauffeurs') {
            $chauffeurs = $this->pdo->query("SELECT * FROM chauffeurs ORDER BY last_name ASC, first_name ASC")->fetchAll(\PDO::FETCH_ASSOC);
            $this->render('Chauffeurs', $this->resolveView(['modules/booking-chauffeurs-list.php']), compact('chauffeurs'));
            return;
        }

        if ($action === 'chauffeur_documents') {
            $id = (int)($_GET['id'] ?? 0);
            $chauffeur = $this->findChauffeur($id);

            if (!$chauffeur) {
                redirectTo('/admin.php?module=booking&action=chauffeurs&error=Chauffeur introuvable');
            }

            $stmt = $this->pdo->prepare("SELECT * FROM chauffeur_documents WHERE chauffeur_id = :chauffeur_id ORDER BY created_at DESC, id DESC");
            $stmt->execute(['chauffeur_id' => $id]);
            $documents = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->render('Documents chauffeur', $this->resolveView(['modules/booking-chauffeur-documents.php']), compact('chauffeur', 'documents'));
            return;
        }

        if ($action === 'chauffeur_document_upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->uploadChauffeurDocument((int)($_GET['id'] ?? 0));
            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . (int)($_GET['id'] ?? 0) . '&success=Document ajouté');
        }

        if ($action === 'chauffeur_document_validate') {
            $documentId = (int)($_GET['id'] ?? 0);
            $chauffeurId = (int)($_GET['chauffeur_id'] ?? 0);

            $this->pdo->prepare("UPDATE chauffeur_documents SET status = 'valide', validation_note = NULL, validated_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE id = :id")
                ->execute(['id' => $documentId]);

            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . $chauffeurId . '&success=Document validé');
        }

        if ($action === 'chauffeur_document_reject' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $documentId = (int)($_GET['id'] ?? 0);
            $chauffeurId = (int)($_GET['chauffeur_id'] ?? 0);
            $note = trim((string)($_POST['validation_note'] ?? ''));

            $this->pdo->prepare("UPDATE chauffeur_documents SET status = 'refuse', validation_note = :note, validated_at = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id")
                ->execute([
                    'id' => $documentId,
                    'note' => $note,
                ]);

            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . $chauffeurId . '&success=Document refusé');
        }

        if ($action === 'chauffeur_document_delete') {
            $documentId = (int)($_GET['id'] ?? 0);
            $chauffeurId = (int)($_GET['chauffeur_id'] ?? 0);
            $this->deleteChauffeurDocument($documentId);

            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . $chauffeurId . '&success=Document supprimé');
        }

        if ($action === 'chauffeur_create') {
            $chauffeur = $this->emptyChauffeur();
            $this->render('Nouveau chauffeur', $this->resolveView(['modules/booking-chauffeur-form.php']), compact('chauffeur'));
            return;
        }

        if ($action === 'chauffeur_edit') {
            $id = (int)($_GET['id'] ?? 0);
            $chauffeur = $this->findChauffeur($id);

            if (!$chauffeur) {
                redirectTo('/admin.php?module=booking&action=chauffeurs&error=Chauffeur introuvable');
            }

            $this->render('Modifier chauffeur', $this->resolveView(['modules/booking-chauffeur-form.php']), compact('chauffeur'));
            return;
        }

        if ($action === 'chauffeur_save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveChauffeur((int)($_GET['id'] ?? 0));
            redirectTo('/admin.php?module=booking&action=chauffeurs&success=Chauffeur enregistré');
        }

        if ($action === 'chauffeur_delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->pdo->prepare("DELETE FROM chauffeurs WHERE id = :id")->execute(['id' => $id]);
            redirectTo('/admin.php?module=booking&action=chauffeurs&success=Chauffeur supprimé');
        }

        if ($action === 'client_search') {
            $this->bookingClientSearch();
            return;
        }

        if ($action === 'quote') {
            $this->bookingQuote();
            return;
        }

        
        if ($action === 'invoice') {
            $id = (int)($_GET['id'] ?? 0);
            $booking = $this->findReservation($id);

            if (!$booking) {
                redirectTo('/admin.php?module=booking&error=Réservation introuvable');
            }

            if (($booking['status'] ?? '') !== 'terminee') {
                redirectTo('/admin.php?module=booking&error=La facture est disponible uniquement pour une course terminée');
            }

            $this->render('Facture', $this->resolveView(['modules/booking-invoice.php']), compact('booking'));
            return;
        }

        if ($action === 'voucher') {
            $id = (int)($_GET['id'] ?? 0);
            $booking = $this->findReservation($id);

            if (!$booking) {
                echo "Réservation introuvable";
                exit;
            }

            $chauffeur = [];

            if (!empty($booking['chauffeur_id'])) {
                $foundChauffeur = $this->findChauffeur((int)$booking['chauffeur_id']);
                $chauffeur = $foundChauffeur ?: [];
            }

            $this->render('Bon de réservation VTC', $this->resolveView(['modules/booking-voucher.php']), compact('booking', 'chauffeur'));
            return;
        }

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
            $chauffeurs = $this->pdo->query("SELECT * FROM chauffeurs WHERE status = 'active' ORDER BY last_name ASC, first_name ASC")->fetchAll(\PDO::FETCH_ASSOC);
            $this->render('Modifier une réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit', 'chauffeurs'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = $this->reservationPayload();
            $data['client_id'] = $this->ensureReservationClient($data);

            if ($id > 0) {
                $data['id'] = $id;

                $stmt = $this->pdo->prepare("
                    UPDATE reservations SET
                        client_id = :client_id,
                        chauffeur_id = :chauffeur_id,
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
                        stops = :stops,
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
                        client_id,
                        chauffeur_id,
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
                        :client_id,
                        :chauffeur_id,
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

        $editId = (int)($_GET['id'] ?? 0);

        if ($editId > 0 && ($clientName === '' || $clientPhone === '')) {
            $existingReservation = $this->findReservation($editId);

            if ($existingReservation) {
                if ($clientName === '') {
                    $clientName = trim((string)($existingReservation['client_name'] ?? ''));
                }

                if ($clientPhone === '') {
                    $clientPhone = trim((string)($existingReservation['client_phone'] ?? ''));
                }
            }
        }

        if ($clientName === '' || $clientPhone === '' || $pickupAddress === '' || $dropoffAddress === '' || $pickupDatetime === '') {
            redirectTo('/admin.php?module=booking&error=Champs obligatoires manquants');
        }

        $status = trim((string)($_POST['status'] ?? 'a_confirmer'));
        if (!in_array($status, $this->bookingStatuses, true)) {
            $status = 'a_confirmer';
        }

        return [
            'client_id' => max(0, (int)($_POST['client_id'] ?? 0)) ?: null,
            'chauffeur_id' => max(0, (int)($_POST['chauffeur_id'] ?? 0)) ?: null,
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
            'client_id' => null,
            'chauffeur_id' => null,
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
            'stops' => '[]',
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


    private function ensureReservationClient(array $data): ?int
    {
        $existingId = (int)($data['client_id'] ?? 0);

        if ($existingId > 0) {
            return $existingId;
        }

        $firstName = trim((string)($_POST['client_first_name'] ?? ''));
        $lastName = trim((string)($_POST['client_last_name'] ?? ''));
        $phone = trim((string)($data['client_phone'] ?? ''));
        $email = trim((string)($data['client_email'] ?? ''));
        $company = trim((string)($_POST['client_company'] ?? ''));
        $homeAddress = trim((string)($_POST['client_home_address'] ?? ''));

        if ($firstName === '' && $lastName === '') {
            $fullName = trim((string)($data['client_name'] ?? ''));
            $parts = preg_split('/\s+/', $fullName);
            $firstName = array_shift($parts) ?: $fullName;
            $lastName = trim(implode(' ', $parts));
        }

        if ($phone === '') {
            return null;
        }

        $existing = $this->pdo->prepare("SELECT id FROM clients WHERE phone = :phone LIMIT 1");
        $existing->execute(['phone' => $phone]);
        $row = $existing->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return (int)$row['id'];
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO clients (
                first_name,
                last_name,
                email,
                phone,
                company,
                home_address,
                notes,
                created_at,
                updated_at
            ) VALUES (
                :first_name,
                :last_name,
                :email,
                :phone,
                :company,
                :home_address,
                :notes,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            )
        ");

        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'home_address' => $homeAddress,
            'notes' => 'Client créé depuis une réservation.',
        ]);

        return (int)$this->pdo->lastInsertId();
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

    private function bookingClientSearch(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $q = trim((string)($_GET['q'] ?? ''));

        if ($q === '' || mb_strlen($q) < 2) {
            echo json_encode([]);
            return;
        }

        $stmt = $this->pdo->prepare("
            SELECT id, first_name, last_name, email, phone, company, home_address
            FROM clients
            WHERE first_name LIKE :q
               OR last_name LIKE :q
               OR email LIKE :q
               OR phone LIKE :q
               OR company LIKE :q
            ORDER BY id DESC
            LIMIT 10
        ");

        $stmt->execute([
            'q' => '%' . $q . '%',
        ]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $clients = array_map(static function (array $row): array {
            $name = trim((string)($row['first_name'] ?? '') . ' ' . (string)($row['last_name'] ?? ''));

            return [
                'id' => (int)($row['id'] ?? 0),
                'name' => $name,
                'first_name' => (string)($row['first_name'] ?? ''),
                'last_name' => (string)($row['last_name'] ?? ''),
                'email' => (string)($row['email'] ?? ''),
                'phone' => (string)($row['phone'] ?? ''),
                'company' => (string)($row['company'] ?? ''),
                'address' => (string)($row['home_address'] ?? ''),
            ];
        }, $rows);

        echo json_encode($clients, JSON_UNESCAPED_UNICODE);
    }

    private function bookingQuote(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pickup = trim((string)($_POST['pickup_address'] ?? ''));
        $dropoff = trim((string)($_POST['dropoff_address'] ?? ''));
        $vehicle = trim((string)($_POST['vehicle_type'] ?? 'berline'));
        $stops = array_values(array_filter(array_map('trim', (array)($_POST['stops'] ?? []))));

        if ($pickup === '' || $dropoff === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Adresses manquantes'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $googleConfigPath = dirname(__DIR__, 3) . '/config/google.local.php';
        $googleKey = file_exists($googleConfigPath) ? (string)((require $googleConfigPath)['maps_api_key'] ?? '') : '';

        if ($googleKey === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Clé Google Maps manquante'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $params = [
            'origin' => $pickup,
            'destination' => $dropoff,
            'mode' => 'driving',
            'language' => 'fr',
            'units' => 'metric',
            'key' => $googleKey,
        ];

        if (!empty($stops)) {
            $params['waypoints'] = implode('|', $stops);
        }

        $url = 'https://maps.googleapis.com/maps/api/directions/json?' . http_build_query($params);
        $raw = @file_get_contents($url);

        if ($raw === false) {
            http_response_code(502);
            echo json_encode(['error' => 'Impossible de contacter Google Directions'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $google = json_decode($raw, true);

        if (($google['status'] ?? '') !== 'OK' || empty($google['routes'][0]['legs'])) {
            http_response_code(422);
            echo json_encode([
                'error' => 'Google n’a pas pu calculer cet itinéraire',
                'google_status' => $google['status'] ?? null,
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $distanceMeters = 0;
        $durationSeconds = 0;

        foreach ($google['routes'][0]['legs'] as $leg) {
            $distanceMeters += (int)($leg['distance']['value'] ?? 0);
            $durationSeconds += (int)($leg['duration']['value'] ?? 0);
        }

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM booking_tariffs
            WHERE vehicle_type = :vehicle_type
              AND is_active = 1
            LIMIT 1
        ");

        $stmt->execute([
            'vehicle_type' => $vehicle !== '' ? $vehicle : 'berline',
        ]);

        $tariff = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$tariff) {
            http_response_code(422);
            echo json_encode(['error' => 'Aucun tarif actif trouvé pour ce véhicule'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $km = $distanceMeters / 1000;
        $minutes = $durationSeconds / 60;

        $price = (float)$tariff['base_fare']
            + ($km * (float)$tariff['price_per_km'])
            + ($minutes * (float)$tariff['price_per_minute']);

        $pickupDatetime = trim((string)($_POST['pickup_datetime'] ?? ''));
        $hour = $pickupDatetime !== '' ? (int)date('H', strtotime($pickupDatetime)) : (int)date('H');

        if ($hour >= 19 || $hour < 7) {
            $price *= (float)$tariff['night_multiplier'];
        }

        $price = max($price, (float)$tariff['minimum_fare']);
        $price = round($price, 2);

        echo json_encode([
            'price' => $price,
            'distance_meters' => $distanceMeters,
            'duration_seconds' => $durationSeconds,
            'routing_provider' => 'google_directions',
            'stops' => $stops,
        ], JSON_UNESCAPED_UNICODE);
    }






        /*
         * Devis local provisoire.
         * Sans API cartographique branchée dans CrealemStudios, on ne peut pas calculer une vraie distance routière.
         * On produit donc une estimation structurée, remplaçable ensuite par Google Maps / OSRM / autre provider.
         */



    private function uploadChauffeurDocument(int $chauffeurId): void
    {
        $chauffeur = $this->findChauffeur($chauffeurId);

        if (!$chauffeur) {
            redirectTo('/admin.php?module=booking&action=chauffeurs&error=Chauffeur introuvable');
        }

        $documentType = trim((string)($_POST['document_type'] ?? ''));
        $allowedTypes = [
            'carte_vtc',
            'permis_conduire',
            'assurance_rc_pro',
            'carte_grise',
            'assurance_vehicule',
            'controle_technique',
        ];

        if (!in_array($documentType, $allowedTypes, true)) {
            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . $chauffeurId . '&error=Type de document invalide');
        }

        $file = $_FILES['document_file'] ?? null;

        if (!$file || (int)($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . $chauffeurId . '&error=Upload impossible');
        }

        $tmpPath = (string)$file['tmp_name'];
        $originalName = basename((string)$file['name']);
        $size = (int)$file['size'];

        if ($size <= 0 || $size > 10 * 1024 * 1024) {
            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . $chauffeurId . '&error=Fichier trop volumineux');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string)$finfo->file($tmpPath);

        $extensions = [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($extensions[$mime])) {
            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . $chauffeurId . '&error=Format non autorisé');
        }

        $uploadDir = dirname(__DIR__, 3) . '/public/uploads/chauffeur_documents';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $filename = 'chauffeur_' . $chauffeurId . '_' . $documentType . '_' . bin2hex(random_bytes(8)) . '.' . $extensions[$mime];
        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($tmpPath, $destination)) {
            redirectTo('/admin.php?module=booking&action=chauffeur_documents&id=' . $chauffeurId . '&error=Enregistrement fichier impossible');
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO chauffeur_documents (
                chauffeur_id,
                document_type,
                original_name,
                file_path,
                mime_type,
                size_bytes,
                status,
                created_at,
                updated_at
            ) VALUES (
                :chauffeur_id,
                :document_type,
                :original_name,
                :file_path,
                :mime_type,
                :size_bytes,
                'en_attente',
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            )
        ");

        $stmt->execute([
            'chauffeur_id' => $chauffeurId,
            'document_type' => $documentType,
            'original_name' => $originalName,
            'file_path' => '/uploads/chauffeur_documents/' . $filename,
            'mime_type' => $mime,
            'size_bytes' => $size,
        ]);
    }

    private function deleteChauffeurDocument(int $documentId): void
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chauffeur_documents WHERE id = :id");
        $stmt->execute(['id' => $documentId]);
        $document = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$document) {
            return;
        }

        $filePath = dirname(__DIR__, 3) . '/public' . (string)$document['file_path'];

        if (is_file($filePath)) {
            unlink($filePath);
        }

        $this->pdo->prepare("DELETE FROM chauffeur_documents WHERE id = :id")
            ->execute(['id' => $documentId]);
    }

    private function emptyChauffeur(): array
    {
        return [
            'id' => null,
            'first_name' => '',
            'last_name' => '',
            'phone' => '',
            'email' => '',
            'vehicle_label' => '',
            'vehicle_plate' => '',
            'vtc_card_number' => '',
            'status' => 'active',
            'notes' => '',
        ];
    }

    private function findChauffeur(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chauffeurs WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $chauffeur = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $chauffeur ?: null;
    }

    private function saveChauffeur(int $id = 0): void
    {
        $data = [
            'first_name' => trim((string)($_POST['first_name'] ?? '')),
            'last_name' => trim((string)($_POST['last_name'] ?? '')),
            'phone' => trim((string)($_POST['phone'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'vehicle_label' => trim((string)($_POST['vehicle_label'] ?? '')),
            'vehicle_plate' => trim((string)($_POST['vehicle_plate'] ?? '')),
            'vtc_card_number' => trim((string)($_POST['vtc_card_number'] ?? '')),
            'status' => in_array((string)($_POST['status'] ?? 'active'), ['active', 'inactive'], true) ? (string)$_POST['status'] : 'active',
            'notes' => trim((string)($_POST['notes'] ?? '')),
        ];

        if ($data['first_name'] === '' || $data['last_name'] === '') {
            redirectTo('/admin.php?module=booking&action=chauffeurs&error=Nom chauffeur obligatoire');
        }

        if ($id > 0) {
            $data['id'] = $id;

            $stmt = $this->pdo->prepare("
                UPDATE chauffeurs SET
                    first_name = :first_name,
                    last_name = :last_name,
                    phone = :phone,
                    email = :email,
                    vehicle_label = :vehicle_label,
                    vehicle_plate = :vehicle_plate,
                    vtc_card_number = :vtc_card_number,
                    status = :status,
                    notes = :notes,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute($data);
            return;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO chauffeurs (
                first_name,
                last_name,
                phone,
                email,
                vehicle_label,
                vehicle_plate,
                vtc_card_number,
                status,
                notes,
                created_at,
                updated_at
            ) VALUES (
                :first_name,
                :last_name,
                :phone,
                :email,
                :vehicle_label,
                :vehicle_plate,
                :vtc_card_number,
                :status,
                :notes,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            )
        ");

        $stmt->execute($data);
    }

    private function saveBookingTariffs(): void
    {
        $tariffs = $_POST['tariffs'] ?? [];

        foreach ($tariffs as $row) {
            $id = (int)($row['id'] ?? 0);

            if ($id <= 0) {
                continue;
            }

            $stmt = $this->pdo->prepare("
                UPDATE booking_tariffs SET
                    base_fare = :base_fare,
                    price_per_km = :price_per_km,
                    price_per_minute = :price_per_minute,
                    minimum_fare = :minimum_fare,
                    night_multiplier = :night_multiplier,
                    is_active = :is_active,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute([
                'base_fare' => (float)str_replace(',', '.', (string)($row['base_fare'] ?? 0)),
                'price_per_km' => (float)str_replace(',', '.', (string)($row['price_per_km'] ?? 0)),
                'price_per_minute' => (float)str_replace(',', '.', (string)($row['price_per_minute'] ?? 0)),
                'minimum_fare' => (float)str_replace(',', '.', (string)($row['minimum_fare'] ?? 0)),
                'night_multiplier' => (float)str_replace(',', '.', (string)($row['night_multiplier'] ?? 1)),
                'is_active' => (int)($row['is_active'] ?? 0),
                'id' => $id,
            ]);
        }
    }


}
