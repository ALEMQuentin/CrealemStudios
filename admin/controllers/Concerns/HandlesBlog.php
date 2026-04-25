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


}
