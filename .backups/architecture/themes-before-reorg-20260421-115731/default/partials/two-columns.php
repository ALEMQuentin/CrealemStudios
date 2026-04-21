<div class="cs-block mt-4">
    <div class="card">
        <div class="card-body">
            <?php if (!empty($blockSettings['title'])): ?>
                <h2 class="mb-4"><?= e($blockSettings['title']) ?></h2>
            <?php endif; ?>

            <div class="cs-two-columns">
                <div class="cs-column">
                    <?= renderShortcodes($blockSettings['left_content'] ?? '') ?>
                </div>

                <div class="cs-column">
                    <?= renderShortcodes($blockSettings['right_content'] ?? '') ?>
                </div>
            </div>
        </div>
    </div>
</div>
