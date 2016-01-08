<section class="section">
	<article class="article large">
		<h2>Personnel</h2>
		<form action="brouillon.php" methode="GET">
		<input type="text" name="Nom" placeholder="Nom *" class="input" required>
		<input type="text" name="Prenom" placeholder="Prénom *" class="input" required>
		<input type="mail" name="mail" placeholder="e-mail *" class="input" required>
		<input type="tel" name="tel" placeholder="Téléphone" class="input">
		<hr>
		<h2>Professionnel</h2>
		<input type="text" name="job" placeholder="Job" class="input">
		</form>
	</article>
	<article class="article small">
		<h2>Publier</h2>
		<form action="inc/registred.php" methode="GET">
		<button class="btn" type="submit">Publier</button>
		</form>
	</article>
</section>
