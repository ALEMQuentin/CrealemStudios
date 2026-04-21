<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);
$config = require $basePath . '/config/config.php';

$pdo = new PDO('sqlite:' . $config['db']['database']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$now = date('Y-m-d H:i:s');

$passwordHash = password_hash('admin1234', PASSWORD_DEFAULT);

$pdo->exec("DELETE FROM users");
$pdo->exec("DELETE FROM settings");
$pdo->exec("DELETE FROM pages");

$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (:name, :email, :password, :role, :created_at, :updated_at)");
$stmt->execute([
    'name' => 'Admin',
    'email' => 'admin@local.test',
    'password' => $passwordHash,
    'role' => 'admin',
    'created_at' => $now,
    'updated_at' => $now,
]);

$settings = [
    'site_name' => 'CrealemStudios',
    'site_tagline' => 'Base installable',
    'theme' => 'default',
];

$stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES (:setting_key, :setting_value, :created_at, :updated_at)");
foreach ($settings as $key => $value) {
    $stmt->execute([
        'setting_key' => $key,
        'setting_value' => $value,
        'created_at' => $now,
        'updated_at' => $now,
    ]);
}

$stmt = $pdo->prepare("INSERT INTO pages (title, slug, content, meta_title, meta_description, status, created_at, updated_at) VALUES (:title, :slug, :content, :meta_title, :meta_description, :status, :created_at, :updated_at)");
$stmt->execute([
    'title' => 'Accueil',
    'slug' => 'home',
    'content' => 'Bienvenue sur la base publique de CrealemStudios.',
    'meta_title' => 'Accueil',
    'meta_description' => 'Page d’accueil de test.',
    'status' => 'published',
    'created_at' => $now,
    'updated_at' => $now,
]);

echo "Seed terminé.\n";
echo "Admin : admin@local.test / admin1234\n";
