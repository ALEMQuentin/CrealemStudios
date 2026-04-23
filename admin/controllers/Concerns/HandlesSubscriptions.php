<?php
declare(strict_types=1);

namespace App\Controllers\Admin\Concerns;

trait HandlesSubscriptions
{
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
}
