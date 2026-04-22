<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un article' : 'Ajouter un article' ?></h2>
            <div class="text-muted">Contenu éditorial, SEO et catégories</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=blog">Retour aux articles</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=blog&action=save<?= !empty($isEdit) ? '&id=' . (int)$post['id'] : '' ?>">
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" name="title" value="<?= e($post['title'] ?? '') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="draft" <?= (($post['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= (($post['status'] ?? '') === 'published') ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= e($post['slug'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">ID média principal</label>
                    <input type="number" class="form-control" name="featured_media_id" value="<?= e((string)($post['featured_media_id'] ?? '')) ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Extrait</label>
                    <textarea class="form-control" name="excerpt" rows="4"><?= e($post['excerpt'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Contenu</label>
                    <textarea class="form-control" name="content" rows="14"><?= e($post['content'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Meta title</label>
                    <input type="text" class="form-control" name="meta_title" value="<?= e($post['meta_title'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Meta description</label>
                    <input type="text" class="form-control" name="meta_description" value="<?= e($post['meta_description'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Catégories</label>
                    <div class="card" style="box-shadow:none;">
                        <div class="card-body">
                            <div class="row">
                                <?php if (empty($blogCategories)): ?>
                                    <div class="col-12 text-muted">Aucune catégorie disponible.</div>
                                <?php else: ?>
                                    <?php foreach ($blogCategories as $category): ?>
                                        <div class="col-md-4">
                                            <label style="display:flex; align-items:center; gap:0.5rem;">
                                                <input type="checkbox" name="category_ids[]" value="<?= (int)$category['id'] ?>" <?= in_array((int)$category['id'], $selectedCategoryIds ?? [], true) ? 'checked' : '' ?>>
                                                <span><?= e($category['name']) ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer l’article</button>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=blog&action=categories">Gérer les catégories</a>
                </div>
            </div>
        </form>
    </div>
</div>
