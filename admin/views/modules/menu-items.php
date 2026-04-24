<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h5 mb-1">Éléments du menu</h2>
            <div class="text-muted"><?= e($menu['name'] ?? '') ?></div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=menus">Retour aux menus</a>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Pages existantes</h3>

                <?php if (empty($pagesForMenu)): ?>
                    <div class="text-muted">Aucune page disponible.</div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($pagesForMenu as $pageOption): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold"><?= e($pageOption['title']) ?></div>
                                    <div class="small text-muted"><?= e($pageOption['slug']) ?></div>
                                </div>

                                <form method="post" action="/admin.php?module=menus&action=add_item&id=<?= (int)$menu['id'] ?>" class="m-0">
                                    <input type="hidden" name="label" value="<?= e($pageOption['title']) ?>">
                                    <input type="hidden" name="item_type" value="page">
                                    <input type="hidden" name="page_id" value="<?= (int)$pageOption['id'] ?>">
                                    <input type="hidden" name="sort_order" value="0">
                                    <button class="btn btn-sm btn-primary" type="submit">Ajouter</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Éléments déjà dans le menu</h3>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Libellé</th>
                                <th>Type</th>
                                <th>URL</th>
                                <th>Ordre</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="6" class="text-muted">Aucun élément de menu.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= (int)$item['id'] ?></td>
                                    <td><?= e($item['label']) ?></td>
                                    <td><?= e($item['item_type']) ?></td>
                                    <td><code><?= e($item['url'] ?? '') ?></code></td>
                                    <td><?= (int)($item['sort_order'] ?? 0) ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=menus&action=delete_item&id=<?= (int)$menu['id'] ?>&item_id=<?= (int)$item['id'] ?>" onclick="return confirm('Supprimer cet élément ?')">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <hr class="my-4">

                <h3 class="h6 mb-3">Ajouter un lien personnalisé</h3>

                <form method="post" action="/admin.php?module=menus&action=add_item&id=<?= (int)$menu['id'] ?>">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label" class="required">Libellé</label><input type="text" class="form-control" name="label" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" name="url" placeholder="/contact ou https://...">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Ordre</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                        </div>

                        <input type="hidden" name="item_type" value="custom">
                        <input type="hidden" name="page_id" value="0">

                        <div class="col-12">
                            <button class="btn btn-outline-secondary" type="submit">Ajouter le lien</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
