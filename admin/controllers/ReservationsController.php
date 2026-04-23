<?php

namespace App\Controllers\Admin;

use PDO;

class ReservationsController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $stmt = $this->pdo->query("SELECT * FROM bookings ORDER BY datetime DESC");
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/reservations/index.php';
    }

    public function create()
    {
        require __DIR__ . '/../views/reservations/create.php';
    }

    public function store()
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO bookings 
            (client_name, client_phone, pickup_address, dropoff_address, datetime, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, 'pending', datetime('now'), datetime('now'))
        ");

        $stmt->execute([
            $_POST['client_name'],
            $_POST['client_phone'],
            $_POST['pickup_address'],
            $_POST['dropoff_address'],
            $_POST['datetime']
        ]);

        header("Location: /admin.php?module=reservations");
        exit;
    }

    public function edit($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/reservations/edit.php';
    }

    public function update($id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE bookings SET
            client_name = ?,
            client_phone = ?,
            pickup_address = ?,
            dropoff_address = ?,
            datetime = ?,
            status = ?,
            updated_at = datetime('now')
            WHERE id = ?
        ");

        $stmt->execute([
            $_POST['client_name'],
            $_POST['client_phone'],
            $_POST['pickup_address'],
            $_POST['dropoff_address'],
            $_POST['datetime'],
            $_POST['status'],
            $id
        ]);

        header("Location: /admin.php?module=reservations");
        exit;
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: /admin.php?module=reservations");
        exit;
    }
}
