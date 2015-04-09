<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
<head>
	<title>GMDCTO</title>
	<link rel="stylesheet" href="../020/css/menu.css" type="text/css" />
	<link rel="stylesheet" href="../020/css/footer.css" type="text/css" />
	<link rel="stylesheet" href="../020/css/main.css" type="text/css" />
	<link rel="stylesheet" href="../020/css/tabella.css" type="text/css" />
	<link rel="stylesheet" href="../020/css/jquery-ui.css" type="text/css" />
	<link rel="stylesheet" href="../020/lib/font-awesome/css/font-awesome.min.css" type="text/css" />
	<script type="text/javascript" src="../020/lib/jquery.min.js"></script>
	<script type="text/javascript" src="../020/lib/jquery-ui.js"></script>
	<script type="text/javascript" src="../020/lib/jquery.filtertable.js"></script>
	<script type="text/javascript" src="../020/lib/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="../020/lib/jquery.ui.datepicker-it.js"></script>
</head>
<body>

<?php
require_once '../020/lib/vars.php';
require_once '../020/lib/functions.php';
include '../020/lib/menu.php';
?>

<div id="contents">

<?php

logging2(occhiomalocchio("registro"),accesslog);

setlocale(LC_TIME, 'it_IT.UTF-8');

echo remesg("Lista documenti","tit");

if ($handle = opendir('.')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != ".." && $entry != "index.php") {

            echo remesg("<a href=\"".dirname($_SERVER['SCRIPT_NAME'])."/".$entry."\">".basename($entry, ".php")."</a> generato il ".strftime("%d %B %Y %H:%M:%S",filectime($entry)),"info");
        }
    }

    closedir($handle);

    if (isset($_SERVER['HTTP_REFERER'])) echo remesg("<a href=\"".$_SERVER['HTTP_REFERER']."\">Indietro</a>","action");

}
?>

</div>

<?php include '../020/lib/footer.html'; ?>

</body>
</html>
