<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un client' : 'Ajouter un client' ?></h2>
            <div class="text-muted">Formulaire client / CRM</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=clients">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=clients&action=save<?= !empty($isEdit) ? '&id=' . (int)$client['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text" class="form-control" name="first_name" value="<?= e($client['first_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" name="last_name" value="<?= e($client['last_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= e($client['email'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" class="form-control" name="phone" value="<?= e($client['phone'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Entreprise</label>
                    <input type="text" class="form-control" name="company" value="<?= e($client['company'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="8"><?= e($client['notes'] ?? '') ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer le client</button>
                </div>
            </div>
        </form>
    </div>
</div>
