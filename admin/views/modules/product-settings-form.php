<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1">Paramètres du catalogue</h2>
            <div class="text-muted">Réglages globaux du module Produits</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=products">Retour aux produits</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=products&action=save_settings">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Libellé catalogue</label>
                    <input type="text" class="form-control" name="products_catalog_label" value="<?= e($settings['products_catalog_label'] ?? 'Catalogue') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Devise</label>
                    <input type="text" class="form-control" name="products_currency_symbol" value="<?= e($settings['products_currency_symbol'] ?? '€') ?>">
                </div>

                <div class="col-md-4 mt-4">
                    <label class="form-label">Gestion du stock activée</label>
                    <select class="form-select" name="products_stock_enabled">
                        <option value="1" <?= (($settings['products_stock_enabled'] ?? '1') === '1') ? 'selected' : '' ?>>Oui</option>
                        <option value="0" <?= (($settings['products_stock_enabled'] ?? '1') === '0') ? 'selected' : '' ?>>Non</option>
                    </select>
                </div>

                <div class="col-md-4 mt-4">
                    <label class="form-label">Produits par page</label>
                    <input type="number" class="form-control" name="products_per_page" value="<?= e($settings['products_per_page'] ?? '20') ?>">
                </div>

                <div class="col-md-4 mt-4">
                    <label class="form-label">Tri par défaut</label>
                    <select class="form-select" name="products_default_sort">
                        <option value="menu_order" <?= (($settings['products_default_sort'] ?? 'menu_order') === 'menu_order') ? 'selected' : '' ?>>Ordre manuel</option>
                        <option value="date_desc" <?= (($settings['products_default_sort'] ?? '') === 'date_desc') ? 'selected' : '' ?>>Date décroissante</option>
                        <option value="title_asc" <?= (($settings['products_default_sort'] ?? '') === 'title_asc') ? 'selected' : '' ?>>Titre A-Z</option>
                        <option value="price_asc" <?= (($settings['products_default_sort'] ?? '') === 'price_asc') ? 'selected' : '' ?>>Prix croissant</option>
                        <option value="price_desc" <?= (($settings['products_default_sort'] ?? '') === 'price_desc') ? 'selected' : '' ?>>Prix décroissant</option>
                    </select>
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label">Activer les SKU</label>
                    <select class="form-select" name="products_enable_sku">
                        <option value="1" <?= (($settings['products_enable_sku'] ?? '1') === '1') ? 'selected' : '' ?>>Oui</option>
                        <option value="0" <?= (($settings['products_enable_sku'] ?? '1') === '0') ? 'selected' : '' ?>>Non</option>
                    </select>
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label">Activer les dimensions / poids</label>
                    <select class="form-select" name="products_enable_dimensions">
                        <option value="1" <?= (($settings['products_enable_dimensions'] ?? '1') === '1') ? 'selected' : '' ?>>Oui</option>
                        <option value="0" <?= (($settings['products_enable_dimensions'] ?? '1') === '0') ? 'selected' : '' ?>>Non</option>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer les paramètres</button>
                </div>
            </div>
        </form>
    </div>
</div>
