<h1>Champs du formulaire : <?= htmlspecialchars($form['name']) ?></h1>

<a href="/admin.php?module=booking_forms" class="btn btn-secondary mb-3">Retour</a>

<form method="post" action="/admin.php?module=booking_forms&action=add_field&id=<?= $form['id'] ?>">

    <div class="row">
        <div class="col-md-3">
            <input name="label" placeholder="Label" class="form-control" required>
        </div>

        <div class="col-md-3">
            <input name="name" placeholder="Nom (ex: pickup_address)" class="form-control" required>
        </div>

        <div class="col-md-3">
            <select name="type" class="form-control">
                <option value="text">Texte</option>
                <option value="datetime">Date / Heure</option>
                <option value="number">Nombre</option>
                <option value="select">Liste</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="required" class="form-control">
                <option value="0">Optionnel</option>
                <option value="1">Obligatoire</option>
            </select>
        </div>

        <div class="col-md-1">
            <button class="btn btn-success">+</button>
        </div>
    </div>

</form>

<hr>

<table class="table mt-3">
    <thead>
        <tr>
            <th>Label</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Obligatoire</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fields as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['label']) ?></td>
            <td><?= htmlspecialchars($f['name']) ?></td>
            <td><?= htmlspecialchars($f['type']) ?></td>
            <td><?= $f['required'] ? 'Oui' : 'Non' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
