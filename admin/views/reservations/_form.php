<?php
$isEdit = !empty($reservation['id']);
$actionUrl = $isEdit
    ? '/admin.php?module=reservations&action=update&id=' . (int)$reservation['id']
    : '/admin.php?module=reservations&action=store';
?>

<form method="post" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" class="cs-admin-card" style="display:grid; gap:18px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:16px;">
        <div>
            <label>Nom client *</label>
            <input class="form-control" name="client_name" required value="<?= htmlspecialchars((string)($reservation['client_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div>
            <label>Téléphone *</label>
            <input class="form-control" name="client_phone" required value="<?= htmlspecialchars((string)($reservation['client_phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div>
            <label>Email</label>
            <input class="form-control" type="email" name="client_email" value="<?= htmlspecialchars((string)($reservation['client_email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div>
            <label>Date et heure *</label>
            <input class="form-control" type="datetime-local" name="pickup_datetime" required value="<?= htmlspecialchars((string)($reservation['pickup_datetime'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:16px;">
        <div>
            <label>Adresse de départ *</label>
            <input class="form-control" name="pickup_address" required value="<?= htmlspecialchars((string)($reservation['pickup_address'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div>
            <label>Adresse d’arrivée *</label>
            <input class="form-control" name="dropoff_address" required value="<?= htmlspecialchars((string)($reservation['dropoff_address'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px;">
        <div>
            <label>Passagers</label>
            <input class="form-control" type="number" min="1" name="passengers" value="<?= (int)($reservation['passengers'] ?? 1) ?>">
        </div>

        <div>
            <label>Bagages</label>
            <input class="form-control" type="number" min="0" name="luggage" value="<?= (int)($reservation['luggage'] ?? 0) ?>">
        </div>

        <div>
            <label>Véhicule</label>
            <select class="form-control" name="vehicle_type">
                <?php $vehicle = (string)($reservation['vehicle_type'] ?? 'berline'); ?>
                <option value="berline" <?= $vehicle === 'berline' ? 'selected' : '' ?>>Berline</option>
                <option value="van" <?= $vehicle === 'van' ? 'selected' : '' ?>>Van</option>
                <option value="business" <?= $vehicle === 'business' ? 'selected' : '' ?>>Affaires</option>
            </select>
        </div>

        <div>
            <label>Paiement</label>
            <?php $payment = (string)($reservation['payment_method'] ?? ''); ?>
            <select class="form-control" name="payment_method">
                <option value="">Non défini</option>
                <option value="card" <?= $payment === 'card' ? 'selected' : '' ?>>Carte</option>
                <option value="cash" <?= $payment === 'cash' ? 'selected' : '' ?>>Espèces</option>
                <option value="transfer" <?= $payment === 'transfer' ? 'selected' : '' ?>>Virement</option>
                <option value="invoice" <?= $payment === 'invoice' ? 'selected' : '' ?>>Facture</option>
            </select>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px;">
        <div>
            <label>Prix estimé</label>
            <input class="form-control" name="price" value="<?= htmlspecialchars((string)($reservation['price'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div>
            <label>Distance mètres</label>
            <input class="form-control" type="number" min="0" name="distance_meters" value="<?= htmlspecialchars((string)($reservation['distance_meters'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div>
            <label>Durée secondes</label>
            <input class="form-control" type="number" min="0" name="duration_seconds" value="<?= htmlspecialchars((string)($reservation['duration_seconds'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div>
            <label>Provider itinéraire</label>
            <input class="form-control" name="routing_provider" value="<?= htmlspecialchars((string)($reservation['routing_provider'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:16px;">
        <div>
            <label>Note client</label>
            <textarea class="form-control" name="customer_note" rows="4"><?= htmlspecialchars((string)($reservation['customer_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div>
            <label>Note interne</label>
            <textarea class="form-control" name="internal_note" rows="4"><?= htmlspecialchars((string)($reservation['internal_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
    </div>

    <div style="max-width:260px;">
        <label>Statut</label>
        <?php $status = (string)($reservation['status'] ?? 'a_confirmer'); ?>
        <select class="form-control" name="status">
            <option value="a_confirmer" <?= $status === 'a_confirmer' ? 'selected' : '' ?>>À confirmer</option>
            <option value="confirmee" <?= $status === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
            <option value="en_cours" <?= $status === 'en_cours' ? 'selected' : '' ?>>En cours</option>
            <option value="terminee" <?= $status === 'terminee' ? 'selected' : '' ?>>Terminée</option>
            <option value="annulee" <?= $status === 'annulee' ? 'selected' : '' ?>>Annulée</option>
        </select>
    </div>

    <div style="display:flex; gap:8px;">
        <button class="btn btn-primary" type="submit">Enregistrer</button>
        <a class="btn btn-outline-secondary" href="/admin.php?module=reservations">Annuler</a>
    </div>
</form>
