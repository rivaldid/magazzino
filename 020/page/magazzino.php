<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializzo risorse

// variabili
$a = "";
$log = "";

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// log
if (isset($_GET["id"])) {
	$query = "SELECT * FROM vserv_magazzino_id;";
	$log .= remesg("Visualizzazione senza <a href=\"?page=magazzino\">ID</a>","msg");
} else {
	$query = "SELECT * FROM vserv_magazzino;";
	$log .= remesg("Visualizzazione con <a href=\"?page=magazzino&id\">ID</a>","msg");
}

$log .= remesg("Aggiornamento <a href=\"?page=aggiornamento_magazzino\">posizione o quantita'</a> in magazzino","msg");

// interrogazione
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


// risultati
$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";
$a .= "<thead><tr>\n";
	if (isset($_GET["id"])) $a .= "<th>ID</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Posizioni con parziali</th>\n";
	$a .= "<th>Tot</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$a .= "<tr>\n";
	foreach ($row as $cname => $cvalue)
		$a .= "<td>".$cvalue."</td>\n";
	$a .= "</tr>\n";
}

$a .= "</tbody>\n</table>\n";
mysql_free_result($res);


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);


?>

