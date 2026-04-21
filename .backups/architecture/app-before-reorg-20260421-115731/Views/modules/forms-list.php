<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Formulaires</h2>
        <a class="btn btn-primary" href="/admin.php?module=forms&action=create">Ajouter un formulaire</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                <?php if (empty($forms)): ?>
                    <tr><td colspan="5" class="text-muted">Aucun formulaire.</td></tr>
                <?php else: ?>
                    <?php foreach ($forms as $form): ?>
                        <tr>
                            <td><?= (int)$form['id'] ?></td>
                            <td><?= e($form['title']) ?></td>
                            <td><code><?= e($form['slug']) ?></code></td>
                            <td><?= e($form['status']) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=forms&action=edit&id=<?= (int)$form['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=forms&action=delete&id=<?= (int)$form['id'] ?>" onclick="return confirm('Supprimer ce formulaire ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
