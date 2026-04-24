<?php
$name = trim(($chauffeur['first_name'] ?? '') . ' ' . ($chauffeur['last_name'] ?? ''));
?>

<div class="cs-admin-page-header">
    <h1><?= htmlspecialchars($name) ?></h1>
</div>

<div class="cs-admin-card">
    <h2>Courses effectuées</h2>

    <table class="cs-admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Client</th>
                <th>Prix</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($reservations as $b): ?>
            <tr>
                <td>#<?= (int)$b['id'] ?></td>
                <td><?= htmlspecialchars($b['pickup_datetime'] ?? '') ?></td>
                <td><?= htmlspecialchars($b['client_name'] ?? '') ?></td>
                <td><?= number_format((float)$b['price'], 2, ',', ' ') ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
