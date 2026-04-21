<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier une réservation' : 'Ajouter une réservation' ?></h2>
            <div class="text-muted">Formulaire réservation</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=booking">Retour à la liste</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=booking&action=save<?= !empty($isEdit) ? '&id=' . (int)$booking['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" name="title" value="<?= e($booking['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Client (ID)</label>
                    <input type="number" class="form-control" name="client_id" value="<?= e((string)($booking['client_id'] ?? '')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="booking_date" value="<?= e($booking['booking_date'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Heure</label>
                    <input type="time" class="form-control" name="booking_time" value="<?= e($booking['booking_time'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" class="form-control" name="amount" value="<?= e((string)($booking['amount'] ?? '')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="pending" <?= (($booking['status'] ?? '') === 'pending') ? 'selected' : '' ?>>En attente</option>
                        <option value="confirmed" <?= (($booking['status'] ?? '') === 'confirmed') ? 'selected' : '' ?>>Confirmée</option>
                        <option value="cancelled" <?= (($booking['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>Annulée</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="8"><?= e($booking['notes'] ?? '') ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer la réservation</button>
                </div>
            </div>
        </form>
    </div>
</div>
