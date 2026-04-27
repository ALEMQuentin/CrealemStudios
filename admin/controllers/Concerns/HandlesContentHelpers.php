<?php

private function syncCommonMeta(int $contentId): void
{
    $meta = [
        'meta_title' => trim($_POST['meta_title'] ?? ''),
        'meta_description' => trim($_POST['meta_description'] ?? ''),
        'featured_media_id' => $_POST['featured_media_id'] ?? null,
    ];

    foreach ($meta as $key => $value) {

        // delete existing
        $stmt = $this->pdo->prepare("DELETE FROM content_meta WHERE content_id = ? AND meta_key = ?");
        $stmt->execute([$contentId, $key]);

        // insert if value not empty
        if (!empty($value)) {
            $stmt = $this->pdo->prepare("
                INSERT INTO content_meta (content_id, meta_key, meta_value, created_at, updated_at)
                VALUES (?, ?, ?, datetime('now'), datetime('now'))
            ");
            $stmt->execute([$contentId, $key, $value]);
        }
    }
}
