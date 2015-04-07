<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
<head>
	<title>GMDCTO - Gestione Magazzino DC-TO</title>
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

	require_once 'lib/vars.php';
	require_once 'lib/functions.php';
	include 'lib/menu.php';
	echo jsxtop;
	
	?>
	<div id="contents">
		<!-- bottone torna su -->
		<a href="#top" id="toTop"></a>
		<?php include $page; ?>
	</div>
	<?php include 'lib/footer.html'; ?>
</body>
</html>
