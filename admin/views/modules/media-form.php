<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Ajouter un média</h2>
            <div class="text-muted">Importer un fichier dans la bibliothèque</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=media">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=media&action=upload" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label" class="required">Fichier média</label><input type="file" name="media_file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif,.svg" required>
                    <div class="text-muted mt-2">
                        Formats autorisés : JPG, PNG, WEBP, GIF, SVG
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Uploader le média</button>
                </div>
            </div>
        </form>
    </div>
</div>
