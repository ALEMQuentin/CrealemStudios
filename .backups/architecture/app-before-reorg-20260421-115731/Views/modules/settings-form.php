<div class="card">
    <div class="card-body">
        <h2 class="h5 mb-1">Paramètres</h2>
        <div class="text-muted">Configuration générale, modules et tracking</div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=settings&action=save">
            <div class="row">
                <div class="col-12">
                    <h3 class="h6 mb-3">Informations générales</h3>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nom du site</label>
                    <input type="text" class="form-control" name="site_name" value="<?= e($settings['site_name'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slogan</label>
                    <input type="text" class="form-control" name="site_tagline" value="<?= e($settings['site_tagline'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Thème actif</label>
                    <input type="text" class="form-control" name="theme" value="<?= e($settings['theme'] ?? 'default') ?>">
                </div>

                <div class="col-12 mt-4">
                    <h3 class="h6 mb-3">Modules optionnels</h3>
                </div>

                <?php
                $moduleOptions = [
                    'module_blog' => 'Blog',
                    'module_products' => 'Produits',
                    'module_forms' => 'Formulaires',
                    'module_booking' => 'Réservations',
                    'module_clients' => 'Clients',
                    'module_testimonials' => 'Avis',
                    'module_gallery' => 'Galerie',
                    'module_subscriptions' => 'Abonnements',
                ];
                ?>

                <?php foreach ($moduleOptions as $key => $label): ?>
                    <div class="col-md-3">
                        <label style="display:flex; align-items:center; gap:0.5rem;">
                            <input type="checkbox" name="<?= e($key) ?>" <?= (($settings[$key] ?? '0') === '1') ? 'checked' : '' ?>>
                            <span><?= e($label) ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>

                <div class="col-12 mt-4">
                    <h3 class="h6 mb-3">Tracking / pixels</h3>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Google Tag Manager ID</label>
                    <input type="text" class="form-control" name="tracking_gtm_id" value="<?= e($settings['tracking_gtm_id'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Meta Pixel ID</label>
                    <input type="text" class="form-control" name="tracking_meta_pixel_id" value="<?= e($settings['tracking_meta_pixel_id'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">TikTok Pixel ID</label>
                    <input type="text" class="form-control" name="tracking_tiktok_pixel_id" value="<?= e($settings['tracking_tiktok_pixel_id'] ?? '') ?>">
                </div>

                <div class="col-12 mt-4">
                    <h3 class="h6 mb-3">Code personnalisé</h3>
                </div>

                <div class="col-12">
                    <label class="form-label">Code dans le head</label>
                    <textarea class="form-control" name="tracking_head_custom" rows="5"><?= e($settings['tracking_head_custom'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Code après l’ouverture du body</label>
                    <textarea class="form-control" name="tracking_body_custom" rows="5"><?= e($settings['tracking_body_custom'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Code avant la fin de page</label>
                    <textarea class="form-control" name="tracking_footer_custom" rows="5"><?= e($settings['tracking_footer_custom'] ?? '') ?></textarea>
                </div>

                <div class="col-12 mt-4">
                    <h3 class="h6 mb-3">CSS personnalisé</h3>
                </div>

                <div class="col-12">
                    <label class="form-label">CSS global</label>
                    <textarea class="form-control" name="custom_css_global" rows="10"><?= e($settings['custom_css_global'] ?? '') ?></textarea>
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer les paramètres</button>
                </div>
            </div>
        </form>
    </div>
</div>
