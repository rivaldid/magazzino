<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializzo risorse

// $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

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

$log .= remesg("Torna alla <a href=\"?page=magazzino\">visualizzazione magazzino</a>","msg");

$utente = $_SERVER["AUTHENTICATE_UID"];

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
	$log .= remesg("Sessione terminata","msg");
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
		
		// call
		$call = "CALL aggiornamento_magazzino('{$utente}','{$id_merce}','{$posizione}','{$nuova_posizione}','{$quantita}','{$nuova_quantita}','{$data}');";
		$res = mysql_query($call);
		
		if ($res)
			$log .= remesg("Aggiornamento magazzino inviato al database","msg");
		else
			die('Errore nell\'invio dell\'aggiornamento al db: '.mysql_error());
		
		logging2($call,splog);
				
		// reset
		reset_sessione();
	
	} else {
		
		// form di inserimento
		$a .= jsxtable;
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=aggiornamento_magazzino");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		$a .= "<table class='altrowstable' id='alternatecolor'>\n";
		$log .= remesg("Acquisizione nuovi dati","msg");
		
		$a .= "<thead><tr>\n";
			$a .= "<th>Posizione</th>\n";
			$a .= "<th>TAGS</th>\n";
			$a .= "<th>Quantita'</th>\n";
			$a .= "<th>Azione</th>\n";
		$a .= "</tr></thead>\n";
		
		$a .= "<tbody>\n";
		
		$a .= "<tr>\n";
			$a .= "<td>".$posizione."</td>\n";
			$a .= "<td rowspan='3'>".$tags."</td>\n";
			$a .= "<td>".$quantita."</td>\n";
			$a .= "<td rowspan='2'><input type='submit' name='save' value='Salva'/></td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
			$a .= "<td>\n";
				$a .= "<input type='text' name='inuova_posizione'/>\n";
			$a .= "</td>\n";
			$a .= "<td rowspan='2'>\n";
				$a .= "<input type='text' name='nuova_quantita'/>\n";
			$a .= "</td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
			$a .= "<td>".myoptlst("snuova_posizione",$vserv_posizioni)."</td>\n";
			$a .= "<td><input type='submit' name='stop' value='Esci senza salvare'/></td>\n";
		$a .= "</tr>\n";

		$a .= "</tbody>\n</table>\n</form>\n";
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

	// ricevo lista merce
	$query = "SELECT * FROM vista_magazzino;";
	$result_lista_merce = mysql_query($query);
	if (!$result_lista_merce) die('Errore in ricezione lista merce dal db: '.mysql_error());

	// form selezione
	$a .= jsxtable;
	$a .= jsaltrows;
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";
	$log .= remesg("Lista estesa del contenuto del magazzino","msg");
	$a .= "<thead><tr>\n";
		$a .= "<th>Posizione</th>\n";
		$a .= "<th>TAGS</th>\n";
		$a .= "<th>Quantita'</th>\n";
		$a .= "<th>Azione</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";

	while ($row = mysql_fetch_array($result_lista_merce, MYSQL_NUM)) {
		$a .= "<tr>\n";
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=aggiornamento_magazzino");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		foreach ($row as $cname => $cvalue)

			switch ($cname) {
				
				case 0:
					$a .= noinput_hidden("id_merce",$cvalue);
					break;

				case 1:
					$a .= "<td>".input_hidden("posizione",$cvalue)."</td>\n";
					break;
				
				case 2:
					$a .= "<td>".input_hidden("tags",$cvalue)."</td>\n";
					break;

				case 3:
					$a .= "<td>".input_hidden("quantita",$cvalue)."</td>\n";
					break;

				default:
					//$a .= "<td>".$cvalue."</td>\n";
					$a .= "";
			}

		$a .= "<td><input type='submit' name='add' value='Aggiorna'/></td>\n";
		$a .= "</form>\n";
		$a .= "</tr>\n";
	}

	$a .= "</tbody>\n</table>\n";

	mysql_free_result($result_lista_merce);

}


// libero risorse
mysql_close($conn);
session_write_close();


// stampo
echo makepage($a, $log);


?>

