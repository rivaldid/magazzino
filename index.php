<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="it">

<head>
	<title>Magazzino</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="icon" href="img/favicon.png" type="image/png">
</head>

<body>

<?php
	require_once("lib/chaos.php");
?>

<div id="header">
<h1>Gestione Magazzino</h1>
</div>

<div id="menu"><ul>
<li><a href="/main.php">/</a></li>
<li><a href="/magazzino">Home</a></li>
<li><a href="?page=ammi">Amministrazione</a></li>
<li><a href="?page=maga">Magazzino</a></li>
</ul></div>

<?php
	if ($_SERVER["QUERY_STRING"] != NULL) {
		if (!empty($_GET["page"]))
			$page = $_GET["page"];
	}
	else 
		$page = "home";
	
	if (!empty($page)) {
		$parte_dx = sprintf("pages/%s_dx.php",$page);
		$parte_sx = sprintf("pages/%s_sx.php",$page);
	}
	
	if (!file_exists($parte_dx)) $parte_dx = sprintf("pages/404.php");
	if (!file_exists($parte_sx)) $parte_sx = sprintf("pages/404.php");
?>

<div id="contents">
<div class="left">
<div class="left_container">
<p class="greyboxinfo">
<?php 
	echo date('d/m/Y : H:i:s', time());
?>
</p>
</div>
<div class="left_container">
<?php
	include $parte_sx;
?>
</div>
</div>
<div class="right">
<?php
	include $parte_dx;
	include("pages/footer.html");
?>

