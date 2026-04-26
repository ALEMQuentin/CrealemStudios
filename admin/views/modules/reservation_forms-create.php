<div class="cs-admin-page-header">
    <div>
        <h1>Nouveau formulaire de réservation</h1>
        <p>Formulaire destiné au front : course distance, mise à disposition ou circuit touristique.</p>
    </div>

    <a href="/admin.php?module=reservation_forms" class="btn btn-outline-secondary">Retour</a>
</div>

<form method="post" action="/admin.php?module=reservation_forms&action=save" class="cs-admin-card" style="display:grid; gap:18px;">
    <div>
        <label class="required">Nom du formulaire</label>
        <input class="form-control" name="name" required placeholder="Ex : Transfert gare / aéroport">
    </div>

    <div>
        <label class="required">Type de formulaire</label>
        <select class="form-control" name="type" required>
            <option value="distance">Course classique à la distance</option>
            <option value="hourly">Mise à disposition horaire</option>
            <option value="circuit">Circuit touristique prédéfini</option>
        </select>
    </div>

    <div>
        <button class="btn btn-primary" type="submit">Créer le formulaire</button>
    </div>
</form>
