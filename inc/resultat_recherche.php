<?php
	//Récupérer le contenu du champ motscles, à partir du formulaire recherche.php
	$motscles=$_POST['motscles'];

	
	//Récupérer les données dans la base MySQL
	
	include("inc/connexion.php");
	
	//Requête à la base
	$films = mysql_query("SELECT  id, titre, annee FROM t_films
	WHERE titre LIKE '%$motscles%' or annee LIKE '$motscles'");
?>
