<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Réservations</h2>
        <a class="btn btn-primary" href="/admin.php?module=booking&action=create">Ajouter une réservation</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                <?php if (empty($bookings)): ?>
                    <tr><td colspan="6" class="text-muted">Aucune réservation.</td></tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= (int)$booking['id'] ?></td>
                            <td><?= e($booking['title']) ?></td>
                            <td><?= e(trim(($booking['booking_date'] ?? '') . ' ' . ($booking['booking_time'] ?? ''))) ?></td>
                            <td><?= e($booking['status']) ?></td>
                            <td><?= e((string)($booking['amount'] ?? '')) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=edit&id=<?= (int)$booking['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=booking&action=delete&id=<?= (int)$booking['id'] ?>" onclick="return confirm('Supprimer cette réservation ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
