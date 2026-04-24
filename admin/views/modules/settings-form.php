<?php
function setting_value(array $settings, string $key, string $default = ''): string
{
    return htmlspecialchars((string)($settings[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

function setting_checked(array $settings, string $key): string
{
    return !empty($settings[$key]) && (string)$settings[$key] === '1' ? 'checked' : '';
}

$moduleLabels = [
    'module_products' => 'Catalogue / Produits',
    'module_blog' => 'Blog',
    'module_forms' => 'Formulaires',
    'module_booking' => 'Réservations',
    'module_clients' => 'Clients / CRM',
    'module_testimonials' => 'Avis / Témoignages',
    'module_gallery' => 'Galerie média',
    'module_subscriptions' => 'Abonnements',
];
?>

<div class="cs-admin-page-header">
    <div>
        <h1>Paramètres</h1>
        <p>Configuration générale du site, modules, tracking et code personnalisé.</p>
    </div>

    <a class="btn btn-outline-secondary" href="/admin.php?module=settings&action=company">
        Paramètres entreprise
    </a>
</div>

<form method="post" action="/admin.php?module=settings&action=save" class="cs-admin-card settings-tabs-form" id="settings-form">
    <div class="settings-tabs-nav">
        <button type="button" class="settings-tab is-active" data-settings-tab="general">Informations générales</button>
        <button type="button" class="settings-tab" data-settings-tab="modules">Modules optionnels</button>
        <button type="button" class="settings-tab" data-settings-tab="tracking">Tracking / Pixels</button>
        <button type="button" class="settings-tab" data-settings-tab="custom-code">Code personnalisé</button>
        <button type="button" class="settings-tab" data-settings-tab="custom-css">CSS personnalisé</button>
    </div>

    <section class="settings-tab-panel is-active" data-settings-panel="general">
        <h2>Informations générales</h2>

        <div class="settings-grid">
            <div>
                <label>Nom du site</label>
                <input class="form-control" name="site_name" value="<?= setting_value($settings, 'site_name') ?>">
            </div>

            <div>
                <label>Thème</label>
                <select class="form-control" name="theme">
                    <?php $theme = (string)($settings['theme'] ?? 'default'); ?>
                    <option value="default" <?= $theme === 'default' ? 'selected' : '' ?>>Défaut</option>
                    <option value="light" <?= $theme === 'light' ? 'selected' : '' ?>>Clair</option>
                    <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>>Sombre</option>
                </select>
            </div>
        </div>

        <div>
            <label>Slogan / description courte</label>
            <textarea class="form-control" name="site_tagline" rows="3"><?= setting_value($settings, 'site_tagline') ?></textarea>
        </div>
    </section>

    <section class="settings-tab-panel" data-settings-panel="modules">
        <h2>Modules optionnels</h2>

        <div class="settings-modules-grid">
            <?php foreach ($moduleLabels as $key => $label): ?>
                <label class="settings-module-card">
                    <input type="checkbox" name="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" value="1" <?= setting_checked($settings, $key) ?>>
                    <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="settings-tab-panel" data-settings-panel="tracking">
        <h2>Tracking / Pixels</h2>

        <div class="settings-grid">
            <div>
                <label>Google Tag Manager ID</label>
                <input class="form-control" name="tracking_gtm_id" placeholder="GTM-XXXXXXX" value="<?= setting_value($settings, 'tracking_gtm_id') ?>">
            </div>

            <div>
                <label>Meta Pixel ID</label>
                <input class="form-control" name="tracking_meta_pixel_id" value="<?= setting_value($settings, 'tracking_meta_pixel_id') ?>">
            </div>

            <div>
                <label>TikTok Pixel ID</label>
                <input class="form-control" name="tracking_tiktok_pixel_id" value="<?= setting_value($settings, 'tracking_tiktok_pixel_id') ?>">
            </div>
        </div>
    </section>

    <section class="settings-tab-panel" data-settings-panel="custom-code">
        <h2>Code personnalisé</h2>

        <div>
            <label>Code dans &lt;head&gt;</label>
            <textarea class="form-control code-field" name="tracking_head_custom" rows="8"><?= setting_value($settings, 'tracking_head_custom') ?></textarea>
        </div>

        <div>
            <label>Code après ouverture &lt;body&gt;</label>
            <textarea class="form-control code-field" name="tracking_body_custom" rows="8"><?= setting_value($settings, 'tracking_body_custom') ?></textarea>
        </div>

        <div>
            <label>Code avant fermeture &lt;/body&gt;</label>
            <textarea class="form-control code-field" name="tracking_footer_custom" rows="8"><?= setting_value($settings, 'tracking_footer_custom') ?></textarea>
        </div>
    </section>

    <section class="settings-tab-panel" data-settings-panel="custom-css">
        <h2>CSS personnalisé global</h2>

        <textarea class="form-control code-field" name="custom_css_global" rows="14"><?= setting_value($settings, 'custom_css_global') ?></textarea>
    </section>

    <div class="settings-actions">
        <button class="btn btn-primary" type="submit">Enregistrer les paramètres</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = Array.from(document.querySelectorAll('[data-settings-tab]'));
    const panels = Array.from(document.querySelectorAll('[data-settings-panel]'));

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            const target = this.dataset.settingsTab;

            tabs.forEach(item => item.classList.toggle('is-active', item === this));
            panels.forEach(panel => panel.classList.toggle('is-active', panel.dataset.settingsPanel === target));
        });
    });
});
</script>
