<h1>Créer réservation</h1>

<form method="POST" action="/admin.php?module=reservations&action=store">
    <input name="client_name" placeholder="Nom"><br>
    <input name="client_phone" placeholder="Téléphone"><br>
    <input name="pickup_address" placeholder="Départ"><br>
    <input name="dropoff_address" placeholder="Arrivée"><br>
    <input type="datetime-local" name="datetime"><br>

    <button type="submit">Enregistrer</button>
</form>
