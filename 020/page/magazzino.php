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


// interrogazione
if (isset($_GET["id"])) $query = "SELECT * FROM vserv_magazzino_id;";
else $query = "SELECT * FROM vserv_magazzino;";

$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


// risultati
$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

if (isset($_GET["id"])) $log .= remesg("Visualizzazione senza <a href=\"?page=magazzino\">ID</a>","msg");
else $log .= remesg("Visualizzazione con <a href=\"?page=magazzino&id\">ID</a>","msg");

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


// stampo
echo "<div id=\"log\">\n";
echo remesg("Notifiche","tit");
echo remesg("Autenticato come ".$_SERVER["AUTHENTICATE_UID"]." alle ".date('H:i')." del ".date('d/m/Y'),"msg");
if (isset($log)) {
	if ($log == "")
		echo remesg($msg18,"msg");
	else
		echo $log;
}
echo "</div>\n";
echo $a;

mysql_free_result($res);


// termino risorse
mysql_close($conn);

?>

