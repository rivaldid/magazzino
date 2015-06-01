<?php

$a = ""; $log = "";

$log .= remesg("Pagina non trovata, errore 404","err");
$a .= remesg("<img src=\"http://10.98.2.159/GMDCTO/020/imgs/404.jpg\" alt=\"error 404\">","msg");

echo makepage($a, $log);

?>
