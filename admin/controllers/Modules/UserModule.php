<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Modules;

use PDO;

final class UserModule
{
    public function __construct(private PDO $pdo)
    {
    }

    public function handleUsers(string $action, callable $render): void
    {
        if ($action === 'create') {
            $roles = $this->roles();
            $user = [
                'id' => null,
                'name' => '',
                'email' => '',
                'role_id' => '',
                'status' => 'active',
            ];

            $render('Nouvel utilisateur', 'modules/users-form.php', compact('user', 'roles'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $user = $this->findUser($id);

            if (!$user) {
                $this->redirect('/admin.php?module=users&error=Utilisateur introuvable');
            }

            $roles = $this->roles();
            $render('Modifier utilisateur', 'modules/users-form.php', compact('user', 'roles'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveUser();
            return;
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);

            $this->redirect('/admin.php?module=users&success=Utilisateur supprimé');
        }

        $users = $this->pdo->query("
            SELECT users.*, roles.name AS role_name, roles.slug AS role_slug
            FROM users
            LEFT JOIN roles ON roles.id = users.role_id
            ORDER BY users.id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $render('Utilisateurs', 'modules/users-index.php', compact('users'));
    }

    public function handleRoles(string $action, callable $render): void
    {
        if ($action === 'create') {
            $role = [
                'id' => null,
                'name' => '',
                'slug' => '',
                'description' => '',
                'permissions' => '',
            ];

            $render('Nouveau rôle', 'modules/roles-form.php', compact('role'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $role = $this->findRole($id);

            if (!$role) {
                $this->redirect('/admin.php?module=roles&error=Rôle introuvable');
            }

            $render('Modifier rôle', 'modules/roles-form.php', compact('role'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveRole();
            return;
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role_id = :id");
            $stmt->execute(['id' => $id]);

            if ((int)$stmt->fetchColumn() > 0) {
                $this->redirect('/admin.php?module=roles&error=Impossible de supprimer un rôle utilisé');
            }

            $stmt = $this->pdo->prepare("DELETE FROM roles WHERE id = :id");
            $stmt->execute(['id' => $id]);

            $this->redirect('/admin.php?module=roles&success=Rôle supprimé');
        }

        $roles = $this->pdo->query("
            SELECT roles.*,
                   (SELECT COUNT(*) FROM users WHERE users.role_id = roles.id) AS users_count
            FROM roles
            ORDER BY roles.id ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $render('Rôles', 'modules/roles-index.php', compact('roles'));
    }

    private function saveUser(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));
        $roleId = (int)($_POST['role_id'] ?? 0);
        $status = trim((string)($_POST['status'] ?? 'active'));

        if ($name === '' || $email === '' || $roleId <= 0) {
            $this->redirect('/admin.php?module=users&error=Nom, email et rôle obligatoires');
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            $status = 'active';
        }

        if ($id > 0) {
            if ($password !== '') {
                $stmt = $this->pdo->prepare("
                    UPDATE users
                    SET name = :name,
                        email = :email,
                        password = :password,
                        role_id = :role_id,
                        status = :status,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id
                ");

                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role_id' => $roleId,
                    'status' => $status,
                    'id' => $id,
                ]);
            } else {
                $stmt = $this->pdo->prepare("
                    UPDATE users
                    SET name = :name,
                        email = :email,
                        role_id = :role_id,
                        status = :status,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id
                ");

                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'role_id' => $roleId,
                    'status' => $status,
                    'id' => $id,
                ]);
            }

            $this->redirect('/admin.php?module=users&success=Utilisateur modifié');
        }

        if ($password === '') {
            $password = bin2hex(random_bytes(5));
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password, role_id, status, created_at, updated_at)
            VALUES (:name, :email, :password, :role_id, :status, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role_id' => $roleId,
            'status' => $status,
        ]);

        $this->redirect('/admin.php?module=users&success=Utilisateur créé');
    }

    private function saveRole(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $permissions = trim((string)($_POST['permissions'] ?? ''));

        if ($name === '' || $slug === '') {
            $this->redirect('/admin.php?module=roles&error=Nom et identifiant obligatoires');
        }

        if ($permissions === '') {
            $permissions = '[]';
        }

        json_decode($permissions, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->redirect('/admin.php?module=roles&error=Permissions JSON invalides');
        }

        if ($id > 0) {
            $stmt = $this->pdo->prepare("
                UPDATE roles
                SET name = :name,
                    slug = :slug,
                    description = :description,
                    permissions = :permissions,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'permissions' => $permissions,
                'id' => $id,
            ]);

            $this->redirect('/admin.php?module=roles&success=Rôle modifié');
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO roles (name, slug, description, permissions, created_at, updated_at)
            VALUES (:name, :slug, :description, :permissions, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");

        $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'permissions' => $permissions,
        ]);

        $this->redirect('/admin.php?module=roles&success=Rôle créé');
    }

    private function roles(): array
    {
        return $this->pdo->query("SELECT * FROM roles ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    private function findUser(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    private function findRole(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        return $role ?: null;
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
