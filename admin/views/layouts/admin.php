<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= e($pageTitle ?? 'Administration') ?> - CrealemStudios</title>
</head>
<body>
    <header>
        <h1>CrealemStudios</h1>
        <nav>
            <a href="/admin.php?module=dashboard">Dashboard</a>
        </nav>
    </header>

    <main>
        <?php require $viewPath; ?>
    </main>
</body>
</html>
