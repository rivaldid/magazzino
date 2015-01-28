<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializza risorse

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

$log .= remesg("Aggiungi un <a href=\"http://10.98.2.159/GMDCTO/020/?page=documenti_add\">documento nuovo</a>","msg");


// interrogazione
$query = "SELECT id_registro,data,DATE_FORMAT(data,'%d/%m/%Y'),contatto,CONCAT_WS(' - ',tipo,numero,gruppo) as documento,tipo,numero,gruppo,file FROM REGISTRO WHERE NOT tipo='MDS' AND NOT tipo='Sistema' ORDER BY data DESC;";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


// risultati
$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

$a .= "<thead><tr>\n";
	$a .= "<th>Data</th>\n";
	$a .= "<th>Mittente</th>\n";
	$a .= "<th>Documento</th>\n";
	$a .= "<th>Scansione</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$a .= "<tr>\n";
	$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=documenti_add");
	if ($DEBUG) $a .= "&debug";
	$a .= "'>\n";
	foreach ($row as $cname => $cvalue)
	
		switch ($cname) {

			case "0":
				$a .= noinput_hidden("id_registro",$cvalue)."\n";
				break;

			case "1":
				$data = $cvalue;
				break;
			
			case "2":
				if ($data != NULL) {
					$a .= noinput_hidden("data",$data)."\n";
					$a .= "<td>".$cvalue."</td>\n";
				} else
					$a .= "<td><input type='submit' name='add' value='Aggiungi data'/></td>\n";
				break;	
								
			case "3":
				$a .= "<td>".input_hidden("mittente",$cvalue)."</td>\n";
				break;
			
			case "4":
				$a .= "<td>".$cvalue."</td>\n";
				break;

			case "5":
				$a .= noinput_hidden("tipo",$cvalue)."\n";
				break;

			case "6":
				$a .= noinput_hidden("numero",$cvalue)."\n";
				break;
			
			case "7":
				$a .= noinput_hidden("gruppo",$cvalue)."\n";
				break;

			case "8":
				if ($cvalue != NULL)
					$a .= "<td><a href=\"".registro.$cvalue."\">".safetohtml($cvalue)."</a></td>\n";
				else
					$a .= "<td><input type='submit' name='add' value='Aggiungi scansione'/></td>\n";
				break;

			default:
				$a .= "<td>".safetohtml($cvalue)."</td>\n";

		} // end switch

	$a .= "</tr>\n";

} // end while

$a .= "</tbody>\n</table>\n";


// termino risorse
mysql_free_result($res);
mysql_close($conn);


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


?>

