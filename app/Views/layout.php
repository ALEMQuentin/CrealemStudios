<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | <?= htmlspecialchars($config['app']['name']) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><?= htmlspecialchars($config['app']['name']) ?></h1>
            <nav>
                <a href="/?page=home">Accueil</a>
                <a href="/?page=services">Services</a>
                <a href="/?page=about">À propos</a>
                <a href="/?page=contact">Contact</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php
        $viewFile = __DIR__ . '/' . $page . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            require __DIR__ . '/404.php';
        }
        ?>
    </main>
</body>
</html>
