<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Catégories produit</h2>
            <div class="text-muted">Organisation hiérarchique du catalogue</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=products&action=create_category">Ajouter une catégorie</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <?php if (empty($categories)): ?>
            <div class="text-muted">Aucune catégorie.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th>Parent</th>
                            <th>Ordre</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= (int)$category['id'] ?></td>
                            <td><?= e($category['name'] ?? '') ?></td>
                            <td><code><?= e($category['slug'] ?? '') ?></code></td>
                            <td><?= e($category['parent_name'] ?? '-') ?></td>
                            <td><?= e((string)($category['sort_order'] ?? '0')) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=products&action=edit_category&id=<?= (int)$category['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=products&action=delete_category&id=<?= (int)$category['id'] ?>" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
