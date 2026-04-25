<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesMenus
{
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

}
