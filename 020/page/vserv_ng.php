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

// if not begin
if (!(isset($_SESSION['begin']))) {
	
	// test se ho input valido
	if ((isset($_POST['modifica']) OR (isset($_POST['scarica']))) AND (!empty($_POST['check_list']))) {
		
		// quindi copia l'input dal post
		foreach ($_POST['check_list'] as $i => $j) {
		
			$_SESSION['id_merce'][$j] = $_POST['id_merce'][$j];
			$_SESSION['tags'][$j] = $_POST['tags'][$j];
			$_SESSION['posizioni'][$j] = $_POST['posizioni'][$j];
			$_SESSION['tot'][$j] = $_POST['tot'][$j];
		
		}
		
		// begin true
		$_SESSION['begin'] = true;
	
	// altrimenti visualizzo merce
	} else {
		
		$log .= remesg("Magazzino in visualizzazione merce","msg");
		vserv_magazzino_select();
		
	}

// altrimenti se begin inizializzato
} else {
	
	// se ho dato stop resetto
	if (isset($_POST['stop'])) {
		
		$log .= remesg($msg9,"msg");
	
		// reset $_SESSION
		$_SESSION = array();
		session_unset();
		session_destroy();

		/* generate new session id and delete old session in store */
		session_regenerate_id(true);
		if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
		
	} else {
	
		// copio tutto il post
		foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
	
	}
	
}



// ******** FOOTPHP ****************************************************

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

