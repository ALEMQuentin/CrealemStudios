<div class="cs-admin-page-header">
    <div>
        <h1>Documents chauffeur</h1>
        <p>
            <?= htmlspecialchars((string)$chauffeur['first_name'] . ' ' . (string)$chauffeur['last_name'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    </div>

    <a class="btn btn-outline-secondary" href="/admin.php?module=booking&action=chauffeurs">
        Retour chauffeurs
    </a>
</div>

<div class="cs-admin-card" style="margin-bottom:18px;">
    <h2>Ajouter un document</h2>

    <form method="post" action="/admin.php?module=booking&action=chauffeur_document_upload&id=<?= (int)$chauffeur['id'] ?>" enctype="multipart/form-data" style="display:grid; gap:16px;">
        <div>
            <label class="required">Type de document</label>
            <select class="form-control" name="document_type" required>
                <option value="">Sélectionner</option>
                <option value="carte_vtc">Carte professionnelle VTC</option>
                <option value="permis_conduire">Permis de conduire</option>
                <option value="assurance_rcp">Assurance RCP</option>
                <option value="assurance_vehicule">Assurance véhicule</option>
                <option value="carte_grise">Carte grise</option>
                <option value="controle_technique">Contrôle technique</option>
                <option value="kbis">Kbis / justificatif entreprise</option>
                <option value="autre">Autre</option>
            </select>
        </div>

        <div>
            <label class="required">Fichier</label>
            <input class="form-control" type="file" name="document_file" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
        </div>

        <button class="btn btn-primary" type="submit">Ajouter le document</button>
    </form>
</div>

<div class="cs-admin-card">
    <h2>Documents déposés</h2>

    <div style="overflow-x:auto;">
        <table class="cs-admin-table" style="width:100%;">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Fichier</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th style="width:260px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($documents)): ?>
                <tr><td colspan="5">Aucun document enregistré.</td></tr>
            <?php endif; ?>

            <?php foreach ($documents as $document): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$document['document_type'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string)$document['original_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <span class="badge">
                            <?= htmlspecialchars((string)$document['status'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <?php if (!empty($document['rejection_reason'])): ?>
                            <br><small><?= htmlspecialchars((string)$document['rejection_reason'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars((string)($document['uploaded_at'] ?? $document['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=chauffeur_document_validate&id=<?= (int)$document['id'] ?>">
                                Valider
                            </a>

                            <form method="post" action="/admin.php?module=booking&action=chauffeur_document_reject&id=<?= (int)$document['id'] ?>" style="display:flex; gap:6px;">
                                <input class="form-control" name="rejection_reason" placeholder="Motif refus">
                                <button class="btn btn-sm btn-outline-secondary" type="submit">Refuser</button>
                            </form>

                            <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=booking&action=chauffeur_document_delete&id=<?= (int)$document['id'] ?>" onclick="return confirm('Supprimer ce document ?')">
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
