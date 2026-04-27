<section>
    <h2>Rôles</h2>

    <p>
        <a href="/admin.php?module=roles&action=create">Ajouter un rôle</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Identifiant</th>
                <th>Description</th>
                <th>Utilisateurs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($roles as $role): ?>
            <tr>
                <td><?= e($role['name']) ?></td>
                <td><code><?= e($role['slug']) ?></code></td>
                <td><?= e($role['description']) ?></td>
                <td><?= (int)$role['users_count'] ?></td>
                <td>
                    <a href="/admin.php?module=roles&action=edit&id=<?= (int)$role['id'] ?>">Modifier</a>
                    |
                    <a href="/admin.php?module=roles&action=delete&id=<?= (int)$role['id'] ?>" onclick="return confirm('Supprimer ce rôle ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
