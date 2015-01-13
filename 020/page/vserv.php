<?php

logging2("UID: ".$_SERVER["AUTHENTICATE_UID"]." @ ".date('Y/m/d H:i:s')." on ".basename(__FILE__),accesslog);

/*
 * 
 * ALGORITMO:
 * 
 * nella porzione di codice chiamata headphp
 * 	inizializzo risorse e variabili
 * 
 * nella porzione di codice chiamata footphp
 * 	ritorno termino e impagino i contenuti
 * 
 * nella porzione centrale chiamata bodyphp
 * 	ho due test
 * 	un primo test serve a capire se ho del lavoro da svolgere
 * 	quindi instrado il flusso verso la funzione indicata
 * 	nel secondo test verifico che il primo test non sia stato verificato
 * 	quindi stampo la lista delle voci da cui far partire delle attivita
 *
 * _____________________________________________________________________
 *
 *		INDEX PAGE PHP
 * _____________________________________________________________________
 *
 *
 * 	[HEADPHP]
 * 		inizializzo risorse
 * 		inizializzo variabili
 *	[/HEADPHP]
 *
 *
 * 	[BODYPHP]
 *		test attivita and checklist
 *			switch attivita
 *		test contenuti vuoti
 *			select merce
 * 	[/BODYPHP]
 *
 *
 * 	[FOOTPHP]
 * 		ritorno contenuti
 * 		termino risorse
 * 		stampo output
 * 	[/FOOTPHP]
 * 
 * _____________________________________________________________________
 *
 * 
 */




// ******** HEADPHP ****************************************************

// inizializzo risorse
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// inizializzo variabili
$contents = "";
$log = "";
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;




// ******** BODYPHP ****************************************************

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

// test contenuti vuoti
if (!isset($_SESSION['contents'])) {

	// mostra merce
	$log .= remesg("Magazzino in visualizzazione merce","msg");
	vserv_magazzino_select();

}



// ******** FOOTPHP ****************************************************

// ritorno contenuti
$contents .= $_SESSION['contents'];
$log .= $_SESSION['log'];

// termino risorse
unset($_SESSION['contents']);
unset($_SESSION['log']);
session_write_close();
mysql_close($conn);

// stampo output
echo "<div id='log'>\n";
echo remesg("Notifiche","tit");
if ($log == "") echo remesg($msg18,"msg");
else echo $log;
echo "</div>\n";
echo $contents;




?>

