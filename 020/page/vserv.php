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
 *		import $_POST
 *		test1: attivita and checklist
 *			switch attivita
 *		test2: not contents
 *			select merce
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

$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());



// ******** BODYPHP ****************************************************


// import $_POST
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;


// test attivita and checklist
if (isset($_SESSION['attivita']) AND (!empty($_SESSION['check_list']))) {

	// switch attivita
	switch ($_SESSION['attivita']) {

		case "Modifica":
			vserv_magazzino_modifica();
			break;

		case "Scarica":
			vserv_magazzino_scarico();
			break;

		case "Reset":
			$log .= remesg($msg9,"msg");
			reset_sessione();
			break;

		default:
			$log .= remesg("Attivita' non pervenuta","err");

	}
}

// test not contents
if (!isset($_SESSION['contents'])) {

	// select merce
	$log .= remesg("Magazzino in visualizzazione merce","msg");
	vserv_magazzino_select();

}



// ******** FOOTPHP ****************************************************

// ritorno contenuti
$a .= $_SESSION['contents'];
$log .= $_SESSION['log'];

// chiudo risorse
unset($_SESSION['contents']);
unset($_SESSION['log']);
session_write_close();
mysql_close($conn);

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

