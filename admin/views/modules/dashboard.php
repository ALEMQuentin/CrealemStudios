<div class="row">

    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-muted">Réservations aujourd'hui</div>
        <div class="display-6"><?= (int)$stats['bookings_today'] ?></div>
    </div></div></div>

    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-muted">À venir</div>
        <div class="display-6"><?= (int)$stats['bookings_upcoming'] ?></div>
    </div></div></div>

    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-muted">Clients</div>
        <div class="display-6"><?= (int)$stats['clients'] ?></div>
    </div></div></div>

    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-muted">Chauffeurs</div>
        <div class="display-6"><?= (int)$stats['drivers'] ?></div>
    </div></div></div>

</div>

<div class="row mt-3">

    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-muted">CA aujourd'hui</div>
        <div class="display-6"><?= number_format($stats['revenue_today'], 2) ?> €</div>
    </div></div></div>

    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-muted">CA du mois</div>
        <div class="display-6"><?= number_format($stats['revenue_month'], 2) ?> €</div>
    </div></div></div>

    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-muted">Courses non assignées</div>
        <div class="display-6 text-danger"><?= (int)$stats['unassigned'] ?></div>
    </div></div></div>

</div>

<div class="card mt-4">
    <div class="card-body">
        <h2 class="h5 mb-3">Dernières réservations</h2>
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
                <?php foreach ($recentBookings as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['date']) ?></td>
                    <td><?= htmlspecialchars($b['client']) ?></td>
                    <td><?= htmlspecialchars($b['route']) ?></td>
                    <td><?= htmlspecialchars($b['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
