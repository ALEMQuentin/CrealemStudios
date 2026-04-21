<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Avis</h2>
        <a class="btn btn-primary" href="/admin.php?module=testimonials&action=create">Ajouter un avis</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                <?php if (empty($testimonials)): ?>
                    <tr><td colspan="5" class="text-muted">Aucun avis.</td></tr>
                <?php else: ?>
                    <?php foreach ($testimonials as $item): ?>
                        <tr>
                            <td><?= (int)$item['id'] ?></td>
                            <td><?= e($item['author_name']) ?></td>
                            <td><?= (int)$item['rating'] ?>/5</td>
                            <td><?= e($item['status']) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=testimonials&action=edit&id=<?= (int)$item['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=testimonials&action=delete&id=<?= (int)$item['id'] ?>" onclick="return confirm('Supprimer cet avis ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
