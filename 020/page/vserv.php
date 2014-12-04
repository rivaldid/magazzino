<?php

/*
 * ---------------------------------------------------------------------
 * steps vserv:
 * 	step1: selezionare da un elenco
 * 	step2: terminare l'attività iniziata
 * 	step3: reset $_SESSION
 * ---------------------------------------------------------------------
 * 
 * modifica di merce in magazzino, script frontend per stored procedure
 * aggiornamento_magazzino_merce(utente,
 * tags1,id1,posizione1,quantita1,
 * tags2,id2,posizione2,quantita2,
 * data);
 * 
 * 
 * ALGORITMO:
 * 
 * 	definizione variabili
 * 	alloco risorse
 * 	test stop
 * 		fill $_SESSION
 * 		vserv switching
 * 	test contents
 * 		vserv select
 * 	libero risorse
 * 	stampo
 * 
 */



// definizione variabili
// variabili locali per contenuti e log
$a = "";
$log = "";


// startup risorse
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
$_SESSION['utente'] = $_SERVER["AUTHENTICATE_UID"];
$log .= remesg("Autenticato come ".$_SESSION['utente'],"msg");


// test step
if (!isset($_SESSION['step'])) {
	
	$_SESSION['step'] = '1';
	
}


// test attivita'
if (isset($_POST['modifica']) OR (isset($_POST['scarica']))) {
	
	$_SESSION['step'] = '2';

}


// test stop
if (isset($_POST['stop'])) {

	$_SESSION['step'] = '3';

}


// fill $_SESSION
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;


// vserv switching
if ($_SESSION['step'] == '1') {
	
	$log .= remesg("Magazzino in visualizzazione merce","msg");
	vserv_magazzino_select();
	
} elseif ($_SESSION == '2') {
	
	if (isset($_SESSION['modifica'])) {
		$log .= remesg("Modifica merce","msg");
		vserv_magazzino_modifica();
	}		
	if (isset($_SESSION['scarica'])) {
		$log .= remesg("Scarica merce","msg");
		vserv_magazzino_scarico();	
	}
	
}


if ($_SESSION['step'] == '3') {
	
	$log .= remesg($msg9,"msg");
	
	// reset $_SESSION
	$_SESSION = array();
	session_unset();
	session_destroy();

	/* generate new session id and delete old session in store */
	session_regenerate_id(true);
	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
	
}

	
$a .= $_SESSION['contents'];
$log .= $_SESSION['log'];

// libero risorse
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

