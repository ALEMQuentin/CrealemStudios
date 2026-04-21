<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? ($settings['site_name'] ?? 'Site')) ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <header class="container py-4">
        <div class="card">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h1 class="h3 m-0"><?= e($settings['site_name'] ?? 'CrealemStudios') ?></h1>
                    <?php if (!empty($settings['site_tagline'])): ?>
                        <div class="text-muted mt-1"><?= e($settings['site_tagline']) ?></div>
                    <?php endif; ?>
                </div>

                <nav class="d-flex gap-3 flex-wrap">
                    <a href="/?slug=home">Accueil</a>
                    <a href="/?blog=1">Blog</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <?php require $viewPath; ?>
    </main>
</body>
</html>
