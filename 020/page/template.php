<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializzazione

// $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// variabili
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

$a = "";
$log = "";


// test bottoni

// test stop
if (isset($_SESSION['stop'])) {

	if ($DEBUG) $log .= remesg("Valore tasto STOP: ".$_SESSION['stop'],"debug");

	// reset variabili server
	reset_sessione();

	// alert
	$log .= remesg("Sessione terminata","done");

}

// test add||save
if ((isset($_SESSION['add'])) OR (isset($_SESSION['save']))) {

	if (isset($_SESSION['add']))
		if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_SESSION['add'],"debug");

	if (isset($_SESSION['save']))
		if ($DEBUG) $log .= remesg("Valore tasto SAVE: ".$_SESSION['save'],"debug");

	// validazione
	
	// test valid
	if ($valid) {
		
		$a .= "valid";
		// query + logging2 + free result
	
	} else {
		
		$a .= "not valid";
		// form revisione dati
	
	}
}

// reset mysql connection
mysql_close($conn);
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// test contenuti
if (is_null($a) OR empty($a)) {
	
	// interrogazione + tabella risultati + free result
	$a .= "contents";
	
}


// termino risorse
mysql_close($conn);
session_write_close();



// stampo
echo makepage($a, $log);

?>
