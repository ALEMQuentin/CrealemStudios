<?php
$isEdit = !empty($reservation['id']);
$actionUrl = $isEdit
    ? '/admin.php?module=reservations&action=update&id=' . (int)$reservation['id']
    : '/admin.php?module=reservations&action=store';

function reservation_value(array $reservation, string $key, mixed $default = ''): string
{
    return htmlspecialchars((string)($reservation[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

$status = (string)($reservation['status'] ?? 'a_confirmer');
$payment = (string)($reservation['payment_method'] ?? '');
$vehicle = (string)($reservation['vehicle_type'] ?? 'berline');
?>

<form method="post" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" class="cs-admin-card" id="reservation-form">
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px;">
        <button type="button" class="btn btn-primary btn-sm" id="step-indicator-1">Étape 1 · Client</button>
        <button type="button" class="btn btn-outline-secondary btn-sm" id="step-indicator-2" disabled>Étape 2 · Paiement</button>
        <button type="button" class="btn btn-outline-secondary btn-sm" id="step-indicator-3" disabled>Étape 3 · Réservation</button>
    </div>

    <section id="reservation-step-1" style="display:grid; gap:18px;">
        <div>
            <h2>Étape 1 · Demande client</h2>
            <p>Identifier rapidement le client avant de créer la course.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:16px;">
            <div>
                <label>Nom client *</label>
                <input class="form-control" name="client_name" id="client_name" required value="<?= reservation_value($reservation, 'client_name') ?>">
            </div>

            <div>
                <label>Téléphone *</label>
                <input class="form-control" name="client_phone" id="client_phone" required value="<?= reservation_value($reservation, 'client_phone') ?>">
            </div>

            <div>
                <label>Email</label>
                <input class="form-control" type="email" name="client_email" value="<?= reservation_value($reservation, 'client_email') ?>">
            </div>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="button" class="btn btn-primary" id="go-step-2">Continuer vers le paiement</button>
            <a class="btn btn-outline-secondary" href="/admin.php?module=reservations">Annuler</a>
        </div>
    </section>

    <section id="reservation-step-2" style="display:none; gap:18px;">
        <div>
            <h2>Étape 2 · Paiement</h2>
            <p>Choisir le mode de paiement avant les informations de course.</p>
        </div>

        <div style="max-width:420px;">
            <label>Mode de paiement *</label>
            <select class="form-control" name="payment_method" id="payment_method" required>
                <option value="">Sélectionner</option>
                <option value="card" <?= $payment === 'card' ? 'selected' : '' ?>>Carte bancaire</option>
                <option value="cash" <?= $payment === 'cash' ? 'selected' : '' ?>>Espèces</option>
                <option value="transfer" <?= $payment === 'transfer' ? 'selected' : '' ?>>Virement</option>
                <option value="invoice" <?= $payment === 'invoice' ? 'selected' : '' ?>>Compte entreprise / facture</option>
            </select>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="button" class="btn btn-outline-secondary" id="back-step-1">Retour</button>
            <button type="button" class="btn btn-primary" id="go-step-3">Continuer vers la réservation</button>
        </div>
    </section>

    <section id="reservation-step-3" style="display:none; gap:18px;">
        <div>
            <h2>Étape 3 · Informations de réservation</h2>
            <p>Renseigner les informations opérationnelles de la course.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:16px;">
            <div>
                <label>Adresse de prise en charge *</label>
                <input class="form-control" name="pickup_address" id="pickup_address" required value="<?= reservation_value($reservation, 'pickup_address') ?>">
            </div>

            <div>
                <label>Adresse de destination *</label>
                <input class="form-control" name="dropoff_address" id="dropoff_address" required value="<?= reservation_value($reservation, 'dropoff_address') ?>">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px;">
            <div>
                <label>Date et heure de prise en charge *</label>
                <input class="form-control" type="datetime-local" name="pickup_datetime" id="pickup_datetime" required value="<?= reservation_value($reservation, 'pickup_datetime') ?>">
            </div>

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
                <select class="form-control" name="vehicle_type" id="vehicle_type">
                    <option value="berline" <?= $vehicle === 'berline' ? 'selected' : '' ?>>Berline</option>
                    <option value="van" <?= $vehicle === 'van' ? 'selected' : '' ?>>Van</option>
                    <option value="business" <?= $vehicle === 'business' ? 'selected' : '' ?>>Affaires</option>
                </select>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px;">
            <div>
                <label>Prix estimé</label>
                <input class="form-control" name="price" id="price" value="<?= reservation_value($reservation, 'price') ?>">
            </div>

            <div>
                <label>Distance mètres</label>
                <input class="form-control" type="number" min="0" name="distance_meters" id="distance_meters" value="<?= reservation_value($reservation, 'distance_meters') ?>">
            </div>

            <div>
                <label>Durée secondes</label>
                <input class="form-control" type="number" min="0" name="duration_seconds" id="duration_seconds" value="<?= reservation_value($reservation, 'duration_seconds') ?>">
            </div>

            <div>
                <label>Provider itinéraire</label>
                <input class="form-control" name="routing_provider" id="routing_provider" value="<?= reservation_value($reservation, 'routing_provider') ?>">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:16px;">
            <div>
                <label>Note client</label>
                <textarea class="form-control" name="customer_note" rows="4"><?= reservation_value($reservation, 'customer_note') ?></textarea>
            </div>

            <div>
                <label>Note interne</label>
                <textarea class="form-control" name="internal_note" rows="4"><?= reservation_value($reservation, 'internal_note') ?></textarea>
            </div>
        </div>

        <div style="max-width:260px;">
            <label>Statut</label>
            <select class="form-control" name="status">
                <option value="a_confirmer" <?= $status === 'a_confirmer' ? 'selected' : '' ?>>À confirmer</option>
                <option value="confirmee" <?= $status === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                <option value="en_cours" <?= $status === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                <option value="terminee" <?= $status === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                <option value="annulee" <?= $status === 'annulee' ? 'selected' : '' ?>>Annulée</option>
            </select>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="button" class="btn btn-outline-secondary" id="back-step-2">Retour</button>
            <button class="btn btn-primary" type="submit">Enregistrer la réservation</button>
        </div>
    </section>
</form>

<script>
(function () {
    const steps = [
        document.getElementById('reservation-step-1'),
        document.getElementById('reservation-step-2'),
        document.getElementById('reservation-step-3')
    ];

    const indicators = [
        document.getElementById('step-indicator-1'),
        document.getElementById('step-indicator-2'),
        document.getElementById('step-indicator-3')
    ];

    function showStep(index) {
        steps.forEach((step, i) => {
            if (step) step.style.display = i === index ? 'grid' : 'none';
        });

        indicators.forEach((button, i) => {
            if (!button) return;
            button.disabled = i > index;
            button.className = i === index ? 'btn btn-primary btn-sm' : 'btn btn-outline-secondary btn-sm';
        });
    }

    function requireValue(id, message) {
        const field = document.getElementById(id);
        if (!field || !field.value.trim()) {
            alert(message);
            if (field) field.focus();
            return false;
        }
        return true;
    }

    document.getElementById('go-step-2')?.addEventListener('click', function () {
        if (!requireValue('client_name', 'Renseigne le nom du client.')) return;
        if (!requireValue('client_phone', 'Renseigne le téléphone du client.')) return;
        showStep(1);
    });

    document.getElementById('back-step-1')?.addEventListener('click', function () {
        showStep(0);
    });

    document.getElementById('go-step-3')?.addEventListener('click', function () {
        if (!requireValue('payment_method', 'Choisis un mode de paiement.')) return;
        showStep(2);
    });

    document.getElementById('back-step-2')?.addEventListener('click', function () {
        showStep(1);
    });

    document.getElementById('reservation-form')?.addEventListener('submit', function (event) {
        if (!requireValue('pickup_address', 'Renseigne l’adresse de prise en charge.')) {
            event.preventDefault();
            showStep(2);
            return;
        }

        if (!requireValue('dropoff_address', 'Renseigne l’adresse de destination.')) {
            event.preventDefault();
            showStep(2);
            return;
        }

        if (!requireValue('pickup_datetime', 'Renseigne la date et l’heure de prise en charge.')) {
            event.preventDefault();
            showStep(2);
        }
    });

    <?php if ($isEdit): ?>
    showStep(2);
    <?php else: ?>
    showStep(0);
    <?php endif; ?>
})();
</script>
