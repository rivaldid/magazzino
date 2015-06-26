<?php

$a = ""; $log = "";

$log .= remesg("Accesso negato ai contenuti","deny");
$a .= "<a href=\"/magazzino\"><img border=\"0\" src=\"imgs/maga.png\" alt=\"Homepage\" /></a>\n";

echo makepage($a, $log);

?>