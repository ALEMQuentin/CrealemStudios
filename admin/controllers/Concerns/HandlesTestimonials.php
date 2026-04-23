<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesTestimonials
{
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
}
