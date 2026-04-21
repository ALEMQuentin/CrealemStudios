<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Abonnements</h2>
            <div class="text-muted">Liste des abonnements</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=subscriptions&action=create">Ajouter un abonnement</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <?php if (empty($subscriptions)): ?>
            <div class="text-muted">Aucun abonnement.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Prix</th>
                            <th>Cycle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($subscriptions as $subscription): ?>
                        <tr>
                            <td><?= (int)$subscription['id'] ?></td>
                            <td><?= e($subscription['title'] ?? '') ?></td>
                            <td><?= e((string)($subscription['price'] ?? '')) ?></td>
                            <td><?= e($subscription['billing_cycle'] ?? '') ?></td>
                            <td><?= e($subscription['status'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=subscriptions&action=edit&id=<?= (int)$subscription['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=subscriptions&action=delete&id=<?= (int)$subscription['id'] ?>" onclick="return confirm('Supprimer cet abonnement ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
