<?php
$items = [];
$rawItems = trim($blockSettings['items'] ?? '');

if ($rawItems !== '') {
    $lines = preg_split('/\r\n|\r|\n/', $rawItems);

    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line, 2));
        $items[] = [
            'question' => $parts[0] ?? '',
            'answer' => $parts[1] ?? '',
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

            <div class="cs-faq">
                <?php foreach ($items as $item): ?>
                    <div class="cs-faq-item">
                        <h3><?= e($item['question']) ?></h3>
                        <p class="text-muted mb-0"><?= e($item['answer']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
