<?php

//begin mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

$query = "SELECT doc_ingresso,doc_ordine,utente,datacenter.fancydate(data),status,posizione,documento,tags,quantita,note,ordine FROM TRANSITI;";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());

$a = "";

//print
$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

$a .= "<thead><tr>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />Utente</span></th>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />Data</span></th>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />Direzione</span></th>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />Posizione</span></th>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />Documento</span></th>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />TAGS</span></th>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />Quantita'</span></th>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />Note</span></th>\n";
	$a .= "<th><span><img title='ordinamento' src='imgs/upDown.gif' />ODA</span></th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$a .= "<tr>\n";
	foreach ($row as $cname => $cvalue)
		switch ($cname) {

			case "0":
				$doc_ingresso = $cvalue;
				break;

			case "1":
				$doc_ordine = $cvalue;
				break;

			case "6":
				if ($doc_ingresso != NULL)
					$a .= "<td><a href=\"".registro.$doc_ingresso."\">".safetohtml($cvalue)."</a></td>\n";
				else
					$a .= "<td>".safetohtml($cvalue)."</td>\n";
				break;

			case "10":
				if ($doc_ordine != NULL)
					$a .= "<td><a href=\"".registro.$doc_ordine."\">".safetohtml($cvalue)."</a></td>\n";
				else
					$a .= "<td>".safetohtml($cvalue)."</td>\n";
				break;

			default:
				$a .= "<td>".safetohtml($cvalue)."</td>\n";

		} // end switch

	$a .= "</tr>\n";

} // end foreach

$a .= "</tbody>\n</table>\n";

echo $a;

mysql_free_result($res);

// end mysql
mysql_close($conn);

?>

