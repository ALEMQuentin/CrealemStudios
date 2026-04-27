<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesContentHelpers
{
    private function syncCommonMeta(int $contentId): void
    {
        $meta = [
            'meta_title' => trim((string)($_POST['meta_title'] ?? '')),
            'meta_description' => trim((string)($_POST['meta_description'] ?? '')),
            'featured_media_id' => trim((string)($_POST['featured_media_id'] ?? '')),
        ];

        foreach ($meta as $key => $value) {
            $stmt = $this->pdo->prepare("DELETE FROM content_meta WHERE content_id = ? AND meta_key = ?");
            $stmt->execute([$contentId, $key]);

            if ($value !== '') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO content_meta (content_id, meta_key, meta_value, created_at, updated_at)
                    VALUES (?, ?, ?, datetime('now'), datetime('now'))
                ");
                $stmt->execute([$contentId, $key, $value]);
            }
        }
    }
}
