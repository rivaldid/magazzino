<?php
logging2(occhiomalocchio(basename(__FILE__)),accesslog);
echo "<div id=\"log\">\n";
echo remesg("Notifiche","tit");
echo remesg("Autenticato come ".$_SERVER["AUTHENTICATE_UID"]." alle ".date('H:i')." del ".date('d/m/Y'),"msg");
if (isset($log)) {
	if ($log == "")
		echo remesg($msg18,"msg");
	else
		echo $log;
}
echo "</div>\n";
?>
<h1>Gestione Magazzino DataCenter Torino</h1>
<img src="imgs/maga.jpg" alt="Homepage" height="600" width="800" />
