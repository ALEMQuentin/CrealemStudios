<?php require $themePath . '/header.php'; ?>

<div class="card">
    <div class="card-body">
        <h1>Blog</h1>

        <?php if (empty($posts)): ?>
            <p class="text-muted mt-3">Aucun article publié.</p>
        <?php else: ?>
            <div class="mt-4">
                <?php foreach ($posts as $post): ?>
                    <article class="mb-4 pb-4 border-bottom">
                        <h2 class="h4 mb-2">
                            <a href="/?post=<?= e($post['slug']) ?>"><?= e($post['title']) ?></a>
                        </h2>

                        <?php if (!empty($post['excerpt'])): ?>
                            <p class="text-muted mb-2"><?= e($post['excerpt']) ?></p>
                        <?php endif; ?>

                        <a href="/?post=<?= e($post['slug']) ?>">Lire l’article</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require $themePath . '/footer.php'; ?>
