<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Modules;

use PDO;

final class DriverModule
{
    public function __construct(private PDO $pdo)
    {
    }

    public function handle(string $action, callable $render): void
    {
        if ($action === 'create') {
            $driver = $this->emptyDriver();
            $render('Nouveau chauffeur', 'modules/drivers-form.php', compact('driver'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $driver = $this->find($id);

            if (!$driver) {
                $this->redirect('/admin.php?module=drivers&error=Introuvable');
            }

            $render('Modifier chauffeur', 'modules/drivers-form.php', compact('driver'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save();
            return;
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);

            $this->pdo->prepare("DELETE FROM drivers WHERE id = :id")
                ->execute(['id' => $id]);

            $this->redirect('/admin.php?module=drivers');
        }

        $drivers = $this->pdo->query("
            SELECT * FROM drivers ORDER BY id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $render('Chauffeurs', 'modules/drivers-index.php', compact('drivers'));
    }

    private function save(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        $data = [
            'firstname' => trim($_POST['firstname'] ?? ''),
            'lastname' => trim($_POST['lastname'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'status' => $_POST['status'] ?? 'pending',
        ];

        if ($data['firstname'] === '' && $data['lastname'] === '') {
            $this->redirect('/admin.php?module=drivers&error=Nom obligatoire');
        }

        if ($id > 0) {
            $sql = "UPDATE drivers SET
                firstname=:firstname,
                lastname=:lastname,
                email=:email,
                phone=:phone,
                city=:city,
                status=:status,
                updated_at=CURRENT_TIMESTAMP
                WHERE id=:id";

            $data['id'] = $id;
        } else {
            $sql = "INSERT INTO drivers
                (firstname, lastname, email, phone, city, status)
                VALUES (:firstname,:lastname,:email,:phone,:city,:status)";
        }

        $this->pdo->prepare($sql)->execute($data);

        $this->redirect('/admin.php?module=drivers&success=OK');
    }

    private function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM drivers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return $res ?: null;
    }

    private function emptyDriver(): array
    {
        return [
            'id' => null,
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'phone' => '',
            'city' => '',
            'status' => 'pending'
        ];
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
