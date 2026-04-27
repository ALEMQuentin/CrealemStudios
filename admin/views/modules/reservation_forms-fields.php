<?php
$pricing = $settings['pricing'] ?? [];
$availability = $settings['availability'] ?? [];
$circuit = $settings['circuit'] ?? [];
$payments = $settings['payments'] ?? [];

function rf_value(array $source, string $key, string $default = ''): string
{
    return htmlspecialchars((string)($source[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

function rf_checked(array $values, string $key): string
{
    return in_array($key, $values, true) ? 'checked' : '';
}

$days = [
    'monday' => 'Lundi',
    'tuesday' => 'Mardi',
    'wednesday' => 'Mercredi',
    'thursday' => 'Jeudi',
    'friday' => 'Vendredi',
    'saturday' => 'Samedi',
    'sunday' => 'Dimanche',
];
?>

<div class="cs-admin-page-header">
    <div>
        <h1>Configuration du formulaire</h1>
        <p><?= htmlspecialchars((string)($form['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars((string)($form['type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <a href="/admin.php?module=reservation_forms" class="btn btn-outline-secondary">Retour</a>
</div>

<form method="post" action="/admin.php?module=reservation_forms&action=save_config&id=<?= (int)$form['id'] ?>" class="cs-admin-card reservation-form-config">
    <section>
        <h2>État du formulaire</h2>

        <label style="display:flex; gap:10px; align-items:center;">
            <input type="checkbox" name="is_active" value="1" <?= !empty($form['is_active']) ? 'checked' : '' ?>>
            Formulaire actif sur le front
        </label>
    </section>

    <section>
        <h2>Champs générés automatiquement</h2>

        <table class="cs-admin-table" style="width:100%;">
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Nom technique</th>
                    <th>Type</th>
                    <th>Obligatoire</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fields as $field): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$field['label'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><code><?= htmlspecialchars((string)$field['name'], ENT_QUOTES, 'UTF-8') ?></code></td>
                        <td><?= htmlspecialchars((string)$field['type'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= !empty($field['required']) ? 'Oui' : 'Non' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p style="margin-top:12px;color:#64748b;">
            Ces champs sont générés selon le type du formulaire. La personnalisation avancée viendra plus tard.
        </p>
    </section>

    <section>
        <h2>Tarification</h2>

        <div class="settings-grid">
            <div>
                <label>Forfait de base</label>
                <input class="form-control" type="number" step="0.01" name="base_fare" value="<?= rf_value($pricing, 'base_fare') ?>">
            </div>

            <div>
                <label>Prix au km</label>
                <input class="form-control" type="number" step="0.01" name="price_per_km" value="<?= rf_value($pricing, 'price_per_km') ?>">
            </div>

            <div>
                <label>Prix à la minute</label>
                <input class="form-control" type="number" step="0.01" name="price_per_minute" value="<?= rf_value($pricing, 'price_per_minute') ?>">
            </div>

            <div>
                <label>Tarif horaire</label>
                <input class="form-control" type="number" step="0.01" name="hourly_rate" value="<?= rf_value($pricing, 'hourly_rate') ?>">
            </div>

            <div>
                <label>Forfait circuit</label>
                <input class="form-control" type="number" step="0.01" name="circuit_fixed_price" value="<?= rf_value($pricing, 'circuit_fixed_price') ?>">
            </div>

            <div>
                <label>Prix minimum</label>
                <input class="form-control" type="number" step="0.01" name="minimum_fare" value="<?= rf_value($pricing, 'minimum_fare') ?>">
            </div>
        </div>
    </section>

    <section>
        <h2>Disponibilités</h2>

        <div class="settings-modules-grid">
            <?php foreach ($days as $key => $label): ?>
                <label class="settings-module-card">
                    <input type="checkbox" name="availability_days[]" value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" <?= rf_checked((array)($availability['days'] ?? []), $key) ?>>
                    <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="settings-grid" style="margin-top:18px;">
            <div>
                <label>Heure début</label>
                <input class="form-control" type="time" name="start_time" value="<?= rf_value($availability, 'start_time') ?>">
            </div>

            <div>
                <label>Heure fin</label>
                <input class="form-control" type="time" name="end_time" value="<?= rf_value($availability, 'end_time') ?>">
            </div>

            <div>
                <label>Délai minimum de réservation</label>
                <input class="form-control" type="number" name="min_notice_minutes" value="<?= rf_value($availability, 'min_notice_minutes', '30') ?>">
            </div>
        </div>
    </section>

    <section>
        <h2>Véhicules proposés</h2>

        <textarea class="form-control" name="vehicles" rows="5" placeholder="Ex : Berline | 4 passagers | +0€&#10;Van | 7 passagers | +25€"><?= htmlspecialchars((string)($settings['vehicles'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
    </section>

    <section>
        <h2>Configuration circuit touristique</h2>

        <div class="settings-grid">
            <div>
                <label>Nom du circuit</label>
                <input class="form-control" name="route_name" value="<?= rf_value($circuit, 'route_name') ?>">
            </div>

            <div>
                <label>Durée estimée</label>
                <input class="form-control" name="route_duration" value="<?= rf_value($circuit, 'route_duration') ?>">
            </div>
        </div>

        <div style="margin-top:16px;">
            <label>Étapes du circuit</label>
            <textarea class="form-control" name="route_stops" rows="6" placeholder="Une étape par ligne"><?= rf_value($circuit, 'route_stops') ?></textarea>
        </div>
    </section>

    <section>
        <h2>Moyens de paiement</h2>

        <div class="settings-modules-grid">
            <?php foreach (['cash' => 'Espèces', 'card' => 'Carte bancaire', 'sumup' => 'SumUp', 'stripe' => 'Stripe'] as $key => $label): ?>
                <label class="settings-module-card">
                    <input type="checkbox" name="payments[]" value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" <?= rf_checked((array)$payments, $key) ?>>
                    <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </section>

    <div class="settings-actions">
        <button class="btn btn-primary" type="submit">Enregistrer la configuration</button>
    </div>
</form>
