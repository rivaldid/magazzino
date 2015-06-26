<?php

//logging2(occhiomalocchio(basename(__FILE__)),accesslog);

$a = ""; $log = "";

$log .= remesg("In questa applicazione sono previste due modalita' di accesso: in <mark>LETTURA</mark>* ed in <mark>SCRITTURA</mark>","info");
$log .= remesg("Le pagine in acquisizione dati sono provviste di due modalita': <mark>MANUALE</mark>* e <mark>GUIDATA</mark>","info");
$log .= remesg("*L'accesso in <mark>LETTURA</mark> e' privato delle pagine che agiscono attivamente sul database","info");
$log .= remesg("*La modalita' di acquisizione <mark>MANUALE</mark> ha priorita' su quella <mark>GUIDATA</mark>","info");

$a .= "<a href=\"/magazzino\"><img border=\"0\" src=\"imgs/maga.png\" alt=\"Homepage\" /></a>\n";

echo makepage($a, $log);

?>

