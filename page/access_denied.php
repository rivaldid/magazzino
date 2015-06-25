<?php

$a = ""; $log = "";

$log .= remesg("Accesso negato ai contenuti","err");
$a .= "<a href=\"/magazzino\"><img border=\"0\" src=\"imgs/maga.png\" alt=\"Homepage\" /></a>\n";

echo makepage($a, $log);

?>