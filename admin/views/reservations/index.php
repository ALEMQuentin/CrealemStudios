<h1>Réservations</h1>

<a href="/admin.php?module=reservations&action=create">Nouvelle réservation</a>

<table border="1" cellpadding="10">
    <tr>
        <th>Client</th>
        <th>Téléphone</th>
        <th>Trajet</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($reservations as $r): ?>
    <tr>
        <td><?= $r['client_name'] ?></td>
        <td><?= $r['client_phone'] ?></td>
        <td><?= $r['pickup_address'] ?> → <?= $r['dropoff_address'] ?></td>
        <td><?= $r['datetime'] ?></td>
        <td><?= $r['status'] ?></td>
        <td>
            <a href="/admin.php?module=reservations&action=edit&id=<?= $r['id'] ?>">Modifier</a>
            <a href="/admin.php?module=reservations&action=delete&id=<?= $r['id'] ?>">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
