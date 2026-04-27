<section>
    <h2><?= !empty($user['id']) ? 'Modifier utilisateur' : 'Ajouter utilisateur' ?></h2>

    <form method="post" action="/admin.php?module=users&action=save">
        <input type="hidden" name="id" value="<?= e($user['id'] ?? '') ?>">

        <p>
            <label>Nom</label><br>
            <input type="text" name="name" value="<?= e($user['name'] ?? '') ?>" required>
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required>
        </p>

        <p>
            <label>Mot de passe <?= !empty($user['id']) ? '(laisser vide pour ne pas modifier)' : '' ?></label><br>
            <input type="password" name="password" <?= empty($user['id']) ? '' : '' ?>>
        </p>

        <p>
            <label>Rôle</label><br>
            <select name="role_id" required>
                <option value="">Sélectionner</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= (int)$role['id'] ?>" <?= ((int)($user['role_id'] ?? 0) === (int)$role['id']) ? 'selected' : '' ?>>
                        <?= e($role['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label>Statut</label><br>
            <select name="status">
                <option value="active" <?= (($user['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Actif</option>
                <option value="inactive" <?= (($user['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactif</option>
            </select>
        </p>

        <button type="submit">Enregistrer</button>
        <a href="/admin.php?module=users">Annuler</a>
    </form>
</section>
