<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Concerns\HandlesBlog;

use App\Controllers\Admin\Concerns\HandlesPages;

use App\Models\Content;
use PDO;

class Kernel
{
    use HandlesBlog;

    use HandlesPages;

    private PDO $pdo;
    private array $config;
    private array $settings;
    private string $module;
    private string $action;

    public function __construct(PDO $pdo, array $config, string $module, string $action)
    {
        $this->pdo = $pdo;
        $this->config = $config;
        $this->module = $module;
        $this->action = $action;
        $this->settings = getSettings($pdo);
    }

    public function handle(): void
    {
        $module = $this->module;
        $action = $this->action;

        if (!isModuleEnabled($this->settings, $module)) {
            redirectTo('/admin.php?module=dashboard&error=Module désactivé');
        }

        switch ($module) {
            case 'dashboard':
                $this->handleDashboard();
                return;
            case 'pages':
                $this->handlePages($action);
                return;
            case 'blog':
                $this->handleBlog($action);
                return;
            case 'media':
                $this->handleMedia($action);
                return;
            case 'menus':
                $this->handleMenus($action);
                return;
            case 'users':
                $this->handleUsers($action);
                return;
            case 'settings':
                $this->handleSettings($action);
                return;
            case 'products':
                $this->handleProducts($action);
                return;
        }

        http_response_code(404);
        echo 'Module introuvable';
    }

    private function handleDashboard(): void
    {
        $stats = [
            'pages' => count(Content::allByType($this->pdo, 'page')),
            'users' => $this->safeCount('users'),
            'media' => $this->safeCount('media'),
            'posts' => count(Content::allByType($this->pdo, 'post')),
        ];

        $this->render(
            'Dashboard',
            $this->resolveView([
                'modules/dashboard.php',
                'admin/dashboard.php',
            ]),
            compact('stats')
        );
    }


    private function handleProducts(string $action): void
    {
        if ($action === 'categories') {
            $categories = $this->fetchAllSafe("
                SELECT c.*, p.name AS parent_name
                FROM product_categories c
                LEFT JOIN product_categories p ON p.id = c.parent_id
                ORDER BY c.sort_order ASC, c.name ASC
            ");
            $this->render('Catégories produit', $this->resolveView(['modules/product-categories-list.php']), compact('categories'));
            return;
        }

        if ($action === 'create_category') {
            $category = ['name' => '', 'slug' => '', 'parent_id' => '', 'description' => '', 'sort_order' => 0];
            $allCategories = $this->fetchAllSafe("SELECT * FROM product_categories ORDER BY name ASC");
            $isEdit = false;
            $this->render('Ajouter une catégorie produit', $this->resolveView(['modules/product-category-form.php']), compact('category', 'allCategories', 'isEdit'));
            return;
        }

        if ($action === 'edit_category') {
            $id = (int)($_GET['id'] ?? 0);
            $category = $this->fetchOne("SELECT * FROM product_categories WHERE id = :id", ['id' => $id]);
            if (!$category) redirectTo('/admin.php?module=products&action=categories&error=Catégorie introuvable');
            $allCategories = $this->fetchAllSafe("SELECT * FROM product_categories WHERE id != " . $id . " ORDER BY name ASC");
            $isEdit = true;
            $this->render('Modifier une catégorie produit', $this->resolveView(['modules/product-category-form.php']), compact('category', 'allCategories', 'isEdit'));
            return;
        }

        if ($action === 'save_category' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'parent_id' => ($_POST['parent_id'] ?? '') !== '' ? (int)$_POST['parent_id'] : null,
                'description' => trim($_POST['description'] ?? ''),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('product_categories', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('product_categories', $data);
            }
            redirectTo('/admin.php?module=products&action=categories&success=Catégorie enregistrée');
        }

        if ($action === 'delete_category') {
            $id = (int)($_GET['id'] ?? 0);
            if ($this->tableExists('product_category_relations')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_category_relations WHERE category_id = :category_id");
                $stmt->execute(['category_id' => $id]);
            }
            $this->deleteById('product_categories', $id);
            redirectTo('/admin.php?module=products&action=categories&success=Catégorie supprimée');
        }

        if ($action === 'attributes') {
            $attributes = $this->fetchAllSafe("
                SELECT a.*,
                       (SELECT COUNT(*) FROM product_attribute_terms t WHERE t.attribute_id = a.id) AS terms_count
                FROM product_attributes a
                ORDER BY a.sort_order ASC, a.name ASC
            ");
            $this->render('Attributs produit', $this->resolveView(['modules/product-attributes-list.php']), compact('attributes'));
            return;
        }

        if ($action === 'create_attribute') {
            $attribute = ['name' => '', 'slug' => '', 'sort_order' => 0];
            $isEdit = false;
            $this->render('Ajouter un attribut', $this->resolveView(['modules/product-attribute-form.php']), compact('attribute', 'isEdit'));
            return;
        }

        if ($action === 'edit_attribute') {
            $id = (int)($_GET['id'] ?? 0);
            $attribute = $this->fetchOne("SELECT * FROM product_attributes WHERE id = :id", ['id' => $id]);
            if (!$attribute) redirectTo('/admin.php?module=products&action=attributes&error=Attribut introuvable');
            $isEdit = true;
            $this->render('Modifier un attribut', $this->resolveView(['modules/product-attribute-form.php']), compact('attribute', 'isEdit'));
            return;
        }

        if ($action === 'save_attribute' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('product_attributes', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('product_attributes', $data);
            }
            redirectTo('/admin.php?module=products&action=attributes&success=Attribut enregistré');
        }

        if ($action === 'delete_attribute') {
            $id = (int)($_GET['id'] ?? 0);
            if ($this->tableExists('product_attribute_terms')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_attribute_terms WHERE attribute_id = :attribute_id");
                $stmt->execute(['attribute_id' => $id]);
            }
            if ($this->tableExists('product_attribute_relations')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_attribute_relations WHERE attribute_id = :attribute_id");
                $stmt->execute(['attribute_id' => $id]);
            }
            $this->deleteById('product_attributes', $id);
            redirectTo('/admin.php?module=products&action=attributes&success=Attribut supprimé');
        }

        if ($action === 'attribute_terms') {
            $id = (int)($_GET['id'] ?? 0);
            $attribute = $this->fetchOne("SELECT * FROM product_attributes WHERE id = :id", ['id' => $id]);
            if (!$attribute) redirectTo('/admin.php?module=products&action=attributes&error=Attribut introuvable');
            $terms = $this->fetchAllSafe("SELECT * FROM product_attribute_terms WHERE attribute_id = " . $id . " ORDER BY sort_order ASC, name ASC");
            $this->render('Termes d’attribut', $this->resolveView(['modules/product-attribute-terms-list.php']), compact('attribute', 'terms'));
            return;
        }

        if ($action === 'create_attribute_term') {
            $id = (int)($_GET['id'] ?? 0);
            $attribute = $this->fetchOne("SELECT * FROM product_attributes WHERE id = :id", ['id' => $id]);
            if (!$attribute) redirectTo('/admin.php?module=products&action=attributes&error=Attribut introuvable');
            $term = ['name' => '', 'slug' => '', 'sort_order' => 0];
            $isEdit = false;
            $this->render('Ajouter un terme', $this->resolveView(['modules/product-attribute-term-form.php']), compact('attribute', 'term', 'isEdit'));
            return;
        }

        if ($action === 'edit_attribute_term') {
            $id = (int)($_GET['id'] ?? 0);
            $termId = (int)($_GET['term_id'] ?? 0);
            $attribute = $this->fetchOne("SELECT * FROM product_attributes WHERE id = :id", ['id' => $id]);
            if (!$attribute) redirectTo('/admin.php?module=products&action=attributes&error=Attribut introuvable');
            $term = $this->fetchOne("SELECT * FROM product_attribute_terms WHERE id = :id AND attribute_id = :attribute_id", ['id' => $termId, 'attribute_id' => $id]);
            if (!$term) redirectTo('/admin.php?module=products&action=attribute_terms&id=' . $id . '&error=Terme introuvable');
            $isEdit = true;
            $this->render('Modifier un terme', $this->resolveView(['modules/product-attribute-term-form.php']), compact('attribute', 'term', 'isEdit'));
            return;
        }

        if ($action === 'save_attribute_term' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $termId = (int)($_GET['term_id'] ?? 0);
            $data = [
                'attribute_id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($termId > 0) {
                $this->updateRow('product_attribute_terms', $termId, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('product_attribute_terms', $data);
            }
            redirectTo('/admin.php?module=products&action=attribute_terms&id=' . $id . '&success=Terme enregistré');
        }

        if ($action === 'delete_attribute_term') {
            $id = (int)($_GET['id'] ?? 0);
            $termId = (int)($_GET['term_id'] ?? 0);
            if ($this->tableExists('product_attribute_relations')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_attribute_relations WHERE term_id = :term_id");
                $stmt->execute(['term_id' => $termId]);
            }
            if ($this->tableExists('product_variation_attribute_values')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_variation_attribute_values WHERE term_id = :term_id");
                $stmt->execute(['term_id' => $termId]);
            }
            $this->deleteById('product_attribute_terms', $termId);
            redirectTo('/admin.php?module=products&action=attribute_terms&id=' . $id . '&success=Terme supprimé');
        }

        if ($action === 'settings') {
            $this->render('Paramètres du catalogue', $this->resolveView(['modules/product-settings-form.php']), []);
            return;
        }

        if ($action === 'save_settings' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $keys = [
                'products_catalog_label',
                'products_currency_symbol',
                'products_stock_enabled',
                'products_per_page',
                'products_default_sort',
                'products_enable_sku',
                'products_enable_dimensions',
            ];
            foreach ($keys as $key) {
                saveSetting($this->pdo, $key, trim((string)($_POST[$key] ?? '')));
            }
            $this->settings = getSettings($this->pdo);
            redirectTo('/admin.php?module=products&action=settings&success=Paramètres enregistrés');
        }

        if ($action === 'variations') {
            $id = (int)($_GET['id'] ?? 0);
            $product = $this->fetchOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);
            if (!$product) redirectTo('/admin.php?module=products&error=Produit introuvable');

            $variations = $this->fetchAllSafe("SELECT * FROM product_variations WHERE product_id = " . $id . " ORDER BY sort_order ASC, id ASC");

            foreach ($variations as &$variation) {
                $variationId = (int)$variation['id'];
                $rows = $this->fetchAllSafe("
                    SELECT a.name AS attribute_name, t.name AS term_name
                    FROM product_variation_attribute_values v
                    INNER JOIN product_attributes a ON a.id = v.attribute_id
                    INNER JOIN product_attribute_terms t ON t.id = v.term_id
                    WHERE v.variation_id = " . $variationId . "
                    ORDER BY a.name ASC
                ");
                $parts = [];
                foreach ($rows as $row) {
                    $parts[] = ($row['attribute_name'] ?? '') . ': ' . ($row['term_name'] ?? '');
                }
                $variation['attributes_summary'] = implode(' | ', $parts);
            }
            unset($variation);

            $this->render('Variations produit', $this->resolveView(['modules/product-variations-list.php']), compact('product', 'variations'));
            return;
        }

        if ($action === 'create_variation') {
            $id = (int)($_GET['id'] ?? 0);
            $product = $this->fetchOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);
            if (!$product) redirectTo('/admin.php?module=products&error=Produit introuvable');

            $variation = [
                'sku' => '',
                'regular_price' => '',
                'sale_price' => '',
                'stock_quantity' => '',
                'stock_status' => 'instock',
                'image_media_id' => '',
                'sort_order' => 0,
                'status' => 'published',
            ];

            $variationAttributes = $this->fetchAllSafe("
                SELECT DISTINCT a.*
                FROM product_attribute_relations r
                INNER JOIN product_attributes a ON a.id = r.attribute_id
                WHERE r.product_id = " . $id . "
                ORDER BY a.sort_order ASC, a.name ASC
            ");

            $variationTermsMap = [];
            foreach ($variationAttributes as $attribute) {
                $attributeId = (int)$attribute['id'];
                $variationTermsMap[$attributeId] = $this->fetchAllSafe("
                    SELECT DISTINCT t.*
                    FROM product_attribute_relations r
                    INNER JOIN product_attribute_terms t ON t.id = r.term_id
                    WHERE r.product_id = " . $id . " AND r.attribute_id = " . $attributeId . "
                    ORDER BY t.sort_order ASC, t.name ASC
                ");
            }

            $selectedVariationTerms = [];
            $isEdit = false;
            $this->render('Ajouter une variation', $this->resolveView(['modules/product-variation-form.php']), compact('product', 'variation', 'variationAttributes', 'variationTermsMap', 'selectedVariationTerms', 'isEdit'));
            return;
        }

        if ($action === 'edit_variation') {
            $id = (int)($_GET['id'] ?? 0);
            $variationId = (int)($_GET['variation_id'] ?? 0);

            $product = $this->fetchOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);
            if (!$product) redirectTo('/admin.php?module=products&error=Produit introuvable');

            $variation = $this->fetchOne("SELECT * FROM product_variations WHERE id = :id AND product_id = :product_id", [
                'id' => $variationId,
                'product_id' => $id,
            ]);
            if (!$variation) redirectTo('/admin.php?module=products&action=variations&id=' . $id . '&error=Variation introuvable');

            $variationAttributes = $this->fetchAllSafe("
                SELECT DISTINCT a.*
                FROM product_attribute_relations r
                INNER JOIN product_attributes a ON a.id = r.attribute_id
                WHERE r.product_id = " . $id . "
                ORDER BY a.sort_order ASC, a.name ASC
            ");

            $variationTermsMap = [];
            foreach ($variationAttributes as $attribute) {
                $attributeId = (int)$attribute['id'];
                $variationTermsMap[$attributeId] = $this->fetchAllSafe("
                    SELECT DISTINCT t.*
                    FROM product_attribute_relations r
                    INNER JOIN product_attribute_terms t ON t.id = r.term_id
                    WHERE r.product_id = " . $id . " AND r.attribute_id = " . $attributeId . "
                    ORDER BY t.sort_order ASC, t.name ASC
                ");
            }

            $selectedVariationTerms = [];
            $rows = $this->fetchAllSafe("SELECT * FROM product_variation_attribute_values WHERE variation_id = " . $variationId);
            foreach ($rows as $row) {
                $selectedVariationTerms[(int)$row['attribute_id']] = (int)$row['term_id'];
            }

            $isEdit = true;
            $this->render('Modifier une variation', $this->resolveView(['modules/product-variation-form.php']), compact('product', 'variation', 'variationAttributes', 'variationTermsMap', 'selectedVariationTerms', 'isEdit'));
            return;
        }

        if ($action === 'save_variation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $variationId = (int)($_GET['variation_id'] ?? 0);

            $data = [
                'product_id' => $id,
                'sku' => trim($_POST['sku'] ?? ''),
                'regular_price' => ($_POST['regular_price'] ?? '') !== '' ? (float)$_POST['regular_price'] : null,
                'sale_price' => ($_POST['sale_price'] ?? '') !== '' ? (float)$_POST['sale_price'] : null,
                'stock_quantity' => ($_POST['stock_quantity'] ?? '') !== '' ? (int)$_POST['stock_quantity'] : null,
                'stock_status' => trim($_POST['stock_status'] ?? 'instock'),
                'image_media_id' => ($_POST['image_media_id'] ?? '') !== '' ? (int)$_POST['image_media_id'] : null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'status' => trim($_POST['status'] ?? 'published'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($variationId > 0) {
                $this->updateRow('product_variations', $variationId, $data);
                $savedVariationId = $variationId;
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $savedVariationId = $this->insertRow('product_variations', $data);
            }

            if ($this->tableExists('product_variation_attribute_values')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_variation_attribute_values WHERE variation_id = :variation_id");
                $stmt->execute(['variation_id' => $savedVariationId]);

                $variationTermIds = $_POST['variation_term_ids'] ?? [];
                $stmt = $this->pdo->prepare("
                    INSERT INTO product_variation_attribute_values (variation_id, attribute_id, term_id)
                    VALUES (:variation_id, :attribute_id, :term_id)
                ");

                foreach ($variationTermIds as $attributeId => $termId) {
                    $attributeId = (int)$attributeId;
                    $termId = (int)$termId;
                    if ($attributeId > 0 && $termId > 0) {
                        $stmt->execute([
                            'variation_id' => $savedVariationId,
                            'attribute_id' => $attributeId,
                            'term_id' => $termId,
                        ]);
                    }
                }
            }

            redirectTo('/admin.php?module=products&action=variations&id=' . $id . '&success=Variation enregistrée');
        }

        if ($action === 'delete_variation') {
            $id = (int)($_GET['id'] ?? 0);
            $variationId = (int)($_GET['variation_id'] ?? 0);

            if ($this->tableExists('product_variation_attribute_values')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_variation_attribute_values WHERE variation_id = :variation_id");
                $stmt->execute(['variation_id' => $variationId]);
            }

            $this->deleteById('product_variations', $variationId);
            redirectTo('/admin.php?module=products&action=variations&id=' . $id . '&success=Variation supprimée');
        }

        if ($action === 'index') {
            $q = trim($_GET['q'] ?? '');
            $products = $this->fetchAllSafe("SELECT * FROM products ORDER BY sort_order ASC, id DESC");

            if ($q !== '') {
                $products = array_values(array_filter($products, function ($product) use ($q) {
                    return stripos($product['title'] ?? '', $q) !== false
                        || stripos($product['slug'] ?? '', $q) !== false
                        || stripos($product['sku'] ?? '', $q) !== false;
                }));
            }

            $this->render('Produits', $this->resolveView(['modules/products-list.php']), compact('products'));
            return;
        }

        if ($action === 'create') {
            $product = [
                'title' => '',
                'slug' => '',
                'content' => '',
                'short_description' => '',
                'status' => 'draft',
                'featured_media_id' => '',
                'sku' => '',
                'regular_price' => '',
                'sale_price' => '',
                'manage_stock' => 0,
                'stock_quantity' => '',
                'stock_status' => 'instock',
                'catalog_visibility' => 'visible',
                'product_type' => 'simple',
                'weight' => '',
                'length' => '',
                'width' => '',
                'height' => '',
                'sort_order' => 0,
            ];
            $productCategories = $this->fetchAllSafe("SELECT * FROM product_categories ORDER BY sort_order ASC, name ASC");
            $selectedCategoryIds = [];
            $allAttributes = $this->fetchAllSafe("SELECT * FROM product_attributes ORDER BY sort_order ASC, name ASC");
            $attributeTermsMap = [];
            foreach ($allAttributes as $attribute) {
                $attributeTermsMap[(int)$attribute['id']] = $this->fetchAllSafe("SELECT * FROM product_attribute_terms WHERE attribute_id = " . (int)$attribute['id'] . " ORDER BY sort_order ASC, name ASC");
            }
            $selectedAttributeIds = [];
            $selectedAttributeTerms = [];
            $isEdit = false;
            $mediaLibrary = $this->fetchAllSafe("SELECT id, filename, original_name, path, mime_type FROM media ORDER BY id DESC");
            $this->render('Ajouter un produit', $this->resolveView(['modules/products-form.php']), compact('product', 'productCategories', 'selectedCategoryIds', 'allAttributes', 'attributeTermsMap', 'selectedAttributeIds', 'selectedAttributeTerms', 'mediaLibrary', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $product = $this->fetchOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);
            if (!$product) redirectTo('/admin.php?module=products&error=Produit introuvable');

            $productCategories = $this->fetchAllSafe("SELECT * FROM product_categories ORDER BY sort_order ASC, name ASC");
            $selectedCategoryIds = [];
            if ($this->tableExists('product_category_relations')) {
                $stmt = $this->pdo->prepare("SELECT category_id FROM product_category_relations WHERE product_id = :product_id");
                $stmt->execute(['product_id' => $id]);
                $selectedCategoryIds = array_map('intval', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'category_id'));
            }

            $allAttributes = $this->fetchAllSafe("SELECT * FROM product_attributes ORDER BY sort_order ASC, name ASC");
            $attributeTermsMap = [];
            foreach ($allAttributes as $attribute) {
                $attributeTermsMap[(int)$attribute['id']] = $this->fetchAllSafe("SELECT * FROM product_attribute_terms WHERE attribute_id = " . (int)$attribute['id'] . " ORDER BY sort_order ASC, name ASC");
            }

            $selectedAttributeIds = [];
            $selectedAttributeTerms = [];
            if ($this->tableExists('product_attribute_relations')) {
                $rows = $this->fetchAllSafe("SELECT * FROM product_attribute_relations WHERE product_id = " . $id);
                foreach ($rows as $row) {
                    $attributeId = (int)$row['attribute_id'];
                    $termId = (int)$row['term_id'];
                    if (!in_array($attributeId, $selectedAttributeIds, true)) {
                        $selectedAttributeIds[] = $attributeId;
                    }
                    $selectedAttributeTerms[$attributeId][] = $termId;
                }
            }

            $isEdit = true;
            $mediaLibrary = $this->fetchAllSafe("SELECT id, filename, original_name, path, mime_type FROM media ORDER BY id DESC");
            $this->render('Modifier un produit', $this->resolveView(['modules/products-form.php']), compact('product', 'productCategories', 'selectedCategoryIds', 'allAttributes', 'attributeTermsMap', 'selectedAttributeIds', 'selectedAttributeTerms', 'mediaLibrary', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'short_description' => trim($_POST['short_description'] ?? ''),
                'status' => trim($_POST['status'] ?? 'draft'),
                'featured_media_id' => ($_POST['featured_media_id'] ?? '') !== '' ? (int)$_POST['featured_media_id'] : null,
                'sku' => trim($_POST['sku'] ?? ''),
                'regular_price' => ($_POST['regular_price'] ?? '') !== '' ? (float)$_POST['regular_price'] : null,
                'sale_price' => ($_POST['sale_price'] ?? '') !== '' ? (float)$_POST['sale_price'] : null,
                'manage_stock' => (int)($_POST['manage_stock'] ?? 0),
                'stock_quantity' => ($_POST['stock_quantity'] ?? '') !== '' ? (int)$_POST['stock_quantity'] : null,
                'stock_status' => trim($_POST['stock_status'] ?? 'instock'),
                'catalog_visibility' => trim($_POST['catalog_visibility'] ?? 'visible'),
                'product_type' => trim($_POST['product_type'] ?? 'simple'),
                'weight' => ($_POST['weight'] ?? '') !== '' ? (float)$_POST['weight'] : null,
                'length' => ($_POST['length'] ?? '') !== '' ? (float)$_POST['length'] : null,
                'width' => ($_POST['width'] ?? '') !== '' ? (float)$_POST['width'] : null,
                'height' => ($_POST['height'] ?? '') !== '' ? (float)$_POST['height'] : null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($id > 0) {
                $this->updateRow('products', $id, $data);
                $productId = $id;
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $productId = $this->insertRow('products', $data);
            }

            if ($this->tableExists('product_category_relations')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_category_relations WHERE product_id = :product_id");
                $stmt->execute(['product_id' => $productId]);

                $categoryIds = $_POST['category_ids'] ?? [];
                $stmt = $this->pdo->prepare("INSERT INTO product_category_relations (product_id, category_id) VALUES (:product_id, :category_id)");
                foreach ($categoryIds as $categoryId) {
                    $categoryId = (int)$categoryId;
                    if ($categoryId > 0) {
                        $stmt->execute([
                            'product_id' => $productId,
                            'category_id' => $categoryId,
                        ]);
                    }
                }
            }

            if ($this->tableExists('product_attribute_relations')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_attribute_relations WHERE product_id = :product_id");
                $stmt->execute(['product_id' => $productId]);

                $attributeIds = array_map('intval', $_POST['attribute_ids'] ?? []);
                $attributeTermIds = $_POST['attribute_term_ids'] ?? [];

                $stmt = $this->pdo->prepare("
                    INSERT INTO product_attribute_relations (product_id, attribute_id, term_id)
                    VALUES (:product_id, :attribute_id, :term_id)
                ");

                foreach ($attributeIds as $attributeId) {
                    $terms = $attributeTermIds[$attributeId] ?? [];
                    foreach ($terms as $termId) {
                        $termId = (int)$termId;
                        if ($attributeId > 0 && $termId > 0) {
                            $stmt->execute([
                                'product_id' => $productId,
                                'attribute_id' => $attributeId,
                                'term_id' => $termId,
                            ]);
                        }
                    }
                }
            }

            redirectTo('/admin.php?module=products&success=Produit enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            if ($this->tableExists('product_category_relations')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_category_relations WHERE product_id = :product_id");
                $stmt->execute(['product_id' => $id]);
            }
            if ($this->tableExists('product_attribute_relations')) {
                $stmt = $this->pdo->prepare("DELETE FROM product_attribute_relations WHERE product_id = :product_id");
                $stmt->execute(['product_id' => $id]);
            }
            if ($this->tableExists('product_variations')) {
                $variationIds = $this->fetchAllSafe("SELECT id FROM product_variations WHERE product_id = " . $id);
                foreach ($variationIds as $row) {
                    $variationId = (int)$row['id'];
                    if ($this->tableExists('product_variation_attribute_values')) {
                        $stmt = $this->pdo->prepare("DELETE FROM product_variation_attribute_values WHERE variation_id = :variation_id");
                        $stmt->execute(['variation_id' => $variationId]);
                    }
                }
                $stmt = $this->pdo->prepare("DELETE FROM product_variations WHERE product_id = :product_id");
                $stmt->execute(['product_id' => $id]);
            }
            $this->deleteById('products', $id);
            redirectTo('/admin.php?module=products&success=Produit supprimé');
        }

        redirectTo('/admin.php?module=products');
    }

    private function handleForms(string $action): void
    {
        if ($action === 'index') {
            $forms = $this->fetchAllSafe("SELECT * FROM forms ORDER BY id DESC");
            $this->render('Formulaires', $this->resolveView(['modules/forms-list.php']), compact('forms'));
            return;
        }

        if ($action === 'create') {
            $form = ['title' => '', 'slug' => '', 'description' => '', 'form_schema_json' => '[]', 'status' => 'draft'];
            $isEdit = false;
            $this->render('Ajouter un formulaire', $this->resolveView(['modules/forms-form.php']), compact('form', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $form = $this->fetchOne("SELECT * FROM forms WHERE id = :id", ['id' => $id]);
            if (!$form) redirectTo('/admin.php?module=forms&error=Formulaire introuvable');
            $isEdit = true;
            $this->render('Modifier un formulaire', $this->resolveView(['modules/forms-form.php']), compact('form', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'form_schema_json' => trim($_POST['form_schema_json'] ?? '[]'),
                'status' => trim($_POST['status'] ?? 'draft'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('forms', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('forms', $data);
            }
            redirectTo('/admin.php?module=forms&success=Formulaire enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('forms', $id);
            redirectTo('/admin.php?module=forms&success=Formulaire supprimé');
        }

        redirectTo('/admin.php?module=forms');
    }

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

    private function handleTestimonials(string $action): void
    {
        if ($action === 'index') {
            $testimonials = $this->fetchAllSafe("SELECT * FROM testimonials ORDER BY id DESC");
            $this->render('Avis', $this->resolveView(['modules/testimonials-list.php']), compact('testimonials'));
            return;
        }

        if ($action === 'create') {
            $testimonial = ['author_name' => '', 'company' => '', 'content' => '', 'rating' => 5, 'status' => 'published'];
            $isEdit = false;
            $this->render('Ajouter un avis', $this->resolveView(['modules/testimonials-form.php']), compact('testimonial', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $testimonial = $this->fetchOne("SELECT * FROM testimonials WHERE id = :id", ['id' => $id]);
            if (!$testimonial) redirectTo('/admin.php?module=testimonials&error=Avis introuvable');
            $isEdit = true;
            $this->render('Modifier un avis', $this->resolveView(['modules/testimonials-form.php']), compact('testimonial', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'author_name' => trim($_POST['author_name'] ?? ''),
                'company' => trim($_POST['company'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'rating' => (int)($_POST['rating'] ?? 5),
                'status' => trim($_POST['status'] ?? 'published'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('testimonials', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('testimonials', $data);
            }
            redirectTo('/admin.php?module=testimonials&success=Avis enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('testimonials', $id);
            redirectTo('/admin.php?module=testimonials&success=Avis supprimé');
        }

        redirectTo('/admin.php?module=testimonials');
    }

    private function handleClients(string $action): void
    {
        if ($action === 'index') {
            $clients = $this->fetchAllSafe("SELECT * FROM clients ORDER BY id DESC");
            $this->render('Clients', $this->resolveView(['modules/clients-list.php']), compact('clients'));
            return;
        }

        if ($action === 'create') {
            $client = ['first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '', 'company' => '', 'notes' => ''];
            $isEdit = false;
            $this->render('Ajouter un client', $this->resolveView(['modules/clients-form.php']), compact('client', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $client = $this->fetchOne("SELECT * FROM clients WHERE id = :id", ['id' => $id]);
            if (!$client) redirectTo('/admin.php?module=clients&error=Client introuvable');
            $isEdit = true;
            $this->render('Modifier un client', $this->resolveView(['modules/clients-form.php']), compact('client', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'company' => trim($_POST['company'] ?? ''),
                'notes' => trim($_POST['notes'] ?? ''),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('clients', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('clients', $data);
            }
            redirectTo('/admin.php?module=clients&success=Client enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('clients', $id);
            redirectTo('/admin.php?module=clients&success=Client supprimé');
        }

        redirectTo('/admin.php?module=clients');
    }

    private function handleBooking(string $action): void
    {
        if ($action === 'index') {
            $bookings = $this->fetchAllSafe("SELECT * FROM bookings ORDER BY id DESC");
            $this->render('Réservations', $this->resolveView(['modules/booking-list.php']), compact('bookings'));
            return;
        }

        if ($action === 'create') {
            $booking = ['client_id' => '', 'title' => '', 'booking_date' => '', 'booking_time' => '', 'status' => 'pending', 'amount' => '', 'notes' => ''];
            $isEdit = false;
            $this->render('Ajouter une réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $booking = $this->fetchOne("SELECT * FROM bookings WHERE id = :id", ['id' => $id]);
            if (!$booking) redirectTo('/admin.php?module=booking&error=Réservation introuvable');
            $isEdit = true;
            $this->render('Modifier une réservation', $this->resolveView(['modules/booking-form.php']), compact('booking', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'client_id' => ($_POST['client_id'] ?? '') !== '' ? (int)$_POST['client_id'] : null,
                'title' => trim($_POST['title'] ?? ''),
                'booking_date' => trim($_POST['booking_date'] ?? ''),
                'booking_time' => trim($_POST['booking_time'] ?? ''),
                'status' => trim($_POST['status'] ?? 'pending'),
                'amount' => ($_POST['amount'] ?? '') !== '' ? (float)$_POST['amount'] : null,
                'notes' => trim($_POST['notes'] ?? ''),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('bookings', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('bookings', $data);
            }
            redirectTo('/admin.php?module=booking&success=Réservation enregistrée');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('bookings', $id);
            redirectTo('/admin.php?module=booking&success=Réservation supprimée');
        }

        redirectTo('/admin.php?module=booking');
    }

    private function handleSubscriptions(string $action): void
    {
        if ($action === 'index') {
            $subscriptions = $this->fetchAllSafe("SELECT * FROM subscriptions ORDER BY id DESC");
            $this->render('Abonnements', $this->resolveView(['modules/subscriptions-list.php']), compact('subscriptions'));
            return;
        }

        if ($action === 'create') {
            $subscription = ['title' => '', 'description' => '', 'price' => '', 'billing_cycle' => 'monthly', 'status' => 'active'];
            $isEdit = false;
            $this->render('Ajouter un abonnement', $this->resolveView(['modules/subscriptions-form.php']), compact('subscription', 'isEdit'));
            return;
        }

        if ($action === 'edit') {
            $id = (int)($_GET['id'] ?? 0);
            $subscription = $this->fetchOne("SELECT * FROM subscriptions WHERE id = :id", ['id' => $id]);
            if (!$subscription) redirectTo('/admin.php?module=subscriptions&error=Abonnement introuvable');
            $isEdit = true;
            $this->render('Modifier un abonnement', $this->resolveView(['modules/subscriptions-form.php']), compact('subscription', 'isEdit'));
            return;
        }

        if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => ($_POST['price'] ?? '') !== '' ? (float)$_POST['price'] : null,
                'billing_cycle' => trim($_POST['billing_cycle'] ?? 'monthly'),
                'status' => trim($_POST['status'] ?? 'active'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($id > 0) {
                $this->updateRow('subscriptions', $id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->insertRow('subscriptions', $data);
            }
            redirectTo('/admin.php?module=subscriptions&success=Abonnement enregistré');
        }

        if ($action === 'delete') {
            $id = (int)($_GET['id'] ?? 0);
            $this->deleteById('subscriptions', $id);
            redirectTo('/admin.php?module=subscriptions&success=Abonnement supprimé');
        }

        redirectTo('/admin.php?module=subscriptions');
    }


    private function syncCommonMeta(int $contentId): void
    {
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDescription = trim($_POST['meta_description'] ?? '');
        $featuredMediaId = trim((string)($_POST['featured_media_id'] ?? ''));

        if ($metaTitle !== '') {
            Content::setMeta($this->pdo, $contentId, 'meta_title', $metaTitle);
        } else {
            Content::deleteMeta($this->pdo, $contentId, 'meta_title');
        }

        if ($metaDescription !== '') {
            Content::setMeta($this->pdo, $contentId, 'meta_description', $metaDescription);
        } else {
            Content::deleteMeta($this->pdo, $contentId, 'meta_description');
        }

        if ($featuredMediaId !== '') {
            Content::setMeta($this->pdo, $contentId, 'featured_media_id', $featuredMediaId);
        } else {
            Content::deleteMeta($this->pdo, $contentId, 'featured_media_id');
        }
    }

    private function fetchBlocks(int $contentId): array
    {
        return $this->fetchAllSafe("SELECT * FROM content_blocks WHERE content_id = " . (int)$contentId . " ORDER BY sort_order ASC, id ASC");
    }

    private function moveBlock(int $contentId, int $blockId, string $direction): void
    {
        $blocks = $this->fetchBlocks($contentId);
        $index = null;

        foreach ($blocks as $i => $block) {
            if ((int)$block['id'] === $blockId) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return;
        }

        if ($direction === 'up' && $index > 0) {
            $other = $blocks[$index - 1];
            $current = $blocks[$index];
            $this->swapBlockOrder((int)$current['id'], (int)$other['id'], (int)$current['sort_order'], (int)$other['sort_order']);
        }

        if ($direction === 'down' && $index < count($blocks) - 1) {
            $other = $blocks[$index + 1];
            $current = $blocks[$index];
            $this->swapBlockOrder((int)$current['id'], (int)$other['id'], (int)$current['sort_order'], (int)$other['sort_order']);
        }
    }

    private function swapBlockOrder(int $firstId, int $secondId, int $firstOrder, int $secondOrder): void
    {
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("UPDATE content_blocks SET sort_order = :sort_order, updated_at = :updated_at WHERE id = :id");
        $stmt->execute([
            'sort_order' => $secondOrder,
            'updated_at' => $now,
            'id' => $firstId,
        ]);

        $stmt->execute([
            'sort_order' => $firstOrder,
            'updated_at' => $now,
            'id' => $secondId,
        ]);
    }

    private function extractBlockSettings(string $blockType): array
    {
        return match ($blockType) {
            'hero' => [
                'title' => trim($_POST['hero_title'] ?? ''),
                'subtitle' => trim($_POST['hero_subtitle'] ?? ''),
                'button_text' => trim($_POST['hero_button_text'] ?? ''),
                'button_url' => trim($_POST['hero_button_url'] ?? ''),
            ],
            'rich-text' => [
                'title' => trim($_POST['rich_text_title'] ?: ($_POST['hero_title'] ?? '')),
                'content' => trim($_POST['rich_text_content'] ?? ''),
            ],
            'menu' => [
                'menu_location' => trim($_POST['menu_location'] ?? 'main'),
            ],
            'cta' => [
                'title' => trim($_POST['cta_title'] ?? ''),
                'text' => trim($_POST['cta_text'] ?? ''),
                'button_text' => trim($_POST['cta_button_text'] ?? ''),
                'button_url' => trim($_POST['cta_button_url'] ?? ''),
            ],
            'posts-list' => [
                'title' => trim($_POST['posts_list_title'] ?? ''),
                'limit' => (int)($_POST['posts_list_limit'] ?? 3),
            ],
            default => [],
        };
    }

    private function render(string $pageTitle, string $viewPath, array $data = []): void
    {
        extract($data);
        $module = $this->module;
        $action = $this->action;
        $config = $this->config;
        $settings = $this->settings;

        require ADMIN_VIEWS_PATH . '/layouts/admin.php';
    }

    private function resolveView(array $candidates): string
    {
        $base = ADMIN_VIEWS_PATH . '/';

        foreach ($candidates as $candidate) {
            $path = $base . $candidate;
            if (file_exists($path)) {
                return $path;
            }
        }

        return $base . 'admin/module-placeholder.php';
    }

    private function titleFor(string $module): string
    {
        return match ($module) {
            'products' => 'Produits',
            'forms' => 'Formulaires',
            'booking' => 'Réservations',
            'clients' => 'Clients',
            'testimonials' => 'Avis',
            'gallery' => 'Galerie',
            'subscriptions' => 'Abonnements',
            default => ucfirst($module),
        };
    }

    private function tableExists(string $table): bool
    {
        $stmt = $this->pdo->prepare("SELECT name FROM sqlite_master WHERE type = 'table' AND name = :name LIMIT 1");
        $stmt->execute(['name' => $table]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function insertRow(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);

        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return (int)$this->pdo->lastInsertId();
    }

    private function updateRow(string $table, int $id, array $data): void
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = $column . ' = :' . $column;
        }

        $data['id'] = $id;

        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    private function deleteById(string $table, int $id): void
    {
        if (!$this->tableExists($table)) {
            return;
        }

        $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    private function fetchAllSafe(string $sql): array
    {
        try {
            return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function fetchOne(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function safeCount(string $table): int
    {
        if (!$this->tableExists($table)) {
            return 0;
        }

        try {
            return (int)$this->pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
