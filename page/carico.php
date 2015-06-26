<?php

/*
 * carico merce in magazzino, script frontend per stored procedure
 * CALL CARICO(utente, fornitore, tipo_doc, num_doc, data_doc, scansione,
 * tags, quantita, posizione, data_carico, note_carico, trasportatore, num_oda);
 *
 * $_SESSION permette carichi multipli "riciclando" di pagina in pagina valori
 * 	- la tripla [fornitore-tipo_doc-num_doc]
 * 	- data carico
 * 	- trasportatore
 * 	- ODA
 *	- note
 * 	- scansione* - data-doc*
 * non per la tripla [tags-quantita'-posizione]
 *
 * nb: scansione - data_doc seguono una logica diversa per il lato server
 *
 * $_SESSION permette anche di iterare la pagina laddove manchino dati
 *
 *
 * MINI-ALGORITMO DI VALORIZZAZIONE VARIABILI
 * $_POST viene usato come corriere per il passaggio dei valori nella pagina,
 * dopo il foreach punto 4a, valorizzo $_SESSION['myvar'] partendo
 * da due tipi della stessa variabile, imyvar ed smyvar
 * a seconda che sia un inserimento manuale o un suggerimento
 * if isset imyvar allora myvar = imyvar
 * elseif isset smyvar  allora myvar = smyvar
 * else myvar = null
 *
 *
 * MINI-ALGORITMO DI VALIDAZIONE VARIABILI
 * la validazione interverra' solo in quei casi di esito negativo al test
 * if is_null myvar allora
 * accoda un messaggio di allerta
 * cambia stato booleano ad una variabile semaforo
 * il CARICO verrà lanciato solo se al termine della validazione
 * nessun singolo test ha cambiato lo stato del semaforo
 *
 *
 * MINI-ALGORITMO DI INTERAZIONE VARIABILI VIA FORM
 * a seconda che myvar sia nulla o meno verranno stampati due formati di input
 * if myvar nulla allora devo inserirla
 * o da inserimento manuale(imyvar)
 * o da option list(smyvar)
 * altrimenti
 * disabiliti l'inserimento manuale
 * mi mostri cio' che sto utilizzando
 * me la passi come input nascosto per il $_POST
 *
 *
 *
 * ALGORITMO:
 *
 *
 * 		1. inizializzo risorse
 *
 * 			$_SESSION
 * 			mysql
 * 			variabili
 * 				globali
 * 				tripla fornitore - tipo_doc - num_doc
 * 				data carico
 * 				trasportatore - ODA - note
 * 				data_doc - nome_doc
 * 				utente (deprecato)
 * 				tripla tags - quantita' - posizione
 *
 * 		2. test bottoni
 *
 * 			stop
 * 				reset variabili server
 *
 * 			add||save
 * 				validazione
 * 					utente
 * 					tripla fornitore - tipo_doc - num_doc
 * 					data carico
 * 					reset tripla tags - quantita' - posizione
 *
 *				3. test valid
 * 					scansione
 * 						exists_db (deprecato)
 * 						exists_file
 * 						upload
 * 					CARICO
 * 					logging
 * 					reset tripla tags - quantita' - posizione client
 * 					test save
 * 						reset altre variabili client
 * 						reset variabili server
 *
 * 		4. form
 * 		5. termino risorse
 * 		6. stampo
 *
 *
 */



// 1. inizializzo risorse

// $_SESSION
session_apri();


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

// reintegro
if (isset($_GET["reintegro"])) {
	$_SESSION['ifornitore'] = "Accessi";
	$_SESSION['itipo_doc'] = "Reintegro";
	$_SESSION['inum_doc'] = myquery::next_reintegro_doc($db);
	$_SESSION['idata_doc'] = date('Y-m-d');

	if ($DEBUG) {
		$log .= remesg("Reintegro, valore fornitore: ".$_SESSION['ifornitore'],"debug");
		$log .= remesg("Reintegro, valore tipo_doc: ".$_SESSION['itipo_doc'],"debug");
		$log .= remesg("Reintegro, valore num_doc: ".$_SESSION['inum_doc'],"debug");
		$log .= remesg("Reintegro, valore data_doc: ".$_SESSION['idata_doc'],"debug");
	}
}

$log .= $menu_carico;

if ($DEBUG) $log .= "<pre>".var_dump($_SESSION)."</pre>";

// tripla fornitore - tipo_doc - num_doc
if (isset($_SESSION['ifornitore'])AND(!empty($_SESSION['ifornitore'])))
	$fornitore = epura_special2chars($_SESSION['ifornitore']);
else {
	if (isset($_SESSION['sfornitore'])AND(!empty($_SESSION['sfornitore'])))
		$fornitore = $_SESSION['sfornitore'];
	else
		$fornitore = NULL;
}

if ($DEBUG) {
	if (isset($fornitore)) $log .= remesg("Valore fornitore: ".$fornitore,"debug");
	if (isset($ifornitore)) $log .= remesg("Inserimento fornitore: ".$ifornitore,"debug");
	if (isset($sfornitore)) $log .= remesg("Suggerimento fornitore: ".$sfornitore,"debug");
}

if (isset($_SESSION['itipo_doc'])AND(!empty($_SESSION['itipo_doc'])))
	$tipo_doc = epura_special2chars($_SESSION['itipo_doc']);
else {
	if (isset($_SESSION['stipo_doc'])AND(!empty($_SESSION['stipo_doc'])))
		$tipo_doc = $_SESSION['stipo_doc'];
	else
		$tipo_doc = NULL;
}

if ($DEBUG) {
	if (isset($tipo_doc)) $log .= remesg("Valore tipo_doc: ".$tipo_doc,"debug");
	if (isset($itipo_doc)) $log .= remesg("Inserimento tipo_doc: ".$itipo_doc,"debug");
	if (isset($stipo_doc)) $log .= remesg("Suggerimento tipo_doc: ".$stipo_doc,"debug");
}

if (isset($_SESSION['inum_doc'])AND(!empty($_SESSION['inum_doc'])))
	$num_doc = epura_special2chars($_SESSION['inum_doc']);
else {
	if (isset($_SESSION['snum_doc'])AND(!empty($_SESSION['snum_doc'])))
		$num_doc = $_SESSION['snum_doc'];
	else
		$num_doc = NULL;
}

if ($DEBUG) {
	if (isset($num_doc)) $log .= remesg("Valore num_doc: ".$num_doc,"debug");
	if (isset($inum_doc)) $log .= remesg("Inserimento num_doc: ".$inum_doc,"debug");
	if (isset($snum_doc)) $log .= remesg("Suggerimento num_doc: ".$snum_doc,"debug");
}

// data carico
if (isset($_SESSION['idata_carico'])AND(!empty($_SESSION['idata_carico'])))
	$data_carico = $_SESSION['idata_carico'];
else {
	if (isset($_SESSION['sdata_carico'])AND(!empty($_SESSION['sdata_carico'])))
		$data_carico = $_SESSION['sdata_carico'];
	else
		$data_carico = NULL;
}

if ($DEBUG) {
	if (isset($data_carico)) $log .= remesg("Valore data_carico: ".$data_carico,"debug");
	if (isset($idata_carico)) $log .= remesg("Inserimento data_carico: ".$idata_carico,"debug");
	if (isset($sdata_carico)) $log .= remesg("Suggerimento data_carico: ".$sdata_carico,"debug");
}

// trasportatore - ODA - note
if (isset($_SESSION['itrasportatore'])AND(!empty($_SESSION['itrasportatore'])))
	$trasportatore = epura_special2chars($_SESSION['itrasportatore']);
else {
	if (isset($_SESSION['strasportatore'])AND(!empty($_SESSION['strasportatore'])))
		$trasportatore = $_SESSION['strasportatore'];
	else
		$trasportatore = NULL;
}

if ($DEBUG) {
	if (isset($trasportatore)) $log .= remesg("Valore trasportatore: ".$trasportatore,"debug");
	if (isset($itrasportatore)) $log .= remesg("Inserimento trasportatore: ".$itrasportatore,"debug");
	if (isset($strasportatore)) $log .= remesg("Suggerimento trasportatore: ".$strasportatore,"debug");
}

if (isset($_SESSION['inum_oda'])AND(!empty($_SESSION['inum_oda'])))
	$num_oda = $_SESSION['inum_oda'];
else {
	if (isset($_SESSION['snum_oda'])AND(!empty($_SESSION['snum_oda'])))
		$num_oda = $_SESSION['snum_oda'];
	else
		$num_oda = NULL;
}

if ($DEBUG) {
	if (isset($num_oda)) $log .= remesg("Valore num_oda: ".$num_oda,"debug");
	if (isset($inum_oda)) $log .= remesg("Inserimento num_oda: ".$inum_oda,"debug");
	if (isset($snum_oda)) $log .= remesg("Suggerimento num_oda: ".$snum_oda,"debug");
}

if (isset($_SESSION['inote'])AND(!empty($_SESSION['inote'])))
	$note = $_SESSION['inote'];
else {
	if (isset($_SESSION['snote'])AND(!empty($_SESSION['snote'])))
		$note = $_SESSION['snote'];
	else
		$note = NULL;
}

if ($DEBUG) {
	if (isset($note)) $log .= remesg("Valore note: ".$note,"debug");
	if (isset($inote)) $log .= remesg("Inserimento note: ".$inote,"debug");
	if (isset($snote)) $log .= remesg("Suggerimento note: ".$snote,"debug");
}

// data_doc - nome_doc
if (isset($_SESSION['idata_doc'])AND(!empty($_SESSION['idata_doc'])))
	$data_doc = $_SESSION['idata_doc'];
else {
	if (isset($_SESSION['sdata_doc'])AND(!empty($_SESSION['sdata_doc'])))
		$data_doc = $_SESSION['sdata_doc'];
	else
		$data_doc = NULL;
}

if ($DEBUG) {
	if (isset($data_doc)) $log .= remesg("Valore data_doc: ".$data_doc,"debug");
	if (isset($idata_doc)) $log .= remesg("Inserimento data_doc: ".$idata_doc,"debug");
	if (isset($sdata_doc)) $log .= remesg("Suggerimento data_doc: ".$sdata_doc,"debug");
}

if (isset($_SESSION['nome_doc'])AND(!empty($_SESSION['nome_doc'])))
	$nome_doc = $_SESSION['nome_doc'];
else
	$nome_doc = NULL;

if ($DEBUG) {
	if (isset($nome_doc)) $log .= remesg("Valore nome_doc: ".$nome_doc,"debug");
}

// utente
$utente = $_SERVER["PHP_AUTH_USER"];

// tripla tags - quantita' - posizione
if (isset($_SESSION['itags'])AND(!empty($_SESSION['itags'])))
	$tags = $_SESSION['itags'];
else {
	if (isset($_SESSION['tag2']) AND (!empty($_SESSION['tag2'])) AND isset($_SESSION['tag3']) AND (!empty($_SESSION['tag3']))) {
		$tags = $_SESSION['tag1']." ".$_SESSION['tag2']." ".$_SESSION['tag3'];
	} else
	{
		if (isset($_SESSION['stags'])AND(!empty($_SESSION['stags'])))
			$tags = $_SESSION['stags'];
		else
			$tags = NULL;
	}
}

if ($DEBUG) {
	if (isset($itags)) $log .= remesg("Inserimento tags: ".$itags,"debug");
	if (isset($tags)) $log .= remesg("Valore tags: ".$tags,"debug");
}

if (isset($_SESSION['iquantita'])AND(!empty($_SESSION['iquantita'])))
	$quantita = $_SESSION['iquantita'];
else {
	if (isset($_SESSION['squantita'])AND(!empty($_SESSION['squantita'])))
		$quantita = $_SESSION['squantita'];
	else
		$quantita = NULL;
}

if ($DEBUG) {
	if (isset($quantita)) $log .= remesg("Valore quantita: ".$quantita,"debug");
	if (isset($iquantita)) $log .= remesg("Inserimento quantita: ".$iquantita,"debug");
	if (isset($squantita)) $log .= remesg("Suggerimento quantita: ".$squantita,"debug");
}

if (isset($_SESSION['iposizione'])AND(!empty($_SESSION['iposizione'])))
	$posizione = epura_special2chars($_SESSION['iposizione']);
else {
	if (isset($_SESSION['sposizione'])AND(!empty($_SESSION['sposizione'])))
		$posizione = $_SESSION['sposizione'];
	else
		$posizione = NULL;
}

if ($DEBUG) {
	if (isset($posizione)) $log .= remesg("Valore posizione: ".$posizione,"debug");
	if (isset($iposizione)) $log .= remesg("Inserimento posizione: ".$iposizione,"debug");
	if (isset($sposizione)) $log .= remesg("Suggerimento posizione: ".$sposizione,"debug");
}


// 2. test bottoni

// stop
if (isset($_SESSION['stop'])) {
	
	// reset variabili client
	unset($tags, $quantita, $posizione);
	unset($fornitore, $trasportatore, $tipo_doc, $num_doc, $data_doc, $nome_doc, $data_carico, $note, $num_oda);
	
	// reset variabili server
	session_riavvia();
	
	// alert
	$log .= remesg("Sessione terminata","done");
}

// add||save
if ((isset($_SESSION['add'])) OR (isset($_SESSION['save']))) {

	// validazione

	// utente
	if (is_null($utente) OR empty($utente)) {
		$log .= remesg("Mancata selezione di un utente per l'attivita' in corso (errore 1)","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	/*if(!(in_array($utente, $enabled_users))){
		$log .= remesg("Utente non abilitato per l'attivita' in oggetto (errore 17)","err");
		$valid = false;
	}*/

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// tripla fornitore - tipo_doc - num_doc
	if (is_null($fornitore) OR empty($fornitore)) {
		//$log .= remesg("Mancata selezione di un fornitore per l'attivita' in corso (errore 2)","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if (is_null($tipo_doc) OR empty($tipo_doc)) {
		//$log .= remesg("Mancata selezione di un tipo di documento per l'attivita' in corso (errore 3)","err");
		$valid = false;
	} elseif (strcmp($tipo_doc,"Sistema")==0) {
		$log .= remesg("La selezione del tipo di documento Sistema e' riservata alle sole attivita' di sistema (errore 4)","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if (is_null($num_doc) OR empty($num_doc)) {
		//$log .= remesg("Mancata selezione di un numero di documento per l'attivita' in corso (errore 5)","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// data carico
	if (is_null($data_carico) OR empty($data_carico)) {
		//$log .= remesg("Mancata selezione di una data cui far riferimento per l'attivita' in corso (errore 6)","err");
		$valid = false;
	}

	// tripla tags - quantita' - posizione
	if (is_null($tags) OR empty($tags)) {
		//$log .= remesg("Mancato inserimento di tags per contrassegnare la merce in carico (errore 7)","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if (is_null($quantita) OR empty($quantita)) {
		//$log .= remesg("Mancato inserimento della quantita' per la merce in carico (errore 8)","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if (!(testinteger($quantita))) {
		//$log .= remesg("Inserimento errato del campo quantita' (errore 9)","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	if (is_null($posizione) OR empty($posizione)) {
		//$log .= remesg("Mancato inserimento della posizione in magazzino per la merce in carico (errore 10)","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");



	// 3. test valid
	if ($valid) {

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
				$nome_doc = epura_specialchars(epura_space2underscore($tipo_doc))."-".epura_specialchars(epura_space2underscore($fornitore))."-".epura_specialchars(epura_space2underscore($num_doc)).".".getfilext($_FILES['scansione']['name']);
				$filename = $_SERVER['DOCUMENT_ROOT'].registro.$nome_doc;
				if (file_exists(registro.$filename)) {
					$log .= remesg("Nessun file caricato perche' presente sul disco","warn");
					$upload = false;
				}

				// upload
				if ($upload == true) {
					$moved = move_uploaded_file($_FILES['scansione']['tmp_name'], $filename);
					if ($moved)
					  $log .= remesg("Scansione del documento caricata correttamente","done");
					else
					  $log .= remesg("Scansione del documento non caricata","err");
				} else
					$nome_doc = NULL;
			}

		}

		// CARICO
		myquery::carico($db,$utente,$fornitore,$tipo_doc,$num_doc,$data_doc,$nome_doc,$tags,$quantita,$posizione,$data_carico,$note,$trasportatore,$num_oda);
		$log .= remesg("Carico inviato il database","done");

		// reset tripla tags - quantita' - posizione client
		unset($tags, $quantita, $posizione);
		unset($_SESSION['tag1'], $_SESSION['tag2'], $_SESSION['tag3']);
		unset($_SESSION['itags'], $_SESSION['iquantita'], $_SESSION['iposizione']);
		unset($_SESSION['stags'], $_SESSION['squantita'], $_SESSION['sposizione']);

		// test save
		if (isset($_SESSION['save'])) {
			
			// reset altre variabili client
			unset($fornitore, $trasportatore, $tipo_doc, $num_doc, $data_doc, $nome_doc, $data_carico, $note, $num_oda);
			
			// reset variabili server
			session_riavvia();
		}

	}
}


// 4pre. query
$lista_contatti = myquery::contatti($db);
$lista_tipi_doc = myquery::tipi_doc($db);
$lista_numdoc = myquery::numdoc($db);
$lista_tags2 = myquery::tags2($db);
$lista_tags3 = myquery::tags3($db);
$lista_posizioni = myquery::posizioni($db);
$lista_numoda = myquery::numoda($db);


// 4. form
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=carico");
if ($DEBUG) $a .= "&debug";
$a .= "'>\n";
$a .= jsxdate;
//$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

	$a .= "<thead><tr>\n";
		$a .= "<th>Campo</th>\n";
		$a .= "<th>Inserimento manuale</th>\n";
		$a .= "<th>Inserimento guidato</th>\n";
	$a .= "</tr></thead>\n";

	$a .= "<tfoot>\n";
		$a .= "<tr>\n";
		$a .= "<td colspan='3'>\n";
			$a .= "<input type='reset' name='reset' value='Pulisci il foglio'/>\n";
			$a .= "<input type='submit' name='add' value='Salva e continua'/>\n";
			$a .= "<input type='submit' name='save' value='Salva'/>\n";
			$a .= "<input type='submit' name='stop' value='Esci senza salvare'/>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";
	$a .= "</tfoot>\n";

	$a .= "<tbody>\n";
		
		$a .= "<tr>\n";
		if (isset($fornitore)) {
			$a .= "<td><label for='ifornitore'>Fornitore</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sfornitore",$fornitore)."</td>\n";
		} else {
			$a .= "<td><label for='ifornitore'>Fornitore ".add_tooltip("Campo fornitore obbligatorio")."</label></td>\n";
			$a .= "<td><input type='text' name='ifornitore'/></td>\n";
			$a .= "<td>".myoptlst("sfornitore",$lista_contatti)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='itrasportatore'>Trasportatore</label></td>\n";
		if (isset($trasportatore)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("strasportatore",$trasportatore)."</td>\n";
		} else {
			$a .= "<td><input type='text' name='itrasportatore'/></td>\n";
			$a .= "<td>".myoptlst("strasportatore",$lista_contatti)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		if (isset($tipo_doc)) {
			$a .= "<td><label for='itipo_doc'>Tipo documento</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("stipo_doc",$tipo_doc)."</td>\n";
		} else {
			$a .= "<td><label for='itipo_doc'>Tipo documento ".add_tooltip("Campo tipo di documento obbligatorio")."</label></td>\n";
			$a .= "<td><input type='text' name='itipo_doc'/></td>\n";
			$a .= "<td>".myoptlst("stipo_doc",$lista_tipi_doc)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		if (isset($num_doc)) {
			$a .= "<td><label for='inum_doc'>Numero documento</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("snum_doc",$num_doc)."</td>\n";
		} else {
			$a .= "<td><label for='inum_doc'>Numero documento ".add_tooltip("Campo numero di documento obbligatorio")."</label></td>\n";
			$a .= "<td><input type='text' name='inum_doc'/></td>\n";
			$a .= "<td>".myoptlst("snum_doc",$lista_numdoc)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='idata_doc'>Data documento</label></td>\n";
		if (isset($data_doc)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sdata_doc",$data_doc)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			//$a .= "<td><input name='idata_doc' type='date' value='' class='date'/></td>\n";
			$a .= "<td><input type='text' class='datepicker' name='idata_doc'/></td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='scansione'>Scansione documento</label></td>\n";
		if (isset($nome_doc)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("nome_doc",$nome_doc)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td><input type='file' name='scansione'/></td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		if (isset($tags)) {
			$a .= "<td><label for='itags'>TAGS merce</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("stags",$tags)."</td>\n";
		} else {
			$a .= "<td><label for='itags'>TAGS merce ".add_tooltip("Campo tags merce obbligatorio")."</label></td>\n";
			$a .= "<td><textarea rows='4' cols='25' name='itags'></textarea></td>\n";
			$a .= "<td align=\"center\">\n";
				$a .= "<fieldset class=\"fieldform\">\n";
				$a .= remesg("Per bretelle rame/fibra:","info");
				$a .= input_hidden("tag1","BRETELLA")." \n";
				$a .= myoptlst("tag2",$lista_tags2)." \n";
				$a .= myoptlst("tag3",$lista_tags3)." \n";
				$a .= "</fieldset>\n";
			$a .= "</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		if (isset($quantita)) {
			if (testinteger($quantita)) {
				$a .= "<td><label for='iquantita'>Quantita'</label></td>\n";
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("squantita",$quantita)."</td>\n";
			} else {
				$a .= "<td><label for='iquantita'>Quantita' ".add_tooltip("Campo quantita' di tipo numerico")."</label></td>\n";
				$a .= "<td><input type='text' name='iquantita'/></td>\n";
				$a .= "<td></td>\n";
			}
		} else {
			$a .= "<td><label for='iquantita'>Quantita' ".add_tooltip("Campo quantita' obbligatorio")."</label></td>\n";
			$a .= "<td><input type='text' name='iquantita'/></td>\n";
			$a .= "<td></td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		if (isset($posizione)) {
			$a .= "<td><label for='iposizione'>Posizione</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sposizione",$posizione)."</td>\n";
		} else {
			$a .= "<td><label for='iposizione'>Posizione ".add_tooltip("Campo posizione obbligatorio")."</label></td>\n";
			$a .= "<td><input type='text' name='iposizione'/></td>\n";
			$a .= "<td>".myoptlst("sposizione",$lista_posizioni)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		if (isset($data_carico)) {
			$a .= "<td><label for='idata'>Data carico</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sdata_carico",$data_carico)."</td>\n";
		} else {
			$a .= "<td><label for='idata'>Data carico ".add_tooltip("Campo data carico obbligatorio")."</label></td>\n";
			$a .= "<td></td>\n";
			//$a .= "<td><input name='idata_carico' type='date' value='' class='date'/></td>\n";
			$a .= "<td><input type='text' class='datepicker' name='idata_carico'/></td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='inote'>Note</label></td>\n";
		if (isset($note))
			$a .= "<td>".input_hidden("snote",$note)."</td>\n";
		else
			$a .= "<td><textarea rows='4' cols='25' name='inote'></textarea></td>\n";
		$a .= "<td align=\"center\">\n";
		$a .= "<fieldset class=\"fieldform\">\n";
		$a .= remesg("Campo ad inserimento libero per dettagli mirati al recupero delle informazioni a posteriori","info");
		$a .= "</fieldset>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='inum_oda'>Numero ODA</label></td>\n";
		if (isset($num_oda)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("snum_oda",$num_oda)."</td>\n";
		} else {
			$a .= "<td><input type='text' name='inum_oda'/></td>\n";
			$a .= "<td>".myoptlst("snum_oda",$lista_numoda)."</td>\n";
		}
		$a .= "</tr>\n";

	$a .= "</tbody>\n";

$a .= "</table>\n";
$a .= "</form>\n";



// 5. termino risorse
session_chiudi();


// 6. stampo
echo makepage($a, $log);


?>
