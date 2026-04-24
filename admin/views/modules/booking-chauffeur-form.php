<?php
$isEdit = !empty($chauffeur['id']);
$actionUrl = $isEdit
    ? '/admin.php?module=booking&action=chauffeur_save&id=' . (int)$chauffeur['id']
    : '/admin.php?module=booking&action=chauffeur_save';

function chauffeur_value(array $chauffeur, string $key, mixed $default = ''): string
{
    return htmlspecialchars((string)($chauffeur[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

$status = (string)($chauffeur['status'] ?? 'active');
?>

<div class="cs-admin-page-header">
    <div>
        <h1><?= $isEdit ? 'Modifier chauffeur' : 'Nouveau chauffeur' ?></h1>
        <p>Informations chauffeur, véhicule et carte professionnelle.</p>
    </div>
</div>

<form method="post" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" class="cs-admin-card" style="display:grid; gap:18px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:16px;">
        <div>
            <label class="required">Prénom</label>
            <input class="form-control" name="first_name" required value="<?= chauffeur_value($chauffeur, 'first_name') ?>">
        </div>

        <div>
            <label class="required">Nom</label>
            <input class="form-control" name="last_name" required value="<?= chauffeur_value($chauffeur, 'last_name') ?>">
        </div>

        <div>
            <label>Téléphone</label>
            <input class="form-control" name="phone" value="<?= chauffeur_value($chauffeur, 'phone') ?>">
        </div>

        <div>
            <label>Email</label>
            <input class="form-control" type="email" name="email" value="<?= chauffeur_value($chauffeur, 'email') ?>">
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:16px;">
        <div>
            <label>Véhicule</label>
            <input class="form-control" name="vehicle_label" value="<?= chauffeur_value($chauffeur, 'vehicle_label') ?>">
        </div>

        <div>
            <label>Plaque d’immatriculation</label>
            <input class="form-control" name="vehicle_plate" value="<?= chauffeur_value($chauffeur, 'vehicle_plate') ?>">
        </div>

        <div>
            <label>Numéro carte VTC</label>
            <input class="form-control" name="vtc_card_number" value="<?= chauffeur_value($chauffeur, 'vtc_card_number') ?>">
        </div>

        <div>
            <label>Statut</label>
            <select class="form-control" name="status">
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Actif</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactif</option>
            </select>
        </div>
    </div>

    <div>
        <label>Notes</label>
        <textarea class="form-control" name="notes" rows="4"><?= chauffeur_value($chauffeur, 'notes') ?></textarea>
    </div>

    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <button class="btn btn-primary" type="submit">Enregistrer</button>
        <a class="btn btn-outline-secondary" href="/admin.php?module=booking&action=chauffeurs">Annuler</a>
    </div>
</form>
