<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


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
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// variabili

// globali
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
$a = "";
$log = "";

$valid = true;
$upload = true;

// tripla fornitore - tipo_doc - num_doc
if (isset($_SESSION['ifornitore'])AND(!empty($_SESSION['ifornitore'])))
	$fornitore = safe($_SESSION['ifornitore']);
else {
	if (isset($_SESSION['sfornitore'])AND(!empty($_SESSION['sfornitore'])))
		$fornitore = safe($_SESSION['sfornitore']);
	else
		$fornitore = NULL;
}

if (isset($_SESSION['itipo_doc'])AND(!empty($_SESSION['itipo_doc'])))
	$tipo_doc = safe($_SESSION['itipo_doc']);
else {
	if (isset($_SESSION['stipo_doc'])AND(!empty($_SESSION['stipo_doc'])))
		$tipo_doc = safe($_SESSION['stipo_doc']);
	else
		$tipo_doc = NULL;
}

if (isset($_SESSION['inum_doc'])AND(!empty($_SESSION['inum_doc'])))
	$num_doc = safe($_SESSION['inum_doc']);
else {
	if (isset($_SESSION['snum_doc'])AND(!empty($_SESSION['snum_doc'])))
		$num_doc = safe($_SESSION['snum_doc']);
	else
		$num_doc = NULL;
}

// data carico
if (isset($_SESSION['idata_carico'])AND(!empty($_SESSION['idata_carico'])))
	$data_carico = safe($_SESSION['idata_carico']);
else {
	if (isset($_SESSION['sdata_carico'])AND(!empty($_SESSION['sdata_carico'])))
		$data_carico = safe($_SESSION['sdata_carico']);
	else
		$data_carico = NULL;
}

// trasportatore - ODA - note
if (isset($_SESSION['itrasportatore'])AND(!empty($_SESSION['itrasportatore'])))
	$trasportatore = safe($_SESSION['itrasportatore']);
else {
	if (isset($_SESSION['strasportatore'])AND(!empty($_SESSION['strasportatore'])))
		$trasportatore = safe($_SESSION['strasportatore']);
	else
		$trasportatore = NULL;
}

if (isset($_SESSION['inum_oda'])AND(!empty($_SESSION['inum_oda'])))
	$num_oda = safe($_SESSION['inum_oda']);
else {
	if (isset($_SESSION['snum_oda'])AND(!empty($_SESSION['snum_oda'])))
		$num_oda = safe($_SESSION['snum_oda']);
	else
		$num_oda = NULL;
}

if (isset($_SESSION['inote'])AND(!empty($_SESSION['inote'])))
	$note = safe($_SESSION['inote']);
else {
	if (isset($_SESSION['snote'])AND(!empty($_SESSION['snote'])))
		$note = safe($_SESSION['snote']);
	else
		$note = NULL;
}

// data_doc - nome_doc
if (isset($_SESSION['idata_doc'])AND(!empty($_SESSION['idata_doc'])))
	$data_doc = safe($_SESSION['idata_doc']);
else {
	if (isset($_SESSION['sdata_doc'])AND(!empty($_SESSION['sdata_doc'])))
		$data_doc = safe($_SESSION['sdata_doc']);
	else
		$data_doc = NULL;
}

if (isset($_SESSION['nome_doc'])AND(!empty($_SESSION['nome_doc'])))
	$nome_doc = safe($_SESSION['nome_doc']);
else
	$nome_doc = NULL;

// utente
/*
 * if (isset($_SESSION['utente'])AND(!empty($_SESSION['utente'])))
 * 		$utente = safe($_SESSION['utente']);
 * else
 * 		$utente = NULL;
 */
$utente = $_SERVER["AUTHENTICATE_UID"];

// tripla tags - quantita' - posizione
if (isset($_SESSION['itags'])AND(!empty($_SESSION['itags'])))
	$tags = safe($_SESSION['itags']);
else {
	if (isset($_SESSION['tag2']) AND (!empty($_SESSION['tag2'])) AND isset($_SESSION['tag3']) AND (!empty($_SESSION['tag3']))) {
		$tags = safe($_SESSION['tag1'])." ".safe($_SESSION['tag2'])." ".safe($_SESSION['tag3']);
	} else
	{
		if (isset($_SESSION['stags'])AND(!empty($_SESSION['stags'])))
			$tags = safe($_SESSION['stags']);
		else
			$tags = NULL;
	}
}

if (isset($_SESSION['iquantita'])AND(!empty($_SESSION['iquantita'])))
	$quantita = safe($_SESSION['iquantita']);
else {
	if (isset($_SESSION['squantita'])AND(!empty($_SESSION['squantita'])))
		$quantita = safe($_SESSION['squantita']);
	else
		$quantita = NULL;
}

if (isset($_SESSION['iposizione'])AND(!empty($_SESSION['iposizione'])))
	$posizione = safe($_SESSION['iposizione']);
else {
	if (isset($_SESSION['sposizione'])AND(!empty($_SESSION['sposizione'])))
		$posizione = safe($_SESSION['sposizione']);
	else
		$posizione = NULL;
}



// 2. test bottoni

// stop
if (isset($_SESSION['stop'])) {
	// reset variabili server
	reset_sessione();
}

// add||save
if ((isset($_SESSION['add'])) OR (isset($_SESSION['save']))) {

	// validazione

	// utente
	if (is_null($utente) OR empty($utente)) {
		$log .= remesg("Mancata selezione di un utente per l'attivita' in corso (errore 1)","err");
		$valid = false;
	}
	if(!(in_array($utente, $enabled_users))){
		$log .= remesg($msg17,"err");
		$valid = false;
	}

	// tripla fornitore - tipo_doc - num_doc
	if (is_null($fornitore) OR empty($fornitore)) {
		$log .= remesg("Mancata selezione di un fornitore per l'attivita' in corso (errore 2)","err");
		$valid = false;
	}

	if (is_null($tipo_doc) OR empty($tipo_doc)) {
		$log .= remesg("Mancata selezione di un tipo di documento per l'attivita' in corso (errore 3)","err");
		$valid = false;
	} elseif (strcmp($tipo_doc,"Sistema")==0) {
		$log .= remesg("La selezione del tipo di documento Sistema e' riservata alle sole attivita' di sistema (errore 4)","err");
		$valid = false;
	}

	if (is_null($num_doc) OR empty($num_doc)) {
		$log .= remesg("Mancata selezione di un numero di documento per l'attivita' in corso (errore 5)","err");
		$valid = false;
	}

	// data carico
	if (is_null($data_carico) OR empty($data_carico)) {
		$log .= remesg("Mancata selezione di una data cui far riferimento per l'attivita' in corso (errore 6)","err");
		$valid = false;
	}

	// tripla tags - quantita' - posizione
	if (is_null($tags) OR empty($tags)) {
		$log .= remesg("Mancato inserimento di tags per contrassegnare la merce in carico (errore 7)","err");
		$valid = false;
	}

	if (is_null($quantita) OR empty($quantita)) {
		$log .= remesg("Mancato inserimento della quantita' per la merce in carico (errore 8)","err");
		$valid = false;
	}

	if (!(testinteger($quantita))) {
		$log .= remesg("Inserimento errato del campo quantita' (errore 9)","err");
		$valid = false;
	}

	if (is_null($posizione) OR empty($posizione)) {
		$log .= remesg("Mancato inserimento della posizione in magazzino per la merce in carico (errore 10)","err");
		$valid = false;
	}



	// 3. test valid
	if ($valid) {

		// scansione
		if (empty($_FILES['scansione']['name'])) {
			$log .= remesg($msg10,"warn");
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
					$log .= remesg($msg11,"warn");
					$upload = false;
				}
				*/

				// exists_file
				$nome_doc = epura_specialchars(epura_space2underscore($tipo_doc))."-".epura_specialchars(epura_space2underscore($fornitore))."-".epura_specialchars(epura_space2underscore($num_doc)).".".getfilext($_FILES['scansione']['name']);
				$filename = $_SERVER['DOCUMENT_ROOT'].registro.$nome_doc;
				if (file_exists(registro.$filename)) {
					$log .= remesg($msg12,"warn");
					$upload = false;
				}

				// upload
				if ($upload == true) {
					$moved = move_uploaded_file($_FILES['scansione']['tmp_name'], $filename);
					if ($moved)
					  $log .= remesg($msg13,"msg");
					else
					  $log .= remesg($msg14,"err");
				} else
					$nome_doc = NULL;
			}

		}

		// CARICO

		$call = "CALL CARICO('{$utente}','{$fornitore}','{$tipo_doc}','{$num_doc}','{$data_doc}','{$nome_doc}','{$tags}','{$quantita}','{$posizione}','{$data_carico}','{$note}','{$trasportatore}','{$num_oda}');";
		//$log .= remesg($call,"msg");

		$res_carico = mysql_query($call);

		if ($res_carico)
			$log .= remesg($msg15,"msg");
		else
			die('Errore nell\'interrogazione del db: '.mysql_error());

		/* nb: fail @ mysql_free_result($res_carico);
		 * Warning: mysql_free_result() expects parameter 1 to be resource, boolean given in
		 * You can't free the result of an INSERT query, since you can't free a boolean
		 */

		// logging
		logging2($call,splog);

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
			reset_sessione();
		}

	}
}



// 4. form
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=carico")."'>\n";
$a .= jsxdate;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

	//$a .= "<caption>CARICO MERCE</caption>\n";
	$log .= remesg("Pagina per il carico della merce in magazzino","msg");

	$a .= "<thead><tr>\n";
		$a .= "<th>Descrizione</th>\n";
		$a .= "<th>Inserimento</th>\n";
		$a .= "<th>Suggerimento</th>\n";
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
		$a .= "<td><label for='utente'>Utente</label></td>\n";
		if (isset($utente)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("utente",$utente)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			//$a .= "<td>".myoptlst("utente",$q6)."</td>\n";
			$a .= "<td>\n".$magamanager."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='ifornitore'>Fornitore</label></td>\n";
		if (isset($fornitore)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sfornitore",$fornitore)."</td>\n";
		} else {
			$a .= "<td><input type='text' name='ifornitore'/></td>\n";
			$a .= "<td>".myoptlst("sfornitore",$vserv_contatti)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='itrasportatore'>Trasportatore</label></td>\n";
		if (isset($trasportatore)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("strasportatore",$trasportatore)."</td>\n";
		} else {
			$a .= "<td><input type='text' name='itrasportatore'/></td>\n";
			$a .= "<td>".myoptlst("strasportatore",$vserv_contatti)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='itipo_doc'>Tipo documento</label></td>\n";
		if (isset($tipo_doc)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("stipo_doc",$tipo_doc)."</td>\n";
		} else {
			$a .= "<td><input type='text' name='itipo_doc'/></td>\n";
			$a .= "<td>".myoptlst("stipo_doc",$vserv_tipodoc)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='inum_doc'>Numero documento</label></td>\n";
		if (isset($num_doc)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("snum_doc",$num_doc)."</td>\n";
		} else {
			$a .= "<td><input type='text' name='inum_doc'/></td>\n";
			$a .= "<td>".myoptlst("snum_doc",$vserv_numdoc)."</td>\n";
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
		$a .= "<td><label for='itags'>TAGS merce</label></td>\n";
		if (isset($tags)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("stags",$tags)."</td>\n";
		} else {
			$a .= "<td><textarea rows='4' cols='25' name='itags'></textarea></td>\n";
			$a .= "<td>\n";
				$a .= remesg("Per bretelle rame/fibra:","msg");
				$a .= input_hidden("tag1","BRETELLA")." \n";
				$a .= myoptlst("tag2",vserv_tags2)." \n";
				$a .= myoptlst("tag3",vserv_tags3)." \n";
			$a .= "</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='iquantita'>Quantita'</label></td>\n";
		if (isset($quantita)) {
			if (testinteger($quantita)) {
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("squantita",$quantita)."</td>\n";
			} else {
				$a .= "<td><input type='text' name='iquantita'/></td>\n";
				$a .= "<td></td>\n";
			}
		} else {
			$a .= "<td><input type='text' name='iquantita'/></td>\n";
			$a .= "<td></td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='iposizione'>Posizione</label></td>\n";
		if (isset($posizione)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sposizione",$posizione)."</td>\n";
		} else {
			$a .= "<td><input type='text' name='iposizione'/></td>\n";
			$a .= "<td>".myoptlst("sposizione",$vserv_posizioni)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='idata'>Data carico</label></td>\n";
		if (isset($data_carico)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sdata_carico",$data_carico)."</td>\n";
		} else {
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
		$a .= "<td>\n";
			$a .= remesg($msg19,"msg");
			$a .= remesg($msg20,"msg");
		$a .= "</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='inum_oda'>Numero ODA</label></td>\n";
		if (isset($num_oda)) {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("snum_oda",$num_oda)."</td>\n";
		} else {
			$a .= "<td><input type='text' name='inum_oda'/></td>\n";
			$a .= "<td>".myoptlst("num_oda",$vserv_numoda)."</td>\n";
		}
		$a .= "</tr>\n";

	$a .= "</tbody>\n";

$a .= "</table>\n";
$a .= "</form>\n";



// 5. termino risorse
mysql_close($conn);
session_write_close();



// 6. stampo
echo "<div id=\"log\">\n";
echo remesg("Notifiche","tit");
echo remesg("Autenticato come ".$_SERVER["AUTHENTICATE_UID"]." alle ".date('H:i')." del ".date('d/m/Y'),"msg");
if (isset($log)) {
	if ($log == "")
		echo remesg($msg18,"msg");
	else
		echo $log;
}
echo "</div>\n";

echo $a;



?>
