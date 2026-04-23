<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;


use App\Models\Content;
trait HandlesBlog
{



    private function handleBlog(string $action): void
    {
        if ($action === 'index') {
            $posts = Content::allByType($this->pdo, 'post');
            $this->render('Blog', $this->resolveView(['modules/blog-list.php']), compact('posts'));
            return;
        }

        if ($action === 'categories') {
            $categories = $this->fetchAllSafe("SELECT * FROM post_categories ORDER BY id DESC");
            $this->render('Catégories du blog', $this->resolveView(['modules/blog-categories-list.php']), compact('categories'));
            return;
        }

        if ($action === 'create_category') {
            $category = ['name' => '', 'slug' => ''];
            $isEdit = false;
            $this->render('Ajouter une catégorie', $this->resolveView(['modules/blog-category-form.php']), compact('category', 'isEdit'));
            return;
        }

        if ($action === 'edit_category') {
            $id = (int)($_GET['id'] ?? 0);
            $category = $this->fetchOne("SELECT * FROM post_categories WHERE id = :id", ['id' => $id]);

            if (!$category) {
                redirectTo('/admin.php?module=blog&action=categories&error=Catégorie introuvable');
            }

            $isEdit = true;
            $this->render('Modifier une catégorie', $this->resolveView(['modules/blog-category-form.php']), compact('category', 'isEdit'));
            return;
        }

        if ($action === 'save_category' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $slug = trim($_POST['slug'] ?? '');

            $check = $this->pdo->prepare("SELECT id FROM post_categories WHERE slug = :slug AND id != :id LIMIT 1");
            $check->execute([
                'slug' => $slug,
                'id' => $id,
            ]);

            if ($check->fetch()) {
                redirectTo('/admin.php?module=blog&action=categories&error=Slug déjà utilisé');
            }

            $now = date('Y-m-d H:i:s');
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'slug' => $slug,
                'updated_at' => $now,
            ];

            if ($id > 0) {
                $this->updateRow('post_categories', $id, $data);
                redirectTo('/admin.php?module=blog&action=categories&success=Catégorie modifiée');
            }

            $data['created_at'] = $now;
            $this->insertRow('post_categories', $data);

            redirectTo('/admin.php?module=blog&action=categories&success=Catégorie ajoutée');
        }

        if ($action === 'delete_category') {
            $id = (int)($_GET['id'] ?? 0);

            if ($this->tableExists('post_category_relations')) {
                $stmt = $this->pdo->prepare("DELETE FROM post_category_relations WHERE category_id = :category_id");
                $stmt->execute(['category_id' => $id]);
            }

            $this->deleteById('post_categories', $id);
            redirectTo('/admin.php?module=blog&action=categories&success=Catégorie supprimée');
        }

        if ($action === 'create') {
            $post = [
                'title' => '',
                'slug' => '',
                'excerpt' => '',
                'content' => '',
                'status' => 'draft',
                'meta_title' => '',
                'meta_description' => '',
                'featured_media_id' => '',
            ];
            $mediaLibrary = $this->fetchAllSafe("SELECT id, filename, original_name, path FROM media ORDER BY id DESC");
            $blogCategories = $this->fetchAllSafe("SELECT * FROM post_categories ORDER BY name ASC");
            $selectedCategoryIds = [];
            $isEdit = false;

            $this->render('Ajouter un article', $this->resolveView(['modules/blog-form.php']), compact('post', 'mediaLibrary', 'blogCategories', 'selectedCategoryIds', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $post = Content::findById($this->pdo, $id);

            if (!$post || ($post['type'] ?? '') !== 'post') {
                redirectTo('/admin.php?module=blog&error=Article introuvable');
            }

            $meta = Content::meta($this->pdo, $id);
            $post = array_merge($post, [
                'meta_title' => $meta['meta_title'] ?? '',
                'meta_description' => $meta['meta_description'] ?? '',
                'featured_media_id' => $meta['featured_media_id'] ?? '',
            ]);

            $mediaLibrary = $this->fetchAllSafe("SELECT id, filename, original_name, path FROM media ORDER BY id DESC");
            $blogCategories = $this->fetchAllSafe("SELECT * FROM post_categories ORDER BY name ASC");

            $selectedCategoryIds = [];
            if ($this->tableExists('post_category_relations')) {
                $stmt = $this->pdo->prepare("SELECT category_id FROM post_category_relations WHERE post_id = :post_id");
                $stmt->execute(['post_id' => $id]);
                $selectedCategoryIds = array_map('intval', array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'category_id'));
            }

            $isEdit = true;
            $this->render('Modifier un article', $this->resolveView(['modules/blog-form.php']), compact('post', 'mediaLibrary', 'blogCategories', 'selectedCategoryIds', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $slug = trim($_POST['slug'] ?? '');

            if (Content::slugExists($this->pdo, 'post', $slug, $id)) {
                redirectTo('/admin.php?module=blog&error=Slug déjà utilisé');
            }

            $now = date('Y-m-d H:i:s');
            $data = [
                'type' => 'post',
                'title' => trim($_POST['title'] ?? ''),
                'slug' => $slug,
                'excerpt' => trim($_POST['excerpt'] ?? ''),
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

            if ($this->tableExists('post_category_relations')) {
                $this->pdo->prepare("DELETE FROM post_category_relations WHERE post_id = :post_id")->execute(['post_id' => $contentId]);

                $categoryIds = $_POST['category_ids'] ?? [];
                $stmt = $this->pdo->prepare("INSERT INTO post_category_relations (post_id, category_id) VALUES (:post_id, :category_id)");

                foreach ($categoryIds as $categoryId) {
                    $categoryId = (int)$categoryId;
                    if ($categoryId > 0) {
                        $stmt->execute([
                            'post_id' => $contentId,
                            'category_id' => $categoryId,
                        ]);
                    }
                }
            }

            redirectTo('/admin.php?module=blog&success=Article enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            Content::delete($this->pdo, $id);
            redirectTo('/admin.php?module=blog&success=Article supprimé');
        }

        redirectTo('/admin.php?module=blog');
    }

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

            $uploadsDir = dirname(__DIR__, 2) . '/public/uploads';
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

    private function handleMenus(string $action): void
    {
        if ($action === 'index') {
            $menus = $this->fetchAllSafe("SELECT * FROM menus ORDER BY id DESC");
            $this->render('Menus', $this->resolveView(['modules/menus-list.php']), compact('menus'));
            return;
        }

        if ($action === 'create') {
            $menu = ['name' => '', 'location_key' => ''];
            $isEdit = false;
            $this->render('Ajouter un menu', $this->resolveView(['modules/menus-form.php']), compact('menu', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $menu = $this->fetchOne("SELECT * FROM menus WHERE id = :id", ['id' => $id]);

            if (!$menu) {
                redirectTo('/admin.php?module=menus&error=Menu introuvable');
            }

            $isEdit = true;
            $this->render('Modifier un menu', $this->resolveView(['modules/menus-form.php']), compact('menu', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $now = date('Y-m-d H:i:s');

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'location_key' => trim($_POST['location_key'] ?? ''),
                'updated_at' => $now,
            ];

            if ($id > 0) {
                $this->updateRow('menus', $id, $data);
                redirectTo('/admin.php?module=menus&success=Menu modifié');
            }

            $data['created_at'] = $now;
            $this->insertRow('menus', $data);

            redirectTo('/admin.php?module=menus&success=Menu ajouté');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);

            if ($this->tableExists('menu_items')) {
                $stmt = $this->pdo->prepare("DELETE FROM menu_items WHERE menu_id = :menu_id");
                $stmt->execute(['menu_id' => $id]);
            }

            $this->deleteById('menus', $id);
            redirectTo('/admin.php?module=menus&success=Menu supprimé');
        }

        if ($action === 'items') {
            $id = (int)($_GET['id'] ?? 0);
            $menu = $this->fetchOne("SELECT * FROM menus WHERE id = :id", ['id' => $id]);

            if (!$menu) {
                redirectTo('/admin.php?module=menus&error=Menu introuvable');
            }

            $items = [];
            if ($this->tableExists('menu_items')) {
                $stmt = $this->pdo->prepare("SELECT * FROM menu_items WHERE menu_id = :menu_id ORDER BY sort_order ASC, id ASC");
                $stmt->execute(['menu_id' => $id]);
                $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }

            $pagesForMenu = Content::allByType($this->pdo, 'page');
            $this->render('Éléments du menu', $this->resolveView(['modules/menu-items.php']), compact('menu', 'items', 'pagesForMenu'));
            return;
        }

        if ($action === 'add_item' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuId = (int)($_GET['id'] ?? 0);
            $itemType = trim($_POST['item_type'] ?? 'custom');
            $pageId = (int)($_POST['page_id'] ?? 0);
            $url = trim($_POST['url'] ?? '');

            if ($itemType === 'page' && $pageId > 0) {
                $pageData = Content::findById($this->pdo, $pageId);
                if ($pageData && ($pageData['type'] ?? '') === 'page') {
                    $url = '/?slug=' . $pageData['slug'];
                }
            }

            $data = [
                'menu_id' => $menuId,
                'label' => trim($_POST['label'] ?? ''),
                'item_type' => $itemType,
                'url' => $url,
                'page_id' => $pageId ?: null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->insertRow('menu_items', $data);
            redirectTo('/admin.php?module=menus&action=items&id=' . $menuId . '&success=Élément ajouté');
        }

        if ($action === 'delete_item') {
            $menuId = (int)($_GET['id'] ?? 0);
            $itemId = (int)($_GET['item_id'] ?? 0);

            $this->deleteById('menu_items', $itemId);
            redirectTo('/admin.php?module=menus&action=items&id=' . $menuId . '&success=Élément supprimé');
        }

        redirectTo('/admin.php?module=menus');
    }

    private function handleUsers(string $action): void
    {
        if ($action === 'index') {
            $users = $this->fetchAllSafe("SELECT * FROM users ORDER BY id DESC");
            $this->render('Utilisateurs', $this->resolveView(['modules/users-list.php']), compact('users'));
            return;
        }

        if ($action === 'create') {
            $user = ['name' => '', 'email' => '', 'role' => 'editor'];
            $isEdit = false;
            $this->render('Ajouter un utilisateur', $this->resolveView(['modules/users-form.php']), compact('user', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $user = $this->fetchOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);

            if (!$user) {
                redirectTo('/admin.php?module=users&error=Utilisateur introuvable');
            }

            $isEdit = true;
            $this->render('Modifier un utilisateur', $this->resolveView(['modules/users-form.php']), compact('user', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $now = date('Y-m-d H:i:s');

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => trim($_POST['role'] ?? 'editor'),
                'updated_at' => $now,
            ];

            $password = trim($_POST['password'] ?? '');

            if ($id > 0) {
                if ($password !== '') {
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                $this->updateRow('users', $id, $data);
                redirectTo('/admin.php?module=users&success=Utilisateur modifié');
            }

            $data['password'] = password_hash($password !== '' ? $password : 'admin1234', PASSWORD_DEFAULT);
            $data['created_at'] = $now;
            $this->insertRow('users', $data);

            redirectTo('/admin.php?module=users&success=Utilisateur ajouté');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('users', $id);
            redirectTo('/admin.php?module=users&success=Utilisateur supprimé');
        }

        redirectTo('/admin.php?module=users');
    }

    private function handleSettings(string $action): void
    {
        if ($action === 'index') {
            $this->render('Paramètres', $this->resolveView(['modules/settings-form.php']), []);
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $moduleKeys = [
                'module_products',
                'module_blog',
                'module_forms',
                'module_booking',
                'module_clients',
                'module_testimonials',
                'module_gallery',
                'module_subscriptions',
            ];

            $trackingKeys = [
                'tracking_gtm_id',
                'tracking_meta_pixel_id',
                'tracking_tiktok_pixel_id',
                'tracking_head_custom',
                'tracking_body_custom',
                'tracking_footer_custom',
                'site_name',
                'site_tagline',
                'theme',
                'custom_css_global',
            ];

            foreach ($moduleKeys as $key) {
                saveSetting($this->pdo, $key, isset($_POST[$key]) ? '1' : '0');
            }

            foreach ($trackingKeys as $key) {
                saveSetting($this->pdo, $key, trim($_POST[$key] ?? ''));
            }

            $this->settings = getSettings($this->pdo);
            redirectTo('/admin.php?module=settings&success=Paramètres enregistrés');
        }

        redirectTo('/admin.php?module=settings');
    }
}
