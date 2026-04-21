<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un terme' : 'Ajouter un terme' ?></h2>
            <div class="text-muted">Attribut : <?= e($attribute['name'] ?? '') ?></div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=attribute_terms&id=<?= (int)$attribute['id'] ?>">Retour aux termes</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=products&action=save_attribute_term&id=<?= (int)$attribute['id'] ?><?= !empty($isEdit) ? '&term_id=' . (int)$term['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" name="name" value="<?= e($term['name'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= e($term['slug'] ?? '') ?>" required>
                </div>

                <div class="col-md-4 mt-4">
                    <label class="form-label">Ordre</label>
                    <input type="number" class="form-control" name="sort_order" value="<?= e((string)($term['sort_order'] ?? '0')) ?>">
                </div>

                <div class="col-12 mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer le terme</button>
                </div>
            </div>
        </form>
    </div>
</div>
