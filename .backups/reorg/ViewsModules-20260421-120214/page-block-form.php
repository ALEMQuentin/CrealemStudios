<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h5 mb-1">Configurer le bloc</h2>
            <div class="text-muted"><?= e($page['title'] ?? '') ?> • <?= e($block['block_type'] ?? '') ?></div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=pages&action=blocks&id=<?= (int)$page['id'] ?>">Retour aux blocs</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=pages&action=save_block&id=<?= (int)$page['id'] ?>&block_id=<?= (int)$block['id'] ?>">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Ordre</label>
                    <input type="number" class="form-control" name="sort_order" value="<?= (int)($block['sort_order'] ?? 0) ?>">
                </div>

                <?php if (($block['block_type'] ?? '') === 'hero'): ?>
                    <div class="col-md-10">
                        <label class="form-label">Titre principal</label>
                        <input type="text" class="form-control" name="hero_title" value="<?= e($blockSettings['title'] ?? '') ?>">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Sous-titre</label>
                        <textarea class="form-control" name="hero_subtitle" rows="4"><?= e($blockSettings['subtitle'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Texte du bouton</label>
                        <input type="text" class="form-control" name="hero_button_text" value="<?= e($blockSettings['button_text'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">URL du bouton</label>
                        <input type="text" class="form-control" name="hero_button_url" value="<?= e($blockSettings['button_url'] ?? '') ?>">
                    </div>
                <?php endif; ?>

                <?php if (($block['block_type'] ?? '') === 'rich-text'): ?>
                    <div class="col-md-12">
                        <label class="form-label">Titre du bloc</label>
                        <input type="text" class="form-control" name="rich_text_title" value="<?= e($blockSettings['title'] ?? '') ?>">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Contenu</label>
                        <textarea class="form-control" name="rich_text_content" rows="10"><?= e($blockSettings['content'] ?? '') ?></textarea>
                    </div>
                <?php endif; ?>

                <?php if (($block['block_type'] ?? '') === 'menu'): ?>
                    <div class="col-md-12">
                        <label class="form-label">Emplacement du menu</label>
                        <input type="text" class="form-control" name="menu_location" value="<?= e($blockSettings['menu_location'] ?? 'main') ?>">
                    </div>
                <?php endif; ?>

                <?php if (($block['block_type'] ?? '') === 'cta'): ?>
                    <div class="col-md-12">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="cta_title" value="<?= e($blockSettings['title'] ?? '') ?>">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Texte</label>
                        <textarea class="form-control" name="cta_text" rows="5"><?= e($blockSettings['text'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Texte du bouton</label>
                        <input type="text" class="form-control" name="cta_button_text" value="<?= e($blockSettings['button_text'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">URL du bouton</label>
                        <input type="text" class="form-control" name="cta_button_url" value="<?= e($blockSettings['button_url'] ?? '') ?>">
                    </div>
                <?php endif; ?>

                <?php if (($block['block_type'] ?? '') === 'posts-list'): ?>
                    <div class="col-md-8">
                        <label class="form-label">Titre du bloc</label>
                        <input type="text" class="form-control" name="posts_list_title" value="<?= e($blockSettings['title'] ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nombre d’articles</label>
                        <input type="number" class="form-control" name="posts_list_limit" value="<?= (int)($blockSettings['limit'] ?? 3) ?>" min="1" max="12">
                    </div>
                <?php endif; ?>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Enregistrer le bloc</button>
                </div>
            </div>
        </form>
    </div>
</div>
