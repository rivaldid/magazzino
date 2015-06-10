<?php

// inizializzazione

// variabili
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

$utente = $_SERVER["PHP_AUTH_USER"];

if (isset($_POST['id_operazioni'])AND(!empty($_POST['id_operazioni'])))
	$id_operazioni = safe($_POST['id_operazioni']);
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

	/*$call = "CALL revert('{$utente}','{$id_operazioni}');";

	$res_revert = mysql_query($call);

	if ($res_revert)
		$log .= remesg("Hai confermato l'annullamento del transito #".$id_operazioni,"done");
	else
		die('Errore nell\'invio dei dati al db: '.mysql_error());

	logging2($call,splog);*/

// test revert
} elseif (isset($_POST['revert'])) {

	if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_POST['revert'],"debug");
	$log .= remesg("Annullamento transito #".$id_operazioni,"info");


	// form revisione dati
	$target = myquery::revisione_revert($db,$id_operazioni);
	
	//print_r($target);

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

	$a .= "<td>".safetohtml($target['2'])."</td>\n";
	$a .= "<td>".safetohtml($target['3'])."</td>\n";
	$a .= "<td>".safetohtml($target['4'])."</td>\n";
	$a .= "<td>".safetohtml($target['5'])."</td>\n";
	
	if (isset($target['0']) AND ($target['0']!= NULL))
		$a .= "<td><a href=\"".registro.$target['0']."\">".safetohtml($target['6'])."</a></td>\n";
	else
		$a .= "<td>".safetohtml($target['6'])."</td>\n";
	
	$a .= "<td>".safetohtml($target['7'])."</td>\n";
	$a .= "<td>".safetohtml($target['8'])."</td>\n";
	$a .= "<td>".safetohtml($target['9'])."</td>\n";
	$a .= "<td>".safetohtml(strtolower($target['10']))."</td>\n";
	
	if (isset($target['1']) AND ($target['1']!= NULL))
		$a .= "<td><a href=\"".registro.$target['1']."\">".safetohtml($target['11'])."</a></td>\n";
	else
		$a .= "<td>".safetohtml($target['11'])."</td>\n";
			
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
			
			$riga .= "<td>".safetohtml($row['2'])."</td>\n";
			$riga .= "<td>".safetohtml($row['3'])."</td>\n";
			$riga .= "<td>".safetohtml($row['4'])."</td>\n";
			$riga .= "<td>".safetohtml($row['5'])."</td>\n";
			
			if (isset($row['0']) AND ($row['0']!= NULL))
				$riga .= "<td><a href=\"".registro.$row['0']."\">".safetohtml($row['6'])."</a></td>\n";
			else
				$riga .= "<td>".safetohtml($row['6'])."</td>\n";
			
			$riga .= "<td>".safetohtml($row['7'])."</td>\n";
			$riga .= "<td>".safetohtml($row['8'])."</td>\n";
			$riga .= "<td>".safetohtml($row['9'])."</td>\n";
			$riga .= "<td>".safetohtml(strtolower($row['10']))."</td>\n";
			
			if (isset($row['1']) AND ($row['1']!= NULL))
				$riga .= "<td><a href=\"".registro.$row['1']."\">".safetohtml($row['11'])."</a></td>\n";
			else
				$riga .= "<td>".safetohtml($row['11'])."</td>\n";
			
			$riga .= "<td>\n";

			$riga .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=revert");
			if ($DEBUG) $output_row .= "&debug";
			$riga .= "'>\n";
			$riga .= noinput_hidden("id_operazioni",$row['13']);
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
