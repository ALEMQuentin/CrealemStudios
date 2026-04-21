<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Pages</h2>
            <div class="text-muted">Gère les pages du site</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=pages&action=create">Ajouter une page</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="get" action="/admin.php" class="row">
            <input type="hidden" name="module" value="pages">
            <div class="col-md-10">
                <label class="form-label">Recherche</label>
                <input type="text" class="form-control" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Titre ou slug">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-outline-secondary w-100" type="submit">Rechercher</button>
            </div>
        </form>
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
                <?php if (empty($pages)): ?>
                    <tr>
                        <td colspan="6" class="text-muted">Aucune page.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pages as $page): ?>
                        <tr>
                            <td><?= (int)$page['id'] ?></td>
                            <td><?= e($page['title']) ?></td>
                            <td><code><?= e($page['slug']) ?></code></td>
                            <td><?= e($page['status']) ?></td>
                            <td><?= e($page['updated_at'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=pages&action=preview&id=<?= (int)$page['id'] ?>">Aperçu</a>
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=pages&action=edit&id=<?= (int)$page['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=pages&action=blocks&id=<?= (int)$page['id'] ?>">Blocs</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=pages&action=delete&id=<?= (int)$page['id'] ?>" onclick="return confirm('Supprimer cette page ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
