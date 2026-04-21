<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Catégories du blog</h2>
        <a class="btn btn-primary" href="/?module=blog&action=create_category">Ajouter une catégorie</a>
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
                        <th>Slug</th>
                        <th>Créée le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" class="text-muted">Aucune catégorie.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= (int)$category['id'] ?></td>
                            <td><?= e($category['name']) ?></td>
                            <td><code><?= e($category['slug']) ?></code></td>
                            <td><?= e($category['created_at'] ?? '') ?></td>
                            <td>
                                <div class="cms-table-actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="/?module=blog&action=edit_category&id=<?= (int)$category['id'] ?>">Modifier</a>
                                    <a class="btn btn-sm btn-outline-danger" href="/?module=blog&action=delete_category&id=<?= (int)$category['id'] ?>" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
