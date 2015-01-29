<?php
logging2(occhiomalocchio(basename(__FILE__)),accesslog);

echo "<div id=\"log\">\n";
echo remesg("Pagina non trovata, errore 404","err");
echo remesg("<img src=\"http://10.98.2.159/GMDCTO/020/imgs/404.jpg\" alt=\"error 404\">","err");
if (isset($log)) {
	if ($log == "")
		echo remesg("Nessuna notifica da visualizzare","msg");
	else
		echo $log;
}
echo "</div>\n";

?>
