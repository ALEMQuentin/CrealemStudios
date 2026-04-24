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
$chauffeurs = $chauffeurs ?? [];
$googleConfigPath = dirname(__DIR__, 3) . '/config/google.local.php';
$googleMapsKey = file_exists($googleConfigPath) ? (string)((require $googleConfigPath)['maps_api_key'] ?? '') : '';
?>

<style>
.booking-page-title{margin-bottom:26px}
.booking-page-title h1{margin:0;font-size:44px;line-height:1.05;font-weight:800}
.booking-page-title p{margin:12px 0 0;color:#6b7280;font-size:18px;font-weight:600}
.booking-tabs{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px}
.booking-tab{border:1px solid #9ca3af;border-radius:14px;padding:10px 14px;background:transparent;color:#9ca3af;font-weight:800;font-size:16px;cursor:pointer}
.booking-tab.is-active{background:var(--cs-primary);border-color:var(--cs-primary);color:#fff}
.booking-card{background:#fff;border:1px solid #dbe1e8;border-radius:22px;padding:26px;box-shadow:0 12px 28px rgba(15,23,42,.05)}
.booking-panel{display:none}
.booking-panel.is-active{display:block}
.booking-panel h2{margin:0 0 8px;font-size:24px;font-weight:800}
.booking-muted{color:#6b7280;font-size:18px;margin:0 0 28px}
.booking-field{margin-bottom:18px}
.booking-field label,.booking-label{display:block;margin-bottom:8px;font-size:17px;font-weight:800}
.booking-input,.booking-select,.booking-textarea{width:100%;border:1px solid #dbe1e8;border-radius:14px;padding:14px 16px;background:#fff;font-size:18px;color:#111827}
.booking-textarea{min-height:120px;resize:vertical}
.booking-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
.booking-radio-line{display:flex;gap:14px;flex-wrap:wrap;font-size:18px}
.booking-client-card,.booking-quote-card,.booking-map-card{border:1px solid #dbe1e8;border-radius:18px;padding:20px;margin-top:18px;background:#fff}
.booking-client-card h3,.booking-quote-card h3,.booking-map-card h3{margin:0 0 6px;font-size:20px;font-weight:800}
.booking-client-details{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px 60px;margin-top:22px;font-size:18px}
.booking-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}
.booking-btn-primary{border:0;border-radius:14px;padding:14px 20px;background:var(--cs-primary);color:#fff;font-weight:800;font-size:18px;cursor:pointer}
.booking-btn-secondary{border:1px solid #6b7280;border-radius:14px;padding:14px 20px;background:#fff;color:#6b7280;font-weight:800;font-size:18px;cursor:pointer}
.booking-btn-outline{border:1px solid var(--cs-primary);border-radius:14px;padding:10px 14px;background:#fff;color:var(--cs-primary);font-weight:800;font-size:16px;cursor:pointer}
.booking-quote-line{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-top:18px;font-size:18px}
.booking-map-preview{height:320px;border-radius:16px;overflow:hidden;background:#eef2f7;margin-top:16px}
.booking-results{display:grid;gap:8px;margin-top:10px}
.booking-result{border:1px solid #dbe1e8;border-radius:12px;padding:10px 12px;background:#fff;text-align:left;cursor:pointer}
.booking-result:hover{border-color:var(--cs-primary);background:#f8fbff}
@media(max-width:900px){.booking-grid,.booking-client-details,.booking-quote-line{grid-template-columns:1fr}.booking-page-title h1{font-size:34px}}
</style>

<div class="booking-page-title">
    <h1><?= $isEdit ? 'Modifier la réservation' : 'Nouvelle réservation' ?></h1>
    <p>Tunnel de réservation en 3 étapes.</p>
</div>

<form method="post" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" id="booking-form">
    <input type="hidden" name="client_id" id="client_id" value="<?= booking_field($booking, 'client_id') ?>">
    <input type="hidden" name="client_name" id="client_name" value="<?= booking_field($booking, 'client_name') ?>">
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
                <label><input type="radio" name="client_mode" value="existing" checked> Client existant</label>
                <label><input type="radio" name="client_mode" value="new"> Nouveau client</label>
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
                <div class="booking-field"><label>Prénom</label><input class="booking-input" name="client_first_name" id="client_first_name" data-conditional-required="1"></div>
                <div class="booking-field"><label>Nom</label><input class="booking-input" name="client_last_name" id="client_last_name" data-conditional-required="1"></div>
                <div class="booking-field"><label>Téléphone</label><input class="booking-input" name="client_phone" id="client_phone" data-conditional-required="1" value="<?= booking_field($booking, 'client_phone') ?>"></div>
                <div class="booking-field"><label>Email</label><input class="booking-input" type="email" name="client_email" id="client_email" value="<?= booking_field($booking, 'client_email') ?>"></div>
                <div class="booking-field"><label>Entreprise</label><input class="booking-input" name="client_company" id="client_company"></div>
                <div class="booking-field"><label>Adresse domicile</label><input class="booking-input google-address-input" name="client_home_address" id="client_home_address" autocomplete="off"></div>
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
            <select class="booking-select" name="payment_method" id="payment_method" data-conditional-required="1">
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
            <div class="booking-route-stack">
            <div class="booking-field"><label>Adresse de prise en charge</label><input class="booking-input google-address-input" name="pickup_address" id="pickup_address" data-conditional-required="1" placeholder="Indiquez un lieu" autocomplete="off" value="<?= booking_field($booking, 'pickup_address') ?>"></div>

            <div class="booking-stops-between">
                <div id="stops-container"></div>

                <button type="button" class="booking-stop-add" id="add-stop" title="Ajouter un arrêt intermédiaire">
                    + Ajouter un arrêt
                </button>
            </div>

            <div class="booking-field"><label>Adresse de destination</label><input class="booking-input google-address-input" name="dropoff_address" id="dropoff_address" data-conditional-required="1" placeholder="Indiquez un lieu" autocomplete="off" value="<?= booking_field($booking, 'dropoff_address') ?>"></div>
        </div>
            <div class="booking-field"><label>Date et heure de prise en charge</label><input class="booking-input" type="datetime-local" name="pickup_datetime" id="pickup_datetime" data-conditional-required="1" value="<?= booking_field($booking, 'pickup_datetime') ?>"></div>
            <div class="booking-field"><label>Nombre de passagers</label><input class="booking-input" type="number" min="1" name="passengers" id="passengers" value="<?= (int)($booking['passengers'] ?? 1) ?>"></div>
            <div class="booking-field"><label>Véhicule</label><select class="booking-select" name="vehicle_type" id="vehicle_type" data-conditional-required="1"><option value="">Choisir</option><option value="berline" <?= $vehicle === 'berline' ? 'selected' : '' ?>>Berline</option><option value="van" <?= $vehicle === 'van' ? 'selected' : '' ?>>Van</option><option value="business" <?= $vehicle === 'business' ? 'selected' : '' ?>>Business</option></select></div>

            <div class="booking-field">
                <label>Chauffeur assigné</label>
                <select class="booking-select" name="chauffeur_id" id="chauffeur_id">
                    <option value="">Non assigné</option>
                    <?php foreach ($chauffeurs as $chauffeur): ?>
                        <?php $chauffeurLabel = trim((string)($chauffeur['first_name'] ?? '') . ' ' . (string)($chauffeur['last_name'] ?? '')); ?>
                        <option value="<?= (int)$chauffeur['id'] ?>" <?= (int)($booking['chauffeur_id'] ?? 0) === (int)$chauffeur['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($chauffeurLabel, ENT_QUOTES, 'UTF-8') ?>
                            <?php if (!empty($chauffeur['vehicle_label'])): ?>
                                · <?= htmlspecialchars((string)$chauffeur['vehicle_label'], ENT_QUOTES, 'UTF-8') ?>
                            <?php endif; ?>
                            <?php if (!empty($chauffeur['vehicle_plate'])): ?>
                                · <?= htmlspecialchars((string)$chauffeur['vehicle_plate'], ENT_QUOTES, 'UTF-8') ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="booking-field"><label>Prix (€)</label><input class="booking-input" type="number" min="0" step="0.01" name="price" id="price" value="<?= booking_field($booking, 'price') ?>" readonly></div>
        </div>

        

        <div class="booking-field"><label>Commentaire</label><textarea class="booking-textarea" name="customer_note" placeholder="Détails complémentaires"><?= booking_field($booking, 'customer_note') ?></textarea></div>

        <div class="booking-actions">
            <button type="button" class="booking-btn-outline" id="calculate_quote">Calculer le prix</button>
            <span id="quote_status" class="booking-muted"></span>
        </div>

        <div class="booking-quote-card" id="quote_card" style="display:none;">
            <h3>Devis calculé</h3>
            <p class="booking-muted">Estimation basée sur Google et tes paramètres tarifaires.</p>
            <div class="booking-quote-line">
                <div><strong>Distance :</strong> <span id="quote_distance">-</span></div>
                <div><strong>Durée :</strong> <span id="quote_duration">-</span></div>
                <div><strong>Prix :</strong> <span id="quote_price">-</span></div>
            </div>
        </div>

        <div class="booking-map-card" id="map_card" style="display:none;">
            <h3>Itinéraire</h3>
            <p class="booking-muted">Visualisation du trajet estimé.</p>
            <div class="booking-map-preview" id="route-map"></div>
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

<?php if ($googleMapsKey !== ''): ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= htmlspecialchars($googleMapsKey, ENT_QUOTES, 'UTF-8') ?>&libraries=places,geometry"></script>
<?php endif; ?>

<script>
(function(){
let map=null, originMarker=null, destinationMarker=null, routePolyline=null;
const panels=[...document.querySelectorAll('[data-step-panel]')];
const tabs=[...document.querySelectorAll('[data-step-button]')];
function showStep(step){panels.forEach(p=>p.classList.toggle('is-active',p.dataset.stepPanel===String(step)));tabs.forEach(t=>t.classList.toggle('is-active',t.dataset.stepButton===String(step)));}
function value(id){return document.getElementById(id)?.value?.trim()||'';}
function requireValue(id,msg){const f=document.getElementById(id);if(!f||!f.value.trim()){alert(msg);if(f)f.focus();return false;}return true;}
function syncClientName(){
    const first = value('client_first_name');
    const last = value('client_last_name');
    const full = (first + ' ' + last).trim();

    if (full !== '') {
        document.getElementById('client_name').value = full;
    }
}
function setRequired(id, active){
    const field = document.getElementById(id);
    if (!field) return;
    if (active) {
        field.setAttribute('required', 'required');
    } else {
        field.removeAttribute('required');
    }
}

function refreshRequiredFields(){
    const mode = document.querySelector('input[name="client_mode"]:checked')?.value || 'existing';
    const activePanel = document.querySelector('.booking-panel.is-active')?.dataset.stepPanel || '1';

    document.querySelectorAll('[data-conditional-required]').forEach(field => {
        field.removeAttribute('required');
    });

    if (activePanel === '1') {
        if (mode === 'new') {
            setRequired('client_first_name', true);
            setRequired('client_last_name', true);
            setRequired('client_phone', true);
        }
    }

    if (activePanel === '2') {
        setRequired('payment_method', true);
    }

    if (activePanel === '3') {
        setRequired('pickup_address', true);
        setRequired('dropoff_address', true);
        setRequired('pickup_datetime', true);
        setRequired('vehicle_type', true);
    }
}

function toggleClientMode(){
    const mode=document.querySelector('input[name="client_mode"]:checked')?.value||'existing';
    document.getElementById('existing-client-block').style.display=mode==='existing'?'block':'none';
    document.getElementById('new-client-block').style.display=mode==='new'?'block':'none';
    if(mode==='new'){document.getElementById('client_id').value='';}
    refreshRequiredFields();
}
document.querySelectorAll('input[name="client_mode"]').forEach(i=>i.addEventListener('change',toggleClientMode));
['client_first_name','client_last_name'].forEach(id=>document.getElementById(id)?.addEventListener('input',syncClientName));
document.querySelectorAll('[data-next-step]').forEach(b=>b.addEventListener('click',function(){const next=this.dataset.nextStep;if(next==='2'){const mode=document.querySelector('input[name="client_mode"]:checked')?.value||'existing';if(mode==='existing'&&!value('client_id')){alert('Sélectionne un client existant ou choisis nouveau client.');return;}if(mode==='new'){syncClientName();if(!requireValue('client_first_name','Renseigne le prénom.'))return;if(!requireValue('client_last_name','Renseigne le nom.'))return;if(!requireValue('client_phone','Renseigne le téléphone.'))return;}}if(next==='3'&&!requireValue('payment_method','Choisis un mode de paiement.'))return;showStep(next);}));
document.querySelectorAll('[data-prev-step]').forEach(b=>b.addEventListener('click',function(){showStep(this.dataset.prevStep);}));
let timer=null;const search=document.getElementById('client_search');const results=document.getElementById('client_results');
search?.addEventListener('input',function(){clearTimeout(timer);const q=this.value.trim();if(q.length<2){results.innerHTML='';return;}timer=setTimeout(async()=>{const r=await fetch('/admin.php?module=booking&action=client_search&q='+encodeURIComponent(q));const clients=await r.json();results.innerHTML='';clients.forEach(c=>{const btn=document.createElement('button');btn.type='button';btn.className='booking-result';btn.innerHTML='<strong>'+(c.name||'-')+'</strong><br><small>'+(c.phone||'-')+' · '+(c.email||'-')+'</small>';btn.addEventListener('click',()=>{document.getElementById('client_id').value=c.id||'';document.getElementById('client_name').value=c.name||'';document.getElementById('client_phone').value=c.phone||'';document.getElementById('client_email').value=c.email||'';document.getElementById('selected_client_name').textContent=c.name||'-';document.getElementById('selected_client_phone').textContent=c.phone||'-';document.getElementById('selected_client_email').textContent=c.email||'-';document.getElementById('selected_client_company').textContent=c.company||'-';document.getElementById('selected_client_address').textContent=c.address||'-';document.getElementById('selected-client-card').style.display='block';search.value=c.name||'';results.innerHTML='';});results.appendChild(btn);});},250);});
let pickupPlace = null;
let dropoffPlace = null;
let directionsService = null;
let directionsRenderer = null;

function initGoogle(){
    if(!('google' in window) || !google.maps || !google.maps.places) return;

    document.querySelectorAll('.google-address-input').forEach(input => {
        const autocomplete = new google.maps.places.Autocomplete(input, {
            fields: ['formatted_address', 'geometry', 'name'],
            types: ['geocode']
        });

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();

            if (place && place.formatted_address) {
                input.value = place.formatted_address;
            }

            if (input.id === 'pickup_address') {
                pickupPlace = place || null;
            }

            if (input.id === 'dropoff_address') {
                dropoffPlace = place || null;
            }

            if (value('pickup_address') && value('dropoff_address')) {
                calculateQuote(false);
            }
        });
    });

    directionsService = new google.maps.DirectionsService();

    const mapElement = document.getElementById('route-map');
    if (mapElement) {
        map = new google.maps.Map(mapElement, {
            zoom: 11,
            center: { lat: 48.6921, lng: 6.1844 }
        });

        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: false,
            preserveViewport: false
        });
    }
}
function getStopValues(){
    return Array.from(document.querySelectorAll('.stop-input'))
        .map(input => input.value.trim())
        .filter(value => value !== '');
}

function drawMap(){
    if (!('google' in window) || !google.maps || !directionsService || !directionsRenderer) return;
    if (!value('pickup_address') || !value('dropoff_address')) return;

    document.getElementById('map_card').style.display = 'block';

    const waypoints = getStopValues().map(address => ({
        location: address,
        stopover: true
    }));

    directionsService.route({
        origin: value('pickup_address'),
        destination: value('dropoff_address'),
        waypoints: waypoints,
        optimizeWaypoints: false,
        travelMode: google.maps.TravelMode.DRIVING
    }, function(result, status) {
        if (status === 'OK') {
            directionsRenderer.setDirections(result);
        }
    });
}
async function calculateQuote(showAlerts = true){
    if(!requireValue('pickup_address','Renseigne l’adresse de prise en charge.')) return;
    if(!requireValue('dropoff_address','Renseigne l’adresse de destination.')) return;

    const form = new FormData();
    ['pickup_address','dropoff_address','vehicle_type','passengers','pickup_datetime'].forEach(id => form.append(id, value(id))); getStopValues().forEach(stop => form.append('stops[]', stop));

    document.getElementById('quote_status').textContent = 'Calcul en cours...';

    const r = await fetch('/admin.php?module=booking&action=quote', {
        method: 'POST',
        body: form
    });

    const q = await r.json();

    if(q.error){
        if (showAlerts) alert(q.error);
        document.getElementById('quote_status').textContent = '';
        return;
    }

    document.getElementById('price').value = q.price || '';
    document.getElementById('distance_meters').value = q.distance_meters || '';
    document.getElementById('duration_seconds').value = q.duration_seconds || '';
    document.getElementById('routing_provider').value = q.routing_provider || '';

    document.getElementById('quote_distance').textContent = q.distance_meters ? (q.distance_meters / 1000).toFixed(2) + ' km' : '-';
    document.getElementById('quote_duration').textContent = q.duration_seconds ? (q.duration_seconds / 60).toFixed(1) + ' min' : '-';
    document.getElementById('quote_price').textContent = q.price ? q.price + ' €' : '-';

    document.getElementById('quote_card').style.display = 'block';
    document.getElementById('quote_status').textContent = 'Devis calculé.';

    drawMap();
}

document.getElementById('calculate_quote')?.addEventListener('click', function () {
    calculateQuote(true);
});

['pickup_address','dropoff_address','vehicle_type','passengers','pickup_datetime'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', function () {
        if (value('pickup_address') && value('dropoff_address')) {
            calculateQuote(false);
        }
    });
});

document.getElementById('add-stop')?.addEventListener('click', function(){
    const container = document.getElementById('stops-container');
    const index = container.querySelectorAll('.booking-stop').length + 1;

    const row = document.createElement('div');
    row.className = 'booking-stop';
    row.innerHTML = `
        <div class="booking-stop-row">
            <input type="text" name="stops[]" class="booking-input stop-input google-address-input" placeholder="Adresse arrêt ${index}" autocomplete="off">
            <button type="button" class="remove-stop">✕</button>
        </div>
    `;

    container.appendChild(row);

    row.querySelector('.remove-stop').addEventListener('click', function(){
        row.remove();
        if (value('pickup_address') && value('dropoff_address')) {
            calculateQuote(false);
        }
    });

    if ('google' in window && google.maps && google.maps.places) {
        const input = row.querySelector('.stop-input');
        const autocomplete = new google.maps.places.Autocomplete(input, {
            fields: ['formatted_address', 'geometry', 'name'],
            types: ['geocode']
        });

        autocomplete.addListener('place_changed', function(){
            const place = autocomplete.getPlace();
            if (place && place.formatted_address) {
                input.value = place.formatted_address;
            }

            if (value('pickup_address') && value('dropoff_address')) {
                calculateQuote(false);
            }
        });
    }
});

document.getElementById('booking-form')?.addEventListener('submit',e=>{syncClientName();if(!requireValue('pickup_address','Renseigne l’adresse de prise en charge.')){e.preventDefault();showStep(3);return;}if(!requireValue('dropoff_address','Renseigne l’adresse de destination.')){e.preventDefault();showStep(3);return;}if(!requireValue('pickup_datetime','Renseigne la date et l’heure de prise en charge.')){e.preventDefault();showStep(3);}});
toggleClientMode();initGoogle();<?php if ($isEdit): ?>showStep(3);<?php else: ?>showStep(1);<?php endif; ?>
})();
</script>

</div>

