<?php

// inizializzazione

// variabili
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

$utente = $_SERVER["PHP_AUTH_USER"];

if (isset($_POST['id_operazioni'])AND(!empty($_POST['id_operazioni'])))
	$id_operazioni = $_POST['id_operazioni'];
else
	$id_operazioni = NULL;

$a = "";
$log = "";
$riga = "";
$data_revert = date("Y-m-d");

$log .= $menu_revert;

if ($DEBUG) $log .= remesg("DEBUG ATTIVO","debug");
if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

if ($DEBUG) $log .= "<pre>".var_dump($_POST)."</pre>";


// test bottoni
if (isset($_POST['finish'])) {
	
	$res_revert = myquery::revert_do($db,$utente,$id_operazioni);
	$log .= remesg("Hai confermato l'annullamento del transito #".$id_operazioni,"done");

// test revert
} elseif (isset($_POST['revert'])) {

	if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_POST['revert'],"debug");
	$log .= remesg("Annullamento transito #".$id_operazioni,"info");

	// form revisione dati
	$target = myquery::revisione_revert($db,$id_operazioni);

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

	$a .= "<td>".safetohtml($target[0]['rete'])."</td>\n";
	$a .= "<td>".safetohtml($target[0]['dataop'])."</td>\n";
	$a .= "<td>".safetohtml($target[0]['status'])."</td>\n";
	$a .= "<td>".safetohtml($target[0]['posizione'])."</td>\n";
	$a .= "<td>".$target[0]['documento']."</td>\n";
	$a .= "<td>".safetohtml($target[0]['data_doc'])."</td>\n";
	$a .= "<td>".safetohtml($target[0]['tags'])."</td>\n";
	$a .= "<td>".safetohtml($target[0]['quantita'])."</td>\n";
	$a .= "<td>".safetohtml(strtolower($target[0]['note']))."</td>\n";
	$a .= "<td>".$target[0]['doc_ordine']."</td>\n";		
	
	$a .= "<td>\n";

	$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=revert");
	if ($DEBUG) $a .= "&debug";
	$a .= "'>\n";
	$a .= noinput_hidden("id_operazioni",$id_operazioni);
	$a .= "<input type='submit' name='finish' value='Conferma'/>\n";
	$a .= "</form>\n";

	$a .= "</td>\n";

	$a .= "</tr></tbody></table>\n";

}

// test contenuti
if (is_null($a) OR empty($a)) {

	$resultset = myquery::vista_transiti_revertibili($db);

	if (count($resultset)>0) {

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
		
		foreach ($resultset as $row) {
			
			$riga .= "<tr>\n";
			
			$riga .= "<td>".safetohtml($row['rete'])."</td>\n";
			$riga .= "<td>".safetohtml($row['dataop'])."</td>\n";
			$riga .= "<td>".safetohtml($row['status'])."</td>\n";
			$riga .= "<td>".safetohtml($row['posizione'])."</td>\n";
			$riga .= "<td>".$row['documento']."</td>\n";
			$riga .= "<td>".safetohtml($row['data_doc'])."</td>\n";
			$riga .= "<td>".safetohtml($row['tags'])."</td>\n";
			$riga .= "<td>".safetohtml($row['quantita'])."</td>\n";
			$riga .= "<td>".safetohtml(strtolower($row['note']))."</td>\n";
			$riga .= "<td>".$row['doc_ordine']."</td>\n";
			
			$riga .= "<td>\n";

			$riga .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=revert");
			if ($DEBUG) $riga .= "&debug";
			$riga .= "'>\n";
			$riga .= noinput_hidden("id_operazioni",$row['id_operazioni']);
			$riga .= "<input type='submit' name='revert' value='Annulla'/>\n";
			$riga .= "</form>\n";

			$riga .= "</td>\n";

			$riga .= "</tr>\n";

		}

		$a .= $riga;
		$a .= "</tbody>\n</table>\n";

	} else

		$a .= remesg("Nessun transito da annullare","tit");

}


// stampo
echo makepage($a, $log);

?>
