<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un attribut' : 'Ajouter un attribut' ?></h2>
            <div class="text-muted">Attribut global du catalogue</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=attributes">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=products&action=save_attribute<?= !empty($isEdit) ? '&id=' . (int)$attribute['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" name="name" value="<?= e($attribute['name'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= e($attribute['slug'] ?? '') ?>" required>
                </div>

                <div class="col-md-4 mt-4">
                    <label class="form-label">Ordre</label>
                    <input type="number" class="form-control" name="sort_order" value="<?= e((string)($attribute['sort_order'] ?? '0')) ?>">
                </div>

                <div class="col-12 mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer l’attribut</button>
                </div>
            </div>
        </form>
    </div>
</div>
