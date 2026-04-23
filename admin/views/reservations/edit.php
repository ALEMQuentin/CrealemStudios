<h1>Modifier réservation</h1>

<form method="POST" action="/admin.php?module=reservations&action=update&id=<?= $reservation['id'] ?>">
    <input name="client_name" value="<?= $reservation['client_name'] ?>"><br>
    <input name="client_phone" value="<?= $reservation['client_phone'] ?>"><br>
    <input name="pickup_address" value="<?= $reservation['pickup_address'] ?>"><br>
    <input name="dropoff_address" value="<?= $reservation['dropoff_address'] ?>"><br>
    <input type="datetime-local" name="datetime" value="<?= $reservation['datetime'] ?>"><br>

    <select name="status">
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmé</option>
        <option value="done">Terminé</option>
    </select>

    <button type="submit">Mettre à jour</button>
</form>
