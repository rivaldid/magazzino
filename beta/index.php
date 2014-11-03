<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>GMDCTO</title>
	<link rel="stylesheet" href="css/menu.css" type="text/css" />
	<link rel="stylesheet" href="css/footer.css" type="text/css" />
	<link rel="stylesheet" href="css/main.css" type="text/css" />
	<link rel="stylesheet" href="css/tabella.css" type="text/css" />
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" />
	<script type="text/javascript" src="lib/jquery.min.js"></script>
	<script type="text/javascript" src="lib/jquery-ui.js"></script>
	<script type="text/javascript" src="lib/jquery.filtertable.js"></script>
	<script type="text/javascript" src="lib/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="lib/jquery.ui.datepicker-it.js"></script>
	<script type="text/javascript" src="lib/table2CSV.js"></script>
</head>
<body>

<?php
if ($_SERVER["QUERY_STRING"] != NULL) {
	if (!empty($_GET["page"])) $page = sprintf("page/%s.php",$_GET["page"]);
	if (!file_exists($page)) $page = sprintf("page/404.php");
} else $page = sprintf("page/home.php");

require_once 'lib/functions.php';
include 'lib/menu.html';
include $page;
include 'lib/footer.html';
?>

</body>
</html>
