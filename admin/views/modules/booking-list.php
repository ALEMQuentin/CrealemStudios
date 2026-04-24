<div class="cs-admin-page-header">
    <div>
        <h1>Réservations</h1>
        <p>Gestion des demandes clients, paiements et informations de course.</p>
    </div>

    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a class="btn btn-outline-secondary" href="/admin.php?module=booking&action=tariffs">Tarifs</a>
        <a class="btn btn-outline-secondary" href="/admin.php?module=booking&action=chauffeurs">Chauffeurs</a>
        <a class="btn btn-primary" href="/admin.php?module=booking&action=create">Nouvelle réservation</a>
    </div>
</div>

<div class="cs-admin-card">
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking">Toutes</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&status=a_confirmer">À confirmer</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&status=confirmee">Confirmées</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&status=en_cours">En cours</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&status=terminee">Terminées</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&status=annulee">Annulées</a>
        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&status=archived">Archivées</a>
    </div>

    <div style="overflow-x:auto;">
        <table class="cs-admin-table" style="width:100%;">
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
            <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="7">Aucune réservation enregistrée.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars((string)($booking['pickup_datetime'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                    </td>

                    <td>
                        <?= htmlspecialchars((string)($booking['client_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?><br>
                        <small><?= htmlspecialchars((string)($booking['client_phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small>
                    </td>

                    <td>
                        <strong>Départ :</strong>
                        <?= htmlspecialchars((string)($booking['pickup_address'] ?? ''), ENT_QUOTES, 'UTF-8') ?><br>

                        <strong>Arrivée :</strong>
                        <?= htmlspecialchars((string)($booking['dropoff_address'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= (int)($booking['passengers'] ?? 1) ?> passager(s)<br>
                        <small><?= (int)($booking['luggage'] ?? 0) ?> bagage(s)</small>
                    </td>

                    <td>
                        <?php if (($booking['price'] ?? '') !== ''): ?>
                            <?= number_format((float)$booking['price'], 2, ',', ' ') ?> €
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>

                    <td>
                        <span class="badge">
                            <?= htmlspecialchars((string)($booking['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>

                    <td>
                        <div style="display:flex; flex-wrap:wrap; gap:6px;">
                            <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=edit&id=<?= (int)$booking['id'] ?>">
                                Modifier
                            </a>

                            <?php if ((int)($booking['is_archived'] ?? 0) === 1): ?>
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=unarchive&id=<?= (int)$booking['id'] ?>">
                                    Restaurer
                                </a>
                            <?php else: ?>
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=archive&id=<?= (int)$booking['id'] ?>">
                                    Archiver
                                </a>
                            <?php endif; ?>

                            <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=booking&action=delete&id=<?= (int)$booking['id'] ?>" onclick="return confirm('Supprimer cette réservation ?')">
                                Supprimer
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
