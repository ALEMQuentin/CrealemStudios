<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesReservationForms
{
    private function handleReservationForms(string $action): void
    {
        if ($action === 'save') {
            $stmt = $this->pdo->prepare("INSERT INTO booking_forms (name, type) VALUES (?, ?)");
            $stmt->execute([$_POST['name'], $_POST['type']]);

            header("Location: /admin.php?module=booking_forms");
            exit;
        }

        if ($action === 'create') {
            $this->render('Créer formulaire', 'modules/reservation_forms-create.php');
            return;
        }

        $forms = $this->pdo->query("SELECT * FROM booking_forms ORDER BY id DESC")->fetchAll();

        $this->render('Formulaires réservation', 'modules/reservation_forms-index.php', compact('forms'));
    }
}
