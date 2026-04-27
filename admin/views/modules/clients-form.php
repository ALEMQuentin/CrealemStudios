<section>
    <h2><?= !empty($client['id']) ? 'Modifier client' : 'Ajouter client' ?></h2>

    <form method="post" action="/admin.php?module=clients&action=save">
        <input type="hidden" name="id" value="<?= e($client['id'] ?? '') ?>">

        <p>
            <label>Prénom</label><br>
            <input type="text" name="firstname" value="<?= e($client['firstname'] ?? '') ?>">
        </p>

        <p>
            <label>Nom</label><br>
            <input type="text" name="lastname" value="<?= e($client['lastname'] ?? '') ?>">
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="<?= e($client['email'] ?? '') ?>">
        </p>

        <p>
            <label>Téléphone</label><br>
            <input type="text" name="phone" value="<?= e($client['phone'] ?? '') ?>">
        </p>

        <p>
            <label>Notes</label><br>
            <textarea name="notes" rows="5"><?= e($client['notes'] ?? '') ?></textarea>
        </p>

        <button type="submit">Enregistrer</button>
        <a href="/admin.php?module=clients">Annuler</a>
    </form>
</section>
