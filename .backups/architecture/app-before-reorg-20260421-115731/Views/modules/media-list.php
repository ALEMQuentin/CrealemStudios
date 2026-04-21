<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="h5 mb-1">Médias</h2>
                <div class="text-muted">Bibliothèque d’images et fichiers</div>
            </div>
        </div>

        <form method="post" action="/admin.php?module=media&action=upload" enctype="multipart/form-data" class="row mt-4">
            <div class="col-md-10">
                <label class="form-label">Fichier</label>
                <input type="file" class="form-control" name="media_file" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="submit">Uploader</button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom original</th>
                        <th>Fichier</th>
                        <th>Type MIME</th>
                        <th>Taille</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($mediaItems)): ?>
                    <tr>
                        <td colspan="6" class="text-muted">Aucun média.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($mediaItems as $item): ?>
                        <tr>
                            <td><?= (int)$item['id'] ?></td>
                            <td><?= e($item['original_name'] ?? '') ?></td>
                            <td><code><?= e($item['filename'] ?? '') ?></code></td>
                            <td><?= e($item['mime_type'] ?? '') ?></td>
                            <td><?= e((string)($item['size'] ?? '')) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <?php if (!empty($item['path'])): ?>
                                    <a class="btn btn-sm btn-outline-secondary" href="<?= e($item['path']) ?>" target="_blank" rel="noopener noreferrer">Ouvrir</a>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=media&action=delete&id=<?= (int)$item['id'] ?>" onclick="return confirm('Supprimer ce média ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
