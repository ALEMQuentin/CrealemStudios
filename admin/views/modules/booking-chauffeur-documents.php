<?php
$documentTypes = [
    'carte_vtc' => 'Carte professionnelle VTC',
    'permis_conduire' => 'Permis de conduire',
    'assurance_rc_pro' => 'Assurance RC Pro',
    'carte_grise' => 'Carte grise du véhicule',
    'assurance_vehicule' => 'Assurance véhicule',
    'controle_technique' => 'Contrôle technique',
];

$chauffeurName = trim((string)$chauffeur['first_name'] . ' ' . (string)$chauffeur['last_name']);
?>

<div class="cs-admin-page-header">
    <div>
        <h1>Documents chauffeur</h1>
        <p><?= htmlspecialchars($chauffeurName, ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a class="btn btn-outline-secondary" href="/admin.php?module=booking&action=chauffeurs">Retour chauffeurs</a>
        <a class="btn btn-outline-secondary" href="/admin.php?module=booking&action=chauffeur_edit&id=<?= (int)$chauffeur['id'] ?>">Modifier chauffeur</a>
    </div>
</div>

<div class="cs-admin-card" style="margin-bottom:18px;">
    <h2>Ajouter un document</h2>

    <form method="post" enctype="multipart/form-data" action="/admin.php?module=booking&action=chauffeur_document_upload&id=<?= (int)$chauffeur['id'] ?>" style="display:grid; gap:16px;">
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:16px;">
            <div>
                <label class="required">Type de document</label>
                <select class="form-control" name="document_type" required>
                    <option value="">Choisir</option>
                    <?php foreach ($documentTypes as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="required">Fichier</label>
                <input class="form-control" type="file" name="document_file" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                <small>PDF, JPG, PNG ou WEBP. Taille max : 10 Mo.</small>
            </div>
        </div>

        <div>
            <button class="btn btn-primary" type="submit">Ajouter le document</button>
        </div>
    </form>
</div>

<div class="cs-admin-card">
    <h2>Documents enregistrés</h2>

    <div style="overflow-x:auto;">
        <table class="cs-admin-table" style="width:100%;">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Fichier</th>
                    <th>Statut</th>
                    <th>Note</th>
                    <th>Date</th>
                    <th style="width:260px;">Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($documents)): ?>
                <tr>
                    <td colspan="6">Aucun document enregistré.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($documents as $document): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($documentTypes[$document['document_type']] ?? (string)$document['document_type'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <a href="<?= htmlspecialchars((string)$document['file_path'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                            <?= htmlspecialchars((string)$document['original_name'], ENT_QUOTES, 'UTF-8') ?>
                        </a><br>
                        <small><?= number_format(((int)$document['size_bytes']) / 1024, 1, ',', ' ') ?> Ko</small>
                    </td>

                    <td>
                        <span class="badge">
                            <?= htmlspecialchars((string)$document['status'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>

                    <td><?= htmlspecialchars((string)($document['validation_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>

                    <td><?= htmlspecialchars((string)$document['created_at'], ENT_QUOTES, 'UTF-8') ?></td>

                    <td>
                        <div style="display:grid; gap:6px;">
                            <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=booking&action=chauffeur_document_validate&id=<?= (int)$document['id'] ?>&chauffeur_id=<?= (int)$chauffeur['id'] ?>">
                                Valider
                            </a>

                            <form method="post" action="/admin.php?module=booking&action=chauffeur_document_reject&id=<?= (int)$document['id'] ?>&chauffeur_id=<?= (int)$chauffeur['id'] ?>" style="display:flex; gap:6px;">
                                <input class="form-control" name="validation_note" placeholder="Motif du refus">
                                <button class="btn btn-sm btn-outline-secondary" type="submit">Refuser</button>
                            </form>

                            <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=booking&action=chauffeur_document_delete&id=<?= (int)$document['id'] ?>&chauffeur_id=<?= (int)$chauffeur['id'] ?>" onclick="return confirm('Supprimer ce document ?')">
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
