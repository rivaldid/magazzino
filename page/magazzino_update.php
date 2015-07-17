<?php

// inizializzo risorse

// variabili
$a = ""; $log = "";
$valid = true;

if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

if ($DEBUG) $log .= "<pre>".var_dump($_POST)."</pre>";
if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

$log .= remesg("Torna alla <a href=\"?page=magazzino\">visualizzazione magazzino</a>","action");

$utente = $_SERVER["PHP_AUTH_USER"];
$data = date('Y-m-d');


// test input: id_merce tags posizione quantita target inserimento

if (isset($_POST['id_merce'])AND(!empty($_POST['id_merce'])))
	$id_merce = $_POST['id_merce'];
else
	$id_merce = NULL;

if (isset($_POST['tags'])AND(!empty($_POST['tags'])))
	$tags = $_POST['tags'];
else
	$tags = NULL;

if (isset($_POST['posizione'])AND(!empty($_POST['posizione'])))
	$posizione = $_POST['posizione'];
else
	$posizione = NULL;

if (isset($_POST['quantita'])AND(!empty($_POST['quantita'])))
	$quantita = $_POST['quantita'];
else
	$quantita = NULL;

if (isset($_POST['inserimento'])AND(!empty($_POST['inserimento'])))
	$inserimento = $_POST['inserimento'];
else
	$inserimento = NULL;

if (isset($_POST['target'])AND(!empty($_POST['target']))) {

	switch ($_POST['target']) {
		case "posizione":
			$nuova_posizione=$inserimento;
			break;
		case "quantita":
			$nuova_quantita=$inserimento;
			break;
		default:
			$nuova_posizione=NULL;
			$nuova_quantita=NULL;
	}

} else {
	$nuova_posizione=NULL;
	$nuova_quantita=NULL;
}


// test add
if (isset($_POST['save'])) {

	if ($DEBUG) $log .= remesg("Valore tasto SAVE: ".$_POST['save'],"debug");

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

		// call
		if (isset($nuova_posizione))
			myquery::magazzino_agg_posizione($db,$utente,$id_merce,$posizione,$nuova_posizione,$quantita,$data);
		elseif (isset($nuova_quantita))
			myquery::magazzino_agg_quantita($db,$utente,$id_merce,$posizione,$quantita,$nuova_quantita,$data);

		$log .= remesg("Aggiornamento posizione magazzino inviato al database","done");

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
		$a .= "<th>Merce</th>\n";
		$a .= "<th>Posizione</th>\n";
		$a .= "<th>Quantita'</th>\n";
		$a .= "<th>Aggiornamento</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";

	foreach ($query AS $row) {
		$a .= "<tr>\n";
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=magazzino_update");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";

		$a .= noinput_hidden("id_merce",$row['id_merce']);
		$a .= "<td>".input_hidden("tags",$row['merce'])."</td>\n";
		$a .= "<td>".input_hidden("posizione",$row['posizione'])."</td>\n";
		$a .= "<td>".input_hidden("quantita",$row['quantita'])."</td>\n";

		$a .= "<td>\n";

		$a .= "<fieldset class=\"fieldform\">\n";
		$a .= "<select name='target'>\n";
		$a .= "<option selected='selected' value=''>Seleziona...</option>\n";
		$a .= "<option value='posizione'>Posizione</option>\n";
		$a .= "<option value='quantita'>Quantita'</option>\n";
		$a .= "</select>\n";
		$a .= "<input type='text' name='inserimento'/>\n";
		$a .= "<input type='submit' name='save' value='Salva'/>\n";
		$a .= "</form>\n";
		$a .= "</fieldset>\n";

		$a .= "</td>\n";

		$a .= "</tr>\n";
	}

	$a .= "</tbody>\n</table>\n";

}

// stampo
echo makepage($a, $log);

?>

