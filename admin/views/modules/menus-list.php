<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Menus</h2>
            <div class="text-muted">Organisation des navigations du site</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=menus&action=create">Ajouter un menu</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Emplacement</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($menus)): ?>
                    <tr>
                        <td colspan="5" class="text-muted">Aucun menu.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($menus as $menu): ?>
                        <tr>
                            <td><?= (int)$menu['id'] ?></td>
                            <td><?= e($menu['name']) ?></td>
                            <td><code><?= e($menu['location_key'] ?? '') ?></code></td>
                            <td><?= e($menu['created_at'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=menus&action=items&id=<?= (int)$menu['id'] ?>">Éléments</a>
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=menus&action=edit&id=<?= (int)$menu['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=menus&action=delete&id=<?= (int)$menu['id'] ?>" onclick="return confirm('Supprimer ce menu ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
