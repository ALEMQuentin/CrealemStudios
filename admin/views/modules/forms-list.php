<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Formulaires</h2>
            <div class="text-muted">Liste des formulaires</div>
        </div>
        <a class="btn btn-primary" href="/admin.php?module=forms&action=create">Ajouter un formulaire</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <?php if (empty($forms)): ?>
            <div class="text-muted">Aucun formulaire.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Slug</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($forms as $form): ?>
                        <tr>
                            <td><?= (int)$form['id'] ?></td>
                            <td><?= e($form['title'] ?? '') ?></td>
                            <td><code><?= e($form['slug'] ?? '') ?></code></td>
                            <td><?= e($form['status'] ?? '') ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=forms&action=edit&id=<?= (int)$form['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=forms&action=delete&id=<?= (int)$form['id'] ?>" onclick="return confirm('Supprimer ce formulaire ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
