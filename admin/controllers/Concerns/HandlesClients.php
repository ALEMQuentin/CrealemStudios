<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesClients
{
    private function handleClients(string $action): void
    {
        if ($action === 'index') {
            $clients = $this->fetchAllSafe("SELECT * FROM clients ORDER BY id DESC");
            $this->render('Clients', $this->resolveView(['modules/clients-list.php']), compact('clients'));
            return;
        }

        

        if ($action === 'show') {
            $id = (int)($_GET['id'] ?? 0);

            $client = $this->fetchOne("SELECT * FROM clients WHERE id = :id", ['id' => $id]);
            if (!$client) redirectTo('/admin.php?module=clients&error=Client introuvable');

            $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE client_id = :id ORDER BY id DESC");
            $stmt->execute(['id' => $id]);
            $reservations = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->render('Fiche client', $this->resolveView(['modules/clients-show.php']), compact('client','reservations'));
            return;
        }


if ($action === 'create') {
            $client = ['first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '', 'company' => '', 'notes' => ''];
            $isEdit = false;
            $this->render('Ajouter un client', $this->resolveView(['modules/clients-form.php']), compact('client', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $client = $this->fetchOne("SELECT * FROM clients WHERE id = :id", ['id' => $id]);
            if (!$client) redirectTo('/admin.php?module=clients&error=Client introuvable');
            $isEdit = true;
            $this->render('Modifier un client', $this->resolveView(['modules/clients-form.php']), compact('client', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'company' => trim($_POST['company'] ?? ''),
                'notes' => trim($_POST['notes'] ?? ''),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('clients', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('clients', $data);
            }
            redirectTo('/admin.php?module=clients&success=Client enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('clients', $id);
            redirectTo('/admin.php?module=clients&success=Client supprimé');
        }

        redirectTo('/admin.php?module=clients');
    }
    private function ensureClientUserAccount(int $clientId): void
    {
        $client = $this->fetchOne("SELECT * FROM clients WHERE id = :id", ['id' => $clientId]);

        if (!$client || empty($client['email'])) {
            return;
        }

        if (!empty($client['user_id'])) {
            return;
        }

        $existingUser = $this->fetchOne("SELECT * FROM users WHERE email = :email LIMIT 1", [
            'email' => $client['email'],
        ]);

        if ($existingUser) {
            $this->pdo->prepare("UPDATE clients SET user_id = :user_id WHERE id = :client_id")
                ->execute([
                    'user_id' => (int)$existingUser['id'],
                    'client_id' => $clientId,
                ]);
            return;
        }

        $name = trim((string)($client['first_name'] ?? '') . ' ' . (string)($client['last_name'] ?? ''));
        $temporaryPassword = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password, role, created_at, updated_at)
            VALUES (:name, :email, :password, 'client', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");

        $stmt->execute([
            'name' => $name !== '' ? $name : (string)$client['email'],
            'email' => (string)$client['email'],
            'password' => $temporaryPassword,
        ]);

        $this->pdo->prepare("UPDATE clients SET user_id = :user_id WHERE id = :client_id")
            ->execute([
                'user_id' => (int)$this->pdo->lastInsertId(),
                'client_id' => $clientId,
            ]);
    }


}
