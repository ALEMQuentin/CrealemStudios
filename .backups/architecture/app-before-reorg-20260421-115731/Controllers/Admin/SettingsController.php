<?php

namespace App\Controllers\Admin;

use App\Core\Database;

class SettingsController
{
    public function index()
    {
        $settings = $this->getSettings();

        require __DIR__ . '/../../Views/layouts/header.php';
        require __DIR__ . '/../../Views/modules/settings-form.php';
        require __DIR__ . '/../../Views/layouts/footer.php';
    }

    public function save()
    {
        $db = Database::getConnection();

        $settings = [
            'site_name' => trim($_POST['site_name'] ?? ''),
            'site_tagline' => trim($_POST['site_tagline'] ?? ''),
            'custom_css_global' => trim($_POST['custom_css_global'] ?? ''),
        ];

        foreach ($settings as $key => $value) {
            $stmt = $db->prepare("
                INSERT INTO settings (setting_key, setting_value)
                VALUES (:key, :value)
                ON DUPLICATE KEY UPDATE setting_value = :value
            ");

            $stmt->execute([
                'key' => $key,
                'value' => $value,
            ]);
        }

        header('Location: /admin.php?module=settings&success=Paramètres enregistrés');
        exit;
    }

    private function getSettings(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings");

        $settings = [];

        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return $settings;
    }
}
