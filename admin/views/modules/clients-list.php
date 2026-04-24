<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Clients</h2>
            <div class="text-muted">Liste des clients</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=clients&action=create">Ajouter un client</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <?php if (empty($clients)): ?>
            <div class="text-muted">Aucun client.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= (int)$client['id'] ?></td>
                            <td><?= e(trim(($client['first_name'] ?? '') . ' ' . ($client['last_name'] ?? ''))) ?></td>
                            <td><?= e($client['email'] ?? '') ?></td>
                            <td><?= e($client['phone'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=clients&action=edit&id=<?= (int)$client['id'] ?>">Voir</a>
<a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=clients&action=edit&id=<?= (int)$client['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=clients&action=delete&id=<?= (int)$client['id'] ?>" onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
