<div class="cs-admin-page-header">
    <div>
        <h1>Tarifs de réservation</h1>
        <p>Gestion des tarifs utilisés pour calculer automatiquement les prix des courses.</p>
    </div>

    <a class="btn btn-outline-secondary" href="/admin.php?module=booking">
        Retour aux réservations
    </a>
</div>

<form method="post" action="/admin.php?module=booking&action=save_tariffs" class="cs-admin-card">
    <div style="overflow-x:auto;">
        <table class="cs-admin-table" style="width:100%;">
            <thead>
                <tr>
                    <th>Véhicule</th>
                    <th>Forfait départ</th>
                    <th>Prix / km</th>
                    <th>Prix / minute</th>
                    <th>Course minimum</th>
                    <th>Majoration nuit</th>
                    <th>Actif</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tariffs as $tariff): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars((string)$tariff['label'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <input type="hidden" name="tariffs[<?= (int)$tariff['id'] ?>][id]" value="<?= (int)$tariff['id'] ?>">
                        </td>

                        <td>
                            <input class="form-control" name="tariffs[<?= (int)$tariff['id'] ?>][base_fare]" value="<?= htmlspecialchars((string)$tariff['base_fare'], ENT_QUOTES, 'UTF-8') ?>">
                        </td>

                        <td>
                            <input class="form-control" name="tariffs[<?= (int)$tariff['id'] ?>][price_per_km]" value="<?= htmlspecialchars((string)$tariff['price_per_km'], ENT_QUOTES, 'UTF-8') ?>">
                        </td>

                        <td>
                            <input class="form-control" name="tariffs[<?= (int)$tariff['id'] ?>][price_per_minute]" value="<?= htmlspecialchars((string)$tariff['price_per_minute'], ENT_QUOTES, 'UTF-8') ?>">
                        </td>

                        <td>
                            <input class="form-control" name="tariffs[<?= (int)$tariff['id'] ?>][minimum_fare]" value="<?= htmlspecialchars((string)$tariff['minimum_fare'], ENT_QUOTES, 'UTF-8') ?>">
                        </td>

                        <td>
                            <input class="form-control" name="tariffs[<?= (int)$tariff['id'] ?>][night_multiplier]" value="<?= htmlspecialchars((string)$tariff['night_multiplier'], ENT_QUOTES, 'UTF-8') ?>">
                        </td>

                        <td>
                            <select class="form-control" name="tariffs[<?= (int)$tariff['id'] ?>][is_active]">
                                <option value="1" <?= (int)$tariff['is_active'] === 1 ? 'selected' : '' ?>>Oui</option>
                                <option value="0" <?= (int)$tariff['is_active'] === 0 ? 'selected' : '' ?>>Non</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">
        <button class="btn btn-primary" type="submit">Enregistrer les tarifs</button>
    </div>
</form>
