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
 * 	[BODYPHP]
 * 	[/BODYPHP]
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




// ******** HEADPHP ****************************************************

// inizializzo variabili
$a = "";
$log = "";

// inizializzo risorse
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
$log .= remesg("Autenticato come ".$_SERVER["AUTHENTICATE_UID"],"msg");



// ******** BODYPHP ****************************************************



foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;



if (!isset($_SESSION['attivita']) OR (empty($_SESSION['check_list']))) {
	
	$log .= remesg("Magazzino in visualizzazione merce","msg");
	vserv_magazzino_select();
	
} 


if (isset($_SESSION['attivita'])) {
	
	switch ($_SESSION['attivita']) {
	
		case "Modifica":
			vserv_magazzino_modifica();
			break;
		
		case "Scarica":
			vserv_magazzino_scarico();
			break;
		
		case "Reset":
			$log .= remesg($msg9,"msg");
			if (reset_sessione()) vserv_magazzino_select();
			break;
		
		default:
			$log .= remesg("Attivita' non pervenuta","err");
		
	}
}



// ******** FOOTPHP ****************************************************

// ritorno contenuti
$a .= $_SESSION['contents'];
$log .= $_SESSION['log'];

// chiudo risorse
unset($_SESSION['contents']);
unset($_SESSION['log']);
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

