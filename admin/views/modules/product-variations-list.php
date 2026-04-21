<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Variations produit : <?= e($product['title'] ?? '') ?></h2>
            <div class="text-muted">Déclinaisons du produit variable</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=edit&id=<?= (int)$product['id'] ?>">Retour au produit</a>
            <a class="btn btn-primary" href="/admin.php?module=products&action=create_variation&id=<?= (int)$product['id'] ?>">Ajouter une variation</a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <?php if (empty($variations)): ?>
            <div class="text-muted">Aucune variation.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SKU</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Valeurs d’attributs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($variations as $variation): ?>
                        <tr>
                            <td><?= (int)$variation['id'] ?></td>
                            <td><code><?= e($variation['sku'] ?? '') ?></code></td>
                            <td>
                                <?php if (($variation['sale_price'] ?? '') !== null && $variation['sale_price'] !== ''): ?>
                                    <strong><?= e((string)$variation['sale_price']) ?></strong>
                                    <br><span class="text-muted" style="text-decoration:line-through;"><?= e((string)($variation['regular_price'] ?? '')) ?></span>
                                <?php else: ?>
                                    <?= e((string)($variation['regular_price'] ?? '')) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= e($variation['stock_status'] ?? 'instock') ?>
                                <br><span class="text-muted">Qté: <?= e((string)($variation['stock_quantity'] ?? '')) ?></span>
                            </td>
                            <td>
                                <?= e($variation['attributes_summary'] ?? '') ?>
                            </td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=products&action=edit_variation&id=<?= (int)$product['id'] ?>&variation_id=<?= (int)$variation['id'] ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=products&action=delete_variation&id=<?= (int)$product['id'] ?>&variation_id=<?= (int)$variation['id'] ?>" onclick="return confirm('Supprimer cette variation ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
