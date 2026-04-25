<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesReservationForms
{
    private function handleReservationForms(string $action): void
    {
        if ($action === 'save') {
            $stmt = $this->pdo->prepare("INSERT INTO reservation_forms (name, type) VALUES (?, ?)");
            $stmt->execute([$_POST['name'], $_POST['type']]);

            header("Location: /admin.php?module=reservation_forms");
            exit;
        }

        
        
        if ($action === 'add_field') {

            $formId = (int)($_GET['id'] ?? 0);

            $stmt = $this->pdo->prepare("
                INSERT INTO reservation_form_fields (form_id, name, label, type, required)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $formId,
                $_POST['name'],
                $_POST['label'],
                $_POST['type'],
                (int)$_POST['required']
            ]);

            header("Location: /admin.php?module=reservation_forms&action=fields&id=" . $formId);
            exit;
        }

        if ($action === 'fields') {

            $formId = (int)($_GET['id'] ?? 0);

            $stmt = $this->pdo->prepare("SELECT * FROM reservation_forms WHERE id = ?");
            $stmt->execute([$formId]);
            $form = $stmt->fetch();

            $stmt = $this->pdo->prepare("SELECT * FROM reservation_form_fields WHERE form_id = ? ORDER BY position ASC");
            $stmt->execute([$formId]);
            $fields = $stmt->fetchAll();

            $this->render('Champs formulaire', $this->resolveView(['modules/reservation_forms-fields.php']), compact('form', 'fields'));
            return;
        }

        if ($action === 'create') {
            $this->render('Créer formulaire', $this->resolveView(['modules/reservation_forms-create.php']));
            return;
        }

        $forms = $this->pdo->query("SELECT * FROM reservation_forms ORDER BY id DESC")->fetchAll();

        $this->render('Formulaires réservation', $this->resolveView(['modules/reservation_forms-index.php']), compact('forms'));
    }
}
