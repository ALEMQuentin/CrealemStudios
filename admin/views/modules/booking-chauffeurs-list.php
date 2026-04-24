<div class="cs-admin-page-header">
    <div>
        <h1>Chauffeurs</h1>
        <p>Gestion des chauffeurs assignables aux réservations.</p>
    </div>

    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a class="btn btn-outline-secondary" href="/admin.php?module=booking">Réservations</a>
        <a class="btn btn-primary" href="/admin.php?module=booking&action=chauffeur_create">Nouveau chauffeur</a>
    </div>
</div>

<div class="cs-admin-card">
    <div style="overflow-x:auto;">
        <table class="cs-admin-table" style="width:100%;">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Véhicule</th>
                    <th>Carte VTC</th>
                    <th>Statut</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($chauffeurs)): ?>
                <tr><td colspan="6">Aucun chauffeur enregistré.</td></tr>
            <?php endif; ?>

            <?php foreach ($chauffeurs as $chauffeur): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars((string)$chauffeur['first_name'], ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars((string)$chauffeur['last_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </td>
                    <td>
                        <?= htmlspecialchars((string)($chauffeur['phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?><br>
                        <small><?= htmlspecialchars((string)($chauffeur['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small>
                    </td>
                    <td>
                        <?= htmlspecialchars((string)($chauffeur['vehicle_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?><br>
                        <small><?= htmlspecialchars((string)($chauffeur['vehicle_plate'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small>
                    </td>
                    <td><?= htmlspecialchars((string)($chauffeur['vtc_card_number'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string)$chauffeur['status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=chauffeur_edit&id=<?= (int)$chauffeur['id'] ?>">Historique</a>
<a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=chauffeur_edit&id=<?= (int)$chauffeur['id'] ?>">Modifier</a>
                            <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=chauffeur_documents&id=<?= (int)$chauffeur['id'] ?>">Documents</a>
                            <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=booking&action=chauffeur_delete&id=<?= (int)$chauffeur['id'] ?>" onclick="return confirm('Supprimer ce chauffeur ?')">Supprimer</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
