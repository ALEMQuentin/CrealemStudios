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
$vehicle = (string)($booking['vehicle_type'] ?? '');
?>

<style>
.booking-page-title {
    margin-bottom: 26px;
}

.booking-page-title h1 {
    margin: 0;
    font-size: 44px;
    line-height: 1.05;
    font-weight: 800;
}

.booking-page-title p {
    margin: 12px 0 0;
    color: #6b7280;
    font-size: 18px;
    font-weight: 600;
}

.booking-tabs {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.booking-tab {
    border: 1px solid #9ca3af;
    border-radius: 14px;
    padding: 10px 14px;
    background: transparent;
    color: #9ca3af;
    font-weight: 800;
    font-size: 16px;
    cursor: pointer;
}

.booking-tab.is-active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: #ffffff;
}

.booking-card {
    background: #ffffff;
    border: 1px solid #dbe1e8;
    border-radius: 22px;
    padding: 26px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
}

.booking-panel {
    display: none;
}

.booking-panel.is-active {
    display: block;
}

.booking-panel h2 {
    margin: 0 0 8px;
    font-size: 24px;
    font-weight: 800;
}

.booking-muted {
    color: #6b7280;
    font-size: 18px;
    margin: 0 0 28px;
}

.booking-field {
    margin-bottom: 18px;
}

.booking-field label,
.booking-label {
    display: block;
    margin-bottom: 8px;
    font-size: 17px;
    font-weight: 800;
}

.booking-input,
.booking-select,
.booking-textarea {
    width: 100%;
    border: 1px solid #dbe1e8;
    border-radius: 14px;
    padding: 14px 16px;
    background: #ffffff;
    font-size: 18px;
    color: #111827;
}

.booking-textarea {
    min-height: 120px;
    resize: vertical;
}

.booking-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.booking-radio-line {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    font-size: 18px;
}

.booking-client-card,
.booking-quote-card,
.booking-map-card {
    border: 1px solid #dbe1e8;
    border-radius: 18px;
    padding: 20px;
    margin-top: 18px;
    background: #ffffff;
}

.booking-client-card h3,
.booking-quote-card h3,
.booking-map-card h3 {
    margin: 0 0 6px;
    font-size: 20px;
    font-weight: 800;
}

.booking-client-details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px 60px;
    margin-top: 22px;
    font-size: 18px;
}

.booking-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 26px;
}

.booking-btn-primary {
    border: 0;
    border-radius: 14px;
    padding: 14px 20px;
    background: #1677ff;
    color: #fff;
    font-weight: 800;
    font-size: 18px;
    cursor: pointer;
}

.booking-btn-secondary {
    border: 1px solid #6b7280;
    border-radius: 14px;
    padding: 14px 20px;
    background: #fff;
    color: #6b7280;
    font-weight: 800;
    font-size: 18px;
    cursor: pointer;
}

.booking-btn-outline {
    border: 1px solid #1677ff;
    border-radius: 14px;
    padding: 10px 14px;
    background: #fff;
    color: #1677ff;
    font-weight: 800;
    font-size: 16px;
    cursor: pointer;
}

.booking-quote-line {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-top: 18px;
    font-size: 18px;
}

.booking-map-preview {
    height: 320px;
    border-radius: 16px;
    overflow: hidden;
    background: #eef2f7;
    margin-top: 16px;
    position: relative;
}

.booking-map-preview iframe,
.booking-map-preview img {
    width: 100%;
    height: 100%;
    border: 0;
    object-fit: cover;
}

.booking-results {
    display: grid;
    gap: 8px;
    margin-top: 10px;
}

.booking-result {
    border: 1px solid #dbe1e8;
    border-radius: 12px;
    padding: 10px 12px;
    background: #fff;
    text-align: left;
    cursor: pointer;
}

.booking-result:hover {
    border-color: #1677ff;
    background: #f8fbff;
}

@media (max-width: 900px) {
    .booking-grid,
    .booking-client-details,
    .booking-quote-line {
        grid-template-columns: 1fr;
    }

    .booking-page-title h1 {
        font-size: 34px;
    }
}
</style>

<div class="booking-page-title">
    <h1><?= $isEdit ? 'Modifier la réservation' : 'Nouvelle réservation' ?></h1>
    <p>Tunnel de réservation en 3 étapes.</p>
</div>

<form method="post" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" id="booking-form">
    <input type="hidden" name="client_id" id="client_id" value="<?= booking_field($booking, 'client_id') ?>">
    <input type="hidden" name="routing_provider" id="routing_provider" value="<?= booking_field($booking, 'routing_provider') ?>">
    <input type="hidden" name="distance_meters" id="distance_meters" value="<?= booking_field($booking, 'distance_meters') ?>">
    <input type="hidden" name="duration_seconds" id="duration_seconds" value="<?= booking_field($booking, 'duration_seconds') ?>">

    <div class="booking-tabs">
        <button type="button" class="booking-tab is-active" data-step-button="1">Étape 1 · Client</button>
        <button type="button" class="booking-tab" data-step-button="2">Étape 2 · Paiement</button>
        <button type="button" class="booking-tab" data-step-button="3">Étape 3 · Réservation</button>
    </div>

    <section class="booking-card booking-panel is-active" data-step-panel="1">
        <h2>Étape 1 · Client</h2>
        <p class="booking-muted">Choisir un client existant ou créer un nouveau client.</p>

        <div class="booking-field">
            <span class="booking-label">Type de client</span>
            <div class="booking-radio-line">
                <label>
                    <input type="radio" name="client_mode" value="existing" checked>
                    Client existant
                </label>

                <label>
                    <input type="radio" name="client_mode" value="new">
                    Nouveau client
                </label>
            </div>
        </div>

        <div id="existing-client-block">
            <div class="booking-field">
                <label>Recherche client</label>
                <input class="booking-input" type="search" id="client_search" placeholder="Tape un prénom, un nom ou un téléphone">
                <div class="booking-results" id="client_results"></div>
            </div>

            <div class="booking-client-card" id="selected-client-card" style="display:none;">
                <h3>Client sélectionné</h3>
                <p class="booking-muted">Informations récupérées depuis le compte client</p>

                <div class="booking-client-details">
                    <div><strong>Nom complet :</strong> <span id="selected_client_name">-</span></div>
                    <div><strong>Entreprise :</strong> <span id="selected_client_company">-</span></div>
                    <div><strong>Téléphone :</strong> <span id="selected_client_phone">-</span></div>
                    <div><strong>Adresse domicile :</strong> <span id="selected_client_address">-</span></div>
                    <div><strong>Email :</strong> <span id="selected_client_email">-</span></div>
                </div>
            </div>
        </div>

        <div id="new-client-block" style="display:none;">
            <div class="booking-grid">
                <div class="booking-field">
                    <label>Nom client *</label>
                    <input class="booking-input" name="client_name" id="client_name" value="<?= booking_field($booking, 'client_name') ?>">
                </div>

                <div class="booking-field">
                    <label>Téléphone *</label>
                    <input class="booking-input" name="client_phone" id="client_phone" value="<?= booking_field($booking, 'client_phone') ?>">
                </div>

                <div class="booking-field">
                    <label>Email</label>
                    <input class="booking-input" type="email" name="client_email" id="client_email" value="<?= booking_field($booking, 'client_email') ?>">
                </div>
            </div>
        </div>

        <div class="booking-actions">
            <button type="button" class="booking-btn-primary" data-next-step="2">Continuer vers le paiement</button>
        </div>
    </section>

    <section class="booking-card booking-panel" data-step-panel="2">
        <h2>Étape 2 · Paiement</h2>
        <p class="booking-muted">Choisir le mode de paiement avant les informations de course.</p>

        <div class="booking-field">
            <label>Mode de paiement</label>
            <select class="booking-select" name="payment_method" id="payment_method">
                <option value="">Choisir</option>
                <option value="card" <?= $payment === 'card' ? 'selected' : '' ?>>Carte bancaire</option>
                <option value="cash" <?= $payment === 'cash' ? 'selected' : '' ?>>Espèces</option>
                <option value="transfer" <?= $payment === 'transfer' ? 'selected' : '' ?>>Virement</option>
                <option value="invoice" <?= $payment === 'invoice' ? 'selected' : '' ?>>Facturation entreprise</option>
            </select>
        </div>

        <div class="booking-actions">
            <button type="button" class="booking-btn-secondary" data-prev-step="1">Retour</button>
            <button type="button" class="booking-btn-primary" data-next-step="3">Continuer vers la réservation</button>
        </div>
    </section>

    <section class="booking-card booking-panel" data-step-panel="3">
        <h2>Étape 3 · Réservation</h2>
        <p class="booking-muted">Saisir les informations de course.</p>

        <div class="booking-grid">
            <div class="booking-field">
                <label>Adresse de prise en charge</label>
                <input class="booking-input google-address-input" name="pickup_address" id="pickup_address" placeholder="Indiquez un lieu" autocomplete="off" value="<?= booking_field($booking, 'pickup_address') ?>">
            </div>

            <div class="booking-field">
                <label>Adresse de destination</label>
                <input class="booking-input google-address-input" name="dropoff_address" id="dropoff_address" placeholder="Indiquez un lieu" autocomplete="off" value="<?= booking_field($booking, 'dropoff_address') ?>">
            </div>

            <div class="booking-field">
                <label>Date et heure de prise en charge</label>
                <input class="booking-input" type="datetime-local" name="pickup_datetime" id="pickup_datetime" value="<?= booking_field($booking, 'pickup_datetime') ?>">
            </div>

            <div class="booking-field">
                <label>Nombre de passagers</label>
                <input class="booking-input" type="number" min="1" name="passengers" id="passengers" value="<?= (int)($booking['passengers'] ?? 1) ?>">
            </div>

            <div class="booking-field">
                <label>Véhicule</label>
                <select class="booking-select" name="vehicle_type" id="vehicle_type">
                    <option value="">Choisir</option>
                    <option value="berline" <?= $vehicle === 'berline' ? 'selected' : '' ?>>Berline</option>
                    <option value="van" <?= $vehicle === 'van' ? 'selected' : '' ?>>Van</option>
                    <option value="business" <?= $vehicle === 'business' ? 'selected' : '' ?>>Business</option>
                </select>
            </div>

            <div class="booking-field">
                <label>Prix (€)</label>
                <input class="booking-input" type="number" min="0" step="0.01" name="price" id="price" value="<?= booking_field($booking, 'price') ?>" readonly>
            </div>
        </div>

        <div class="booking-field">
            <label>Commentaire</label>
            <textarea class="booking-textarea" name="customer_note" placeholder="Détails complémentaires"><?= booking_field($booking, 'customer_note') ?></textarea>
        </div>

        <div class="booking-actions">
            <button type="button" class="booking-btn-outline" id="calculate_quote">Calculer le prix</button>
            <span id="quote_status" class="booking-muted"></span>
        </div>

        <div class="booking-quote-card" id="quote_card" style="display:none;">
            <h3>Devis calculé</h3>
            <p class="booking-muted">Estimation basée sur le calcul disponible dans le CMS.</p>

            <div class="booking-quote-line">
                <div><strong>Distance :</strong> <span id="quote_distance">-</span></div>
                <div><strong>Durée :</strong> <span id="quote_duration">-</span></div>
                <div><strong>Prix :</strong> <span id="quote_price">-</span></div>
            </div>
        </div>

        <div class="booking-map-card" id="map_card" style="display:none;">
            <h3>Itinéraire</h3>
            <p class="booking-muted">Visualisation du trajet estimé.</p>
            <div class="booking-map-preview" id="map_preview"></div>
        </div>

        <div class="booking-field" style="max-width:320px;">
            <label>Statut</label>
            <select class="booking-select" name="status">
                <option value="a_confirmer" <?= $status === 'a_confirmer' ? 'selected' : '' ?>>À confirmer</option>
                <option value="confirmee" <?= $status === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                <option value="en_cours" <?= $status === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                <option value="terminee" <?= $status === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                <option value="annulee" <?= $status === 'annulee' ? 'selected' : '' ?>>Annulée</option>
            </select>
        </div>

        <div class="booking-actions">
            <button type="button" class="booking-btn-secondary" data-prev-step="2">Retour</button>
            <button class="booking-btn-primary" type="submit"><?= $isEdit ? 'Mettre à jour' : 'Créer la réservation' ?></button>
        </div>
    </section>
</form>

<script>
(function () {
    const panels = Array.from(document.querySelectorAll('[data-step-panel]'));
    const tabs = Array.from(document.querySelectorAll('[data-step-button]'));

    function showStep(step) {
        panels.forEach(panel => panel.classList.toggle('is-active', panel.dataset.stepPanel === String(step)));
        tabs.forEach(tab => tab.classList.toggle('is-active', tab.dataset.stepButton === String(step)));
    }

    function value(id) {
        return document.getElementById(id)?.value?.trim() || '';
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

    function toggleClientMode() {
        const mode = document.querySelector('input[name="client_mode"]:checked')?.value || 'existing';
        document.getElementById('existing-client-block').style.display = mode === 'existing' ? 'block' : 'none';
        document.getElementById('new-client-block').style.display = mode === 'new' ? 'block' : 'none';
    }

    document.querySelectorAll('input[name="client_mode"]').forEach(input => {
        input.addEventListener('change', toggleClientMode);
    });

    document.querySelectorAll('[data-next-step]').forEach(button => {
        button.addEventListener('click', function () {
            const next = this.dataset.nextStep;

            if (next === '2') {
                const mode = document.querySelector('input[name="client_mode"]:checked')?.value || 'existing';
                if (mode === 'existing' && !value('client_id')) {
                    alert('Sélectionne un client existant ou choisis nouveau client.');
                    return;
                }

                if (mode === 'new') {
                    if (!requireValue('client_name', 'Renseigne le nom du client.')) return;
                    if (!requireValue('client_phone', 'Renseigne le téléphone du client.')) return;
                }
            }

            if (next === '3' && !requireValue('payment_method', 'Choisis un mode de paiement.')) return;

            showStep(next);
        });
    });

    document.querySelectorAll('[data-prev-step]').forEach(button => {
        button.addEventListener('click', function () {
            showStep(this.dataset.prevStep);
        });
    });

    let clientTimer = null;
    const clientSearch = document.getElementById('client_search');
    const clientResults = document.getElementById('client_results');

    clientSearch?.addEventListener('input', function () {
        clearTimeout(clientTimer);

        const q = this.value.trim();
        if (q.length < 2) {
            clientResults.innerHTML = '';
            return;
        }

        clientTimer = setTimeout(async function () {
            const response = await fetch('/admin.php?module=booking&action=client_search&q=' + encodeURIComponent(q));
            const clients = await response.json();

            clientResults.innerHTML = '';

            clients.forEach(client => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'booking-result';
                button.innerHTML = '<strong>' + (client.name || '-') + '</strong><br><small>' + (client.phone || '-') + ' · ' + (client.email || '-') + '</small>';

                button.addEventListener('click', function () {
                    document.getElementById('client_id').value = client.id || '';
                    document.getElementById('client_name').value = client.name || '';
                    document.getElementById('client_phone').value = client.phone || '';
                    document.getElementById('client_email').value = client.email || '';

                    document.getElementById('selected_client_name').textContent = client.name || '-';
                    document.getElementById('selected_client_phone').textContent = client.phone || '-';
                    document.getElementById('selected_client_email').textContent = client.email || '-';
                    document.getElementById('selected_client_company').textContent = client.company || '-';
                    document.getElementById('selected_client_address').textContent = client.address || '-';
                    document.getElementById('selected-client-card').style.display = 'block';

                    clientSearch.value = client.name || '';
                    clientResults.innerHTML = '';
                });

                clientResults.appendChild(button);
            });
        }, 250);
    });

    document.getElementById('calculate_quote')?.addEventListener('click', async function () {
        if (!requireValue('pickup_address', 'Renseigne l’adresse de départ.')) return;
        if (!requireValue('dropoff_address', 'Renseigne l’adresse de destination.')) return;

        const form = new FormData();
        form.append('pickup_address', value('pickup_address'));
        form.append('dropoff_address', value('dropoff_address'));
        form.append('vehicle_type', value('vehicle_type'));
        form.append('passengers', value('passengers'));

        document.getElementById('quote_status').textContent = 'Calcul en cours...';

        const response = await fetch('/admin.php?module=booking&action=quote', {
            method: 'POST',
            body: form
        });

        const quote = await response.json();

        if (quote.error) {
            alert(quote.error);
            document.getElementById('quote_status').textContent = '';
            return;
        }

        document.getElementById('price').value = quote.price || '';
        document.getElementById('distance_meters').value = quote.distance_meters || '';
        document.getElementById('duration_seconds').value = quote.duration_seconds || '';
        document.getElementById('routing_provider').value = quote.routing_provider || 'local_estimate';

        document.getElementById('quote_distance').textContent = quote.distance_meters ? (quote.distance_meters / 1000).toFixed(2) + ' km' : '-';
        document.getElementById('quote_duration').textContent = quote.duration_seconds ? (quote.duration_seconds / 60).toFixed(1) + ' min' : '-';
        document.getElementById('quote_price').textContent = quote.price ? quote.price + ' €' : '-';

        document.getElementById('quote_card').style.display = 'block';
        document.getElementById('quote_status').textContent = 'Devis calculé.';

        const pickup = encodeURIComponent(value('pickup_address'));
        const dropoff = encodeURIComponent(value('dropoff_address'));
        const mapUrl = 'https://www.google.com/maps/embed/v1/directions?key=&origin=' + pickup + '&destination=' + dropoff;

        document.getElementById('map_card').style.display = 'block';
        document.getElementById('map_preview').innerHTML = '<div style="padding:20px;color:#6b7280;">Carte Google prête à brancher dès qu’une clé API est configurée.</div>';
    });

    if ('google' in window && google.maps && google.maps.places) {
        document.querySelectorAll('.google-address-input').forEach(input => {
            new google.maps.places.Autocomplete(input, {
                fields: ['formatted_address', 'geometry', 'name'],
                types: ['geocode']
            });
        });
    }

    document.getElementById('booking-form')?.addEventListener('submit', function (event) {
        if (!requireValue('pickup_address', 'Renseigne l’adresse de prise en charge.')) {
            event.preventDefault();
            showStep(3);
            return;
        }

        if (!requireValue('dropoff_address', 'Renseigne l’adresse de destination.')) {
            event.preventDefault();
            showStep(3);
            return;
        }

        if (!requireValue('pickup_datetime', 'Renseigne la date et l’heure de prise en charge.')) {
            event.preventDefault();
            showStep(3);
        }
    });

    toggleClientMode();
    <?php if ($isEdit): ?>
    showStep(3);
    <?php else: ?>
    showStep(1);
    <?php endif; ?>
})();
</script>
