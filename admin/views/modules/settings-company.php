<?php
function company_setting(array $settings, string $key, string $default = ''): string
{
    return htmlspecialchars((string)($settings[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}
?>

<div class="cs-admin-page-header">
    <div>
        <h1>Paramètres entreprise</h1>
        <p>Informations utilisées sur les factures, bons de réservation et futurs documents PDF.</p>
    </div>

    <a class="btn btn-outline-secondary" href="/admin.php?module=settings">Retour paramètres</a>
</div>

<form method="post" action="/admin.php?module=settings&action=save_company" class="cs-admin-card company-settings-form">
    <div class="company-settings-grid">
        <div>
            <label class="required">Nom de l’entreprise</label>
            <input class="form-control" name="company_name" required value="<?= company_setting($companySettings, 'company_name') ?>">
        </div>

        <div>
            <label>Nom commercial</label>
            <input class="form-control" name="company_trade_name" value="<?= company_setting($companySettings, 'company_trade_name') ?>">
        </div>

        <div>
            <label class="required">SIRET</label>
            <input class="form-control" name="company_siret" required value="<?= company_setting($companySettings, 'company_siret') ?>">
        </div>

        <div>
            <label>Numéro TVA intracommunautaire</label>
            <input class="form-control" name="company_vat_number" value="<?= company_setting($companySettings, 'company_vat_number') ?>">
        </div>

        <div>
            <label>Registre VTC</label>
            <input class="form-control" name="company_vtc_register" value="<?= company_setting($companySettings, 'company_vtc_register') ?>">
        </div>

        <div>
            <label>Téléphone</label>
            <input class="form-control" name="company_phone" value="<?= company_setting($companySettings, 'company_phone') ?>">
        </div>

        <div>
            <label>Email</label>
            <input class="form-control" type="email" name="company_email" value="<?= company_setting($companySettings, 'company_email') ?>">
        </div>

        <div>
            <label>Site web</label>
            <input class="form-control" name="company_website" value="<?= company_setting($companySettings, 'company_website') ?>">
        </div>
    </div>

    <div>
        <label class="required">Adresse complète</label>
        <textarea class="form-control" name="company_address" required rows="4"><?= company_setting($companySettings, 'company_address') ?></textarea>
    </div>

    <div>
        <label>Mentions légales facture</label>
        <textarea class="form-control" name="company_invoice_legal" rows="4"><?= company_setting($companySettings, 'company_invoice_legal') ?></textarea>
    </div>

    <div class="company-settings-actions">
        <button class="btn btn-primary" type="submit">Enregistrer les paramètres entreprise</button>
    </div>
</form>
