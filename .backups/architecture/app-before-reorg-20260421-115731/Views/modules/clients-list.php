<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Clients</h2>
        <a class="btn btn-primary" href="/admin.php?module=clients&action=create">Ajouter un client</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                <?php if (empty($clients)): ?>
                    <tr><td colspan="5" class="text-muted">Aucun client.</td></tr>
                <?php else: ?>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= (int)$client['id'] ?></td>
                            <td><?= e(trim(($client['first_name'] ?? '') . ' ' . ($client['last_name'] ?? ''))) ?></td>
                            <td><?= e($client['email'] ?? '') ?></td>
                            <td><?= e($client['phone'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=clients&action=edit&id=<?= (int)$client['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=clients&action=delete&id=<?= (int)$client['id'] ?>" onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
