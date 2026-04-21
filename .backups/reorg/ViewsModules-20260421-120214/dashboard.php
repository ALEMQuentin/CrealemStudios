<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted mb-1">Pages</div>
                <div style="font-size: 2rem; font-weight: 700;"><?= (int)($stats['pages'] ?? 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted mb-1">Articles</div>
                <div style="font-size: 2rem; font-weight: 700;"><?= (int)($stats['posts'] ?? 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted mb-1">Médias</div>
                <div style="font-size: 2rem; font-weight: 700;"><?= (int)($stats['media'] ?? 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted mb-1">Utilisateurs</div>
                <div style="font-size: 2rem; font-weight: 700;"><?= (int)($stats['users'] ?? 0) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h2 class="h5 mb-3">Accès rapide</h2>

                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary" href="/admin.php?module=pages&action=create">Nouvelle page</a>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=blog&action=create">Nouvel article</a>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=media">Bibliothèque média</a>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=menus">Gérer les menus</a>
                    <a class="btn btn-outline-secondary" href="/admin.php?module=settings">Paramètres</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h2 class="h5 mb-3">État</h2>
                <p class="text-muted mb-0">
                    Base admin remise sur une structure simple, lisible et réutilisable pour la suite du builder et des modules.
                </p>
            </div>
        </div>
    </div>
</div>
