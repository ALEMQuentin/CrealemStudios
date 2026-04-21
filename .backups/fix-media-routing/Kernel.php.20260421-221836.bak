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
                $this->render('Dashboard', null, []);
        }
    }

    private function handleMedia($action)
    {
        if ($action === 'create') {
            echo "<h1>Créer un média</h1>";
            return;
        }

        echo "<h1>Liste des médias</h1>";
    }

    private function handleProducts($action)
    {
        if ($action === 'create') {
            echo "<h1>Créer un produit</h1>";
            return;
        }

        echo "<h1>Liste des produits</h1>";
    }

    private function render($title, $view, $data)
    {
        echo "<h1>" . htmlspecialchars($title) . "</h1>";
    }
}
