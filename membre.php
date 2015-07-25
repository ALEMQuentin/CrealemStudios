<?php
session_start();
if (!isset($_SESSION['login'])) {
	header ('Location: index.php');
	exit();
}
?>

<html>
<head>
<title>Dashboard</title>
	<link rel="stylesheet" href="src/css/app.css">
	<link rel="stylesheet" href="src/assets/ionicons-1.5.2/css/ionicons.min.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
</head>

<body>
<?php 	include("inc/header.php");
		include("inc/sidebar.php");
?>

	<section class="section">
		<article class="article welcome"><h2>Bienvenue <?php echo htmlentities(trim($_SESSION['login'])); ?> !</h2><br />
<a href="deconnexion.php">Déconnexion</a></article>
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
		<article class="article" id="deux">
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
		</article>
		<article class="article" id="trois">
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
			<form action="brouillon.php" method="post">
				<input type="text" placeholder="Titre">
				<textarea placeholder="Placez votre contenu ici"></textarea>
				<label for="image">Insérer votre image</label>
				<input type="file" for="image" src="image/jpg">
				<input type="submit" value="Sauvegarder" id="btn">
			</form>
			<img src="http://lorempixel.com/300/136/business" style="border-radius:0 0 7px 7px; margin-left:3px">
		</article>
	</section>
<?php include("inc/footer.php"); ?>
</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="src/js/app.js"></script>

</html>
