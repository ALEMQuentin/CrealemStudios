<div class="card">
    <div class="card-body">
        <?php if (!empty($featuredMedia['path'])): ?>
            <div class="mb-4">
                <img src="<?= e($featuredMedia['path']) ?>" alt="" class="img-fluid rounded border">
            </div>
        <?php endif; ?>

        <h2 class="h4 mb-3"><?= e($page['title']) ?></h2>

        <?php if (!empty($page['meta_title']) || !empty($page['meta_description'])): ?>
            <div class="mb-4">
                <div><strong>Meta title :</strong> <?= e($page['meta_title'] ?? '') ?></div>
                <div><strong>Meta description :</strong> <?= e($page['meta_description'] ?? '') ?></div>
            </div>
        <?php endif; ?>

        <hr>

        <div class="mt-4">
            <?= nl2br(renderShortcodes($page['content'] ?? '')) ?>
        </div>
    </div>
</div>
