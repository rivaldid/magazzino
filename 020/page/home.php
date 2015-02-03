<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);

$a = ""; $log = "";

$a .= "<h1>Gestione Magazzino DataCenter Torino</h1>\n";
$a .= "<a href=\"/GMDCTO\"><img src=\"imgs/maga.png\" alt=\"Homepage\" /></a>\n";

echo makepage($a, $log);

?>

