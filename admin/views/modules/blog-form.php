<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un article' : 'Ajouter un article' ?></h2>
            <div class="text-muted">Éditeur d’article</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=blog">Retour à la liste</a>
    </div>
</div>

<script type="application/json" id="cs-media-library-data"><?= json_encode($mediaLibrary ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

<form method="post" action="/admin.php?module=blog&action=save<?= !empty($isEdit) ? '&id=' . (int)$post['id'] : '' ?>
    <?= \Csrf::input() ?>">
    <div class="row mt-4">
        <div class="col-md-8">

            <div class="card">
                <div class="card-body">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" name="title" value="<?= e($post['title'] ?? '') ?>" required>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" name="slug" value="<?= e($post['slug'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Extrait</label>
                            <textarea class="form-control" name="excerpt" rows="3"><?= e($post['excerpt'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Contenu</label>
                        <textarea class="form-control" name="content" rows="14"><?= e($post['content'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="draft" <?= (($post['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= (($post['status'] ?? '') === 'published') ? 'selected' : '' ?>>Publié</option>
                    </select>

                    <div class="mt-4">
                        <label class="form-label">Catégories</label>
                        <div class="card" style="box-shadow:none;">
                            <div class="card-body">
                                <?php if (empty($blogCategories)): ?>
                                    <div class="text-muted">Aucune catégorie.</div>
                                <?php else: ?>
                                    <?php foreach ($blogCategories as $category): ?>
                                        <label style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.45rem;">
                                            <input type="checkbox" name="category_ids[]" value="<?= (int)$category['id'] ?>" <?= in_array((int)$category['id'], $selectedCategoryIds ?? [], true) ? 'checked' : '' ?>>
                                            <span><?= e($category['name']) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Image mise en avant (ID média)</label>
                        <input type="number" class="form-control" id="blog_featured_media_id" name="featured_media_id" value="<?= e((string)($post['featured_media_id'] ?? '')) ?>">

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openMediaModal('blog-featured')">Choisir une image</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearBlogFeaturedMedia()">Retirer l’image</button>
                        </div>

                        <div id="blog-featured-media-preview" style="margin-top:10px;"></div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Titre SEO</label>
                        <input type="text" class="form-control" name="seo_title" value="<?= e($post['seo_title'] ?? '') ?>">
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Meta description</label>
                        <textarea class="form-control" name="seo_description" rows="4"><?= e($post['seo_description'] ?? '') ?></textarea>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function getBlogMediaLibraryMap() {
    var source = document.getElementById('cs-media-library-data');
    if (!source) return {};
    try {
        var items = JSON.parse(source.textContent || '[]');
        var map = {};
        items.forEach(function(item) { map[String(item.id)] = item; });
        return map;
    } catch (e) {
        return {};
    }
}

function selectBlogFeaturedMedia(mediaId) {
    var field = document.getElementById('blog_featured_media_id');
    if (!field) return;
    field.value = mediaId;
    renderBlogFeaturedPreview();
}

function clearBlogFeaturedMedia() {
    var field = document.getElementById('blog_featured_media_id');
    if (!field) return;
    field.value = '';
    renderBlogFeaturedPreview();
}

function renderBlogFeaturedPreview() {
    var id = document.getElementById('blog_featured_media_id').value;
    var items = getBlogMediaLibraryMap();
    var container = document.getElementById('blog-featured-media-preview');
    if (!container) return;
    container.innerHTML = '';

    if (!id) return;

    var item = items[String(id)];
    if (item && item.path) {
        var img = document.createElement('img');
        img.src = item.path;
        img.alt = item.original_name || item.filename || '';
        img.style.maxWidth = '140px';
        img.style.maxHeight = '140px';
        img.style.objectFit = 'contain';
        img.style.borderRadius = '8px';
        container.appendChild(img);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    renderBlogFeaturedPreview();
});
</script>
