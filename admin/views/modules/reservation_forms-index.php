<div class="cs-admin-page-header">
    <div>
        <h1>Formulaires de réservation</h1>
        <p>Formulaires destinés au front : course distance, mise à disposition, circuit touristique.</p>
    </div>

    <a href="/admin.php?module=reservation_forms&action=create" class="btn btn-primary">
        Nouveau formulaire
    </a>
</div>

<div class="cs-admin-card">
    <div style="overflow-x:auto;">
        <table class="cs-admin-table" style="width:100%;">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Shortcode</th>
                    <th>Statut</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($forms)): ?>
                <tr>
                    <td colspan="5">Aucun formulaire de réservation créé.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($forms as $f): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$f['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string)$f['type'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><code>[reservation_form id="<?= (int)$f['id'] ?>"]</code></td>
                    <td><?= !empty($f['is_active']) ? 'Actif' : 'Inactif' ?></td>
                    <td>
                        <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=reservation_forms&action=fields&id=<?= (int)$f['id'] ?>">
                            Configurer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
