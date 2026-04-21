<?php
$resolvedMenuItems = $menuItems ?? [];

if (!empty($blockSettings['menu_location'] ?? '')) {
    $resolvedMenuItems = \App\Core\ThemeRenderer::getMenuItems($pdo, $blockSettings['menu_location']);
}
?>
<nav class="d-flex gap-3 flex-wrap">
    <?php if (empty($resolvedMenuItems)): ?>
        <a href="/?slug=home">Accueil</a>
        <a href="/?blog=1">Blog</a>
    <?php else: ?>
        <?php foreach ($resolvedMenuItems as $item): ?>
            <?php
            $href = trim((string)($item['url'] ?? ''));
            if ($href === '') {
                $href = '#';
            }
            ?>
            <a href="<?= e($href) ?>"><?= e($item['label'] ?? 'Lien') ?></a>
        <?php endforeach; ?>
    <?php endif; ?>
</nav>
