<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un média' : 'Ajouter un média' ?></h2>
            <div class="text-muted">Bibliothèque de médias</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=media">Retour à la liste</a>
    </div>
</div>

<form method="post" action="/admin.php?module=media&action=save<?= !empty($isEdit) ? '&id=' . (int)$mediaItem['id'] : '' ?>" enctype="multipart/form-data">
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Titre</label>
                            <input type="text" class="form-control" name="title" value="<?= e($mediaItem['title'] ?? '') ?>">
                        </div>

                        <div class="col-md-12 mt-4">
                            <label class="form-label">Texte alternatif</label>
                            <input type="text" class="form-control" name="alt_text" value="<?= e($mediaItem['alt_text'] ?? '') ?>">
                        </div>

                        <?php if (empty($isEdit)): ?>
                            <div class="col-md-12 mt-4">
                                <label class="form-label">Fichier</label>
                                <input type="file" class="form-control" name="media_file" required>
                            </div>
                        <?php else: ?>
                            <div class="col-md-12 mt-4">
                                <label class="form-label">Fichier actuel</label>
                                <input type="text" class="form-control" value="<?= e($mediaItem['filename'] ?? '') ?>" disabled>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($isEdit) && !empty($mediaItem['filepath']) && str_starts_with((string)($mediaItem['mime_type'] ?? ''), 'image/')): ?>
                        <div class="mb-3">
                            <img src="<?= e($mediaItem['filepath']) ?>" alt="" style="width:100%; max-height:260px; object-fit:contain; border-radius:10px;">
                        </div>
                    <?php endif; ?>

                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</form>
