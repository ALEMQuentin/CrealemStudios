<section>
    <h2>Utilisateurs</h2>

    <p>
        <a href="/admin.php?module=users&action=create">Ajouter un utilisateur</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Créé le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= e($user['name']) ?></td>
                <td><?= e($user['email']) ?></td>
                <td><?= e($user['role_name'] ?? 'Aucun') ?></td>
                <td><?= e($user['status']) ?></td>
                <td><?= e($user['created_at']) ?></td>
                <td>
                    <a href="/admin.php?module=users&action=edit&id=<?= (int)$user['id'] ?>">Modifier</a>
                    |
                    <a href="/admin.php?module=users&action=delete&id=<?= (int)$user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
