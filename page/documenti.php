<?php

// apri sessione
session_apri();

// variabili
$a = "";
$log = "";
$valid = true;
$utente = $_SERVER["PHP_AUTH_USER"];

// variabili: get
if (isset($_GET["debug"])) $DEBUG=true;
else $DEBUG=false;

if ($DEBUG) $log .= remesg("DEBUG ATTIVO","debug");
if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

// variabili: post
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

if ($DEBUG) $log .= "<pre>".var_dump($_POST)."</pre>";
if ($DEBUG) $log .= "<pre>".var_dump($_SESSION)."</pre>";

// variabili: session
$mittente = $_SESSION['imittente'] ?: $_SESSION['smittente'] ?: $_SESSION['mittente'] ?: NULL;
$tipo = $_SESSION['itipo'] ?: $_SESSION['stipo'] ?: $_SESSION['tipo'] ?: NULL;
$numero = $_SESSION['inumero'] ?: $_SESSION['snumero'] ?: $_SESSION['numero'] ?: NULL;
$data = $_SESSION['idata'] ?: $_SESSION['sdata'] ?: $_SESSION['data'] ?: NULL;
$scansione = $_SESSION['scansione'] ?: NULL;

$link_id_registro = $_SESSION['link_id_registro'] ?: NULL;
if ($link_id_registro)
	$gruppo = myquery::gruppo_da_documento($db,$link_id_registro);
else
	$gruppo = NULL;

if ($DEBUG) {
	if (isset($link_id_registro)) $log .= remesg("id_registro da cui prendere il gruppo: ".$link_id_registro,"debug");
	else $log .= remesg("link_id_registro nullo","debug");

	if (isset($gruppo)) $log .= remesg("gruppo ottenuto: ".$gruppo,"debug");
	else $log .= remesg("gruppo nullo","debug");
}

// test add da get
if (isset($_GET['add'])) $_SESSION['add']=true;


// menu: nuovo inserimento
$log .= remesg("<a href=\"?page=documenti&add\"\>Aggiungi nuovo</a>","action");


// 2. test bottoni

// test stop
if (isset($_SESSION['stop'])) {

	if ($DEBUG) $log .= remesg("Valore tasto STOP: ".$_SESSION['stop'],"debug");

	// reset variabili server
	session_riavvia();

	// alert
	$log .= remesg("Sessione terminata","done");

}

// test add||save
if ((isset($_SESSION['add'])) OR (isset($_SESSION['save']))) {

	if (isset($_SESSION['add']))
		if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_SESSION['add'],"debug");

	if (isset($_SESSION['save']))
		if ($DEBUG) $log .= remesg("Valore tasto SAVE: ".$_SESSION['save'],"debug");

	// validazione

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

		// UPLOAD
		if (empty($_FILES['scansione']['name'])) {
			
			$log .= remesg("Nessun file selezionato","warn");
			
		} else {
			
			if ($_FILES['scansione']['size'] > 0) {

				$scansione = epura_specialchars(epura_space2underscore($tipo))."-".epura_specialchars(epura_space2underscore($mittente))."-".epura_specialchars(epura_space2underscore($numero)).".".getfilext($_FILES['scansione']['name']);
				$filename = $_SERVER['DOCUMENT_ROOT'].registro.$scansione;
				
				if (file_exists(registro.$scansione)) {
					
					$log .= remesg("Nessun file caricato perche' presente sul disco","warn");
							
				} else {
					
					if (move_uploaded_file($_FILES['scansione']['tmp_name'], $filename)) {
						
						$log .= remesg("Scansione del documento caricata correttamente","done");
						
					} else {
						
						$log .= remesg("Scansione del documento non caricata","err");
						$scansione = NULL;
					}		
				}
			}
		}
		
		/* SP
		passo1: se gruppo nullo mi calcolo il prossimo disponibile
		passo2: se ho dato un id a cui collegare gli aggiorno il gruppo dato che vuoto in quella posizione
		passo3: inserisco i dati correnti
		*/
		
		if (is_null($gruppo) OR empty($gruppo)) 
			$gruppo = myquery::prossimo_gruppo($db)[0];

		if (isset($link_id_registro)AND(!empty($link_id_registro)))
			myquery::aggiornamento_registro($db,$link_id_registro,NULL,NULL,NULL,$gruppo,NULL,NULL);

		myquery::aggiornamento_registro($db,NULL,$mittente,$tipo,$numero,$gruppo,$data,$scansione);

		// reset
		unset($mittente,$tipo,$numero,$data,$scansione,$link_id_registro,$gruppo);
		session_riavvia();

	} else {
		
		// pre
		$lista_contatti = myquery::contatti($db); //pre1
		$lista_tipi_doc = myquery::tipi_doc($db); //pre2
		$lista_numdoc = myquery::numdoc($db); //pre3
		$lista_documenti_per_link = myquery::lista_documenti_con_id($db); //pre5

		// case not $valid
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=documenti");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		$a .= jsxdate;
		//$a .= jsaltrows;
		$a .= "<table class='altrowstable' id='alternatecolor'>\n";

			$log .= remesg("Pagina per la gestione di documenti di magazzino","info");

			$a .= "<thead><tr>\n";
				$a .= "<th>Descrizione</th>\n";
				$a .= "<th>Inserimento manuale</th>\n";
				$a .= "<th>Inserimento guidato</th>\n";
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
					$a .= "<td>".myoptlst("smittente",$lista_contatti)."</td>\n"; //pre1
				}
				$a .= "</tr>\n";

				$a .= "<tr>\n";

				if (isset($tipo)) {
					$a .= "<td><label for='tipo'>Tipo documento</label></td>\n";
					$a .= "<td colspan='2'>".input_hidden("stipo",$tipo)."</td>\n";
				} else {
					$a .= "<td><label for='tipo'>Tipo documento ".add_tooltip("Campo tipo di documento obbligatorio")."</label></td>\n";
					$a .= "<td><input type='text' name='itipo'></td>\n";
					$a .= "<td>".myoptlst("stipo",$lista_tipi_doc)."</td>\n"; //pre2
				}
				$a .= "</tr>\n";

				$a .= "<tr>\n";
				if (isset($numero)) {
					$a .= "<td><label for='numero'>Numero documento</label></td>\n";
					$a .= "<td colspan='2'>".input_hidden("snumero",htmlspecialchars($numero))."</td>\n";
				} else {
					$a .= "<td><label for='numero'>Numero documento ".add_tooltip("Campo numero di documento obbligatorio")."</label></td>\n";
					$a .= "<td><input type='text' name='inumero'></td>\n";
					$a .= "<td>".myoptlst("snumero",$lista_numdoc)."</td>\n"; //pre3
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

				// gruppo per associazione a documento, visualizzo il documento dato un id e passo di nascosto gruppo e id
				$a .= "<tr>\n";
				$a .= "<td><label for='associazione'>Associazione a documento</label></td>\n";
				if ((isset($link_id_registro)AND(!empty($link_id_registro)))) {
					
					$a .= "<td colspan='2'>".myquery::documento_da_id($db,$link_id_registro)['documento']."</td>\n"; //pre4: solo visualizzazione
					$a .= noinput_hidden("link_id_registro",$link_id_registro);
					
				} else {
					
					$a .= "<td colspan='2'>".myoptlst_double("link_id_registro",$lista_documenti_per_link)."</td>\n"; // pre5
				}
				$a .= "</tr>\n";

			$a .= "</tbody>\n";

		$a .= "</table>\n";
		$a .= "</form>\n";

	}

}



// 3. test contenuti
if (is_null($a) OR empty($a)) {

	// interrogazione
	$query = myquery::dati_per_aggiornamento_registro($db);

	// risultati
	$a .= jsxtable;
	//$a .= jsaltrows;
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";

	$a .= "<thead><tr>\n";
		$a .= "<th>Data</th>\n";
		$a .= "<th>Mittente</th>\n";
		$a .= "<th>Documento</th>\n";
		$a .= "<th>Scansione</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";
	
	foreach ($query as $row) {
				
		$a .= "<tr>\n";
		
		// data - data_ita
		if (is_null($row['data']) OR empty($row['data'])) {
			$a .= "<td>\n";
			$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=documenti");
			if ($DEBUG) $a .= "&debug";
			$a .= "'>\n";
			$a .= noinput_hidden("id_registro",$row['id_registro'])."\n";
			$a .= noinput_hidden("smittente",$row['contatto'])."\n";
			$a .= noinput_hidden("tipo",$row['tipo'])."\n";
			$a .= noinput_hidden("numero",$row['numero'])."\n";
			$a .= noinput_hidden("gruppo",$row['gruppo'])."\n";
			$a .= "<input type='submit' name='add' value='Aggiungi data'/>\n";
			$a .= "</form>\n";
			$a .= "</td>\n";
		} else {
			$a .= "<td>".$row['data_ita']."</td>\n";
		}
		
		// mittente
		$a .= "<td>".$row['contatto']."</td>\n";
		
		// documento
		$a .= "<td>".$row['documento']."</td>\n";
	
		// scansione
		if (is_null($row['scansione']) OR empty($row['scansione'])) {
			$a .= "<td>\n";
			$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=documenti");
			if ($DEBUG) $a .= "&debug";
			$a .= "'>\n";
			$a .= noinput_hidden("id_registro",$row['id_registro'])."\n";
			$a .= noinput_hidden("smittente",$row['contatto'])."\n";
			$a .= noinput_hidden("tipo",$row['tipo'])."\n";
			$a .= noinput_hidden("numero",$row['numero'])."\n";
			$a .= noinput_hidden("gruppo",$row['gruppo'])."\n";
			$a .= "<input type='submit' name='add' value='Aggiungi scansione'/>\n";
			$a .= "</form>\n";
			$a .= "</td>\n";
		} else {
			$a .= "<td>".$row['scansione']."</td>\n";
		}

		$a .= "</tr>\n";

	}
	
	$a .= "</tbody>\n</table>\n";
}

// 4. termino risorse
session_chiudi();

// 5. stampo
echo makepage($a, $log);

?>
