<?php

// occhiomalocchio
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());
if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;
$logging = "CALL input_trace('{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_URI']}','{$_SERVER['HTTP_REFERER']}','{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REMOTE_USER']}','{$_SERVER['PHP_AUTH_USER']}','{$_SERVER['HTTP_USER_AGENT']}');";
mysql_query($logging);
mysql_close($conn);

$a = ""; $log = "";

$log .= remesg("Pagina non trovata, errore 404","err");
$a .= remesg("<img src=\"http://10.98.2.159/GMDCTO/020/imgs/404.jpg\" alt=\"error 404\">","msg");

echo makepage($a, $log);

?>
