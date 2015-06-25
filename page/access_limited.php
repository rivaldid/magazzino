<?php

$a = ""; $log = "";

$log .= remesg("Accesso limitato ai contenuti","warn");
$a .= "<a href=\"/magazzino\"><img border=\"0\" src=\"imgs/maga.png\" alt=\"Homepage\" /></a>\n";

echo makepage($a, $log);

?>