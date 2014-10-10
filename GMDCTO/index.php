<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//IT" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>GMDCTO</title>
	<link rel="stylesheet" href="css/menu.css" type="text/css" />
	<link rel="stylesheet" href="css/footer.css" type="text/css" />
	<link rel="stylesheet" href="css/home.css" type="text/css" />
	<link rel="stylesheet" href="css/tabella.css" type="text/css" />
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
