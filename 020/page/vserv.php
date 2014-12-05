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
 * 	definizione variabili locali
 * 	startup risorse
 * 	importo da $_POST
 * 	test azione
 * 		modifica
 * 		scarica
 * 		reset
 * 	test $reset
 * 	test $selected
 * 	libero risorse
 * 	stampo
 * 
 */



// definizione variabili locali
$a = "";
$log = "";
$selected = false;
$reset = false;



// startup risorse
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
$log .= remesg("Autenticato come ".$_SERVER["AUTHENTICATE_UID"],"msg");



// test e importo $_POST
if ((isset($_POST['modifica']) OR (isset($_POST['scarica']))) AND (!empty($_POST['check_list']))) {

	foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
	$selected = true;

// altrimenti test stop
} elseif (isset($_POST['stop'])) {

		$reset = true;

}



// test $selected
if (!$reset AND $selected) {
	
	if (isset($_SESSION['modifica'])) vserv_magazzino_modifica();		
	if (isset($_SESSION['scarica'])) vserv_magazzino_scarico();

}

	

// test $reset
if ($reset) {
	
	$log .= remesg($msg9,"msg");
	
	// reset $_SESSION
	$_SESSION = array();
	session_unset();
	session_destroy();

	/* generate new session id and delete old session in store */
	session_regenerate_id(true);
	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
	
	$selected = false;
	
}
	
	
// test $selected
if (!$selected) {
	
	$log .= remesg("Magazzino in visualizzazione merce","msg");
	vserv_magazzino_select();
	
}


// ritorno i dati
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

