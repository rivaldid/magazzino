<?php

$log = remesg("Errore nel collegamento al db ".$mysqli->connect_error,"err");
$a = remesg("<img src=\"imgs/db_error.jpg\" alt=\"db error\">","msg");

echo makepage($a, $log);
exit();

?>
