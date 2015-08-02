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
<?php
		include("inc/header.php");
		include("inc/sidebar.php");
		include("inc/content_accueil.php");
		include("inc/footer.php");
?>
</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="src/js/app.js"></script>

</html>
