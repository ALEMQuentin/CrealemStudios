<?php

namespace App\Controllers\Admin;

class Kernel
{
    public function handle()
    {
        $module = $_GET['module'] ?? 'dashboard';
        $action = $_GET['action'] ?? 'index';

        switch ($module) {
            case 'media':
                $this->handleMedia($action);
                break;

            case 'products':
                $this->handleProducts($action);
                break;

            default:
                $this->render('Dashboard', 'modules/dashboard.php', []);
        }
    }

    private function handleMedia($action)
    {
        // 🔴 ROUTE UNIQUE : SAVE
        if ($action === 'save') {

            // POST → upload
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (!isset($_FILES['file'])) {
                    echo "Aucun fichier";
                    return;
                }

                $file = $_FILES['file'];
                $name = time() . '-' . basename($file['name']);
                $target = __DIR__ . '/../../public/uploads/' . $name;

                if (!is_dir(dirname($target))) {
                    mkdir(dirname($target), 0777, true);
                }

                if (move_uploaded_file($file['tmp_name'], $target)) {
                    echo "<h2>Upload réussi</h2>";
                    echo "<a href='/uploads/$name' target='_blank'>Voir le fichier</a>";
                } else {
                    echo "Erreur upload";
                }

                return;
            }

            // GET → formulaire upload
            $this->render('Ajouter un média', 'modules/media-form.php', []);
            return;
        }

        // LISTE MEDIA
        $this->render('Médias', 'modules/media-list.php', []);
    }

    private function handleProducts($action)
    {
        if ($action === 'create') {
            $this->render('Ajouter un produit', 'modules/product-form.php', []);
            return;
        }

        $this->render('Produits', 'modules/product-list.php', []);
    }

    private function render($title, $view, $data = [])
    {
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $view;

        if (!file_exists($viewPath)) {
            echo "<h1>Vue introuvable: $view</h1>";
            return;
        }

        include __DIR__ . '/../views/layouts/admin.php';
    }
}
