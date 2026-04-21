<?php require $themePath . '/header.php'; ?>

<div class="card">
    <div class="card-body">
        <h1><?= e($content['title'] ?? '') ?></h1>

        <?php if (!empty($meta['meta_description'])): ?>
            <p class="text-muted mt-2"><?= e($meta['meta_description']) ?></p>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($blocks)): ?>
    <?php foreach ($blocks as $block): ?>
        <?php
        $blockType = $block['block_type'];
        $blockSettings = json_decode($block['settings_json'] ?? '{}', true) ?: [];
        $partial = $themePath . '/partials/' . $blockType . '.php';
        if (file_exists($partial)) {
            require $partial;
        }
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card mt-4">
        <div class="card-body">
            <?= renderShortcodes($content['content'] ?? '') ?>
        </div>
    </div>
<?php endif; ?>

<?php require $themePath . '/footer.php'; ?>
