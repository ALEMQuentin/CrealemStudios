<div class="card mt-4">
    <div class="card-body text-center py-5">
        <?php if (!empty($blockSettings['title'])): ?>
            <h2><?= e($blockSettings['title']) ?></h2>
        <?php endif; ?>

        <?php if (!empty($blockSettings['text'])): ?>
            <p class="text-muted mt-3"><?= e($blockSettings['text']) ?></p>
        <?php endif; ?>

        <?php if (!empty($blockSettings['button_text']) && !empty($blockSettings['button_url'])): ?>
            <div class="mt-4">
                <a class="btn btn-primary" href="<?= e($blockSettings['button_url']) ?>">
                    <?= e($blockSettings['button_text']) ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
