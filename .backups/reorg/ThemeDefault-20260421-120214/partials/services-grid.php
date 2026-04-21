<?php
$items = [];
$rawItems = trim($blockSettings['items'] ?? '');

if ($rawItems !== '') {
    $lines = preg_split('/\r\n|\r|\n/', $rawItems);

    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line));
        $items[] = [
            'title' => $parts[0] ?? '',
            'text' => $parts[1] ?? '',
            'button_text' => $parts[2] ?? '',
            'button_url' => $parts[3] ?? '',
        ];
    }
}
?>
<div class="cs-block mt-4">
    <div class="card">
        <div class="card-body">
            <?php if (!empty($blockSettings['title'])): ?>
                <h2 class="mb-4"><?= e($blockSettings['title']) ?></h2>
            <?php endif; ?>

            <div class="cs-grid">
                <?php foreach ($items as $item): ?>
                    <div class="cs-grid-card">
                        <?php if (!empty($item['title'])): ?>
                            <h3><?= e($item['title']) ?></h3>
                        <?php endif; ?>

                        <?php if (!empty($item['text'])): ?>
                            <p class="text-muted"><?= e($item['text']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($item['button_text']) && !empty($item['button_url'])): ?>
                            <a class="btn btn-outline-secondary btn-sm mt-2" href="<?= e($item['button_url']) ?>">
                                <?= e($item['button_text']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
