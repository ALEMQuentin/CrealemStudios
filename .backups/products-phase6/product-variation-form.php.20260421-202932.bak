<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier une variation' : 'Ajouter une variation' ?></h2>
            <div class="text-muted">Produit : <?= e($product['title'] ?? '') ?></div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=products&action=variations&id=<?= (int)$product['id'] ?>">Retour aux variations</a>
    </div>
</div>

<form method="post" action="/admin.php?module=products&action=save_variation&id=<?= (int)$product['id'] ?><?= !empty($isEdit) ? '&variation_id=' . (int)$variation['id'] : '' ?>">
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" name="sku" value="<?= e($variation['sku'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Prix normal</label>
                            <input type="number" step="0.01" class="form-control" name="regular_price" value="<?= e((string)($variation['regular_price'] ?? '')) ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Prix promo</label>
                            <input type="number" step="0.01" class="form-control" name="sale_price" value="<?= e((string)($variation['sale_price'] ?? '')) ?>">
                        </div>

                        <div class="col-md-4 mt-4">
                            <label class="form-label">Quantité</label>
                            <input type="number" class="form-control" name="stock_quantity" value="<?= e((string)($variation['stock_quantity'] ?? '')) ?>">
                        </div>

                        <div class="col-md-4 mt-4">
                            <label class="form-label">État du stock</label>
                            <select class="form-select" name="stock_status">
                                <option value="instock" <?= (($variation['stock_status'] ?? 'instock') === 'instock') ? 'selected' : '' ?>>En stock</option>
                                <option value="outofstock" <?= (($variation['stock_status'] ?? '') === 'outofstock') ? 'selected' : '' ?>>Rupture</option>
                                <option value="onbackorder" <?= (($variation['stock_status'] ?? '') === 'onbackorder') ? 'selected' : '' ?>>Commande en attente</option>
                            </select>
                        </div>

                        <div class="col-md-4 mt-4">
                            <label class="form-label">Image (ID média)</label>
                            <input type="number" class="form-control" name="image_media_id" value="<?= e((string)($variation['image_media_id'] ?? '')) ?>">
                        </div>

                        <div class="col-md-4 mt-4">
                            <label class="form-label">Ordre</label>
                            <input type="number" class="form-control" name="sort_order" value="<?= e((string)($variation['sort_order'] ?? '0')) ?>">
                        </div>

                        <div class="col-md-4 mt-4">
                            <label class="form-label">Statut</label>
                            <select class="form-select" name="status">
                                <option value="published" <?= (($variation['status'] ?? 'published') === 'published') ? 'selected' : '' ?>>Publié</option>
                                <option value="draft" <?= (($variation['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="h6 mb-3">Valeurs d’attributs</h3>

                    <?php if (empty($variationAttributes)): ?>
                        <div class="text-muted">Aucun attribut produit disponible. Ajoute d’abord des attributs sur le produit parent.</div>
                    <?php else: ?>
                        <?php foreach ($variationAttributes as $attribute): ?>
                            <?php
                            $attributeId = (int)$attribute['id'];
                            $terms = $variationTermsMap[$attributeId] ?? [];
                            $selectedTermId = $selectedVariationTerms[$attributeId] ?? '';
                            ?>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= e($attribute['name']) ?></label>
                                    <select class="form-select" name="variation_term_ids[<?= $attributeId ?>]">
                                        <option value="">Choisir une valeur</option>
                                        <?php foreach ($terms as $term): ?>
                                            <option value="<?= (int)$term['id'] ?>" <?= ((string)$selectedTermId === (string)$term['id']) ? 'selected' : '' ?>>
                                                <?= e($term['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary" type="submit">Enregistrer la variation</button>
                </div>
            </div>
        </div>
    </div>
</form>
