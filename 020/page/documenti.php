<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);

//begin mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

$query = "SELECT id_registro,file,contatto,documento,datacenter.fancydate(data) FROM vista_documenti;";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());

$a = "";

//print
$a .= jsxtable;
$a .= "<table>\n";

$a .= "<caption>\n";
$a .= "DOCUMENTI REGISTRATI\n";
$a .= "</caption>\n";

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

