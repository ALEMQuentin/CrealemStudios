<?php
$company = [];

$stmt = $this->pdo->query("SELECT setting_key, setting_value FROM settings");
foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
    $company[$row['setting_key']] = $row['setting_value'];
}

$bookingId = (int)($booking['id'] ?? 0);
$invoiceNumber = 'FAC-' . date('Y') . '-' . str_pad((string)$bookingId, 5, '0', STR_PAD_LEFT);

$priceTtc = (float)($booking['price'] ?? 0);
$tvaRate = 10.0;
$priceHt = $priceTtc / (1 + ($tvaRate / 100));
$tvaAmount = $priceTtc - $priceHt;

$pickupAt = !empty($booking['pickup_datetime'])
    ? date('d/m/Y H:i', strtotime((string)$booking['pickup_datetime']))
    : '-';

$createdAt = date('d/m/Y');

$distanceKm = !empty($booking['distance_meters'])
    ? number_format(((int)$booking['distance_meters']) / 1000, 2, ',', ' ') . ' km'
    : '-';
?>

<div class="cs-admin-page-header no-print">
    <div>
        <h1>Facture</h1>
        <p><?= htmlspecialchars($invoiceNumber, ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <button onclick="window.print()" class="btn btn-primary">Imprimer / PDF</button>
        <a href="/admin.php?module=booking" class="btn btn-outline-secondary">Retour</a>
    </div>
</div>

<div class="booking-invoice">
    <header class="booking-invoice-header">
        <div>
            <h1>Facture</h1>
            <p><?= htmlspecialchars($invoiceNumber, ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div>
            <strong><?= htmlspecialchars($company['company_name'] ?? 'Entreprise non renseignée') ?></strong><br>
<?= nl2br(htmlspecialchars($company['company_address'] ?? 'Adresse non renseignée')) ?><br>
SIRET : <?= htmlspecialchars($company['company_siret'] ?? '') ?><br>
<?php if (!empty($company['company_vat_number'])): ?>
TVA : <?= htmlspecialchars($company['company_vat_number']) ?><br>
<?php endif; ?>
<?php if (!empty($company['company_vtc_register'])): ?>
Registre VTC : <?= htmlspecialchars($company['company_vtc_register']) ?>
<?php endif; ?>
        </div>
    </header>

    <section class="booking-invoice-grid">
        <div>
            <h2>Client</h2>
            <p>
                <?= htmlspecialchars((string)($booking['client_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?><br>
                <?= htmlspecialchars((string)($booking['client_phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?><br>
                <?= htmlspecialchars((string)($booking['client_email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>

        <div>
            <h2>Informations facture</h2>
            <p>
                Date facture : <?= htmlspecialchars($createdAt, ENT_QUOTES, 'UTF-8') ?><br>
                Réservation : #<?= $bookingId ?><br>
                Date course : <?= htmlspecialchars($pickupAt, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>
    </section>

    <section class="booking-invoice-section">
        <h2>Prestation</h2>

        <table class="booking-invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Distance</th>
                    <th>TVA</th>
                    <th>Total TTC</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>
                        Transport VTC<br>
                        <small>
                            Départ : <?= htmlspecialchars((string)($booking['pickup_address'] ?? '-'), ENT_QUOTES, 'UTF-8') ?><br>
                            Destination : <?= htmlspecialchars((string)($booking['dropoff_address'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>
                        </small>
                    </td>
                    <td><?= htmlspecialchars($distanceKm, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= number_format($tvaRate, 1, ',', ' ') ?> %</td>
                    <td><?= number_format($priceTtc, 2, ',', ' ') ?> €</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="booking-invoice-totals">
        <div></div>

        <div>
            <div><span>Total HT</span><strong><?= number_format($priceHt, 2, ',', ' ') ?> €</strong></div>
            <div><span>TVA <?= number_format($tvaRate, 1, ',', ' ') ?> %</span><strong><?= number_format($tvaAmount, 2, ',', ' ') ?> €</strong></div>
            <div class="booking-invoice-total"><span>Total TTC</span><strong><?= number_format($priceTtc, 2, ',', ' ') ?> €</strong></div>
        </div>
    </section>

    <footer class="booking-invoice-footer">
        <p>
            <?= htmlspecialchars($company['company_invoice_legal'] ?? 'Facture générée depuis CréAlemStudios. Paiement selon le mode convenu lors de la réservation.', ENT_QUOTES, 'UTF-8') ?>
        </p>
    </footer>
</div>
