<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier une catégorie produit' : 'Ajouter une catégorie produit' ?></h2>
            <div class="text-muted">Formulaire catégorie</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=categories">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=products&action=save_category<?= !empty($isEdit) ? '&id=' . (int)$category['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" name="name" value="<?= e($category['name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= e($category['slug'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mt-4">
                    <label class="form-label">Catégorie parente</label>
                    <select class="form-select" name="parent_id">
                        <option value="">Aucune</option>
                        <?php foreach (($allCategories ?? []) as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" <?= ((string)($category['parent_id'] ?? '') === (string)$cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mt-4">
                    <label class="form-label">Ordre</label>
                    <input type="number" class="form-control" name="sort_order" value="<?= e((string)($category['sort_order'] ?? '0')) ?>">
                </div>
                <div class="col-12 mt-4">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="6"><?= e($category['description'] ?? '') ?></textarea>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer la catégorie</button>
                </div>
            </div>
        </form>
    </div>
</div>
