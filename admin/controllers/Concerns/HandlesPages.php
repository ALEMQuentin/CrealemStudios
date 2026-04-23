<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

use App\Models\Content;
trait HandlesPages
{

    private function handlePages(string $action): void
    {
        if ($action === 'index') {
            $q = trim($_GET['q'] ?? '');
            $pages = Content::allByType($this->pdo, 'page');

            if ($q !== '') {
                $pages = array_values(array_filter($pages, function ($page) use ($q) {
                    return stripos($page['title'] ?? '', $q) !== false || stripos($page['slug'] ?? '', $q) !== false;
                }));
            }

            $this->render('Pages', $this->resolveView(['modules/pages-list.php']), compact('pages'));
            return;
        }

        if ($action === 'create') {
            $page = [
                'title' => '',
                'slug' => '',
                'content' => '',
                'status' => 'draft',
            ];
            $pageMeta = [
                'meta_title' => '',
                'meta_description' => '',
                'featured_media_id' => '',
            ];
            $mediaLibrary = $this->fetchAllSafe("SELECT id, filename, original_name, path FROM media ORDER BY id DESC");
            $isEdit = false;

            $this->render('Ajouter une page', $this->resolveView(['modules/pages-form.php']), [
                'page' => array_merge($page, $pageMeta),
                'mediaLibrary' => $mediaLibrary,
                'isEdit' => $isEdit,
            ]);
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $page = Content::findById($this->pdo, $id);

            if (!$page || ($page['type'] ?? '') !== 'page') {
                redirectTo('/admin.php?module=pages&error=Page introuvable');
            }

            $meta = Content::meta($this->pdo, $id);
            $page = array_merge($page, [
                'meta_title' => $meta['meta_title'] ?? '',
                'meta_description' => $meta['meta_description'] ?? '',
                'featured_media_id' => $meta['featured_media_id'] ?? '',
            ]);

            $mediaLibrary = $this->fetchAllSafe("SELECT id, filename, original_name, path FROM media ORDER BY id DESC");
            $isEdit = true;

            $this->render('Modifier une page', $this->resolveView(['modules/pages-form.php']), compact('page', 'mediaLibrary', 'isEdit'));
            return;
        }

        if ($action === 'preview') {
            $id = (int)($_GET['id'] ?? 0);
            $page = Content::findById($this->pdo, $id);

            if (!$page || ($page['type'] ?? '') !== 'page') {
                redirectTo('/admin.php?module=pages&error=Page introuvable');
            }

            $meta = Content::meta($this->pdo, $id);
            $page = array_merge($page, [
                'meta_title' => $meta['meta_title'] ?? '',
                'meta_description' => $meta['meta_description'] ?? '',
                'featured_media_id' => $meta['featured_media_id'] ?? '',
            ]);

            $featuredMedia = null;
            if (!empty($page['featured_media_id'])) {
                $featuredMedia = $this->fetchOne("SELECT * FROM media WHERE id = :id", ['id' => (int)$page['featured_media_id']]);
            }

            $this->render('Aperçu de page', $this->resolveView(['modules/pages-preview.php']), compact('page', 'featuredMedia'));
            return;
        }

        if ($action === 'blocks') {
            $id = (int)($_GET['id'] ?? 0);
            $page = Content::findById($this->pdo, $id);

            if (!$page || ($page['type'] ?? '') !== 'page') {
                redirectTo('/admin.php?module=pages&error=Page introuvable');
            }

            $blocks = $this->fetchBlocks($id);
            $menus = $this->fetchAllSafe("SELECT * FROM menus ORDER BY name ASC");

            $this->render('Blocs de page', $this->resolveView(['modules/page-blocks.php']), compact('page', 'blocks', 'menus'));
            return;
        }

        if ($action === 'add_block' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $page = Content::findById($this->pdo, $id);

            if (!$page || ($page['type'] ?? '') !== 'page') {
                redirectTo('/admin.php?module=pages&error=Page introuvable');
            }

            $blockType = trim($_POST['block_type'] ?? '');
            $sortOrder = (int)($_POST['sort_order'] ?? 0);
            $settings = $this->extractBlockSettings($blockType);

            $this->insertRow('content_blocks', [
                'content_id' => $id,
                'block_type' => $blockType,
                'sort_order' => $sortOrder,
                'settings_json' => json_encode($settings, JSON_UNESCAPED_UNICODE),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            redirectTo('/admin.php?module=pages&action=blocks&id=' . $id . '&success=Bloc ajouté');
        }

        if ($action === 'edit_block') {
            $id = (int)($_GET['id'] ?? 0);
            $blockId = (int)($_GET['block_id'] ?? 0);

            $page = Content::findById($this->pdo, $id);
            $block = $this->fetchOne("SELECT * FROM content_blocks WHERE id = :id AND content_id = :content_id", [
                'id' => $blockId,
                'content_id' => $id,
            ]);

            if (!$page || !$block || ($page['type'] ?? '') !== 'page') {
                redirectTo('/admin.php?module=pages&error=Bloc introuvable');
            }

            $menus = $this->fetchAllSafe("SELECT * FROM menus ORDER BY name ASC");
            $blockSettings = json_decode($block['settings_json'] ?? '{}', true) ?: [];

            $this->render('Modifier un bloc', $this->resolveView(['modules/page-block-form.php']), compact('page', 'block', 'blockSettings', 'menus'));
            return;
        }

        if ($action === 'save_block' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $blockId = (int)($_GET['block_id'] ?? 0);

            $block = $this->fetchOne("SELECT * FROM content_blocks WHERE id = :id AND content_id = :content_id", [
                'id' => $blockId,
                'content_id' => $id,
            ]);

            if (!$block) {
                redirectTo('/admin.php?module=pages&error=Bloc introuvable');
            }

            $blockType = $block['block_type'];
            $settings = $this->extractBlockSettings($blockType);

            $this->updateRow('content_blocks', $blockId, [
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'settings_json' => json_encode($settings, JSON_UNESCAPED_UNICODE),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            redirectTo('/admin.php?module=pages&action=blocks&id=' . $id . '&success=Bloc modifié');
        }

        if ($action === 'move_block_up') {
            $id = (int)($_GET['id'] ?? 0);
            $blockId = (int)($_GET['block_id'] ?? 0);
            $this->moveBlock($id, $blockId, 'up');
            redirectTo('/admin.php?module=pages&action=blocks&id=' . $id . '&success=Bloc réordonné');
        }

        if ($action === 'move_block_down') {
            $id = (int)($_GET['id'] ?? 0);
            $blockId = (int)($_GET['block_id'] ?? 0);
            $this->moveBlock($id, $blockId, 'down');
            redirectTo('/admin.php?module=pages&action=blocks&id=' . $id . '&success=Bloc réordonné');
        }

        if ($action === 'delete_block') {
            $id = (int)($_GET['id'] ?? 0);
            $blockId = (int)($_GET['block_id'] ?? 0);

            $this->deleteById('content_blocks', $blockId);
            redirectTo('/admin.php?module=pages&action=blocks&id=' . $id . '&success=Bloc supprimé');
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $slug = trim($_POST['slug'] ?? '');

            if (Content::slugExists($this->pdo, 'page', $slug, $id)) {
                redirectTo('/admin.php?module=pages&error=Slug déjà utilisé');
            }

            $now = date('Y-m-d H:i:s');
            $data = [
                'type' => 'page',
                'title' => trim($_POST['title'] ?? ''),
                'slug' => $slug,
                'excerpt' => null,
                'content' => trim($_POST['content'] ?? ''),
                'status' => trim($_POST['status'] ?? 'draft'),
                'author_id' => null,
                'parent_id' => null,
                'menu_order' => 0,
                'updated_at' => $now,
            ];

            if ($id > 0) {
                Content::update($this->pdo, $id, $data);
                $contentId = $id;
            } else {
                $data['created_at'] = $now;
                $contentId = Content::create($this->pdo, $data);
            }

            $this->syncCommonMeta($contentId);

            redirectTo('/admin.php?module=pages&success=Page enregistrée');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                redirectTo('/admin.php?module=media&error=Média introuvable');
            }

            $mediaItem = $this->fetchOne("SELECT * FROM media WHERE id = :id", ['id' => $id]);
            if (!$mediaItem) {
                redirectTo('/admin.php?module=media&error=Média introuvable');
            }

            $projectRoot = dirname(__DIR__, 2);
            $relativePath = $mediaItem['path'] ?? '';
            $filePath = $projectRoot . '/public' . $relativePath;

            if ($relativePath !== '' && is_file($filePath)) {
                @unlink($filePath);
            }

            $this->deleteById('media', $id);
            redirectTo('/admin.php?module=media&success=Média supprimé');
        }

        redirectTo('/admin.php?module=pages');
    }
}
