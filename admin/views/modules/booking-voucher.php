<?php
$bookingId = (int)($booking['id'] ?? 0);
?>

<div class="cs-admin-page-header no-print">
    <div>
        <h1>Bon de réservation VTC</h1>
        <p>Réservation #<?= $bookingId ?></p>
    </div>

    <div>
        <button onclick="window.print()" class="btn btn-primary">Imprimer</button>
        <a href="/admin.php?module=booking" class="btn btn-secondary">Retour</a>
    </div>
</div>

<div class="voucher">
    <h2>Client</h2>
    <p><?= htmlspecialchars($booking['client_name'] ?? '') ?></p>

    <h2>Trajet</h2>
    <p><strong>Départ :</strong> <?= htmlspecialchars($booking['pickup_address'] ?? '') ?></p>
    <p><strong>Destination :</strong> <?= htmlspecialchars($booking['dropoff_address'] ?? '') ?></p>

    <h2>Détails</h2>
    <p><strong>Date :</strong> <?= htmlspecialchars($booking['pickup_datetime'] ?? '') ?></p>
    <p><strong>Prix :</strong> <?= number_format((float)($booking['price'] ?? 0), 2, ',', ' ') ?> €</p>
</div>
