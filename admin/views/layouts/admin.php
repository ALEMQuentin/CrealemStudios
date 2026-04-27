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
            <a href="/admin.php?module=users">Utilisateurs</a>
            <a href="/admin.php?module=roles">Rôles</a>
            <a href="/admin.php?module=clients">Clients</a>
        </nav>
    </header>

    <?php if (!empty($_GET['success'])): ?>
        <p style="color:green;"><?= e($_GET['success']) ?></p>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <p style="color:red;"><?= e($_GET['error']) ?></p>
    <?php endif; ?>

    <main>
        <?php require $viewPath; ?>
    </main>
</body>
</html>
