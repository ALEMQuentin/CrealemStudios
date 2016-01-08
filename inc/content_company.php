<section class="section">
	<article class="article large">
		<h2>Mon Entreprise</h2>
		<form action="brouillon.php" methode="GET">
		<input type="text" name="Nom" placeholder="Nom" class="input" required>
		<input type="mail" name="mail" placeholder="e-mail" class="input" required>
		<input type="text" name="siret" placeholder="N°Siret" class="input">
    	<textarea name="description" placeholder="Une Courte description"></textarea>
		<hr>
		<h2>Référencement</h2>
		<input type="text" name="motcles" placeholder="Mots clés" class="input">
		</form>
	</article>
	<article class="article small">
		<h2>Publier</h2>
		<form action="inc/registred.php" methode="GET">
		<button class="btn" type="submit">Publier</button>
		</form>
	</article>
</section>
