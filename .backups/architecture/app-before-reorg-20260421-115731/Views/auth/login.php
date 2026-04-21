<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | <?= e($config['app']['name']) ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow-sm" style="width: 100%; max-width: 380px;">
        <div class="card-body p-4">
            <h1 class="h4 text-center mb-4">Connexion</h1>

            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger"><?= e($_GET['error']) ?></div>
            <?php endif; ?>

            <form method="post" action="/?module=auth&action=login">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-dark w-100">Se connecter</button>
            </form>
        </div>
    </div>

</body>
</html>
