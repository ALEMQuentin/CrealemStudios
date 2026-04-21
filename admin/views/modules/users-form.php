<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="h5 mb-1"><?= !empty($isEdit) ? 'Modifier un utilisateur' : 'Ajouter un utilisateur' ?></h2>
            <div class="text-muted">Gestion des accès au back-office</div>
        </div>
        <a class="btn btn-outline-secondary" href="/admin.php?module=users">Retour aux utilisateurs</a>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="post" action="/admin.php?module=users&action=save<?= !empty($isEdit) ? '&id=' . (int)$user['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" name="name" value="<?= e($user['name'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= e($user['email'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rôle</label>
                    <select class="form-select" name="role">
                        <option value="admin" <?= (($user['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Administrateur</option>
                        <option value="editor" <?= (($user['role'] ?? '') === 'editor') ? 'selected' : '' ?>>Éditeur</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><?= !empty($isEdit) ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe' ?></label>
                    <input type="password" class="form-control" name="password" <?= empty($isEdit) ? 'required' : '' ?>>
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-primary" type="submit">Enregistrer l’utilisateur</button>
                </div>
            </div>
        </form>
    </div>
</div>
