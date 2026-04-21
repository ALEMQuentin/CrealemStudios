<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un élément de galerie' : 'Ajouter un élément de galerie' ?></h2>
            <div class="text-muted">Formulaire galerie</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=gallery">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=gallery&action=save<?= !empty($isEdit) ? '&id=' . (int)$galleryItem['id'] : '' ?>">
            <div class="row">
                <div class="col-md-7">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" name="title" value="<?= e($galleryItem['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ID média image</label>
                    <input type="number" class="form-control" name="image_media_id" value="<?= e((string)($galleryItem['image_media_id'] ?? '')) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Ordre</label>
                    <input type="number" class="form-control" name="sort_order" value="<?= e((string)($galleryItem['sort_order'] ?? '0')) ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Légende</label>
                    <textarea class="form-control" name="caption" rows="5"><?= e($galleryItem['caption'] ?? '') ?></textarea>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer l’élément</button>
                </div>
            </div>
        </form>
    </div>
</div>
