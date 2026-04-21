<?php

namespace App\Core;

use PDO;

class ThemeRenderer
{
    public static function getMenuItems(PDO $pdo, string $locationKey = 'main'): array
    {
        $menu = null;

        try {
            $stmt = $pdo->prepare("SELECT * FROM menus WHERE location_key = :location_key LIMIT 1");
            $stmt->execute(['location_key' => $locationKey]);
            $menu = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$menu) {
                $stmt = $pdo->query("
                    SELECT m.*
                    FROM menus m
                    WHERE EXISTS (
                        SELECT 1
                        FROM menu_items mi
                        WHERE mi.menu_id = m.id
                    )
                    ORDER BY m.id ASC
                    LIMIT 1
                ");
                $menu = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (\Throwable $e) {
            return [];
        }

        if (!$menu) {
            return [];
        }

        try {
            $stmt = $pdo->prepare("
                SELECT *
                FROM menu_items
                WHERE menu_id = :menu_id
                ORDER BY sort_order ASC, id ASC
            ");
            $stmt->execute(['menu_id' => $menu['id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function getBlocks(PDO $pdo, int $contentId): array
    {
        try {
            $stmt = $pdo->prepare("
                SELECT *
                FROM content_blocks
                WHERE content_id = :content_id
                ORDER BY sort_order ASC, id ASC
            ");
            $stmt->execute(['content_id' => $contentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return [];
        }
    }
}
