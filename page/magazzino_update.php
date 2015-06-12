<?php

// inizializzo risorse

// $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// variabili
$a = ""; $log = "";
$valid = true;

if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

if ($DEBUG) $log .= "<pre>".var_dump($_POST)."</pre>";
if ($DEBUG) $log .= "<pre>".var_dump($_SESSION)."</pre>";

if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

$log .= remesg("Torna alla <a href=\"?page=magazzino\">visualizzazione magazzino</a>","action");

$utente = $_SERVER["PHP_AUTH_USER"];

if (isset($_SESSION['posizione'])AND(!empty($_SESSION['posizione'])))
	$posizione = safe($_SESSION['posizione']);
else
	$posizione = NULL;

if (isset($_SESSION['id_merce'])AND(!empty($_SESSION['id_merce'])))
	$id_merce = safe($_SESSION['id_merce']);
else
	$id_merce = NULL;

if (isset($_SESSION['tags'])AND(!empty($_SESSION['tags'])))
	$tags = safe($_SESSION['tags']);
else
	$tags = NULL;

if (isset($_SESSION['quantita'])AND(!empty($_SESSION['quantita'])))
	$quantita = safe($_SESSION['quantita']);
else
	$quantita = NULL;

// nuovi valori
if (isset($_SESSION['inuova_posizione'])AND(!empty($_SESSION['inuova_posizione'])))
	$nuova_posizione = safe($_SESSION['inuova_posizione']);
else {
	if (isset($_SESSION['snuova_posizione'])AND(!empty($_SESSION['snuova_posizione'])))
		$nuova_posizione = safe($_SESSION['snuova_posizione']);
	else
		$nuova_posizione = NULL;
}

if (isset($_SESSION['nuova_quantita'])AND(!empty($_SESSION['nuova_quantita'])))
	$nuova_quantita = safe($_SESSION['nuova_quantita']);
else
	$nuova_quantita = NULL;

$data = date('Y-m-d');


// test stop
if (isset($_SESSION['stop'])) {
	reset_sessione();
	$log .= remesg("Sessione terminata","done");
}

// test add
if (isset($_SESSION['add'])) {

	if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_SESSION['add'],"debug");

	// validazione
	if (is_null($posizione) OR empty($posizione)) $valid = false;
	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if (is_null($id_merce) OR empty($id_merce)) $valid = false;
	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if (is_null($quantita) OR empty($quantita)) $valid = false;
	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if ((is_null($nuova_posizione) OR empty($nuova_posizione)) AND
		(is_null($nuova_quantita) OR empty($nuova_quantita)))
		$valid = false;

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if ($DEBUG) $log .= remesg("Valore nuova posizione: ".$nuova_posizione,"debug");
	if ($DEBUG) $log .= remesg("Valore nuova quantita: ".$nuova_quantita,"debug");


	if ($valid) {

		if (isset($nuova_posizione))
			myquery::magazzino_agg_posizione($db,$utente,$posizione,$nuova_posizione,$quantita,$data);
		elseif (isset($nuova_quantita))
			myquery::magazzino_agg_quantita($db,$utente,$posizione,$nuova_posizione,$quantita,$data);

		$log .= remesg("Aggiornamento posizione magazzino inviato al database","done");

		// reset
		reset_sessione();

	} else {

		// form di inserimento
		$a .= jsxtable;
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=magazzino_update");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		$a .= "<table class='altrowstable' id='alternatecolor'>\n";
		$log .= remesg("Acquisizione nuovi dati","info");


		$a .= "<thead><th>Target</th><th>Corrente</th><th>Nuovo</th><th>Aggiornamento</th></thead>\n";

		$a .= "<tfoot><tr><td colspan='4'><input type='submit' name='stop' value='Esci senza salvare'/></td>\n</tr>\n</tfoot>\n";

		$a .= "<tbody>\n";

		$a .= "<tr>\n";
			$a .= "<td colspan='4'>".$tags."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>Aggiorna la posizione</td>\n";
			$a .= "<td>".$posizione."</td>\n";
			$a .= "<td>\n";
				$a .= "<input type='text' name='inuova_posizione'/>\n";
				$a .= myoptlst("snuova_posizione",$vserv_posizioni)."\n";
			$a .= "</td>\n";
			$a .= "<td><input type='submit' name='save' value='Salva'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>Aggiorna la quantita'</td>\n";
			$a .= "<td>".$quantita."</td>\n";
			$a .= "<td>\n";
				$a .= "<input type='text' name='nuova_quantita'/>\n";
			$a .= "</td>\n";
			$a .= "<td><input type='submit' name='save' value='Salva'/></td>\n";
		$a .= "</tr>\n";

		$a .= "</tbody>\n";

		$a .= "</table>\n</form>\n";
	}

}


// test contenuti
if (is_null($a) OR empty($a)) {

	// ricevo lista merce
	$query = myquery::magazzino_simple($db);

	// form selezione
	$a .= jsxtable;
	$a .= jsaltrows;
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";
	$log .= remesg("Lista estesa del contenuto del magazzino","info");
	$a .= "<thead><tr>\n";
		$a .= "<th>Posizione</th>\n";
		$a .= "<th>TAGS</th>\n";
		$a .= "<th>Quantita'</th>\n";
		$a .= "<th>Azione</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";

	foreach ($query AS $row) {
		$a .= "<tr>\n";
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=magazzino_update");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		
		$a .= noinput_hidden("id_merce",$row['id_merce']);
		$a .= "<td>".input_hidden("posizione",$row['posizione'])."</td>\n";
		$a .= "<td>".input_hidden("tags",$row['merce'])."</td>\n";
		$a .= "<td>".input_hidden("quantita",$row['quantita'])."</td>\n";
		
		$a .= "<td><input type='submit' name='add' value='Aggiorna'/></td>\n";
		$a .= "</form>\n";
		$a .= "</tr>\n";
	}

	$a .= "</tbody>\n</table>\n";

}


// libero risorse
session_write_close();

// stampo
echo makepage($a, $log);


?>

