<?php

/*
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
 * 	routing
 * 	libero risorse
 * 	stampo
 * 
 */



// definizione variabili
$a = "";
$log = "";
$utente = $_SERVER["AUTHENTICATE_UID"];


// startup risorse
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
$log .= remesg("Autenticato come ".$utente,"msg");


// test stop
if (isset($_POST['stop'])) {
	
	$log .= remesg($msg9,"msg");
	$_SESSION = array();
	session_unset();
	session_destroy();

	/* generate new session id and delete old session in store */
	session_regenerate_id(true);
	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
}


// routing
if ((isset($_POST['modifica']) OR (isset($_POST['scarica']))) AND (!empty($_POST['check_list']))) {
	
	foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
	
	// valorizzo $array_merce da $_SESSION['posizioni']
	$j=0;
	foreach ($_SESSION['check_list'] as $i) {
		
		$temp_merce = explode(",",$_SESSION['posizioni'][$i]);
		
		foreach ($temp_merce AS $items) {
			
			$items = explode("(",$items);

			$_SESSION[$j]['id_merce'] = $_SESSION['id_merce'][$i];
			$_SESSION[$j]['posizione'] = $items[0];
			$_SESSION[$j]['quantita'] = rtrim($items[1],")");
			$_SESSION[$j]['tot'] = $_SESSION['tot'][$i];
			
			$j++;
		}
	}
	
	// $_SESSION[indice] ([id_merce], [posizione], [quantita], [tot]);
	
	if (isset($_POST['modifica'])) {
		
		$_SESSION = vserv_magazzino_modifica($utente, $_SESSION);
		
	}
		
		
	if (isset($_POST['scarica'])) {
		
		$_SESSION = vserv_magazzino_scarico($utente, $_SESSION);
		
	}
		

// else routing
} else {

	$_SESSION = vserv_magazzino_select($utente);

}


// libero risorse
session_write_close();


// stampo
$a .= $_SESSION['contents'];
$log .= $_SESSION['log'];

echo "<div id='log'>\n";
echo remesg("Notifiche","tit");
if ($log == "")
	echo remesg($msg18,"msg");
else
	echo $log;
echo "</div>\n";

echo $a;


?>

