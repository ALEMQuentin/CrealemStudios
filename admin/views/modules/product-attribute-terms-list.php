<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Termes de l’attribut : <?= e($attribute['name'] ?? '') ?></h2>
            <div class="text-muted">Valeurs disponibles pour cet attribut</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=attributes">Retour aux attributs</a>
            <a class="btn btn-primary" href="/admin.php?module=products&action=create_attribute_term&id=<?= (int)$attribute['id'] ?>">Ajouter un terme</a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <?php if (empty($terms)): ?>
            <div class="text-muted">Aucun terme.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th>Ordre</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($terms as $term): ?>
                        <tr>
                            <td><?= (int)$term['id'] ?></td>
                            <td><?= e($term['name'] ?? '') ?></td>
                            <td><code><?= e($term['slug'] ?? '') ?></code></td>
                            <td><?= e((string)($term['sort_order'] ?? '0')) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=products&action=edit_attribute_term&id=<?= (int)$attribute['id'] ?>&term_id=<?= (int)$term['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=products&action=delete_attribute_term&id=<?= (int)$attribute['id'] ?>&term_id=<?= (int)$term['id'] ?>" onclick="return confirm('Supprimer ce terme ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
