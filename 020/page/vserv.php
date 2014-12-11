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

// RESET
// test stop
if (isset($_POST['stop'])) {
	
	$log .= remesg($msg9,"msg");
	reset_sessione();
	
}

/* 
 * PRE DATA
 * Stabilisce se vi e' attivita' in corso, in tal caso girerebbe l'intero pacchetto post al session
 * Altrimenti controlla se vi sono input validi per il lancio di una attivita, in tal caso
 * 		valorizza l'attivita' a seconda del bottone premuto in form
 * 		ricopia le tuple selezionate dal pacchetto post al session
 * In caso di input non validi per il lancio di una attivita'
 * 		stampa la tabella per la selezione della merce
 * 
 */
 
if (isset($_SESSION['attivita'])) {
	
	foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;		
	
} else {
	
	if ((isset($_POST['modifica']) OR (isset($_POST['scarica']))) AND (!empty($_POST['check_list']))) {
		
		if (isset($_POST['modifica']))
			$_SESSION['attivita'] = "modifica";
		
		if (isset($_POST['scarica']))
			$_SESSION['attivita'] = "scarica";
		
		foreach ($_POST['check_list'] as $i => $j) {
		
			$_SESSION['id_merce'][$j] = $_POST['id_merce'][$j];
			$_SESSION['tags'][$j] = $_POST['tags'][$j];
			$_SESSION['posizioni'][$j] = $_POST['posizioni'][$j];
			$_SESSION['tot'][$j] = $_POST['tot'][$j];
		
		}
	
	} else {
		
		$log .= remesg("Magazzino in visualizzazione merce","msg");
		vserv_magazzino_select();
		
	}
	
}

/* DATA
 * A questo punto ho l'array session valorizzato
 * 		o con le tuple da mandare in processo
 * 		o con i dati di una attivita' in corso
 * quindi lancio l'attivita' con gli opportuni dati
 * 
 */

if (isset($_SESSION['attivita'])) {
	
	switch ($_SESSION['attivita']) {
		
		case "modifica":
			vserv_magazzino_modifica();
			break;
		
		case "scarica":
			vserv_magazzino_scarica();
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

