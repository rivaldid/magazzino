<?php

// inizializzazione

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

if (isset($_SERVER["AUTHENTICATE_UID"])AND(!empty($_SERVER["AUTHENTICATE_UID"])))
	$utente = $_SERVER["AUTHENTICATE_UID"];
else
	$utente = NULL;

$a = "";
$log = "";

$query_interrogazione = "SELECT data,REQUEST_URI,HTTP_REFERER,REMOTE_ADDR,REMOTE_USER,PHP_AUTH_USER,HTTP_USER_AGENT FROM vista_trace ORDER BY data DESC LIMIT 0,20";

if ($DEBUG) $log .= remesg("DEBUG ATTIVO","debug");
if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

if ($DEBUG) $log .= "<pre>".var_dump($_POST)."</pre>";


$resultset = mysql_query($query_interrogazione);
if (!$resultset) die('Errore nell\'interrogazione del db: '.mysql_error());

if (mysql_num_rows($resultset)>0) {

$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

$a .= "<thead><tr>\n";
	$a .= "<th>data</th>\n";
	$a .= "<th>Destinazione</th>\n";
	$a .= "<th>Provenienza</th>\n";
	$a .= "<th>Ospite</th>\n";
	$a .= "<th>Utente</th>\n";
	$a .= "<th>Utente autenticato</th>\n";
	$a .= "<th>User Agent</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

while ($input_row = mysql_fetch_array($resultset, MYSQL_NUM)) {
	$a .= "<tr>\n";
	
	foreach ($input_row as $cname => $cvalue) {
		
		/*if ($cname == "6") {
			$ua=getBrowser($cvalue);
			//$yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
			//print_r($yourbrowser);
			$a .= "<td>".$ua['name']." ".$ua['version']." da ".$ua['platform']."</td>\n";
		} else*/
			$a .= "<td>".safetohtml($cvalue)."</td>\n";
	}
			
	$a .= "</tr>\n";
}

$a .= "</tbody>\n</table>\n";

mysql_free_result($resultset);

}


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);

?>
