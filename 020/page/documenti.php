<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);



// 1. inizializza risorse

// $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// variabili

// globali
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

$a = "";
$log = "";

$valid = true;
$upload = true;

if ($DEBUG) $log .= remesg("DEBUG ATTIVO","debug");
if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

if ($DEBUG) $log .= "<pre>".var_dump($_POST)."</pre>";
if ($DEBUG) $log .= "<pre>".var_dump($_SESSION)."</pre>";


// utente
/*
 * if (isset($_SESSION['utente'])AND(!empty($_SESSION['utente'])))
 * 		$utente = safe($_SESSION['utente']);
 * else
 * 		$utente = NULL;
 */
$utente = $_SERVER["AUTHENTICATE_UID"];

/*
// id_registro
if (isset($_SESSION['id_registro'])AND(!empty($_SESSION['id_registro'])))
	$id_registro = safe($_SESSION['id_registro']);
else
	$id_registro = NULL;
*/

// tripla mittente - tipo - numero
if (isset($_SESSION['imittente'])AND(!empty($_SESSION['imittente'])))
	$mittente = safe($_SESSION['imittente']);
else {
	if (isset($_SESSION['smittente'])AND(!empty($_SESSION['smittente'])))
		$mittente = safe($_SESSION['smittente']);
	else
		$mittente = NULL;
}

if (isset($_SESSION['itipo'])AND(!empty($_SESSION['itipo'])))
	$tipo = safe($_SESSION['itipo']);
else {
	if (isset($_SESSION['stipo'])AND(!empty($_SESSION['stipo'])))
		$tipo = safe($_SESSION['stipo']);
	else
		$tipo = NULL;
}

if (isset($_SESSION['inumero'])AND(!empty($_SESSION['inumero'])))
	$numero = safe($_SESSION['inumero']);
else {
	if (isset($_SESSION['snumero'])AND(!empty($_SESSION['snumero'])))
		$numero = safe($_SESSION['snumero']);
	else
		$numero = NULL;
}

// data
if (isset($_SESSION['idata'])AND(!empty($_SESSION['idata'])))
	$data = safe($_SESSION['idata']);
else {
	if (isset($_SESSION['sdata'])AND(!empty($_SESSION['sdata'])))
		$data = safe($_SESSION['sdata']);
	else
		$data = NULL;
}

// link_id_registro - gruppo
if (isset($_SESSION['link_id_registro'])AND(!empty($_SESSION['link_id_registro']))) {
	$link_id_registro = safe($_SESSION['link_id_registro']);
	$gruppo = single_field_query("SELECT gruppo FROM REGISTRO WHERE id_registro='".$link_id_registro."';");
} else {
	$link_id_registro = NULL;
	$gruppo = NULL;
}

if ($DEBUG) {
	if (isset($link_id_registro)) $log .= remesg("id_registro da cui prendere il gruppo: ".$link_id_registro,"debug");
	else $log .= remesg("link_id_registro nullo","debug");

	if (isset($gruppo)) $log .= remesg("gruppo ottenuto: ".$gruppo,"debug");
	else $log .= remesg("gruppo nullo","debug");
}

// scansione
if (isset($_SESSION['scansione'])AND(!empty($_SESSION['scansione'])))
	$scansione = safe($_SESSION['scansione']);
else
	$scansione = NULL;


// nuovo inserimento
$log .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=documenti");
if ($DEBUG) $log .= "&debug";
$log .= "'>\n<input type='submit' name='add' value='Aggiungi nuovo'/>\n</form>\n";



// 2. test bottoni

// test stop
if (isset($_SESSION['stop'])) {

	if ($DEBUG) $log .= remesg("Valore tasto STOP: ".$_SESSION['stop'],"debug");

	// reset variabili server
	reset_sessione();

	// alert
	$log .= remesg("Sessione terminata","msg");

}

// test add||save
if ((isset($_SESSION['add'])) OR (isset($_SESSION['save']))) {

	if (isset($_SESSION['add']))
		if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_SESSION['add'],"debug");

	if (isset($_SESSION['save']))
		if ($DEBUG) $log .= remesg("Valore tasto SAVE: ".$_SESSION['save'],"debug");

	// validazione

	// utente
	if (is_null($utente) OR empty($utente)) {
		$log .= remesg("Mancata selezione di un utente per l'attivita' in corso","err");
		$valid = false;
	}
	if(!(in_array($utente, $enabled_users))){
		$log .= remesg("Utente non abilitato per l'attivita' in oggetto","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// mittente
	if (is_null($mittente) OR empty($mittente)) {
		//$log .= remesg("Mancato inserimento del mittente per il documento","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// tipo
	if (is_null($tipo) OR empty($tipo)) {
		//$log .= remesg("Mancata selezione di un tipo di documento","err");
		$valid = false;
	} elseif (strcmp($tipo,"Sistema")==0) {
		$log .= remesg("La selezione del tipo di documento Sistema e' riservata alle sole attivita' di sistema","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// numero
	if (is_null($numero) OR empty($numero)) {
		//$log .= remesg("Mancata selezione di un numero di documento","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// data
	if (is_null($data) OR empty($data)) {
		//$log .= remesg("Mancata selezione di una data cui far riferimento il documento","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// scansione
	if (is_null($scansione) OR empty($scansione)) {
		if (empty($_FILES['scansione']['name'])) {
			$log .= remesg("Nessun file selezionato","err");
			$valid = false;
		} elseif ($_FILES['scansione']['size'] == 0) {
			$log .= remesg("File selezionato vuoto o non valido","err");
			$valid = false;
		}
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");


	// test valid
	if ($valid) {

		// case $valid

		// scansione
		if (empty($_FILES['scansione']['name'])) {
			$log .= remesg("Nessun file selezionato","warn");
		} else
		{
			if ($_FILES['scansione']['size'] > 0) {

				/*
				// exists_db
				$q7 = "SELECT doc_exists('{$fornitore}','{$tipo_doc}','{$num_doc}') AS risultato";
				$res_q7 = mysql_query($q7);
				if (!$res_q7) die('Errore nell\'interrogazione del db: '.mysql_error());
				$exists_db = mysql_fetch_assoc($res_q7);
				mysql_free_result($res_q7);

				if ($exists_db['risultato'] == "1") {
					$log .= remesg("Nessun file caricato perche' presente sul db","warn");
					$upload = false;
				}
				*/

				// exists_file
				$scansione = epura_specialchars(epura_space2underscore($tipo))."-".epura_specialchars(epura_space2underscore($mittente))."-".epura_specialchars(epura_space2underscore($numero)).".".getfilext($_FILES['scansione']['name']);
				$filename = $_SERVER['DOCUMENT_ROOT'].registro.$scansione;
				if (file_exists(registro.$filename)) {
					$log .= remesg("Nessun file caricato perche' presente sul disco","warn");
					$upload = false;
				}

				// upload
				if ($upload == true) {
					$moved = move_uploaded_file($_FILES['scansione']['tmp_name'], $filename);
					if ($moved)
					  $log .= remesg("Scansione del documento caricata correttamente","msg");
					else
					  $log .= remesg("Scansione del documento non caricata","err");
				} else
					$scansione = NULL;


				// sp
				if (is_null($gruppo) OR empty($gruppo)) {

					$new_gruppo = single_field_query("SELECT MAX(gruppo)+1 FROM REGISTRO;");

					if (isset($link_id_registro)AND(!empty($link_id_registro))) {

						// call_link
						$call_link = "CALL aggiornamento_registro('{$link_id_registro}',NULL,NULL,NULL,'{$new_gruppo}',NULL,NULL,@myvar);";

						if ($DEBUG) $log .= remesg($call_link,"debug");
						$res = mysql_query($call_link);

						if ($res)
							$log .= remesg("Collegamento a documento creato nel database","msg");
						else
							die('Errore in creazione collegamento a documento: '.mysql_error());

						logging2($call_link,splog);
						mysql_free_result($res);

					}

					// call
					$call = "CALL aggiornamento_registro(NULL,'{$mittente}','{$tipo}','{$numero}','{$new_gruppo}','{$data}','{$scansione}',@myvar);";

					if ($DEBUG) $log .= remesg($call,"debug");
					$res = mysql_query($call);

					if ($res)
						$log .= remesg("Documento creato nel database","msg");
					else
						die('Errore in creazione documento dopo aggiornamento: '.mysql_error());

					logging2($call,splog);
					mysql_free_result($res);

				} else {

					// single call
					$call = "CALL aggiornamento_registro(NULL,'{$mittente}','{$tipo}','{$numero}','{$gruppo}','{$data}','{$scansione}',@myvar);";

					if ($DEBUG) $log .= remesg($call,"debug");
					$res = mysql_query($call);

					if ($res)
						$log .= remesg("Documento creato nel database","msg");
					else
						die('Errore in creazione documento: '.mysql_error());

					logging2($call,splog);
					mysql_free_result($res);

				}

				// reset
				unset($mittente,$tipo,$numero,$data,$scansione,$link_id_registro,$gruppo);
				reset_sessione();


			} // end test size>0

		} // end test name file not empty

	} else {

		// case not $valid
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=documenti");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		$a .= jsxdate;
		$a .= jsaltrows;
		$a .= "<table class='altrowstable' id='alternatecolor'>\n";

			$log .= remesg("Pagina per la gestione di documenti di magazzino","msg");

			$a .= "<thead><tr>\n";
				$a .= "<th>Descrizione</th>\n";
				$a .= "<th>Inserimento</th>\n";
				$a .= "<th>Suggerimento</th>\n";
			$a .= "</tr></thead>\n";

			$a .= "<tfoot>\n";
				$a .= "<tr>\n";
				$a .= "<td colspan='3'>\n";
					$a .= "<input type='reset' name='reset' value='Pulisci il foglio'/>\n";
					$a .= "<input type='submit' name='save' value='Salva'/>\n";
					$a .= "<input type='submit' name='stop' value='Esci senza salvare'/>\n";
				$a .= "</td>\n";
				$a .= "</tr>\n";
			$a .= "</tfoot>\n";

			$a .= "<tbody>\n";

				$a .= "<tr>\n";
				if (isset($mittente)) {
					$a .= "<td><label for='mittente'>Mittente documento</label></td>\n";
					$a .= "<td colspan='2'>".input_hidden("smittente",$mittente)."</td>\n";
				} else {
					$a .= "<td><label for='mittente'>Mittente documento ".add_tooltip("Campo mittente documento obbligatorio")."</label></td>\n";
					$a .= "<td><input type='text' name='imittente'></td>\n";
					$a .= "<td>".myoptlst("smittente",$vserv_contatti)."</td>\n";
				}
				$a .= "</tr>\n";

				$a .= "<tr>\n";

				if (isset($tipo)) {
					$a .= "<td><label for='tipo'>Tipo documento</label></td>\n";
					$a .= "<td colspan='2'>".input_hidden("stipo",$tipo)."</td>\n";
				} else {
					$a .= "<td><label for='tipo'>Tipo documento ".add_tooltip("Campo tipo di documento obbligatorio")."</label></td>\n";
					$a .= "<td><input type='text' name='itipo'></td>\n";
					$a .= "<td>".myoptlst("stipo",$vserv_tipodoc)."</td>\n";
				}
				$a .= "</tr>\n";

				$a .= "<tr>\n";
				if (isset($numero)) {
					$a .= "<td><label for='numero'>Numero documento</label></td>\n";
					$a .= "<td colspan='2'>".input_hidden("snumero",$numero)."</td>\n";
				} else {
					$a .= "<td><label for='numero'>Numero documento ".add_tooltip("Campo numero di documento obbligatorio")."</label></td>\n";
					$a .= "<td><input type='text' name='inumero'></td>\n";
					$a .= "<td>".myoptlst("snumero",$vserv_numdoc)."</td>\n";
				}
				$a .= "</tr>\n";

				$a .= "<tr>\n";
				if (isset($data)) {
					$a .= "<td><label for='data'>Data documento</label></td>\n";
					$a .= "<td colspan='2'>".input_hidden("sdata",$data)."</td>\n";
				} else {
					$a .= "<td><label for='data'>Data documento ".add_tooltip("Campo data documento obbligatorio")."</label></td>\n";
					$a .= "<td colspan='2'><input type='text' class='datepicker' name='sdata'/></td>\n";
				}
				$a .= "</tr>\n";

				$a .= "<tr>\n";
				if (isset($scansione)) {
					$a .= "<td><label for='scansione'>Scansione documento</label></td>\n";
					$a .= "<td colspan='2'>".input_hidden("scansione",$scansione)."</td>\n";
				} else {
					$a .= "<td><label for='scansione'>Scansione documento ".add_tooltip("Selezionare una scansione del documento")."</label></td>\n";
					$a .= "<td colspan='2'><input type='file' name='scansione'/></td>\n";
				}
				$a .= "</tr>\n";

				// gruppo
				$a .= "<tr>\n";
				$a .= "<td><label for='associazione'>Associazione a documento</label></td>\n";
				$a .= "<td colspan='2'>";
				if ((isset($link_id_registro)AND(!empty($link_id_registro)))) {

					$a .= single_field_query("SELECT documento FROM vserv_gruppi_doc WHERE id_registro='".$link_id_registro."';");
					if ((isset($gruppo)AND(!empty($gruppo))))
						$a .= noinput_hidden("gruppo",$gruppo);
					else
						$a .= noinput_hidden("link_id_registro",$link_id_registro);

				} else {

					$a .= "<select name='link_id_registro'>\n";
					$a .= "<option selected='selected' value=''>Blank</option>\n";
					$res = mysql_query($vserv_gruppi_doc);
					if (!$res) die('Errore in lista documenti: '.mysql_error());

					while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
						$a .= "<option value='".$row[0]."'>".$row[2];
						if (!empty($row[3])) $a .= " (del ".$row[3].")";
						$a .= "</option>\n";
					}

					mysql_free_result($res);
					$a .= "</select>";

				}
				$a .= "</td>\n";
				$a .= "</tr>\n";

			$a .= "</tbody>\n";

		$a .= "</table>\n";
		$a .= "</form>\n";

	}

}


// reset mysql connection
mysql_close($conn);
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());



// 3. test contenuti
if (is_null($a) OR empty($a)) {

	// interrogazione
	$query = "SELECT id_registro,data,DATE_FORMAT(data,'%d/%m/%Y'),contatto,CONCAT_WS(' - ',tipo,numero,gruppo) as documento,tipo,numero,gruppo,file FROM REGISTRO WHERE NOT tipo='MDS' AND NOT tipo='Sistema' ORDER BY data DESC;";
	$res = mysql_query($query);
	if (!$res) die('Errore nell\'interrogazione del db su '.$query.' con errore '.mysql_error());


	// risultati
	$a .= jsxtable;
	$a .= jsaltrows;
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";

	$a .= "<thead><tr>\n";
		$a .= "<th>Data</th>\n";
		$a .= "<th>Mittente</th>\n";
		$a .= "<th>Documento</th>\n";
		$a .= "<th>Scansione</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";

	while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
		$a .= "<tr>\n";
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=documenti");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		foreach ($row as $cname => $cvalue)

			switch ($cname) {

				case "0":
					$a .= noinput_hidden("id_registro",$cvalue)."\n";
					break;

				case "1":
					$data = $cvalue;
					break;

				case "2":
					if ($data != NULL) {
						$a .= noinput_hidden("sdata",$data)."\n";
						$a .= "<td>".$cvalue."</td>\n";
					} else
						$a .= "<td><input type='submit' name='add' value='Aggiungi data'/></td>\n";
					break;

				case "3":
					$a .= "<td>".input_hidden("smittente",$cvalue)."</td>\n";
					break;

				case "4":
					$a .= "<td>".$cvalue."</td>\n";
					break;

				case "5":
					$a .= noinput_hidden("stipo",$cvalue)."\n";
					break;

				case "6":
					$a .= noinput_hidden("snumero",$cvalue)."\n";
					break;

				case "7":
					$a .= noinput_hidden("sgruppo",$cvalue)."\n";
					break;

				case "8":
					if ($cvalue != NULL) {
						$a .= noinput_hidden("scansione",$cvalue)."\n";
						$a .= "<td><a href=\"".registro.$cvalue."\">".safetohtml($cvalue)."</a></td>\n";
					} else
						$a .= "<td><input type='submit' name='add' value='Aggiungi scansione'/></td>\n";
					break;

				default:
					$a .= "<td>".safetohtml($cvalue)."</td>\n";

			} // end switch

		$a .= "</form>\n</tr>\n";

	} // end while

	$a .= "</tbody>\n</table>\n";
	mysql_free_result($res);

}



// 4. termino risorse
mysql_close($conn);
session_write_close();



// 5. stampo
echo makepage($a, $log);

?>
