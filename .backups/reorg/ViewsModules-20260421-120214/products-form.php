<div class="card" style="border:3px solid red;">
    <div class="card-body">
        <h1 style="color:red; font-size:2rem; margin:0 0 1rem 0;">FORMULAIRE PRODUIT</h1>
        <p class="text-muted">Si tu vois ce titre rouge, c’est bien la vue <code>products-form.php</code> qui est chargée.</p>

        <form method="post" action="/admin.php?module=products&action=save<?= !empty($isEdit) ? '&id=' . (int)$product['id'] : '' ?>">
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" name="title" value="<?= e($product['title'] ?? '') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="draft" <?= (($product['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= (($product['status'] ?? '') === 'published') ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= e($product['slug'] ?? '') ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Prix</label>
                    <input type="number" step="0.01" class="form-control" name="price" value="<?= e((string)($product['price'] ?? '')) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">ID média principal</label>
                    <input type="number" class="form-control" name="featured_media_id" value="<?= e((string)($product['featured_media_id'] ?? '')) ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Extrait</label>
                    <textarea class="form-control" name="excerpt" rows="4"><?= e($product['excerpt'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Contenu</label>
                    <textarea class="form-control" name="content" rows="10"><?= e($product['content'] ?? '') ?></textarea>
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer le produit</button>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=products">Retour à la liste</a>
                </div>
            </div>
        </form>
    </div>
</div>
