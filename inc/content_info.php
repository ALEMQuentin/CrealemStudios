<section class="section">
	<article class="article large">
		<h2>Mes Coordonnées</h2>
		<h3>Personnel</h3>
		<form action="brouillon.php" methode="GET">
		<input type="text" name="Nom" placeholder="Nom *" class="input" required>
		<input type="text" name="Prenom" placeholder="Prénom *" class="input" required>
		<input type="mail" name="mail" placeholder="E-mail *" class="input" required>
		<input type="tel" name="tel" placeholder="Téléphone" class="input">
		<hr>
		<h3>Professionnel</h3>
		<input type="text" name="job" placeholder="Job" class="input">
		</form>
	</article>
	<article class="article small">
		<h3>Publier</h3>
		<form action="inc/registred.php" methode="GET">
		<button class="btn" type="submit">Publier</button>
		</form>
	</article>
</section>
