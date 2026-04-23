<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesProducts
{


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
}
