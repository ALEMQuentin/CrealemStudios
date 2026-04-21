<div class="card">
    <div class="card-body">
        <h2 class="h5 mb-4"><?= $isEdit ? 'Modifier une catégorie' : 'Ajouter une catégorie' ?></h2>

        <form method="post" action="/?module=blog&action=save_category<?= $isEdit ? '&id=' . (int)$category['id'] : '' ?>">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" name="name" required value="<?= e($category['name'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" name="slug" required value="<?= e($category['slug'] ?? '') ?>">
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
                <a class="btn btn-outline-secondary" href="/?module=blog&action=categories">Retour</a>
            </div>
        </form>
    </div>
</div>
