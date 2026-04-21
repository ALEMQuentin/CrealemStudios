<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Galerie</h2>
        <a class="btn btn-primary" href="/admin.php?module=gallery&action=create">Ajouter un élément</a>
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
                        <th>ID média</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($galleryItems)): ?>
                    <tr><td colspan="5" class="text-muted">Aucun élément.</td></tr>
                <?php else: ?>
                    <?php foreach ($galleryItems as $item): ?>
                        <tr>
                            <td><?= (int)$item['id'] ?></td>
                            <td><?= e($item['title']) ?></td>
                            <td><?= e((string)($item['image_media_id'] ?? '')) ?></td>
                            <td><?= (int)($item['sort_order'] ?? 0) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=gallery&action=edit&id=<?= (int)$item['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=gallery&action=delete&id=<?= (int)$item['id'] ?>" onclick="return confirm('Supprimer cet élément ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
