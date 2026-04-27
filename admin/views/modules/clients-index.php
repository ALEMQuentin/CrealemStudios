<section>
    <h2>Clients</h2>

    <p>
        <a href="/admin.php?module=clients&action=create">Ajouter un client</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Créé le</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= e(trim(($client['firstname'] ?? '') . ' ' . ($client['lastname'] ?? ''))) ?></td>
                    <td><?= e($client['email'] ?? '') ?></td>
                    <td><?= e($client['phone'] ?? '') ?></td>
                    <td><?= e($client['created_at'] ?? '') ?></td>
                    <td>
                        <a href="/admin.php?module=clients&action=edit&id=<?= (int)$client['id'] ?>">Modifier</a>
                        |
                        <a href="/admin.php?module=clients&action=delete&id=<?= (int)$client['id'] ?>" onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
