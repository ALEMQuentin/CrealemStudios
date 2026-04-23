<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesMedia
{

    private function handleMedia(string $action): void
    {
        if ($action === 'index') {
            $mediaItems = $this->fetchAllSafe("SELECT * FROM media ORDER BY id DESC");
            $this->render('Médias', $this->resolveView(['modules/media-list.php']), compact('mediaItems'));
            return;
        }

        if ($action === 'create') {
            $this->render('Ajouter un média', $this->resolveView(['modules/media-form.php']), []);
            return;
        }


        if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_FILES['media_file']) || $_FILES['media_file']['error'] !== UPLOAD_ERR_OK) {
                redirectTo('/admin.php?module=media&error=Upload impossible');
            }

            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
                'image/svg+xml',
            ];

            $tmpPath = $_FILES['media_file']['tmp_name'];
            $originalName = $_FILES['media_file']['name'];
            $mimeType = mime_content_type($tmpPath) ?: 'application/octet-stream';
            $size = (int)$_FILES['media_file']['size'];

            if (!in_array($mimeType, $allowedMimeTypes, true)) {
                redirectTo('/admin.php?module=media&error=Format non autorisé');
            }

            $uploadsDir = dirname(__DIR__, 3) . '/public/uploads';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0775, true);
            }

            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $filename = uniqid('media_', true) . '.' . $extension;
            $destination = $uploadsDir . '/' . $filename;
            $publicPath = '/uploads/' . $filename;
            $now = date('Y-m-d H:i:s');

            if (!move_uploaded_file($tmpPath, $destination)) {
                redirectTo('/admin.php?module=media&error=Déplacement du fichier impossible');
            }

            $data = [
                'filename' => $filename,
                
                'path' => $publicPath,
                'mime_type' => $mimeType,
                'size' => $size,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $this->insertRow('media', $data);
            redirectTo('/admin.php?module=media&success=Média ajouté');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $item = $this->fetchOne("SELECT * FROM media WHERE id = :id", ['id' => $id]);

            if ($item && !empty($item['path'])) {
                $filePath = dirname(__DIR__, 3) . '/public' . $item['path'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            $this->deleteById('media', $id);
            redirectTo('/admin.php?module=media&success=Média supprimé');
        }

        redirectTo('/admin.php?module=media');
    }
}
