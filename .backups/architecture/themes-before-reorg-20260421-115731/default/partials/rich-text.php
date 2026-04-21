<div class="card mt-4">
    <div class="card-body">
        <?php if (!empty($blockSettings['title'])): ?>
            <h2><?= e($blockSettings['title']) ?></h2>
        <?php endif; ?>

        <div class="mt-3">
            <?= renderShortcodes($blockSettings['content'] ?? '') ?>
        </div>
    </div>
</div>
