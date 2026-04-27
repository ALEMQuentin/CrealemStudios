<h2>Chauffeur</h2>

<form method="post" action="?module=drivers&action=save">
<input type="hidden" name="id" value="<?= e($driver['id'] ?? '') ?>">

<p>Prénom<br><input type="text" name="firstname" value="<?= e($driver['firstname']) ?>"></p>
<p>Nom<br><input type="text" name="lastname" value="<?= e($driver['lastname']) ?>"></p>
<p>Email<br><input type="email" name="email" value="<?= e($driver['email']) ?>"></p>
<p>Téléphone<br><input type="text" name="phone" value="<?= e($driver['phone']) ?>"></p>
<p>Ville<br><input type="text" name="city" value="<?= e($driver['city']) ?>"></p>

<p>Statut<br>
<select name="status">
<option value="pending" <?= $driver['status']=='pending'?'selected':'' ?>>En attente</option>
<option value="approved" <?= $driver['status']=='approved'?'selected':'' ?>>Validé</option>
<option value="rejected" <?= $driver['status']=='rejected'?'selected':'' ?>>Refusé</option>
</select>
</p>

<button type="submit">Enregistrer</button>
</form>
