<?php

// vars
$a = "";
$q1 = "SELECT * FROM vserv_contatti;";
$q2 = "SELECT * FROM vserv_tipodoc;";
$q3 = "SELECT * FROM vserv_numdoc;";
$q4 = "SELECT * FROM vserv_posizioni;";
$q5 = "SELECT * FROM vserv_numoda;";

// start risorse
session_start();
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// inizializzo
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

if (isset($_SESSION['fornitore'])) 
	$fornitore = safe($_SESSION['fornitore']);
else 
	$fornitore = myoptlst("fornitore",$q1);

if (isset($_SESSION['tipo_doc'])) 
	$tipo_doc = safe($_SESSION['tipo_doc']);
else 
	$tipo_doc = myoptlst("tipo_doc",$q2);

if (isset($_SESSION['num_doc'])) 
	$num_doc = safe($_SESSION['num_doc']);
else 
	$num_doc = myoptlst("num_doc",$q3);

if (isset($_SESSION['num_doc'])) 
	$num_doc = safe($_SESSION['num_doc']);
else 
	$num_doc = myoptlst("num_doc",$q3);

if (isset($_SESSION['trasportatore'])) 
	$trasportatore = safe($_SESSION['trasportatore']);
else 
	$trasportatore = myoptlst("trasportatore",$q1);

if (isset($_SESSION['num_oda'])) 
	$num_oda = safe($_SESSION['num_oda']);
else 
	$num_oda = myoptlst("num_oda",$q5);

$posizione = myoptlst("posizione",$q4);

if (isset($_POST['submit'])) {
}

// stop risorse
mysql_close($conn);
session_write_close();

// display page
echo $a;

?>
