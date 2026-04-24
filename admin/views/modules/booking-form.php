<?php
$isEdit = !empty($isEdit);
$actionUrl = $isEdit
    ? '/admin.php?module=booking&action=save&id=' . (int)$booking['id']
    : '/admin.php?module=booking&action=save';

function booking_field(array $booking, string $key, mixed $default = ''): string
{
    return htmlspecialchars((string)($booking[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

$status = (string)($booking['status'] ?? 'a_confirmer');
$payment = (string)($booking['payment_method'] ?? '');
$vehicle = (string)($booking['vehicle_type'] ?? 'berline');
?>

<style>
.booking-flow {
    display: grid;
    gap: 18px;
}

.booking-steps {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

.booking-step-pill {
    border: 1px solid #d6dde6;
    border-radius: 14px;
    padding: 12px 14px;
    background: #f8fafc;
    color: #64748b;
    font-weight: 700;
    text-align: left;
    cursor: pointer;
}

.booking-step-pill.is-active {
    background: #0f766e;
    border-color: #0f766e;
    color: #fff;
}

.booking-panel {
    display: none;
    gap: 18px;
}

.booking-panel.is-active {
    display: grid;
}

.booking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.booking-field label {
    display: block;
    margin-bottom: 6px;
    font-weight: 700;
}

.booking-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.booking-help {
    margin: 4px 0 0;
    color: #64748b;
}

@media (max-width: 780px) {
    .booking-steps {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="cs-admin-page-header">
    <div>
        <h1><?= $isEdit ? 'Modifier une réservation' : 'Nouvelle réservation' ?></h1>
        <p>Formulaire en trois volets : client, paiement, informations de course.</p>
    </div>
</div>

<form method="post" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" class="cs-admin-card booking-flow" id="booking-form">
    <div class="booking-steps">
        <button type="button" class="booking-step-pill is-active" data-step-button="1">1. Demande client</button>
        <button type="button" class="booking-step-pill" data-step-button="2">2. Paiement</button>
        <button type="button" class="booking-step-pill" data-step-button="3">3. Réservation</button>
    </div>

    <section class="booking-panel is-active" data-step-panel="1">
        <div>
            <h2>Demande client</h2>
            <p class="booking-help">Informations de contact nécessaires pour créer ou suivre la course.</p>
        </div>

        <div class="booking-grid">
            <div class="booking-field">
                <label>Nom client *</label>
                <input class="form-control" name="client_name" id="client_name" required value="<?= booking_field($booking, 'client_name') ?>">
            </div>

            <div class="booking-field">
                <label>Téléphone *</label>
                <input class="form-control" name="client_phone" id="client_phone" required value="<?= booking_field($booking, 'client_phone') ?>">
            </div>

            <div class="booking-field">
                <label>Email</label>
                <input class="form-control" type="email" name="client_email" value="<?= booking_field($booking, 'client_email') ?>">
            </div>
        </div>

        <div class="booking-actions">
            <button type="button" class="btn btn-primary" data-next-step="2">Continuer</button>
            <a class="btn btn-outline-secondary" href="/admin.php?module=booking">Annuler</a>
        </div>
    </section>

    <section class="booking-panel" data-step-panel="2">
        <div>
            <h2>Paiement</h2>
            <p class="booking-help">Mode de règlement prévu pour la réservation.</p>
        </div>

        <div class="booking-grid">
            <div class="booking-field">
                <label>Mode de paiement *</label>
                <select class="form-control" name="payment_method" id="payment_method" required>
                    <option value="">Sélectionner</option>
                    <option value="card" <?= $payment === 'card' ? 'selected' : '' ?>>Carte bancaire</option>
                    <option value="cash" <?= $payment === 'cash' ? 'selected' : '' ?>>Espèces</option>
                    <option value="transfer" <?= $payment === 'transfer' ? 'selected' : '' ?>>Virement</option>
                    <option value="invoice" <?= $payment === 'invoice' ? 'selected' : '' ?>>Facturation entreprise</option>
                </select>
            </div>

            <div class="booking-field">
                <label>Prix estimé</label>
                <input class="form-control" name="price" value="<?= booking_field($booking, 'price') ?>">
            </div>
        </div>

        <div class="booking-actions">
            <button type="button" class="btn btn-outline-secondary" data-prev-step="1">Retour</button>
            <button type="button" class="btn btn-primary" data-next-step="3">Continuer</button>
        </div>
    </section>

    <section class="booking-panel" data-step-panel="3">
        <div>
            <h2>Informations de réservation</h2>
            <p class="booking-help">Détails opérationnels de la course.</p>
        </div>

        <div class="booking-grid">
            <div class="booking-field">
                <label>Adresse de départ *</label>
                <input class="form-control" name="pickup_address" id="pickup_address" required value="<?= booking_field($booking, 'pickup_address') ?>">
            </div>

            <div class="booking-field">
                <label>Adresse d’arrivée *</label>
                <input class="form-control" name="dropoff_address" id="dropoff_address" required value="<?= booking_field($booking, 'dropoff_address') ?>">
            </div>

            <div class="booking-field">
                <label>Date et heure *</label>
                <input class="form-control" type="datetime-local" name="pickup_datetime" id="pickup_datetime" required value="<?= booking_field($booking, 'pickup_datetime') ?>">
            </div>

            <div class="booking-field">
                <label>Passagers</label>
                <input class="form-control" type="number" min="1" name="passengers" value="<?= (int)($booking['passengers'] ?? 1) ?>">
            </div>

            <div class="booking-field">
                <label>Bagages</label>
                <input class="form-control" type="number" min="0" name="luggage" value="<?= (int)($booking['luggage'] ?? 0) ?>">
            </div>

            <div class="booking-field">
                <label>Véhicule</label>
                <select class="form-control" name="vehicle_type">
                    <option value="berline" <?= $vehicle === 'berline' ? 'selected' : '' ?>>Berline</option>
                    <option value="van" <?= $vehicle === 'van' ? 'selected' : '' ?>>Van</option>
                    <option value="business" <?= $vehicle === 'business' ? 'selected' : '' ?>>Affaires</option>
                </select>
            </div>

            <div class="booking-field">
                <label>Distance mètres</label>
                <input class="form-control" type="number" min="0" name="distance_meters" value="<?= booking_field($booking, 'distance_meters') ?>">
            </div>

            <div class="booking-field">
                <label>Durée secondes</label>
                <input class="form-control" type="number" min="0" name="duration_seconds" value="<?= booking_field($booking, 'duration_seconds') ?>">
            </div>

            <div class="booking-field">
                <label>Statut</label>
                <select class="form-control" name="status">
                    <option value="a_confirmer" <?= $status === 'a_confirmer' ? 'selected' : '' ?>>À confirmer</option>
                    <option value="confirmee" <?= $status === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                    <option value="en_cours" <?= $status === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="terminee" <?= $status === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                    <option value="annulee" <?= $status === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                </select>
            </div>
        </div>

        <div class="booking-grid">
            <div class="booking-field">
                <label>Note client</label>
                <textarea class="form-control" name="customer_note" rows="4"><?= booking_field($booking, 'customer_note') ?></textarea>
            </div>

            <div class="booking-field">
                <label>Note interne</label>
                <textarea class="form-control" name="internal_note" rows="4"><?= booking_field($booking, 'internal_note') ?></textarea>
            </div>
        </div>

        <div class="booking-actions">
            <button type="button" class="btn btn-outline-secondary" data-prev-step="2">Retour</button>
            <button class="btn btn-primary" type="submit">Enregistrer la réservation</button>
        </div>
    </section>
</form>

<script>
(function () {
    const panels = Array.from(document.querySelectorAll('[data-step-panel]'));
    const buttons = Array.from(document.querySelectorAll('[data-step-button]'));

    function showStep(step) {
        panels.forEach(panel => {
            panel.classList.toggle('is-active', panel.dataset.stepPanel === String(step));
        });

        buttons.forEach(button => {
            button.classList.toggle('is-active', button.dataset.stepButton === String(step));
        });
    }

    function requireField(id, message) {
        const field = document.getElementById(id);
        if (!field || !field.value.trim()) {
            alert(message);
            if (field) field.focus();
            return false;
        }
        return true;
    }

    document.querySelectorAll('[data-next-step]').forEach(button => {
        button.addEventListener('click', function () {
            const next = this.dataset.nextStep;

            if (next === '2') {
                if (!requireField('client_name', 'Renseigne le nom du client.')) return;
                if (!requireField('client_phone', 'Renseigne le téléphone du client.')) return;
            }

            if (next === '3') {
                if (!requireField('payment_method', 'Choisis un mode de paiement.')) return;
            }

            showStep(next);
        });
    });

    document.querySelectorAll('[data-prev-step]').forEach(button => {
        button.addEventListener('click', function () {
            showStep(this.dataset.prevStep);
        });
    });

    document.getElementById('booking-form')?.addEventListener('submit', function (event) {
        if (!requireField('pickup_address', 'Renseigne l’adresse de départ.')) {
            event.preventDefault();
            showStep(3);
            return;
        }

        if (!requireField('dropoff_address', 'Renseigne l’adresse d’arrivée.')) {
            event.preventDefault();
            showStep(3);
            return;
        }

        if (!requireField('pickup_datetime', 'Renseigne la date et l’heure.')) {
            event.preventDefault();
            showStep(3);
        }
    });

    <?php if ($isEdit): ?>
    showStep(3);
    <?php else: ?>
    showStep(1);
    <?php endif; ?>
})();
</script>
