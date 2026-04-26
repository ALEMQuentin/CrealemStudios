<div class="cs-admin-page-header">
    <div>
        <h1>Configuration du formulaire</h1>
        <p><?= htmlspecialchars((string)($form['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <a href="/admin.php?module=reservation_forms" class="btn btn-outline-secondary">Retour</a>
</div>

<div class="cs-admin-card" style="margin-bottom:18px;">
    <h2>Ajouter un champ</h2>

    <form method="post" action="/admin.php?module=reservation_forms&action=add_field&id=<?= (int)$form['id'] ?>" style="display:grid; gap:16px;">
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px;">
            <div>
                <label class="required">Label</label>
                <input class="form-control" name="label" required placeholder="Adresse de départ">
            </div>

            <div>
                <label class="required">Nom technique</label>
                <input class="form-control" name="name" required placeholder="pickup_address">
            </div>

            <div>
                <label>Type</label>
                <select class="form-control" name="type">
                    <option value="text">Texte</option>
                    <option value="email">Email</option>
                    <option value="tel">Téléphone</option>
                    <option value="datetime">Date / heure</option>
                    <option value="number">Nombre</option>
                    <option value="select">Liste</option>
                    <option value="textarea">Zone de texte</option>
                </select>
            </div>

            <div>
                <label>Obligatoire</label>
                <select class="form-control" name="required">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Ajouter le champ</button>
    </form>
</div>

<div class="cs-admin-card">
    <h2>Champs configurés</h2>

    <table class="cs-admin-table" style="width:100%;">
        <thead>
            <tr>
                <th>Label</th>
                <th>Nom technique</th>
                <th>Type</th>
                <th>Obligatoire</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($fields)): ?>
                <tr><td colspan="4">Aucun champ ajouté.</td></tr>
            <?php endif; ?>

            <?php foreach ($fields as $f): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$f['label'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string)$f['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string)$f['type'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= !empty($f['required']) ? 'Oui' : 'Non' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
