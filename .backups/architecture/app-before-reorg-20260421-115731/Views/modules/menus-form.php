<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un menu' : 'Ajouter un menu' ?></h2>
            <div class="text-muted">Nom du menu et emplacement dans le site</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=menus">Retour aux menus</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=menus&action=save<?= !empty($isEdit) ? '&id=' . (int)$menu['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nom du menu</label>
                    <input type="text" class="form-control" name="name" value="<?= e($menu['name'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Emplacement</label>
                    <input type="text" class="form-control" name="location_key" value="<?= e($menu['location_key'] ?? 'main') ?>" placeholder="main" required>
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer le menu</button>
                    <?php if (!empty($isEdit)): ?>
                        <a class="btn btn-outline-secondary" href="/admin.php?module=menus&action=items&id=<?= (int)$menu['id'] ?>">Gérer les éléments</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>
