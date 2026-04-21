<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Articles</h2>
            <div class="text-muted">Gère le contenu éditorial</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-secondary" href="/admin.php?module=blog&action=categories">Catégories</a>
            <a class="btn btn-primary" href="/admin.php?module=blog&action=create">Ajouter un article</a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Statut</th>
                        <th>Mis à jour</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="6" class="text-muted">Aucun article.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= (int)$post['id'] ?></td>
                            <td><?= e($post['title']) ?></td>
                            <td><code><?= e($post['slug']) ?></code></td>
                            <td><?= e($post['status']) ?></td>
                            <td><?= e($post['updated_at'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=blog&action=edit&id=<?= (int)$post['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=blog&action=delete&id=<?= (int)$post['id'] ?>" onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
