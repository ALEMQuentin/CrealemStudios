<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesSettings
{
    private function handleSettings(string $action): void
    {
        if ($action === 'company') {
            $rows = $this->pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll(\PDO::FETCH_ASSOC);
            $companySettings = [];

            foreach ($rows as $row) {
                $companySettings[(string)$row['setting_key']] = (string)$row['setting_value'];
            }

            $this->render('Paramètres entreprise', $this->resolveView(['modules/settings-company.php']), compact('companySettings'));
            return;
        }

        if ($action === 'save_company' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveCompanySettings();
            redirectTo('/admin.php?module=settings&action=company&success=Paramètres entreprise enregistrés');
        }


        if ($action === 'index') {
            $this->render('Paramètres', $this->resolveView(['modules/settings-form.php']), []);
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $moduleKeys = [
                'module_products',
                'module_blog',
                'module_forms',
                'module_booking',
                'module_clients',
                'module_testimonials',
                'module_gallery',
                'module_subscriptions',
            ];

            $trackingKeys = [
                'tracking_gtm_id',
                'tracking_meta_pixel_id',
                'tracking_tiktok_pixel_id',
                'tracking_head_custom',
                'tracking_body_custom',
                'tracking_footer_custom',
                'site_name',
                'site_tagline',
                'theme',
                'custom_css_global',
            ];

            foreach ($moduleKeys as $key) {
                saveSetting($this->pdo, $key, isset($_POST[$key]) ? '1' : '0');
            }

            foreach ($trackingKeys as $key) {
                saveSetting($this->pdo, $key, trim($_POST[$key] ?? ''));
            }

            $this->settings = getSettings($this->pdo);
            redirectTo('/admin.php?module=settings&success=Paramètres enregistrés');
        }

        redirectTo('/admin.php?module=settings');
    }
    private function saveCompanySettings(): void
    {
        $allowed = [
            'company_name',
            'company_trade_name',
            'company_siret',
            'company_vat_number',
            'company_vtc_register',
            'company_phone',
            'company_email',
            'company_website',
            'company_address',
            'company_invoice_legal',
        ];

        foreach ($allowed as $key) {
            $value = trim((string)($_POST[$key] ?? ''));

            $existing = $this->pdo->prepare("SELECT id FROM settings WHERE setting_key = :key LIMIT 1");
            $existing->execute(['key' => $key]);
            $id = (int)($existing->fetchColumn() ?: 0);

            if ($id > 0) {
                $stmt = $this->pdo->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
                $stmt->execute(['value' => $value, 'id' => $id]);
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES (:key, :value, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
                $stmt->execute(['key' => $key, 'value' => $value]);
            }
        }
    }

}
