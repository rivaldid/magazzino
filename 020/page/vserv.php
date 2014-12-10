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
 * ALGORITMO:
 * 
 * 	definizione variabili main
 * 	startup risorse
 * 
 * 	test1 (task)
 * 		importo $_POST
 * 		attiva $selected
 * 
 * 	test2 (stop)
 * 		attivo $reset
 * 
 * 	test3 (!$reset $selected)
 * 		test31 modifica
 * 		test32 scarica
 * 
 * 	test4 ($reset)
 * 		reset $_SESSION
 * 		disattivo $selected
 * 
 * 	test5 (!$selected)
 * 		lista magazzino
 *
 * 	ritorno contenuti
 * 	libero risorse
 * 	stampo
 * 
 */



// definizione variabili main
$a = "";
$log = "";
$selected = false;
$reset = false;



// startup risorse
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
$log .= remesg("Autenticato come ".$_SERVER["AUTHENTICATE_UID"],"msg");



// test1 (task)
if ((isset($_POST['modifica']) OR (isset($_POST['scarica']))) AND (!empty($_POST['check_list']))) {

	// importo $_POST
	foreach ($_POST['check_list'] as $i => $j) {
		
		$_SESSION['id_merce'][$j] = $_POST['id_merce'][$j];
		$_SESSION['tags'][$j] = $_POST['tags'][$j];
		$_SESSION['posizioni'][$j] = $_POST['posizioni'][$j];
		$_SESSION['tot'][$j] = $_POST['tot'][$j];
		
	}
	
	//foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
	
	// attiva $selected
	$selected = true;

// test2 (stop)
} elseif (isset($_POST['stop'])) {

	// attivo $reset
	$reset = true;

}



// test3 (!$reset $selected)
if (!$reset AND $selected) {
	
	// test31 modifica
	if (isset($_SESSION['modifica'])) 
		vserv_magazzino_modifica();		
	
	// test31 scarica
	if (isset($_SESSION['scarica'])) 
		vserv_magazzino_scarico();

}

	

// test4 ($reset)
if ($reset) {
	
	$log .= remesg($msg9,"msg");
	
	// reset $_SESSION
	$_SESSION = array();
	session_unset();
	session_destroy();

	/* generate new session id and delete old session in store */
	session_regenerate_id(true);
	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
	
	// disattivo $selected
	$selected = false;
	
}
	
	
	
// test5 (!$selected)
if (!$selected) {
	
	// lista magazzino
	$log .= remesg("Magazzino in visualizzazione merce","msg");
	vserv_magazzino_select();
	
}


// ritorno contenuti
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

