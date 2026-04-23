<div class="cs-admin-page-header">
    <div>
        <h1>Réservations</h1>
        <p>Gestion des courses planifiées, demandes clients et statuts chauffeur.</p>
    </div>

    <a class="btn btn-primary" href="/admin.php?module=reservations&action=create">
        Nouvelle réservation
    </a>
</div>

<div class="cs-admin-card" style="margin-bottom:16px;">
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations">Toutes</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&status=a_confirmer">À confirmer</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&status=confirmee">Confirmées</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&status=en_cours">En cours</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&status=terminee">Terminées</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&status=annulee">Annulées</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&status=archived">Archivées</a>
    </div>
</div>

<div class="cs-admin-card">
    <div style="overflow-x:auto;">
        <table class="cs-admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Trajet</th>
                    <th>Passagers</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th style="width:220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($reservations)): ?>
                <tr>
                    <td colspan="7">Aucune réservation.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars((string)$reservation['pickup_datetime'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </td>
                    <td>
                        <?= htmlspecialchars((string)$reservation['client_name'], ENT_QUOTES, 'UTF-8') ?><br>
                        <small><?= htmlspecialchars((string)$reservation['client_phone'], ENT_QUOTES, 'UTF-8') ?></small>
                    </td>
                    <td>
                        <strong>Départ :</strong> <?= htmlspecialchars((string)$reservation['pickup_address'], ENT_QUOTES, 'UTF-8') ?><br>
                        <strong>Arrivée :</strong> <?= htmlspecialchars((string)$reservation['dropoff_address'], ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td>
                        <?= (int)$reservation['passengers'] ?> passager(s)<br>
                        <small><?= (int)$reservation['luggage'] ?> bagage(s)</small>
                    </td>
                    <td>
                        <?php if ($reservation['price'] !== null && $reservation['price'] !== ''): ?>
                            <?= number_format((float)$reservation['price'], 2, ',', ' ') ?> €
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge">
                            <?= htmlspecialchars((string)$reservation['status'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; flex-wrap:wrap; gap:6px;">
                            <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&action=edit&id=<?= (int)$reservation['id'] ?>">Modifier</a>
                            <?php if ((int)$reservation['is_archived'] === 1): ?>
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&action=unarchive&id=<?= (int)$reservation['id'] ?>">Restaurer</a>
                            <?php else: ?>
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservations&action=archive&id=<?= (int)$reservation['id'] ?>">Archiver</a>
                            <?php endif; ?>
                            <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=reservations&action=delete&id=<?= (int)$reservation['id'] ?>" onclick="return confirm('Supprimer cette réservation ?')">Supprimer</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
