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
}
