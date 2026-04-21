<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un avis' : 'Ajouter un avis' ?></h2>
            <div class="text-muted">Formulaire avis</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=testimonials">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=testimonials&action=save<?= !empty($isEdit) ? '&id=' . (int)$testimonial['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nom de l’auteur</label>
                    <input type="text" class="form-control" name="author_name" value="<?= e($testimonial['author_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Entreprise</label>
                    <input type="text" class="form-control" name="company" value="<?= e($testimonial['company'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Note</label>
                    <input type="number" min="1" max="5" class="form-control" name="rating" value="<?= e((string)($testimonial['rating'] ?? '5')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="published" <?= (($testimonial['status'] ?? '') === 'published') ? 'selected' : '' ?>>Publié</option>
                        <option value="draft" <?= (($testimonial['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Contenu</label>
                    <textarea class="form-control" name="content" rows="8" required><?= e($testimonial['content'] ?? '') ?></textarea>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer l’avis</button>
                </div>
            </div>
        </form>
    </div>
</div>
