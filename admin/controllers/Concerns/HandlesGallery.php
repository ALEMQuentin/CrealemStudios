<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesGallery
{
    private function handleGallery(string $action): void
    {
        if ($action === 'index') {
            $galleryItems = $this->fetchAllSafe("SELECT * FROM gallery_items ORDER BY sort_order ASC, id ASC");
            $this->render('Galerie', $this->resolveView(['modules/gallery-list.php']), compact('galleryItems'));
            return;
        }

        if ($action === 'create') {
            $galleryItem = ['title' => '', 'image_media_id' => '', 'caption' => '', 'sort_order' => 0];
            $isEdit = false;
            $this->render('Ajouter un élément de galerie', $this->resolveView(['modules/gallery-form.php']), compact('galleryItem', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $galleryItem = $this->fetchOne("SELECT * FROM gallery_items WHERE id = :id", ['id' => $id]);
            if (!$galleryItem) redirectTo('/admin.php?module=gallery&error=Élément introuvable');
            $isEdit = true;
            $this->render('Modifier un élément de galerie', $this->resolveView(['modules/gallery-form.php']), compact('galleryItem', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'image_media_id' => ($_POST['image_media_id'] ?? '') !== '' ? (int)$_POST['image_media_id'] : null,
                'caption' => trim($_POST['caption'] ?? ''),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('gallery_items', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('gallery_items', $data);
            }
            redirectTo('/admin.php?module=gallery&success=Élément enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('gallery_items', $id);
            redirectTo('/admin.php?module=gallery&success=Élément supprimé');
        }

        redirectTo('/admin.php?module=gallery');
    }
}
