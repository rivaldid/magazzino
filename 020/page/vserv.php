<?php

/*
 * modifica di merce in magazzino, script frontend per stored procedure
 * aggiornamento_magazzino_merce(utente,
 * tags1,id1,posizione1,quantita1,
 * tags2,id2,posizione2,quantita2,
 * data);
 * 
 * ALGORITMO:
 * 	definizione variabili
 * 
 * 	startup risorse
 * 		$_SESSION
 * 		 mysql
 * 	
 * 	termino risorse
 * 		$_SESSION
 * 		mysql
 * 
 * 	impagino
 * 	stampo
 * 
 */



// definizione variabili
$a = "";
$log = "";



// startup risorse

// $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());




// routing
if ((isset($_POST['modifica']) OR (isset($_POST['scarica']))) AND (!empty($_POST['edit_list']))) {
	
	
	foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
	
	if (isset($_POST['modifica']))
		include 'page/pagina_form_modifica.php';
		
	if (isset($_POST['scarica']))
		include 'page/pagina_form_scarica.php';
	
} 
else
	include 'page/pagina_form_seleziona.php';





// termino risorse

// $_SESSION
session_write_close();

// mysql
mysql_close($conn);



// impagino
echo "<div id='log'>\n";
echo remesg("Notifiche","tit");
if ($log == "")
	echo remesg($msg18,"msg");
else
	echo $log;
echo "</div>\n";



// stampo
echo $a;


?>

