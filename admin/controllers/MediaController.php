<?php

class MediaController
{
    private $uploadDir = __DIR__ . '/../../public/uploads/';
    private $maxFileSize = 5 * 1024 * 1024; // 5MB

    private $allowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    public function upload()
    {
        header('Content-Type: application/json');

        if (!isset($_FILES['media_file'])) {
            $this->error('Fichier manquant');
        }

        $file = $_FILES['media_file'];

        // Vérif upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error('Erreur upload');
        }

        // Taille
        if ($file['size'] > $this->maxFileSize) {
            $this->error('Fichier trop volumineux');
        }

        // MIME réel (sécurité)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!isset($this->allowedMimeTypes[$mime])) {
            $this->error('Type de fichier interdit');
        }

        $extension = $this->allowedMimeTypes[$mime];

        // Nom sécurisé
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $destination = $this->uploadDir . $filename;

        // Création dossier si besoin
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // Move sécurisé
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->error('Erreur lors de la sauvegarde');
        }

        // Réponse
        echo json_encode([
            'success' => true,
            'path' => '/uploads/' . $filename,
            'filename' => $filename,
            'mime_type' => $mime,
            'size' => $file['size']
        ]);
        exit;
    }

    private function error($message)
    {
        http_response_code(400);
        echo json_encode(['error' => $message]);
        exit;
    }
}
