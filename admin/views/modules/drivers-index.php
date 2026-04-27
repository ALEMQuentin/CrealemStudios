<h2>Chauffeurs</h2>

<a href="?module=drivers&action=create">Ajouter</a>

<table border="1" cellpadding="8">
<tr>
<th>Nom</th>
<th>Ville</th>
<th>Statut</th>
<th>Actions</th>
</tr>

<?php foreach ($drivers as $d): ?>
<tr>
<td><?= e($d['firstname'].' '.$d['lastname']) ?></td>
<td><?= e($d['city']) ?></td>
<td><?= e($d['status']) ?></td>
<td>
<a href="?module=drivers&action=edit&id=<?= $d['id'] ?>">Modifier</a>
|
<a href="?module=drivers&action=delete&id=<?= $d['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>
</table>
