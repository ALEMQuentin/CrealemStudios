<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier une page' : 'Ajouter une page' ?></h2>
            <div class="text-muted">Contenu, SEO et média principal</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=pages">Retour aux pages</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=pages&action=save<?= !empty($isEdit) ? '&id=' . (int)$page['id'] : '' ?>">
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" name="title" value="<?= e($page['title'] ?? '') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="draft" <?= (($page['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= (($page['status'] ?? '') === 'published') ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= e($page['slug'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">ID média principal</label>
                    <input type="number" class="form-control" name="featured_media_id" value="<?= e((string)($page['featured_media_id'] ?? '')) ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Contenu</label>
                    <textarea class="form-control" name="content" rows="14"><?= e($page['content'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Meta title</label>
                    <input type="text" class="form-control" name="meta_title" value="<?= e($page['meta_title'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Meta description</label>
                    <input type="text" class="form-control" name="meta_description" value="<?= e($page['meta_description'] ?? '') ?>">
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer la page</button>
                    <?php if (!empty($isEdit)): ?>
                        <a class="btn btn-outline-secondary" href="/admin.php?module=pages&action=blocks&id=<?= (int)$page['id'] ?>">Gérer les blocs</a>
                        <a class="btn btn-outline-secondary" href="/admin.php?module=pages&action=preview&id=<?= (int)$page['id'] ?>">Aperçu</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>
