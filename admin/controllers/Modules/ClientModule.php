<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Modules;

use PDO;

final class ClientModule
{
    public function __construct(private PDO $pdo)
    {
    }

    public function handle(string $action, callable $render): void
    {
        if ($action === 'create') {
            $client = [
                'id' => null,
                'firstname' => '',
                'lastname' => '',
                'email' => '',
                'phone' => '',
                'notes' => '',
            ];

            $render('Nouveau client', 'modules/clients-form.php', compact('client'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $client = $this->findClient($id);

            if (!$client) {
                $this->redirect('/admin.php?module=clients&error=Client introuvable');
            }

            $render('Modifier client', 'modules/clients-form.php', compact('client'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveClient();
            return;
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);

            $stmt = $this->pdo->prepare("DELETE FROM clients WHERE id = :id");
            $stmt->execute(['id' => $id]);

            $this->redirect('/admin.php?module=clients&success=Client supprimé');
        }

        $clients = $this->pdo->query("
            SELECT *
            FROM clients
            ORDER BY id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $render('Clients', 'modules/clients-index.php', compact('clients'));
    }

    private function saveClient(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        $data = [
            'firstname' => trim((string)($_POST['firstname'] ?? '')),
            'lastname' => trim((string)($_POST['lastname'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'phone' => trim((string)($_POST['phone'] ?? '')),
            'notes' => trim((string)($_POST['notes'] ?? '')),
        ];

        if ($data['firstname'] === '' && $data['lastname'] === '') {
            $this->redirect('/admin.php?module=clients&error=Nom ou prénom obligatoire');
        }

        if ($id > 0) {
            $stmt = $this->pdo->prepare("
                UPDATE clients
                SET firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    phone = :phone,
                    notes = :notes,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $data['id'] = $id;
            $stmt->execute($data);

            $this->redirect('/admin.php?module=clients&success=Client modifié');
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO clients (firstname, lastname, email, phone, notes, created_at, updated_at)
            VALUES (:firstname, :lastname, :email, :phone, :notes, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");

        $stmt->execute($data);

        $this->redirect('/admin.php?module=clients&success=Client créé');
    }

    private function findClient(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        return $client ?: null;
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
