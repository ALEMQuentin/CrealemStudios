<?php
// on teste si le visiteur a soumis le formulaire de connexion
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
	if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {

	$base = mysql_connect ('localhost', 'root', 'root');
	mysql_select_db ('db_crealemstudios', $base);

	// on teste si une entrée de la base contient ce couple login / pass
	$sql = 'SELECT * FROM t_membre WHERE login="'.mysql_escape_string($_POST['login']).'" AND pass_md5="'.mysql_escape_string(md5($_POST['pass'])).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$data = mysql_fetch_array($req);

	mysql_free_result($req);
	mysql_close();

	// si on obtient une réponse, alors l'utilisateur est un membre
	if ($data[0] == 1) {
		session_start();
		$_SESSION['login'] = $_POST['login'];
		header('Location: membre.php');
		exit();
	}
	// si on ne trouve aucune réponse, le visiteur s'est trompé soit dans son login, soit dans son mot de passe
	elseif ($data[0] == 0) {
		$erreur = 'Compte non reconnu.';
	}
	// sinon, alors la, il y a un gros problème :)
	else {
		$erreur = 'Probème dans la base de données : plusieurs membres ont les mêmes identifiants de connexion.';
	}
	}
	else {
	$erreur = 'Au moins un des champs est vide.';
	}
}
?>
<html>
<head>
	<title>Accueil</title>
	<link rel="stylesheet" href="src/css/app.css">
	<link rel="stylesheet" href="src/assets/ionicons-1.5.2/css/ionicons.min.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
</head>

<body>

<?php
include("inc/header.php");

include("inc/sidebar.php");
?>
<section class="section">
<article id="un" class="article">
<h2>Connexion</h2><br />
<form action="index.php" method="post">
<input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>" placeholder="Login"><br />
<input type="password" name="pass" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>" placeholder="mot de passe"><br />
<button id="btn" type="submit" name="connexion" value="Connexion">Connexion</button></form>
<?php
if (isset($erreur)) echo '<br /><br />',$erreur;
?>
</article>
<article id="quatre" class="article">
<?php
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['inscription']) && $_POST['inscription'] == 'Inscription') {
	// on teste l'existence de nos variables. On teste également si elles ne sont pas vides
	if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm']))) {
	// on teste les deux mots de passe
	if ($_POST['pass'] != $_POST['pass_confirm']) {
		$erreur = 'Les 2 mots de passe sont différents.';
	}
	else {
		$base = mysql_connect ('localhost', 'root', 'root');
		mysql_select_db ('db_crealemstudios', $base);

		// on recherche si ce login est déjà utilisé par un autre membre
		$sql = 'SELECT count(*) FROM t_membre WHERE login="'.mysql_escape_string($_POST['login']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$data = mysql_fetch_array($req);

		if ($data[0] == 0) {
		$sql = 'INSERT INTO t_membre VALUES("", "'.mysql_escape_string($_POST['login']).'", "'.mysql_escape_string(md5($_POST['pass'])).'")';
		mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());

		session_start();
		$_SESSION['login'] = $_POST['login'];
		header('Location: membre.php');
		exit();
		}
		else {
		$erreur = 'Un membre possède déjà ce login.';
		}
	}
	}
	else {
	$erreur = 'Au moins un des champs est vide.';
	}
}
?>
<h2>Inscription</h2><br />
<form action="inscription.php" method="post">
<input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>" placeholder="login"><br />
<input type="password" name="pass" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>" placeholder="mot de passe"><br />
<input type="password" name="pass_confirm" value="<?php if (isset($_POST['pass_confirm'])) echo htmlentities(trim($_POST['pass_confirm'])); ?>" placeholder="confirmé votre mot de passe"><br />
<button id="btn" type="submit" name="inscription" value="Inscription">Inscription</button>
</form>
<?php
if (isset($erreur)) echo '<br />',$erreur;
?>

</article>
</section>

<?php
include("inc/footer.php");
?>

</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="src/js/app.js"></script>

</html>
