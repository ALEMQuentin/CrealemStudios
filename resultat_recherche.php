<?php
	//Récupérer le contenu du champ motscles, à partir du formulaire recherche.php
	$motscles=$_POST['motscles'];

	
	//Récupérer les données dans la base MySQL
	
	include("inc/connexion.php");
	
	//Requête à la base
	$films = mysql_query("SELECT  id, titre, annee FROM t_films
	WHERE titre LIKE '%$motscles%' or annee LIKE '$motscles'");
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Cinéma | Liste des films</title>

<link href="style.css" rel="stylesheet" type="text/css">

</head>

<body>
<div id="wrapper">
<?php include("inc/recherche.php");
?>
<h1>Site Cinéma</h1>
<h2>Listes des films</h2>
<?php
	//Afficher la liste des films (titre)
	while($film=mysql_fetch_array($films)) {
	
?> <strong><a href="film.php?id=<?php echo $film['id']; ?>">

<?php
	echo $film['titre'];
	
?></a></strong> (<?php echo $film ['annee']; ?>) <br />
<?php
}
?>
</div>
</body>
</html>

