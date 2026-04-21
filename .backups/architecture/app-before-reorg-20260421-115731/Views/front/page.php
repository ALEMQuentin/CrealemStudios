<div class="card">
    <div class="card-body">
        <h1><?= e($content['title'] ?? '') ?></h1>

        <?php if (!empty($meta['meta_description'])): ?>
            <p class="text-muted mt-2"><?= e($meta['meta_description']) ?></p>
        <?php endif; ?>

        <div class="mt-4">
            <?= renderShortcodes($content['content'] ?? '') ?>
        </div>
    </div>
</div>
