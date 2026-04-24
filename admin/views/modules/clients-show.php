<?php
$clientName = trim(($client['first_name'] ?? '') . ' ' . ($client['last_name'] ?? ''));
?>

<div class="cs-admin-page-header">
    <div>
        <h1><?= htmlspecialchars($clientName) ?></h1>
        <p>Historique des réservations et factures</p>
    </div>

    <div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=clients">Retour</a>
    </div>
</div>

<div class="cs-admin-card">
    <h2>Réservations</h2>

    <table class="cs-admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Trajet</th>
                <th>Statut</th>
                <th>Prix</th>
                <th>Docs</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($reservations as $b): ?>
            <tr>
                <td>#<?= (int)$b['id'] ?></td>
                <td><?= htmlspecialchars($b['pickup_datetime'] ?? '') ?></td>
                <td>
                    <?= htmlspecialchars($b['pickup_address'] ?? '') ?><br>
                    <small>→ <?= htmlspecialchars($b['dropoff_address'] ?? '') ?></small>
                </td>
                <td><?= htmlspecialchars($b['status'] ?? '') ?></td>
                <td><?= number_format((float)$b['price'], 2, ',', ' ') ?> €</td>
                <td>
                    <a href="/admin.php?module=booking&action=voucher&id=<?= (int)$b['id'] ?>">Bon</a>
                    <?php if (($b['status'] ?? '') === 'terminee'): ?>
                        | <a href="/admin.php?module=booking&action=invoice&id=<?= (int)$b['id'] ?>">Facture</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
