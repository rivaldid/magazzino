<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializzazione

// $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

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


// test bottoni

// test revert
if (isset($_SESSION['revert'])) {

	if (isset($_SESSION['add']))
		if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_SESSION['add'],"debug");


	// validazione
	
	// test valid
	if ($valid) {
		
		$a .= "valid";
		// query + logging2 + free result
	
	} else {
		
		$a .= "not valid";
		// form revisione dati
	
	}
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
	$sql = "SELECT id_operazioni,doc_ingresso,doc_ordine,utente,DATE_FORMAT(data,'%d/%m/%Y'),status,posizione,documento,DATE_FORMAT(data_doc,'%d/%m/%Y'),tags,quantita,note,ordine FROM TRANSITI WHERE 1 LIMIT 1,10";
	$query = mysql_query($sql);
	if (!$query) die('Errore nell\'interrogazione del db: '.mysql_error());
	
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

	while ($input_row = mysql_fetch_array($query, MYSQL_NUM)) {
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
				
				$output_row .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=template");
				if ($DEBUG) $output_row .= "&debug";
				$output_row .= "'>\n";
				$output_row .= noinput_hidden("id_operazioni",$id_operazioni);
				$output_row .= "<input type='submit' name='revert' value='Revert'/>\n";
				$output_row .= "</form>\n";
				
			$output_row .= "</td>\n";

		$output_row .= "</tr>\n";

	} // end while

	$a .= $output_row;
	$a .= "</tbody>\n</table>\n";
	
	
}


// termino risorse
mysql_close($conn);
session_write_close();



// stampo
echo makepage($a, $log);

?>
