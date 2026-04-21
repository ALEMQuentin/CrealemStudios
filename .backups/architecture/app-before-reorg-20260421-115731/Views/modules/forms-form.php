<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un formulaire' : 'Ajouter un formulaire' ?></h2>
            <div class="text-muted">Formulaire du module Formulaires</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=forms">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=forms&action=save<?= !empty($isEdit) ? '&id=' . (int)$form['id'] : '' ?>">
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" name="title" value="<?= e($form['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="draft" <?= (($form['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= (($form['status'] ?? '') === 'published') ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= e($form['slug'] ?? '') ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4"><?= e($form['description'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Schéma JSON du formulaire</label>
                    <textarea class="form-control" name="form_schema_json" rows="16"><?= e($form['form_schema_json'] ?? '[]') ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer le formulaire</button>
                </div>
            </div>
        </form>
    </div>
</div>
