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
 * $_POST funge solo da corriere di dati tra una istanza e l'altra,
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
 * disabili l'inserimento manuale
 * mi mostri cio' che sto utilizzando
 * me la passi come input nascosto per il $_POST
 *
 *
 *
 * ALGORITMO:
 * 		1. definizione variabili
 * 		2. startup risorse
 * 			2a. $_SESSION
 * 			2b. mysql
 * 		3. test fine $_SESSION
 * 			3a. versa $_POST su $_SESSION
 * 		4. inizializza variabili
 * 			4a. tripla fornitore - tipo_doc - num_doc
 * 			4b. data carico
 * 			4c. trasportatore - ODA - note
 * 			4d. data_doc - nome_doc
 * 			4e. utente
 * 			4f. tripla tags - quantita' - posizione
 * 		5. test submit
 * 			5a. validazione
 * 				5aa. utente
 * 				5ab. tripla fornitore - tipo_doc - num_doc
 * 				5ac. data carico
 * 				5ad. tripla tags - quantita' - posizione
 * 			5b. test valid
 * 				5ba. scansione
 * 					5baa. exists_db
 * 					5bab. exists_file
 * 					5bac. upload
 * 				5bb. CARICO
 * 				5bc. reset tripla tags - quantita' - posizione
 * 		6. form
 * 		7. libero risorse
 * 		8. stampa
 *
 */




// 1. definizione variabili

$a = "";
$log = "";

$q1 = "SELECT * FROM vserv_contatti;";
$q2 = "SELECT * FROM vserv_tipodoc;";
$q3 = "SELECT * FROM vserv_numdoc;";
$q4 = "SELECT * FROM vserv_posizioni;";
$q5 = "SELECT * FROM vserv_numoda;";
//$q6 = "SELECT * FROM vserv_utenti;";
$magamanager = "<select name='utente'>\n<option selected='selected' value=''>Blank</option>\n<option value='Piscazzi'>Piscazzi</option>\n<option value='Manzo'>Manzo</option>\n<option value='Muratore'>Muratore</option>\n</select>\n";

$qbtags2 = "SELECT * FROM vserv_tags2;";
$qbtags3 = "SELECT * FROM vserv_tags3;";

$msg1 = "Mancata selezione di un utente per l'attivita' in corso (1)";
$msg2 = "Mancata selezione di un fornitore per l'attivita' in corso (2)";
$msg3 = "Mancata selezione di un tipo di documento per l'attivita' in corso (3)";
$msg4 = "Mancata selezione di un numero di documento per l'attivita' in corso (4)";
$msg5 = "Mancata selezione di una data cui far riferimento per l'attivita' in corso (5)";

$msg6 = "Mancato inserimento di tags per contrassegnare la merce in carico (6)";
$msg7 = "Mancato inserimento della quantita' per la merce in carico (7)";
$msg8 = "Mancato inserimento della posizione in magazzino per la merce in carico (8)";

$msg9 = "Sessione terminata, tutti i campi sono stati azzerati";

$msg10 = "Nessun file selezionato";
$msg11 = "Nessun file caricato perche' presente sul db (11)";
$msg12 = "Nessun file caricato perche' presente sul disco (12)";
$msg13 = "Scansione del documento caricata correttamente";
$msg14 = "Scansione del documento non caricata (14)";

$msg15 = "Carico inserito correttamente";

$msg16 = "Inserimento errato del campo quantita' (16)";

$registro = "aaa";
$valid = true;
$upload = true;



// 2. startup risorse

// 2a. $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// 2b. mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());



// 3. test fine $_SESSION
if (isset($_POST['stop'])) {
	
	$log .= remesg($msg9,"msg");
	
	$_SESSION = array();
	session_unset();
	session_destroy();
	
	/* generate new session id and delete old session in store */
	session_regenerate_id(true);
	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
	
	//$log .= remesg(session_status(),"msg");
	
} else {

	// 3a. versa $_POST su $_SESSION
	foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

}



// 4. inizializza variabili

// 4a. tripla fornitore - tipo_doc - num_doc
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

// 4b. data carico
if (isset($_SESSION['idata_carico'])AND(!empty($_SESSION['idata_carico'])))
	$data_carico = safe($_SESSION['idata_carico']);
else {
	if (isset($_SESSION['sdata_carico'])AND(!empty($_SESSION['sdata_carico'])))
		$data_carico = safe($_SESSION['sdata_carico']);
	else
		$data_carico = NULL;
}

// 4c. trasportatore - ODA - note
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

// 4d. data_doc - nome_doc
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

// 4e. utente
if (isset($_SESSION['utente'])AND(!empty($_SESSION['utente'])))
	$utente = safe($_SESSION['utente']);
else
	$utente = NULL;

// 4f. tripla tags - quantita' - posizione
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



// 5. test submit
if (isset($_SESSION['submit'])) {

	// 5a. validazione

	// 5aa. utente
	if (is_null($utente) OR empty($utente)) {
		$log .= remesg($msg1,"err");
		$valid = false;
	}

	// 5ab. tripla fornitore - tipo_doc - num_doc
	if (is_null($fornitore) OR empty($fornitore)) {
		$log .= remesg($msg2,"err");
		$valid = false;
	}

	if (is_null($tipo_doc) OR empty($tipo_doc)) {
		$log .= remesg($msg3,"err");
		$valid = false;
	}

	if (is_null($num_doc) OR empty($num_doc)) {
		$log .= remesg($msg4,"err");
		$valid = false;
	}

	// 5ac. data carico
	if (is_null($data_carico) OR empty($data_carico)) {
		$log .= remesg($msg5,"err");
		$valid = false;
	}

	// 5ad. tripla tags - quantita' - posizione
	if (is_null($tags) OR empty($tags)) {
		$log .= remesg($msg6,"err");
		$valid = false;
	}

	if (is_null($quantita) OR empty($quantita)) {
		$log .= remesg($msg7,"err");
		$valid = false;
	}
	
	if (!(testinteger($quantita))) {
		$log .= remesg($msg16,"err");
		$valid = false;
	}

	if (is_null($posizione) OR empty($posizione)) {
		$log .= remesg($msg8,"err");
		$valid = false;
	}



	// 5b. test valid
	if ($valid == true) {

		// 5ba. scansione
		if (empty($_FILES['scansione']['name'])) {
			$log .= remesg($msg10,"warn");
		} else
		{
			if ($_FILES['scansione']['size'] > 0) {

				/*
				// 5baa. exists_db
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

				// 5bab. exists_file
				$nome_doc = epura_specialchars(epura_space2underscore($tipo_doc))."-".epura_specialchars(epura_space2underscore($fornitore))."-".epura_specialchars(epura_space2underscore($num_doc)).".".getfilext($_FILES['scansione']['name']);
				if (file_exists($registro."/".$nome_doc)) {
					$log .= remesg($msg12,"warn");
					$upload = false;
				}

				// 5bac. upload
				if ($upload == true) {
					$moved = move_uploaded_file($_FILES['scansione']['tmp_name'], $registro."/".$nome_doc);
					if ($moved)
					  $log .= remesg($msg13,"msg");
					else
					  $log .= remesg($msg14,"err");
				} else
					$nome_doc = NULL;
			}

		}

		// 5bb. CARICO

		$call = "CALL CARICO('{$utente}','{$fornitore}','{$tipo_doc}','{$num_doc}','{$data_doc}','{$nome_doc}','{$tags}','{$quantita}','{$posizione}','{$data_carico}','{$note}','{$trasportatore}','{$num_oda}');";
		$log .= remesg($call,"msg");

		$res_carico = mysql_query($call);

		if ($res_carico)
			$log .= remesg($msg15,"msg");
		else
			die('Errore nell\'interrogazione del db: '.mysql_error());

		/* nb: fail @ mysql_free_result($res_carico); 
		 * Warning: mysql_free_result() expects parameter 1 to be resource, boolean given in
		 * You can't free the result of an INSERT query, since you can't free a boolean
		 */
		
		// 5bc. reset tripla tags - quantita' - posizione
		$tags = $quantita = $posizione = NULL;
		
		$_POST['tag1'] = $_POST['tag2'] = $_POST['tag3'] = NULL;
		$_SESSION['tag1'] = $_SESSION['tag2'] = $_SESSION['tag3'] = NULL;
		
		$_POST['itags'] = $_POST['stags'] = NULL;
		$_POST['iquantita'] = $_POST['squantita'] = NULL;
		$_POST['iposizione'] = $_POST['sposizione'] = NULL;
		
		$_SESSION['itags'] = $_SESSION['stags'] = NULL;
		$_SESSION['iquantita'] = $_SESSION['squantita'] = NULL;
		$_SESSION['iposizione'] = $_SESSION['sposizione'] = NULL;

	}
}



// 6. form
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=carico")."'>\n";
$a .= "<table>\n";

	$a .= "<caption>CARICO MERCE</caption>\n";

	$a .= "<thead><tr>\n";
		$a .= "<th>Descrizione</th>\n";
		$a .= "<th>Inserimento</th>\n";
		$a .= "<th>Suggerimento</th>\n";
	$a .= "</tr></thead>\n";

	$a .= "<tfoot>\n";
		$a .= "<tr>\n";
		$a .= "<td>Invio dati</td>\n";
		$a .= "<td>\n";
			$a .= "<input type='reset' name='reset' value='Azzera'/>\n";
			$a .= "<input type='submit' name='submit' value='Invia'/>\n";
			$a .= "<input type='submit' name='stop' value='Fine'/>\n";
		$a .= "</td>\n";
		$a .= "<td>\n";
			$a .= remesg("<b>Azzera</b> per il reset dei dati inseriti","warn");
			$a .= remesg("<b>Invia</b> per l'invio dei dati inseriti","msg");
			$a .= remesg("<b>Fine</b> per terminare l'attivita' in corso","msg");
		$a .= "</td>\n";
		$a .= "</tr>\n";
	$a .= "</tfoot>\n";

	$a .= "<tbody>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='utente'>Utente</label></td>\n";
		if (is_null($utente)) {
			$a .= "<td></td>\n";
			//$a .= "<td>".myoptlst("utente",$q6)."</td>\n";
			$a .= "<td>\n".$magamanager."</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("utente",$utente)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='ifornitore'>Fornitore</label></td>\n";
		if (is_null($fornitore)) {
			$a .= "<td><input type='text' name='ifornitore'/></td>\n";
			$a .= "<td>".myoptlst("sfornitore",$q1)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sfornitore",$fornitore)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='itrasportatore'>Trasportatore</label></td>\n";
		if (is_null($trasportatore)) {
			$a .= "<td><input type='text' name='itrasportatore'/></td>\n";
			$a .= "<td>".myoptlst("strasportatore",$q1)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("strasportatore",$trasportatore)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='itipo_doc'>Tipo documento</label></td>\n";
		if (is_null($tipo_doc)) {
			$a .= "<td><input type='text' name='itipo_doc'/></td>\n";
			$a .= "<td>".myoptlst("stipo_doc",$q2)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("stipo_doc",$tipo_doc)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='inum_doc'>Numero documento</label></td>\n";
		if (is_null($num_doc)) {
			$a .= "<td><input type='text' name='inum_doc'/></td>\n";
			$a .= "<td>".myoptlst("snum_doc",$q3)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("snum_doc",$num_doc)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='idata_doc'>Data documento</label></td>\n";
		if (is_null($data_doc)) {
			$a .= "<td></td>\n";
			$a .= "<td><input name='idata_doc' type='date' value='' class='date'/></td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sdata_doc",$data_doc)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='scansione'>Scansione documento</label></td>\n";
		if (is_null($nome_doc)) {
			$a .= "<td></td>\n";
			$a .= "<td><input type='file' name='scansione'/></td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("nome_doc",$nome_doc)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='itags'>TAGS merce</label></td>\n";
		if (is_null($tags)) {
			$a .= "<td><textarea rows='4' cols='auto' name='itags'></textarea></td>\n";
			$a .= "<td>\n";
				$a .= remesg("Per bretelle rame/fibra:","msg");
				$a .= input_hidden("tag1","BRETELLA")." \n";
				$a .= myoptlst("tag2",$qbtags2)." \n";
				$a .= myoptlst("tag3",$qbtags3)." \n";
			$a .= "</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("stags",$tags)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='iquantita'>Quantita'</label></td>\n";
		if (is_null($quantita) OR (!(testinteger($quantita)))) {
			$a .= "<td><input type='text' name='iquantita'/></td>\n";
			$a .= "<td></td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("squantita",$quantita)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='iposizione'>Posizione</label></td>\n";
		if (is_null($posizione)) {
			$a .= "<td><input type='text' name='iposizione'/></td>\n";
			$a .= "<td>".myoptlst("sposizione",$q4)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sposizione",$posizione)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='idata'>Data carico</label></td>\n";
		if (is_null($data_carico)) {
			$a .= "<td></td>\n";
			$a .= "<td><input name='idata_carico' type='date' value='' class='date'/></td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("sdata_carico",$data_carico)."</td>\n";
		}
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='inote'>Note</label></td>\n";
		if (is_null($note))
			$a .= "<td><textarea rows='4' cols='auto' name='inote'></textarea></td>\n";
		else
			$a .= "<td>".input_hidden("snote",$note)."</td>\n";
		$a .= "<td>\n";
			$a .= remesg("Campo ad inserimento libero per dettagli vari mirati","msg");
			$a .= remesg("al corretto recupero di informazioni a posteriori","msg");
		$a .= "</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='inum_oda'>Numero ODA</label></td>\n";
		if (is_null($num_oda)) {
			$a .= "<td><input type='text' name='inum_oda'/></td>\n";
			$a .= "<td>".myoptlst("num_oda",$q5)."</td>\n";
		} else {
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("snum_oda",$num_oda)."</td>\n";
		}
		$a .= "</tr>\n";

	$a .= "</tbody>\n";

$a .= "</table>\n";
$a .= "</form>\n";



// 7. libero risorse
mysql_close($conn);
session_write_close();



// 8. stampo
echo "<div id=\"log\">\n";
echo remesg("Notifiche","tit");
if ($log == "")
	echo remesg("nessuna notifica da visualizzare","msg");
else
	echo $log;
echo "</div>\n";

echo $a;



?>
