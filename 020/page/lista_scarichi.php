<?php

// occhiomalocchio
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());
if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;
$logging = "CALL input_trace('{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_URI']}','{$_SERVER['HTTP_REFERER']}','{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REMOTE_USER']}','{$_SERVER['PHP_AUTH_USER']}','{$_SERVER['HTTP_USER_AGENT']}');";
mysql_query($logging);
mysql_close($conn);

// inizializzo risorse

// variabili
$a = "";
$log = "";

if (isset($_GET["ultimi"]))
	$ultimi=true;
else
	$ultimi=false;

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// interrogazione
if ($ultimi)
	$vserv_ultimi_scarichi = "SELECT utente,data,data_doc,tags,quantita,posizione,note FROM vserv_transiti WHERE status='USCITA' LIMIT 15;";
else
	$vserv_ultimi_scarichi = "SELECT utente,data,data_doc,tags,quantita,posizione,note FROM vserv_transiti WHERE status='USCITA';";

$res = mysql_query($vserv_ultimi_scarichi);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


// risultati
$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

$log .= remesg("Torna a <a href=\"?page=scarico\">scarico</a>","info");

$a .= "<thead><tr>\n";
	$a .= "<th>Utente</th>\n";
	$a .= "<th>Data effettiva</th>\n";
	$a .= "<th>Data di riferimento</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Quantita'</th>\n";
	$a .= "<th>Destinazione</th>\n";
	$a .= "<th>Note</th>\n";
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

