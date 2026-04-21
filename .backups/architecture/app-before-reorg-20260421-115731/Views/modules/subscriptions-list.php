<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Abonnements</h2>
        <a class="btn btn-primary" href="/admin.php?module=subscriptions&action=create">Ajouter un abonnement</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                <?php if (empty($subscriptions)): ?>
                    <tr><td colspan="6" class="text-muted">Aucun abonnement.</td></tr>
                <?php else: ?>
                    <?php foreach ($subscriptions as $subscription): ?>
                        <tr>
                            <td><?= (int)$subscription['id'] ?></td>
                            <td><?= e($subscription['title']) ?></td>
                            <td><?= e((string)($subscription['price'] ?? '')) ?></td>
                            <td><?= e($subscription['billing_cycle']) ?></td>
                            <td><?= e($subscription['status']) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=subscriptions&action=edit&id=<?= (int)$subscription['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=subscriptions&action=delete&id=<?= (int)$subscription['id'] ?>" onclick="return confirm('Supprimer cet abonnement ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
