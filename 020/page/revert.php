<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


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

$a = "";
$log = "";
$output_row = "";
$valid = false;

$query_interrogazione = "SELECT id_operazioni,doc_ingresso,doc_ordine,utente,DATE_FORMAT(data,'%d/%m/%Y'),status,posizione,documento,DATE_FORMAT(data_doc,'%d/%m/%Y'),tags,quantita,note,ordine FROM TRANSITI WHERE 1";

if ($DEBUG) $log .= remesg("DEBUG ATTIVO","debug");
if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

if ($DEBUG) $log .= "<pre>".var_dump($_POST)."</pre>";


// test bottoni
if (isset($_POST['finish'])) {

	$log .= remesg("Hai confermato l'eliminazione del transito #".$_POST['id_operazioni'],"done");

// test revert
} elseif (isset($_POST['revert'])) {

	if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_POST['revert'],"debug");
	$log .= remesg("Annullamento transito #".$_POST['id_operazioni'],"info");


	// form revisione dati
	$result_target = mysql_query($query_interrogazione." AND id_operazioni=\"".$_POST['id_operazioni']."\"");
	$a .= remesg("Stai per annullare il transito","warn");

	$target = mysql_fetch_row($result_target);
	mysql_free_result($result_target);

	$a .= jsxtable;
	$a .= jsaltrows;
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";

	$a .= "<thead><tr>\n";
		$a .= "<th>Utente</th>\n";
		$a .= "<th>Data transito</th>\n";
		$a .= "<th>Direzione</th>\n";
		$a .= "<th>Posizione</th>\n";
		$a .= "<th>Documento</th>\n";
		$a .= "<th>Data documento</th>\n";
		$a .= "<th>TAGS</th>\n";
		$a .= "<th>Quantita'</th>\n";
		$a .= "<th>Note</th>\n";
		$a .= "<th>ODA</th>\n";
		$a .= "<th>Azione</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";

	$a .= "<tr>\n";

	$a .= "<td>".$target[3]."</td>\n";
	$a .= "<td>".$target[4]."</td>\n";
	$a .= "<td>".$target[5]."</td>\n";
	$a .= "<td>".$target[6]."</td>\n";

	if ($target[1] != NULL)
		$a .= "<td><a href=\"".registro.$target[1]."\">".safetohtml($target[7])."</a></td>\n";
	else
		$a .= "<td>".$target[7]."</td>\n";

	$a .= "<td>".$target[8]."</td>\n";
	$a .= "<td>".$target[9]."</td>\n";
	$a .= "<td>".$target[10]."</td>\n";
	$a .= "<td>".safetohtml(strtolower($target[11]))."</td>\n";

	if ($target[2] != NULL)
		$a .= "<td><a href=\"".registro.$target[2]."\">".safetohtml($target[12])."</a></td>\n";
	else
		$a .= "<td>".$target[12]."</td>\n";

	$a .= "<td>\n";

		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=revert");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		$a .= noinput_hidden("id_operazioni",$_POST['id_operazioni']);
		$a .= "<input type='submit' name='finish' value='Conferma'/>\n";
		$a .= "</form>\n";

	$a .= "</td>\n";

	$a .= "</tr></tbody></table>\n";

}

// reset mysql connection
mysql_close($conn);
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// test contenuti
if (is_null($a) OR empty($a)) {

	// interrogazione + tabella risultati + free result
	$resultset = mysql_query($query_interrogazione." LIMIT 1,5;");
	if (!$resultset) die('Errore nell\'interrogazione del db: '.mysql_error());

	$a .= jsxtable;
	$a .= jsaltrows;
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";

	$a .= "<thead><tr>\n";
		$a .= "<th>Utente</th>\n";
		$a .= "<th>Data transito</th>\n";
		$a .= "<th>Direzione</th>\n";
		$a .= "<th>Posizione</th>\n";
		$a .= "<th>Documento</th>\n";
		$a .= "<th>Data documento</th>\n";
		$a .= "<th>TAGS</th>\n";
		$a .= "<th>Quantita'</th>\n";
		$a .= "<th>Note</th>\n";
		$a .= "<th>ODA</th>\n";
		$a .= "<th>Azione</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";

	while ($input_row = mysql_fetch_array($resultset, MYSQL_NUM)) {
		$output_row .= "<tr>\n";
		foreach ($input_row as $cname => $cvalue)
			switch ($cname) {

				case "0":
					$id_operazioni = $cvalue;

				case "1":
					$doc_ingresso = $cvalue;
					break;

				case "2":
					$doc_ordine = $cvalue;
					break;

				case "7":
					if ($doc_ingresso != NULL)
						$output_row .= "<td><a href=\"".registro.$doc_ingresso."\">".safetohtml($cvalue)."</a></td>\n";
					else
						$output_row .= "<td>".safetohtml($cvalue)."</td>\n";
					break;

				case "11":
					$output_row .= "<td>".safetohtml(strtolower($cvalue))."</td>\n";
					break;

				case "12":
					if ($doc_ordine != NULL)
						$output_row .= "<td><a href=\"".registro.$doc_ordine."\">".safetohtml($cvalue)."</a></td>\n";
					else
						$output_row .= "<td>".safetohtml($cvalue)."</td>\n";
					break;

				default:
					$output_row .= "<td>".safetohtml($cvalue)."</td>\n";

			} // end switch

			$output_row .= "<td>\n";

				$output_row .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=revert");
				if ($DEBUG) $output_row .= "&debug";
				$output_row .= "'>\n";
				$output_row .= noinput_hidden("id_operazioni",$id_operazioni);
				$output_row .= "<input type='submit' name='revert' value='Annulla'/>\n";
				$output_row .= "</form>\n";

			$output_row .= "</td>\n";

		$output_row .= "</tr>\n";

	} // end while

	$a .= $output_row;
	$a .= "</tbody>\n</table>\n";

	mysql_free_result($resultset);

}


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);

?>
