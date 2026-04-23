<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesBooking
{
    private function handleBooking(string $action): void
    {
        if ($action === 'index') {
            $bookings = $this->fetchAllSafe("SELECT * FROM bookings ORDER BY id DESC");
            $this->render('Réservations', $this->resolveView(['modules/booking-list.php']), compact('bookings'));
            return;
        }

        if ($action === 'create') {
            $booking = ['client_id' => '', 'title' => '', 'booking_date' => '', 'booking_time' => '', 'status' => 'pending', 'amount' => '', 'notes' => ''];
            $isEdit = false;
            $this->render('Ajouter une réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $booking = $this->fetchOne("SELECT * FROM bookings WHERE id = :id", ['id' => $id]);
            if (!$booking) redirectTo('/admin.php?module=booking&error=Réservation introuvable');
            $isEdit = true;
            $this->render('Modifier une réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'client_id' => ($_POST['client_id'] ?? '') !== '' ? (int)$_POST['client_id'] : null,
                'title' => trim($_POST['title'] ?? ''),
                'booking_date' => trim($_POST['booking_date'] ?? ''),
                'booking_time' => trim($_POST['booking_time'] ?? ''),
                'status' => trim($_POST['status'] ?? 'pending'),
                'amount' => ($_POST['amount'] ?? '') !== '' ? (float)$_POST['amount'] : null,
                'notes' => trim($_POST['notes'] ?? ''),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('bookings', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('bookings', $data);
            }
            redirectTo('/admin.php?module=booking&success=Réservation enregistrée');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('bookings', $id);
            redirectTo('/admin.php?module=booking&success=Réservation supprimée');
        }

        redirectTo('/admin.php?module=booking');
    }
}
