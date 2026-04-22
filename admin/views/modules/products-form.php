<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un produit' : 'Ajouter un produit' ?></h2>
            <div class="text-muted">Éditeur produit type WooCommerce</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <?php if (!empty($isEdit) && !empty($product['id'])): ?>
                <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=variations&id=<?= (int)$product['id'] ?>">Variations</a>
            <?php endif; ?>
            <a class="btn btn-outline-secondary" href="/admin.php?module=products">Retour à la liste</a>
        </div>
    </div>
</div>

<script type="application/json" id="cs-media-library-data"><?= json_encode($mediaLibrary ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

<form method="post" action="/admin.php?module=products&action=save<?= !empty($isEdit) ? '&id=' . (int)$product['id'] : '' ?>
    <?= Csrf::input() ?>">
    <div class="row mt-4">
        <div class="col-md-8">

            <div class="card">
                <div class="card-body">
                    <h3 class="h6 mb-3">Informations principales</h3>

                    <label class="form-label">Nom du produit</label>
                    <input type="text" class="form-control" name="title" value="<?= e($product['title'] ?? '') ?>" required>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" name="slug" value="<?= e($product['slug'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" name="sku" value="<?= e($product['sku'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Description longue</label>
                        <textarea class="form-control" name="content" rows="10"><?= e($product['content'] ?? '') ?></textarea>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Description courte</label>
                        <textarea class="form-control" name="short_description" rows="5"><?= e($product['short_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="h6 mb-3">Tarification et type</h3>

                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Type de produit</label>
                            <select class="form-select" name="product_type">
                                <option value="simple" <?= (($product['product_type'] ?? 'simple') === 'simple') ? 'selected' : '' ?>>Produit simple</option>
                                <option value="variable" <?= (($product['product_type'] ?? '') === 'variable') ? 'selected' : '' ?>>Produit variable</option>
                                <option value="grouped" <?= (($product['product_type'] ?? '') === 'grouped') ? 'selected' : '' ?>>Produit groupé</option>
                                <option value="external" <?= (($product['product_type'] ?? '') === 'external') ? 'selected' : '' ?>>Produit externe / affilié</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Prix normal</label>
                            <input type="number" step="0.01" class="form-control" name="regular_price" value="<?= e((string)($product['regular_price'] ?? '')) ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Prix promo</label>
                            <input type="number" step="0.01" class="form-control" name="sale_price" value="<?= e((string)($product['sale_price'] ?? '')) ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Visibilité catalogue</label>
                            <select class="form-select" name="catalog_visibility">
                                <option value="visible" <?= (($product['catalog_visibility'] ?? 'visible') === 'visible') ? 'selected' : '' ?>>Visible</option>
                                <option value="catalog" <?= (($product['catalog_visibility'] ?? '') === 'catalog') ? 'selected' : '' ?>>Catalogue uniquement</option>
                                <option value="search" <?= (($product['catalog_visibility'] ?? '') === 'search') ? 'selected' : '' ?>>Recherche uniquement</option>
                                <option value="hidden" <?= (($product['catalog_visibility'] ?? '') === 'hidden') ? 'selected' : '' ?>>Masqué</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="h6 mb-3">Galerie produit</h3>
                    <p class="text-muted">Le champ reste là pour compatibilité, mais la gestion se fait visuellement.</p>
                    <textarea class="form-control" id="gallery_media_ids" name="gallery_media_ids" rows="3"><?= e($galleryMediaIdsString ?? '') ?></textarea>

                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openMediaModal('product-gallery')">Ouvrir la bibliothèque média</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearGalleryMedia()">Vider la galerie</button>
                    </div>

                    <div id="gallery-media-preview" style="margin-top:12px; display:flex; flex-wrap:wrap; gap:10px;"></div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="draft" <?= (($product['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= (($product['status'] ?? '') === 'published') ? 'selected' : '' ?>>Publié</option>
                    </select>

                    <div class="mt-4">
                        <label class="form-label">Ordre</label>
                        <input type="number" class="form-control" name="sort_order" value="<?= e((string)($product['sort_order'] ?? '0')) ?>">
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Image produit (ID média)</label>
                        <input type="number" class="form-control" id="featured_media_id" name="featured_media_id" value="<?= e((string)($product['featured_media_id'] ?? '')) ?>">

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openMediaModal('product-featured')">Choisir une image</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFeaturedMedia()">Retirer l’image principale</button>
                        </div>

                        <div id="featured-media-preview" style="margin-top:10px;"></div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Catégories</label>
                        <div class="card" style="box-shadow:none;">
                            <div class="card-body">
                                <?php if (empty($productCategories)): ?>
                                    <div class="text-muted">Aucune catégorie.</div>
                                <?php else: ?>
                                    <?php foreach ($productCategories as $category): ?>
                                        <label style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.45rem;">
                                            <input type="checkbox" name="category_ids[]" value="<?= (int)$category['id'] ?>" <?= in_array((int)$category['id'], $selectedCategoryIds ?? [], true) ? 'checked' : '' ?>>
                                            <span><?= e($category['name']) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                        <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=categories">Gérer catégories</a>
                        <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=attributes">Gérer attributs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function getMediaLibraryMap() {
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

function getGalleryIds() {
    var field = document.getElementById('gallery_media_ids');
    if (!field) return [];
    return field.value.split(',').map(function(v) { return v.trim(); }).filter(Boolean);
}

function saveGalleryIds(ids) {
    var field = document.getElementById('gallery_media_ids');
    if (!field) return;
    field.value = ids.join(',');
}

function selectFeaturedMedia(mediaId) {
    var field = document.getElementById('featured_media_id');
    if (!field) return;
    field.value = mediaId;
    renderFeaturedPreview();
}

function clearFeaturedMedia() {
    var field = document.getElementById('featured_media_id');
    if (!field) return;
    field.value = '';
    renderFeaturedPreview();
}

function addMediaToGallery(mediaId) {
    var ids = getGalleryIds();
    if (!ids.includes(String(mediaId))) {
        ids.push(String(mediaId));
    }
    saveGalleryIds(ids);
    renderGalleryPreview();
}

function removeMediaFromGallery(mediaId) {
    var ids = getGalleryIds().filter(function(id) { return id !== String(mediaId); });
    saveGalleryIds(ids);
    renderGalleryPreview();
}

function moveGalleryMedia(mediaId, direction) {
    var ids = getGalleryIds();
    var index = ids.indexOf(String(mediaId));
    if (index === -1) return;

    var newIndex = direction === 'up' ? index - 1 : index + 1;
    if (newIndex < 0 || newIndex >= ids.length) return;

    var temp = ids[index];
    ids[index] = ids[newIndex];
    ids[newIndex] = temp;

    saveGalleryIds(ids);
    renderGalleryPreview();
}

function clearGalleryMedia() {
    saveGalleryIds([]);
    renderGalleryPreview();
}

function renderFeaturedPreview() {
    var id = document.getElementById('featured_media_id').value;
    var container = document.getElementById('featured-media-preview');
    if (!container) return;
    container.innerHTML = '';

    if (!id) return;

    var item = getMediaLibraryMap()[String(id)];
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

function renderGalleryPreview() {
    var ids = getGalleryIds();
    var items = getMediaLibraryMap();
    var container = document.getElementById('gallery-media-preview');
    if (!container) return;
    container.innerHTML = '';

    ids.forEach(function(id, index) {
        var item = items[String(id)];
        if (!item || !item.path) return;

        var wrapper = document.createElement('div');
        wrapper.style.border = '1px solid #e5e7eb';
        wrapper.style.borderRadius = '8px';
        wrapper.style.padding = '8px';
        wrapper.style.background = '#fff';
        wrapper.style.width = '110px';

        var img = document.createElement('img');
        img.src = item.path;
        img.alt = item.original_name || item.filename || '';
        img.style.width = '100%';
        img.style.height = '80px';
        img.style.objectFit = 'cover';
        img.style.borderRadius = '6px';
        wrapper.appendChild(img);

        var meta = document.createElement('div');
        meta.style.fontSize = '12px';
        meta.style.marginTop = '6px';
        meta.textContent = 'ID: ' + id;
        wrapper.appendChild(meta);

        var actions = document.createElement('div');
        actions.style.display = 'grid';
        actions.style.gap = '4px';
        actions.style.marginTop = '8px';

        var upBtn = document.createElement('button');
        upBtn.type = 'button';
        upBtn.className = 'btn btn-outline-secondary btn-sm';
        upBtn.textContent = 'Monter';
        upBtn.disabled = index === 0;
        upBtn.onclick = function() { moveGalleryMedia(id, 'up'); };
        actions.appendChild(upBtn);

        var downBtn = document.createElement('button');
        downBtn.type = 'button';
        downBtn.className = 'btn btn-outline-secondary btn-sm';
        downBtn.textContent = 'Descendre';
        downBtn.disabled = index === ids.length - 1;
        downBtn.onclick = function() { moveGalleryMedia(id, 'down'); };
        actions.appendChild(downBtn);

        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger btn-sm';
        removeBtn.textContent = 'Retirer';
        removeBtn.onclick = function() { removeMediaFromGallery(id); };
        actions.appendChild(removeBtn);

        wrapper.appendChild(actions);
        container.appendChild(wrapper);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    renderFeaturedPreview();
    renderGalleryPreview();
});
</script>
