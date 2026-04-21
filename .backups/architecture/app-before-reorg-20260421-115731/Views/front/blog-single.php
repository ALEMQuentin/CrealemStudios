<div class="card">
    <div class="card-body">
        <p><a href="/?blog=1">← Retour au blog</a></p>

        <h1><?= e($content['title'] ?? '') ?></h1>

        <?php if (!empty($content['excerpt'])): ?>
            <p class="text-muted mt-2"><?= e($content['excerpt']) ?></p>
        <?php endif; ?>

        <?php if (!empty($meta['meta_description'])): ?>
            <p class="text-muted"><?= e($meta['meta_description']) ?></p>
        <?php endif; ?>

        <div class="mt-4">
            <?= renderShortcodes($content['content'] ?? '') ?>
        </div>
    </div>
</div>
