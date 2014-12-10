<?php

/*
 * 
 * modifica di merce in magazzino, script frontend per stored procedure
 * aggiornamento_magazzino_merce(utente,
 * tags1,id1,posizione1,quantita1,
 * tags2,id2,posizione2,quantita2,
 * data);
 * 
 * 
 * _____________________________________________________________________
 * 
 * 			ALGORITMO
 * _____________________________________________________________________
 * 
 * 
 * 	[HEADPHP]
 * 		inizializzo variabili
 * 		inizializzo risorse
 *	[/HEADPHP]
 * 
 * 
 * 
 * 	[BODYPHP]
 * 	[/BODYPHP]
 *
 * 
 * 
 * 	[FOOTPHP]
 * 		ritorno contenuti
 * 		chiudo risorse
 * 		stampo
 * 	[/FOOTPHP]
 * _____________________________________________________________________
 * 
 */





// inizializzo variabili
$bypassaformselect = false;

// inizializzo risorse
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
$log .= remesg("Autenticato come ".$_SERVER["AUTHENTICATE_UID"],"msg");



// test task iniziato
if (isset($_SESSION['begin'])) {
	
	$bypassaformselect = true;
		
} else {
	
	$_SESSION['scarico']
	
}
	







// ritorno contenuti
$a .= $_SESSION['contents'];
$log .= $_SESSION['log'];

// chiudo risorse
session_write_close();

// stampo
echo "<div id='log'>\n";
echo remesg("Notifiche","tit");
if ($log == "")
	echo remesg($msg18,"msg");
else
	echo $log;
echo "</div>\n";

echo $a;




?>

