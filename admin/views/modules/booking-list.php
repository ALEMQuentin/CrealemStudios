<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Réservations</h2>
            <div class="text-muted">Liste des réservations</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=booking&action=create">Ajouter une réservation</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <?php if (empty($bookings)): ?>
            <div class="text-muted">Aucune réservation.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Montant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= (int)$booking['id'] ?></td>
                            <td><?= e($booking['title'] ?? '') ?></td>
                            <td><?= e(trim(($booking['booking_date'] ?? '') . ' ' . ($booking['booking_time'] ?? ''))) ?></td>
                            <td><?= e($booking['status'] ?? '') ?></td>
                            <td><?= e((string)($booking['amount'] ?? '')) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=edit&id=<?= (int)$booking['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=booking&action=delete&id=<?= (int)$booking['id'] ?>" onclick="return confirm('Supprimer cette réservation ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
