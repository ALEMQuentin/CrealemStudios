<?php
$limit = max(1, (int)($blockSettings['limit'] ?? 3));
$postsList = \App\Models\Content::allByType($pdo, 'post', 'published');
$postsList = array_slice($postsList, 0, $limit);
?>
<div class="card mt-4">
    <div class="card-body">
        <?php if (!empty($blockSettings['title'])): ?>
            <h2><?= e($blockSettings['title']) ?></h2>
        <?php endif; ?>

        <?php if (empty($postsList)): ?>
            <p class="text-muted mt-3">Aucun article publié.</p>
        <?php else: ?>
            <div class="mt-4">
                <?php foreach ($postsList as $postItem): ?>
                    <article class="mb-4 pb-4 border-bottom">
                        <h3 class="h5 mb-2">
                            <a href="/?post=<?= e($postItem['slug']) ?>"><?= e($postItem['title']) ?></a>
                        </h3>

                        <?php if (!empty($postItem['excerpt'])): ?>
                            <p class="text-muted mb-2"><?= e($postItem['excerpt']) ?></p>
                        <?php endif; ?>

                        <a href="/?post=<?= e($postItem['slug']) ?>">Lire l’article</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
