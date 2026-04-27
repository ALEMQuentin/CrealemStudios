<section>
    <h2><?= !empty($role['id']) ? 'Modifier rôle' : 'Ajouter rôle' ?></h2>

    <form method="post" action="/admin.php?module=roles&action=save">
        <input type="hidden" name="id" value="<?= e($role['id'] ?? '') ?>">

        <p>
            <label>Nom</label><br>
            <input type="text" name="name" value="<?= e($role['name'] ?? '') ?>" required>
        </p>

        <p>
            <label>Identifiant technique</label><br>
            <input type="text" name="slug" value="<?= e($role['slug'] ?? '') ?>" required>
        </p>

        <p>
            <label>Description</label><br>
            <textarea name="description" rows="4"><?= e($role['description'] ?? '') ?></textarea>
        </p>

        <p>
            <label>Permissions JSON</label><br>
            <textarea name="permissions" rows="8"><?= e($role['permissions'] ?? '[]') ?></textarea>
        </p>

        <button type="submit">Enregistrer</button>
        <a href="/admin.php?module=roles">Annuler</a>
    </form>
</section>
