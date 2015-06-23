<?php

// inizializzo risorse

// $_SESSION
$id = $_SERVER['PHP_AUTH_USER']."-".epura_specialchars($_GET['page']);
session_id($id);
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
$data = date('Y-m-d');


// test input: id_merce tags posizione quantita target inserimento

if (isset($_SESSION['id_merce'])AND(!empty($_SESSION['id_merce'])))
	$id_merce = $_SESSION['id_merce'];
else
	$id_merce = NULL;

if (isset($_SESSION['tags'])AND(!empty($_SESSION['tags'])))
	$tags = $_SESSION['tags'];
else
	$tags = NULL;

if (isset($_SESSION['posizione'])AND(!empty($_SESSION['posizione'])))
	$posizione = $_SESSION['posizione'];
else
	$posizione = NULL;

if (isset($_SESSION['quantita'])AND(!empty($_SESSION['quantita'])))
	$quantita = $_SESSION['quantita'];
else
	$quantita = NULL;

if (isset($_SESSION['inserimento'])AND(!empty($_SESSION['inserimento'])))
	$inserimento = $_SESSION['inserimento'];
else
	$inserimento = NULL;

if (isset($_SESSION['target'])AND(!empty($_SESSION['target']))) {
	
	switch ($_SESSION['target']) {
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



// test stop
if (isset($_SESSION['stop'])) {
	session_destroy();
	session_unset();
	$id = $_SERVER['PHP_AUTH_USER']."-".epura_specialchars($_GET['page']);
	session_id($id);
	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
	$log .= remesg("Sessione terminata","done");
}

// test add
if (isset($_SESSION['save'])) {

	if ($DEBUG) $log .= remesg("Valore tasto SAVE: ".$_SESSION['save'],"debug");

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

		// reset
		session_destroy();
		session_unset();
		$id = $_SERVER['PHP_AUTH_USER']."-".epura_specialchars($_GET['page']);
		session_id($id);
		if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

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

// libero risorse
session_write_close();


// stampo
echo makepage($a, $log);


?>

