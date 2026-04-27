<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesReservationForms
{
    private function handleReservationForms(string $action): void
    {
        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = (string)($_POST['type'] ?? 'distance');

            $stmt = $this->pdo->prepare("
                INSERT INTO reservation_forms (name, type, settings, is_active, created_at, updated_at)
                VALUES (:name, :type, :settings, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ");

            $stmt->execute([
                'name' => trim((string)($_POST['name'] ?? '')),
                'type' => $type,
                'settings' => json_encode([], JSON_UNESCAPED_UNICODE),
            ]);

            $formId = (int)$this->pdo->lastInsertId();
            $this->createDefaultFields($formId, $type);

            redirectTo('/admin.php?module=reservation_forms&action=fields&id=' . $formId);
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);

            $this->pdo->prepare("DELETE FROM reservation_form_fields WHERE form_id = :id")->execute(['id' => $id]);
            $this->pdo->prepare("DELETE FROM reservation_forms WHERE id = :id")->execute(['id' => $id]);

            redirectTo('/admin.php?module=reservation_forms&success=Formulaire supprimé');
        }

        if ($action === 'save_config' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);

            $settings = [
                'pricing' => [
                    'base_fare' => (float)($_POST['base_fare'] ?? 0),
                    'price_per_km' => (float)($_POST['price_per_km'] ?? 0),
                    'price_per_minute' => (float)($_POST['price_per_minute'] ?? 0),
                    'hourly_rate' => (float)($_POST['hourly_rate'] ?? 0),
                    'circuit_fixed_price' => (float)($_POST['circuit_fixed_price'] ?? 0),
                    'minimum_fare' => (float)($_POST['minimum_fare'] ?? 0),
                ],
                'availability' => [
                    'days' => (array)($_POST['availability_days'] ?? []),
                    'start_time' => trim((string)($_POST['start_time'] ?? '')),
                    'end_time' => trim((string)($_POST['end_time'] ?? '')),
                    'min_notice_minutes' => (int)($_POST['min_notice_minutes'] ?? 30),
                ],
                'vehicles' => trim((string)($_POST['vehicles'] ?? '')),
                'circuit' => [
                    'route_name' => trim((string)($_POST['route_name'] ?? '')),
                    'route_stops' => trim((string)($_POST['route_stops'] ?? '')),
                    'route_duration' => trim((string)($_POST['route_duration'] ?? '')),
                ],
                'payments' => (array)($_POST['payments'] ?? []),
            ];

            $stmt = $this->pdo->prepare("
                UPDATE reservation_forms
                SET settings = :settings,
                    is_active = :is_active,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute([
                'settings' => json_encode($settings, JSON_UNESCAPED_UNICODE),
                'is_active' => (int)($_POST['is_active'] ?? 0),
                'id' => $id,
            ]);

            redirectTo('/admin.php?module=reservation_forms&action=fields&id=' . $id . '&success=Configuration enregistrée');
        }

        if ($action === 'create') {
            $this->render('Créer formulaire', $this->resolveView(['modules/reservation_forms-create.php']));
            return;
        }

        if ($action === 'fields' || $action === 'configure') {
            $formId = (int)($_GET['id'] ?? 0);

            $stmt = $this->pdo->prepare("SELECT * FROM reservation_forms WHERE id = :id");
            $stmt->execute(['id' => $formId]);
            $form = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$form) {
                redirectTo('/admin.php?module=reservation_forms&error=Formulaire introuvable');
            }

            $settings = json_decode((string)($form['settings'] ?? ''), true);
            $settings = is_array($settings) ? $settings : [];

            $stmt = $this->pdo->prepare("SELECT * FROM reservation_form_fields WHERE form_id = :id ORDER BY position ASC, id ASC");
            $stmt->execute(['id' => $formId]);
            $fields = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->render('Configuration formulaire', $this->resolveView(['modules/reservation_forms-fields.php']), compact('form', 'fields', 'settings'));
            return;
        }

        $forms = $this->pdo->query("SELECT * FROM reservation_forms ORDER BY id DESC")->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('Formulaires réservation', $this->resolveView(['modules/reservation_forms-index.php']), compact('forms'));
    }

    private function createDefaultFields(int $formId, string $type): void
    {
        $fields = [];

        if ($type === 'distance') {
            $fields = [
                ['client_name', 'Nom du client', 'text', 1],
                ['client_phone', 'Téléphone', 'tel', 1],
                ['pickup_address', 'Adresse de départ', 'text', 1],
                ['dropoff_address', 'Adresse d’arrivée', 'text', 1],
                ['pickup_datetime', 'Date / heure', 'datetime', 1],
                ['passengers', 'Passagers', 'number', 1],
                ['notes', 'Notes', 'textarea', 0],
            ];
        }

        if ($type === 'hourly') {
            $fields = [
                ['client_name', 'Nom du client', 'text', 1],
                ['client_phone', 'Téléphone', 'tel', 1],
                ['pickup_address', 'Adresse de départ', 'text', 1],
                ['start_datetime', 'Date / heure de début', 'datetime', 1],
                ['duration_hours', 'Durée en heures', 'number', 1],
                ['passengers', 'Passagers', 'number', 1],
                ['notes', 'Notes', 'textarea', 0],
            ];
        }

        if ($type === 'circuit') {
            $fields = [
                ['client_name', 'Nom du client', 'text', 1],
                ['client_phone', 'Téléphone', 'tel', 1],
                ['starting_point', 'Point de départ', 'text', 1],
                ['circuit_date', 'Date du circuit', 'datetime', 1],
                ['passengers', 'Passagers', 'number', 1],
                ['notes', 'Notes', 'textarea', 0],
            ];
        }

        foreach ($fields as $position => $field) {
            $stmt = $this->pdo->prepare("
                INSERT INTO reservation_form_fields (form_id, name, label, type, required, position)
                VALUES (:form_id, :name, :label, :type, :required, :position)
            ");

            $stmt->execute([
                'form_id' => $formId,
                'name' => $field[0],
                'label' => $field[1],
                'type' => $field[2],
                'required' => $field[3],
                'position' => $position,
            ]);
        }
    }
}
