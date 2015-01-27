<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializza risorse

// $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// variabili

// globali
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

$a = "";
$log = "";

$valid = true;
$upload = true;



// form input documenti
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=documenti");
if ($DEBUG) $a .= "&debug";
$a .= "'>\n";
$a .= jsxdate;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

	$log .= remesg("Pagina per la gestione di documenti di magazzino","msg");

	$a .= "<thead><tr>\n";
		$a .= "<th>Descrizione</th>\n";
		$a .= "<th>Inserimento</th>\n";
		$a .= "<th>Suggerimento</th>\n";
	$a .= "</tr></thead>\n";

	$a .= "<tfoot>\n";
		$a .= "<tr>\n";
		$a .= "<td colspan='3'>\n";
			$a .= "<input type='reset' name='reset' value='Pulisci il foglio'/>\n";
			//$a .= "<input type='submit' name='add' value='Salva e continua'/>\n";
			$a .= "<input type='submit' name='save' value='Salva'/>\n";
			$a .= "<input type='submit' name='stop' value='Esci senza salvare'/>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";
	$a .= "</tfoot>\n";

	$a .= "<tbody>\n";


		$a .= "<tr>\n";
		$a .= "<td><label for='mittente'>Mittente documento</label></td>\n";
		$a .= "<td><input type='text' name='mittente'/></td>\n";
		$a .= "<td>".myoptlst("mittente",$vserv_contatti)."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='tipo_doc'>Tipo documento</label></td>\n";
		$a .= "<td><input type='text' name='tipo_doc'/></td>\n";
		$a .= "<td>".myoptlst("tipo_doc",$vserv_tipodoc)."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='num_doc'>Numero documento</label></td>\n";
		$a .= "<td><input type='text' name='num_doc'/></td>\n";
		$a .= "<td>".myoptlst("num_doc",$vserv_numdoc)."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='data_doc'>Data e documento</label></td>\n";
		$a .= "<td><input type='text' class='datepicker' name='data_doc'/></td>\n";
		$a .= "<td><input type='file' name='scansione'/></td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='associazione'>Associazione</label></td>\n";
		$a .= "<td>".myoptlst("associazione",$vserv_gruppi_doc)."</td>\n";
		$a .= "</tr>\n";

	$a .= "</tbody>\n";

$a .= "</table>\n";
$a .= "</form>\n";

//begin mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

$query = "SELECT id_registro,file,contatto,documento,datacenter.fancydate(data) FROM vista_documenti;";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


//print
$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

$a .= "<thead><tr>\n";
	$a .= "<th>Contatto</th>\n";
	$a .= "<th>Numero di documento</th>\n";
	$a .= "<th>Data</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$a .= "<tr>\n";
	foreach ($row as $cname => $cvalue)
		switch ($cname) {

			case "0":
				$a .= noinput_hidden("id_registro",$cvalue)."\n";
				break;

			case "1":
				$scansione = $cvalue;
				break;

			case "3":
				if ($scansione != NULL)
					$a .= "<td><a href=\"".registro.$scansione."\">".safetohtml($cvalue)."</a></td>\n";
				else
					$a .= "<td>".safetohtml($cvalue)."</td>\n";
				break;

			default:
				$a .= "<td>".safetohtml($cvalue)."</td>\n";

		} // end switch

	$a .= "</tr>\n";

} // end foreach

$a .= "</tbody>\n</table>\n";

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

// end mysql
mysql_close($conn);

?>

