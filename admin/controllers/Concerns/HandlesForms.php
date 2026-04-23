<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesForms
{
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
}
