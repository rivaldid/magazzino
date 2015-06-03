<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!--[if IE 7]><html lang="it" class="ie7"><![endif]-->
<!--[if IE 8]><html lang="it" class="ie8"><![endif]-->
<!--[if IE 9]><html lang="it" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><html lang="it"><![endif]-->
<!--[if !IE]><html lang="it-IT"><![endif]-->
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

	require_once 'dati/config.php';
	require_once 'lib/vars.php';
	require_once 'lib/functions.php';
	include 'lib/menu.php';
	echo jsxtop;
	
	/*if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
		echo 'We don\'t have mysqli!!!';
	} else {
		echo 'Phew we have it!';
	}*/

	// occhiomalocchio
	if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;
	
	//$logging = "CALL input_trace('{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_URI']}','{$_SERVER['HTTP_REFERER']}','{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REMOTE_USER']}','{$_SERVER['PHP_AUTH_USER']}','{$_SERVER['HTTP_USER_AGENT']}');";
	
	$mysqli = new mysqli(host,user,pass,db);
	if ($mysqli->connect_errno) include 'page/die.php';
	
	$stmt = $mysqli->prepare("CALL input_trace(?,?,?,?,?,?,?)");
	if ($stmt === FALSE) include 'page/die.php';
	
	$rc = $stmt->bind_param("sssssss",$_SERVER['REQUEST_TIME'],$_SERVER['REQUEST_URI'],$_SERVER['HTTP_REFERER'],$_SERVER['REMOTE_ADDR'],$_SERVER['REMOTE_USER'],$_SERVER['PHP_AUTH_USER'],$_SERVER['HTTP_USER_AGENT']);
	if ($rc === FALSE) include 'page/die.php';
	
	$rc = $stmt->execute();
	if ($rc === FALSE) include 'page/die.php';
	
	$stmt->close();

	?>
	<div id="contents">
		<a href="#top" id="toTop"></a>
		<?php include $page; ?>
	</div>
	<?php include 'lib/footer.html'; ?>
</body>
</html>
