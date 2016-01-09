	<section class="section">
		<article class="article welcome"><h2>Bienvenue <?php echo htmlentities(trim($_SESSION['login'])); ?> !</h2><br />
<a href="inc/deconnexion.php">Déconnexion</a></article>
				<article class="article" id="un">
			<h3>Listes des pages</h3>
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
			<h3>Listes des articles</h3>
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
			<h3>Quoi de neuf ?</h3>
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
			<h3>Statistiques</h3>
			<ul>
				<li>Utilisateurs en ligne</li>
				<li>Visites du jour</li>
				<li>Visites de la semaine</li>
				<li>Visites du mois</li>
			</ul>
		</article>
		<article class="article" id="quatre">
			<h3>Une idée ?</h3>
			<form action="inc/brouillon.php" method="post">
				<input type="text" placeholder="Titre" class="input">
				<textarea placeholder="Placez votre contenu ici"></textarea>
				<label for="image">Insérer votre image</label>
				<input type="file" class="input" for="image" src="image/jpg">
				<input type="submit" value="Sauvegarder" class="btn" class="input">
			</form>
		</article>
	</section>
