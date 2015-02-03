<?php
logging2(occhiomalocchio(basename(__FILE__)),accesslog);

$a = ""; $log = "";

$a .= remesg("Pagina non trovata, errore 404","err");
$a .= remesg("<img src=\"http://10.98.2.159/GMDCTO/020/imgs/404.jpg\" alt=\"error 404\">","err");

echo makepage($a, $log);

?>
