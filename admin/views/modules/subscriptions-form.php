<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un abonnement' : 'Ajouter un abonnement' ?></h2>
            <div class="text-muted">Formulaire abonnement</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=subscriptions">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=subscriptions&action=save<?= !empty($isEdit) ? '&id=' . (int)$subscription['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" name="title" value="<?= e($subscription['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prix</label>
                    <input type="number" step="0.01" class="form-control" name="price" value="<?= e((string)($subscription['price'] ?? '')) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cycle</label>
                    <select class="form-select" name="billing_cycle">
                        <option value="monthly" <?= (($subscription['billing_cycle'] ?? '') === 'monthly') ? 'selected' : '' ?>>Mensuel</option>
                        <option value="yearly" <?= (($subscription['billing_cycle'] ?? '') === 'yearly') ? 'selected' : '' ?>>Annuel</option>
                        <option value="weekly" <?= (($subscription['billing_cycle'] ?? '') === 'weekly') ? 'selected' : '' ?>>Hebdomadaire</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="active" <?= (($subscription['status'] ?? '') === 'active') ? 'selected' : '' ?>>Actif</option>
                        <option value="inactive" <?= (($subscription['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="8"><?= e($subscription['description'] ?? '') ?></textarea>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer l’abonnement</button>
                </div>
            </div>
        </form>
    </div>
</div>
