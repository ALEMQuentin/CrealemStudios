<?php
$bookingId = (int)($booking['id'] ?? 0);

$distanceKm = !empty($booking['distance_meters'])
    ? number_format(((int)$booking['distance_meters']) / 1000, 2, ',', ' ') . ' km'
    : '-';

$durationMin = !empty($booking['duration_seconds'])
    ? round(((int)$booking['duration_seconds']) / 60) . ' min'
    : '-';

$price = number_format((float)($booking['price'] ?? 0), 2, ',', ' ') . ' €';

$chauffeurName = trim((string)($chauffeur['first_name'] ?? '') . ' ' . (string)($chauffeur['last_name'] ?? ''));
$chauffeurName = $chauffeurName !== '' ? $chauffeurName : 'Non assigné';

$vehicleLabel = (string)($chauffeur['vehicle_label'] ?? $booking['vehicle_type'] ?? '-');
$vehiclePlate = (string)($chauffeur['vehicle_plate'] ?? '-');
$vtcCard = (string)($chauffeur['vtc_card_number'] ?? '-');

$createdAt = !empty($booking['created_at'])
    ? date('d/m/Y H:i', strtotime((string)$booking['created_at']))
    : date('d/m/Y H:i');

$pickupAt = !empty($booking['pickup_datetime'])
    ? date('d/m/Y H:i', strtotime((string)$booking['pickup_datetime']))
    : '-';
?>

<div class="cs-admin-page-header no-print">
    <div>
        <h1>Bon de réservation VTC</h1>
        <p>Réservation #<?= $bookingId ?></p>
    </div>

    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <button onclick="window.print()" class="btn btn-primary">Imprimer / PDF</button>
        <a href="/admin.php?module=booking" class="btn btn-outline-secondary">Retour</a>
    </div>
</div>

<div class="vtc-voucher">
    <div class="vtc-voucher-header">
        <h1>Bon de réservation VTC</h1>
        <p>Justificatif de réservation préalable à présenter aux agents chargés des contrôles.</p>
    </div>

    <section class="vtc-voucher-section">
        <h2>Information</h2>

        <div class="vtc-voucher-row">
            <strong>Montant TTC convenu</strong>
            <span><?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Justification de la réservation préalable</strong>
            <span>Article R3120-2 du Code des transports — Arrêté du 6 août 2025</span>
        </div>
    </section>

    <section class="vtc-voucher-section">
        <h2>Exploitant de véhicules de transport avec chauffeur</h2>

        <div class="vtc-voucher-list">
            <div>ALEM QUENTIN</div>
            <div>5 RUE DE CRONSTADT</div>
            <div>54000 NANCY</div>
            <div>SIRET : 93519598200019</div>
            <div>Registre VTC : EVTC054240028</div>
            <div>Téléphone : +33 6 04 17 56 89</div>
        </div>
    </section>

    <section class="vtc-voucher-section">
        <h2>Voyage</h2>

        <div class="vtc-voucher-row">
            <strong>Conducteur</strong>
            <span><?= htmlspecialchars($chauffeurName, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Passager</strong>
            <span><?= htmlspecialchars((string)($booking['client_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Commande</strong>
            <span><?= htmlspecialchars($createdAt, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Prise en charge</strong>
            <span><?= htmlspecialchars($pickupAt, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Lieu prise en charge</strong>
            <span><?= htmlspecialchars((string)($booking['pickup_address'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Destination</strong>
            <span><?= htmlspecialchars((string)($booking['dropoff_address'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Distance</strong>
            <span><?= htmlspecialchars($distanceKm, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Durée estimée</strong>
            <span><?= htmlspecialchars($durationMin, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Via</strong>
            <span>Quentin Chauffeur Privé</span>
        </div>
    </section>

    <section class="vtc-voucher-section">
        <h2>Chauffeur</h2>

        <div class="vtc-voucher-row">
            <strong>Nom</strong>
            <span><?= htmlspecialchars($chauffeurName, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Carte VTC</strong>
            <span><?= htmlspecialchars($vtcCard, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Véhicule</strong>
            <span><?= htmlspecialchars($vehicleLabel, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="vtc-voucher-row">
            <strong>Plaque</strong>
            <span><?= htmlspecialchars($vehiclePlate, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    </section>

    <section class="vtc-voucher-section">
        <h2>Mentions réglementaires</h2>

        <p>
            Ce document constitue le justificatif de réservation préalable prévu à l’article R3120-2 du Code des transports.
            Le conducteur est tenu de présenter ce justificatif, sur support papier ou électronique, à toute demande des agents chargés des contrôles.
        </p>

        <p>
            Le justificatif doit permettre d’identifier l’exploitant, le client, le conducteur assigné, ainsi que la date, l’heure et le lieu de prise en charge de la prestation réservée.
        </p>
    </section>
</div>
