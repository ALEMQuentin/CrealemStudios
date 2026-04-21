<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Utilisateurs</h2>
            <div class="text-muted">Gestion des comptes d’administration</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=users&action=create">Ajouter un utilisateur</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-muted">Aucun utilisateur.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= (int)$user['id'] ?></td>
                            <td><?= e($user['name'] ?? '') ?></td>
                            <td><?= e($user['email'] ?? '') ?></td>
                            <td><?= e($user['role'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=users&action=edit&id=<?= (int)$user['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=users&action=delete&id=<?= (int)$user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
