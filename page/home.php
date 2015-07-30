<?php

//logging2(occhiomalocchio(basename(__FILE__)),accesslog);

$a = ""; $log = "";

if (isset($_GET['path'])) {

	$a .= list_directory($_GET['path']);

} else {
	$log .= remesg("In questa applicazione sono previste due modalita' di accesso: in <u>LETTURA</u><mark>*</mark> ed in <u>SCRITTURA</u>","info");
	$log .= remesg("Le pagine in acquisizione dati sono provviste di due modalita': <u>MANUALE</u><mark>**</mark> e <u>GUIDATA</u>","info");
	$log .= remesg("<mark>*</mark>L'accesso in <u>LETTURA</u> e' ristretto alle pagine che non agiscono attivamente sui dati","info");
	$log .= remesg("<mark>**</mark>La modalita' di acquisizione <u>MANUALE</u> ha priorita' su quella <u>GUIDATA</u>","info");
	$a .= "<a href=\"?page=home&path=dati\"><img border=\"0\" src=\"imgs/maga.png\" alt=\"Homepage\" /></a>\n";
}

echo makepage($a, $log);

?>

