<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!--[if IE 7]><html lang="it" class="ie7"><![endif]-->
<!--[if IE 8]><html lang="it" class="ie8"><![endif]-->
<!--[if IE 9]><html lang="it" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><html lang="it"><![endif]-->
<!--[if !IE]><html lang="it-IT"><![endif]-->
<head>
	<title>GMDCTO - Gestione Magazzino DC-TO</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" href="css/menu.css" type="text/css" />
	<link rel="stylesheet" href="css/footer.css" type="text/css" />
	<link rel="stylesheet" href="css/main.css" type="text/css" />
	<link rel="stylesheet" href="css/tabella.css" type="text/css" />
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" />
	<link rel="stylesheet" href="css/pagination.css" type="text/css" />
	<link rel="stylesheet" href="css/scroll_top.css" type="text/css" />
	<link rel="stylesheet" href="lib/font-awesome/css/font-awesome.min.css" type="text/css" />
	<script type="text/javascript" src="lib/jquery.min.js"></script>
	<script type="text/javascript" src="lib/jquery-ui.js"></script>
	<script type="text/javascript" src="lib/jquery.filtertable.js"></script>
	<script type="text/javascript" src="lib/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="lib/jquery.ui.datepicker-it.js"></script>
	<script type="text/javascript" src="lib/jquery.scrollToTop.min.js"></script>
</head>
<body>
	<?php
	if ($_SERVER["QUERY_STRING"] != NULL) {
		if (!empty($_GET["page"])) $page = sprintf("page/%s.php",$_GET["page"]);
		if (!file_exists($page)) $page = sprintf("page/404.php");
	} else $page = sprintf("page/home.php");

	require_once 'lib/init.php';
	include 'lib/menu.php';
	echo jsxtop;

	$db = myquery::start();
	if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;
	myquery::logger($db);

	?>

	<div id="contents">

		<a href="#top" id="toTop"></a>

		<?php

		$lettura=array(NULL,"home","transiti","transiti_search","magazzino");
		//$scrittura=array("transiti_revert","magazzino_update","carico","scarico");

		$permission=myquery::permission($db)[0];

		if ($permission>0) {

			if (($permission==2) OR (($permission==1) AND in_array($_GET["page"],$lettura)))
				include $page;
			else
				include "page/access_limited.php";

		} else {

			header('Location: ' . "/");
		
		}

		?>

	</div>

	<?php include 'lib/footer.php'; ?>

</body>
</html>
