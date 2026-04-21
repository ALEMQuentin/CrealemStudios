<div class="cs-block cs-hero mt-4">
    <div class="card">
        <div class="card-body text-center py-5">
            <?php if (!empty($blockSettings['eyebrow'])): ?>
                <div class="cs-eyebrow mb-3"><?= e($blockSettings['eyebrow']) ?></div>
            <?php endif; ?>

            <?php if (!empty($blockSettings['title'])): ?>
                <h2 class="cs-hero-title"><?= e($blockSettings['title']) ?></h2>
            <?php endif; ?>

            <?php if (!empty($blockSettings['subtitle'])): ?>
                <p class="cs-hero-subtitle mt-3"><?= e($blockSettings['subtitle']) ?></p>
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
</div>
