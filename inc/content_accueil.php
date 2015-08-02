	<section class="section">
		<article class="article welcome"><h2>Bienvenue <?php echo htmlentities(trim($_SESSION['login'])); ?> !</h2><br />
<a href="inc/deconnexion.php">Déconnexion</a></article>
				<article class="article" id="un">
			<h2>Listes des pages</h2>
			<ul>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
				<li>Nom des pages du CMS !!</li>
			</ul>
			<hr/>
			<h2>Listes des articles</h2>
			<ul>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
				<li>Nom des articles du CMS !!</li>
			</ul>
		</article>
		<article class="article" id="trois">
			<h2>Quoi de neuf ?</h2>
			<ul>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
				<li>News de Crealem Studios !!</li>
			</ul>
			<hr/>
			<h2>Statistiques</h2>
			<ul>
				<li>Utilisateurs en ligne</li>
				<li>Visites du jour</li>
				<li>Visites de la semaine</li>
				<li>Visites du mois</li>
			</ul>
		</article>
		<article class="article" id="quatre">
			<h2>Une idée ?</h2>
			<form action="inc/brouillon.php" method="post">
				<input type="text" placeholder="Titre">
				<textarea placeholder="Placez votre contenu ici"></textarea>
				<label for="image">Insérer votre image</label>
				<input type="file" for="image" src="image/jpg">
				<input type="submit" value="Sauvegarder" id="btn">
			</form>
		</article>
	</section>
