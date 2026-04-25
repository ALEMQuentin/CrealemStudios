<div class="row">

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted mb-1">Réservations aujourd'hui</div>
                <div style="font-size:2rem;font-weight:700;">
                    <?= (int)($stats['bookings_today'] ?? 0) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted mb-1">À venir</div>
                <div style="font-size:2rem;font-weight:700;">
                    <?= (int)($stats['bookings_upcoming'] ?? 0) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted mb-1">Clients</div>
                <div style="font-size:2rem;font-weight:700;">
                    <?= (int)($stats['clients'] ?? 0) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted mb-1">Chauffeurs</div>
                <div style="font-size:2rem;font-weight:700;">
                    <?= (int)($stats['drivers'] ?? 0) ?>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row mt-4">

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h2 class="h5 mb-3">Réservations récentes</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Trajet</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($recentBookings ?? []) as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['date'] ?? '') ?></td>
                                <td><?= htmlspecialchars($booking['client'] ?? '') ?></td>
                                <td><?= htmlspecialchars($booking['route'] ?? '') ?></td>
                                <td><?= htmlspecialchars($booking['status'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="col-lg-4">

        <div class="card mb-3">
            <div class="card-body">
                <h2 class="h5 mb-3">Actions rapides</h2>

                <div class="d-flex flex-column gap-2">
                    <a class="btn btn-primary" href="/admin.php?module=booking&action=create">Nouvelle réservation</a>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=clients&action=create">Nouveau client</a>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=drivers&action=create">Nouveau chauffeur</a>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=booking">Voir les réservations</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="h5 mb-3">État plateforme</h2>
                <ul class="mb-0">
                    <li>CMS opérationnel</li>
                    <li>Réservations actives</li>
                    <li>Assignation manuelle</li>
                </ul>
            </div>
        </div>

    </div>

</div>
