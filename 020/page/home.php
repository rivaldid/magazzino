<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);

$a = ""; $log = "";

$a .= "<h1><i class=\"fa fa-terminal\"> Gestione Magazzino DataCenter Torino</i></h1>\n";
$a .= "<a href=\"/GMDCTO\"><img src=\"imgs/maga.png\" alt=\"Homepage\" /></a>\n";

echo makepage($a, $log);

?>

