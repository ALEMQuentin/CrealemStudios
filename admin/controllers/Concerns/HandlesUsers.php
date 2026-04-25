<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesUsers
{
    private function handleUsers(string $action): void
    {
        if ($action === 'index') {
            $users = $this->fetchAllSafe("SELECT * FROM users ORDER BY id DESC");
            $this->render('Utilisateurs', $this->resolveView(['modules/users-list.php']), compact('users'));
            return;
        }

        if ($action === 'create') {
            $user = ['name' => '', 'email' => '', 'role' => 'editor'];
            $isEdit = false;
            $this->render('Ajouter un utilisateur', $this->resolveView(['modules/users-form.php']), compact('user', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $user = $this->fetchOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);

            if (!$user) {
                redirectTo('/admin.php?module=users&error=Utilisateur introuvable');
            }

            $isEdit = true;
            $this->render('Modifier un utilisateur', $this->resolveView(['modules/users-form.php']), compact('user', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $now = date('Y-m-d H:i:s');

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => in_array(trim((string)($_POST['role'] ?? 'editor')), ['admin', 'editor', 'client'], true) ? trim((string)$_POST['role']) : 'editor',
                'updated_at' => $now,
            ];

            $password = trim($_POST['password'] ?? '');

            if ($id > 0) {
                if ($password !== '') {
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                $this->updateRow('users', $id, $data);
                redirectTo('/admin.php?module=users&success=Utilisateur modifié');
            }

            $data['password'] = password_hash($password !== '' ? $password : 'admin1234', PASSWORD_DEFAULT);
            $data['created_at'] = $now;
            $this->insertRow('users', $data);

            redirectTo('/admin.php?module=users&success=Utilisateur ajouté');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('users', $id);
            redirectTo('/admin.php?module=users&success=Utilisateur supprimé');
        }

        redirectTo('/admin.php?module=users');
    }

}
