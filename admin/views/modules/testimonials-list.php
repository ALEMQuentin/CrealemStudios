<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Avis</h2>
            <div class="text-muted">Liste des avis</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=testimonials&action=create">Ajouter un avis</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <?php if (empty($testimonials)): ?>
            <div class="text-muted">Aucun avis.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Auteur</th>
                            <th>Note</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($testimonials as $item): ?>
                        <tr>
                            <td><?= (int)$item['id'] ?></td>
                            <td><?= e($item['author_name'] ?? '') ?></td>
                            <td><?= (int)($item['rating'] ?? 0) ?>/5</td>
                            <td><?= e($item['status'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=testimonials&action=edit&id=<?= (int)$item['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=testimonials&action=delete&id=<?= (int)$item['id'] ?>" onclick="return confirm('Supprimer cet avis ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
