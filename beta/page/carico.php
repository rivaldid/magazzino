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
 * ALGORITMO:
 * 		1. definizione variabili
 * 		2. startup risorse
 * 			2a. $_SESSION
 * 			2b. mysql
 * 		3. test $_SESSION
 * 		4. inizializzazione
 * 			4a. $_SESSION da $_POST
 * 			4b. variabili da $_SESSION
 * 				4ba. tripla fornitore - tipo_doc - num_doc
 * 				4bb. data carico
 * 				4bc. trasportatore - ODA - note
 * 				4bd. data_doc
 * 				4be. utente
 * 				4bf. tripla tags - quantita' - posizione
 * 		5. test submit
 * 			5a. validazione
 * 				5aa. utente
 * 				5ab. tripla fornitore - tipo_doc - num_doc
 * 				5ac. data carico
 * 				5ad. tripla tags - quantita' - posizione
 * 				5ae. scansione
 * 					5aea. exists_db
 * 					5aeb. exists_file
 * 					5aec. upload
 * 			5b. CARICO
 * 			5c. reset tripla tags - quantita' - posizione
 * 		6. form
 * 		7. libero risorse
 * 		8. stampa
 *
 */




// 	1. definizione variabili

$a = "";
$log = "";

$q1 = "SELECT * FROM vserv_contatti;";
$q2 = "SELECT * FROM vserv_tipodoc;";
$q3 = "SELECT * FROM vserv_numdoc;";
$q4 = "SELECT * FROM vserv_posizioni;";
$q5 = "SELECT * FROM vserv_numoda;";
$q6 = "SELECT * FROM vserv_utenti;";

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

$registro = "aaa";
$valid = true;
$upload = true;



// 2. startup risorse

// 2a. $_SESSION
session_start();

// 2b. mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());



// 3. test $_SESSION
if (isset($_SESSION['stop'])) {
	session_unset();
	session_destroy();
	$log .= remesg($msg9,"msg");
}



// 4. inizializzazione

// 4a. $_SESSION da $_POST
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

// 4b. variabili da $_SESSION

// 4ba. tripla fornitore - tipo_doc - num_doc
if (isset($_SESSION['fornitore']) AND (!empty($_SESSION['fornitore'])))
	$fornitore = safe($_SESSION['fornitore']);
else
	$fornitore = myoptlst("fornitore",$q1);

if (isset($_SESSION['tipo_doc']) AND (!empty($_SESSION['tipo_doc'])))
	$tipo_doc = safe($_SESSION['tipo_doc']);
else
	$tipo_doc = myoptlst("tipo_doc",$q2);

if (isset($_SESSION['num_doc']) AND (!empty($_SESSION['num_doc'])))
	$num_doc = safe($_SESSION['num_doc']);
else
	$num_doc = myoptlst("num_doc",$q3);

// 4bb. data carico
if (isset($_SESSION['data_carico']) AND (!empty($_SESSION['data_carico'])))
	$data_carico = safe($_SESSION['data_carico']);
else
	$data_carico = NULL;

// 4bc. trasportatore - ODA - note
if (isset($_SESSION['trasportatore'])AND (!empty($_SESSION['trasportatore'])))
	$trasportatore = safe($_SESSION['trasportatore']);
else
	$trasportatore = myoptlst("trasportatore",$q1);

if (isset($_SESSION['num_oda'])AND (!empty($_SESSION['num_oda'])))
	$num_oda = safe($_SESSION['num_oda']);
else
	$num_oda = myoptlst("num_oda",$q5);

if (isset($_SESSION['note'])AND (!empty($_SESSION['note'])))
	$note = safe($_SESSION['note']);
else
	$note = NULL;

// 4bd. data_doc
if (isset($_SESSION['data_doc'])AND (!empty($_SESSION['data_doc'])))
	$data_doc = safe($_SESSION['data_doc']);
else
	$data_doc = NULL;

// 4be. utente
if (isset($_SESSION['utente'])AND (!empty($_SESSION['utente'])))
	$utente = safe($_SESSION['utente']);
else
	$utente = myoptlst("utente",$q6);

// 4bf. tripla tags - quantita' - posizione
if (isset($_SESSION['tags'])AND (!empty($_SESSION['tags'])))
	$tags = safe($_SESSION['tags']);
else
	$tags = NULL;

if (isset($_SESSION['quantita'])AND (!empty($_SESSION['quantita'])))
	$quantita = safe($_SESSION['quantita']);
else
	$quantita = NULL;

if (isset($_SESSION['posizione'])AND (!empty($_SESSION['posizione'])))
	$posizione = safe($_SESSION['posizione']);
else
	$posizione = myoptlst("posizione",$q4);



// 5. test submit
if (isset($_SESSION['submit'])) {

	// 5a. validazione
	
	// 5aa. utente
	if (is_null($utente) OR empty($utente) OR isoptlst($utente)) {
		$log .= remesg($msg1,"err");
		$valid = false;
	}

	// 5ab. tripla fornitore - tipo_doc - num_doc
	if (is_null($fornitore) OR empty($fornitore) OR isoptlst($fornitore)) {
		$log .= remesg($msg2,"err");
		$valid = false;
	}

	if (is_null($tipo_doc) OR empty($tipo_doc) OR isoptlst($tipo_doc)) {
		$log .= remesg($msg3,"err");
		$valid = false;
	}

	if (is_null($num_doc) OR empty($num_doc) OR isoptlst($num_doc)) {
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

	if (is_null($posizione) OR empty($posizione) OR isoptlst($posizione)) {
		$log .= remesg($msg8,"err");
		$valid = false;
	}

	// 5ae. scansione
	//if (!(isset($_FILE['scansione'])) AND ($valid == true))
	if(isset($_FILES['scansione']) && count($_FILES['scansione']['error']) == 1 && $_FILES['scansione']['error'][0] > 0)
		$log .= remesg($msg10,"warn");
	else
		{

		if ($_FILES['scansione']['size'] > 0) {

			// 5aea. exists_db
			$q7 = "SELECT doc_exists('{$fornitore}','{$tipo_doc}','{$num_doc}') AS risultato";
			$res_q7 = mysql_query($q7);
			if (!$res_q7) die('Errore nell\'interrogazione del db: '.mysql_error());
			$exists_db = mysql_fetch_assoc($res_q7);
			mysql_free_result($res_q7);

			if ($exists_db['risultato'] == "1") {
				$log .= remesg($msg11,"err");
				$upload = false;
			}

			// 5aeb. exists_file
			$nome_doc = epura_specialchars($tipo_doc)."-".epura_specialchars($fornitore)."-".epura_specialchars($num_doc).".".getfilext($_FILES['scansione']['name']);
			if (!(file_exists($registro."/".$nome_doc))) {
				$log .= remesg($msg12,"err");
				$upload = false;
			}

			// 5aec. upload
			if ($upload == true) {
				$moved = move_uploaded_file($_FILES['scansione']['tmp_name'], $registro."/".$nome_doc);
				if( $moved )
				  $log .= remesg($msg13,"err");
				else
				  $log .= remesg($msg14,"err");
			} else
				$nome_doc = NULL;
		}

	}

	// 5b. CARICO
	if ($valid == true) {
		echo $call = "CALL CARICO('{$utente}','{$fornitore}','{$tipo_doc}','{$num_doc}','{$data_doc}','{$nome_doc}','{$tags}','{$quantita}','{$posizione}','{$data_carico}','{$note}','{$trasportatore}','{$num_oda}');";
		$res_carico = mysql_query($call);
		if ($res_query)
			$log .= remesg($msg15,"msg");
		else
			die('Errore nell\'interrogazione del db: '.mysql_error());

		mysql_free_result($res_carico);

		// 5c. reset tripla tags - quantita' - posizione
		$tags = $quantita = $posizione = NULL;

	}
}



// 6. form
$a .= "<form name='carico' method='post' enctype='multipart/form-data' action='".htmlentities("?page=carico")."'>\n";
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
			$a .= "<input type='reset' name='reset' value='Clear'/>\n";
			$a .= "<input type='submit' name='stop' value='Stop'/>\n";
			$a .= "<input type='submit' name='submit' value='Submit'/>\n";
		$a .= "</td>\n";
		$a .= "<td>\n";
			$a .= "<p><b>Clear</b> per il reset dei dati appena inseriti</p>\n";
			$a .= "<p><b>Stop</b> per il reset di tutti i dati della pagina</p>\n";
			$a .= "<p><b>Submit</b> per l'invio dei dati al server</p>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";
	$a .= "</tfoot>\n";

	$a .= "<tbody>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='utente'>Utente</label></td>\n";
		$a .= "<td></td>\n";
		$a .= "<td>".$utente."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='fornitore'>Fornitore</label></td>\n";
		$a .= "<td><input type='text' name='fornitore'/></td>\n";
		$a .= "<td>".$fornitore."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='trasportatore'>Trasportatore</label></td>\n";
		$a .= "<td><input type='text' name='trasportatore'/></td>\n";
		$a .= "<td>".$trasportatore."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='tipo_doc'>Tipo documento</label></td>\n";
		$a .= "<td><input type='text' name='tipo_doc'/></td>\n";
		$a .= "<td>".$tipo_doc."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='num_doc'>Numero documento</label></td>\n";
		$a .= "<td><input type='text' name='num_doc'/></td>\n";
		$a .= "<td>".$num_doc."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='data_doc'>Data documento</label></td>\n";
		$a .= "<td><input name='data_doc' type='date' value='' class='date'/></td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='scansione'>Scansione documento</label></td>\n";
		$a .= "<td><input type='file' name='scansione'/></td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='tags'>TAGS</label></td>\n";
		$a .= "<td><textarea rows='4' cols='auto' name='tags'></textarea></td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='quantita'>Quantita'</label></td>\n";
		$a .= "<td><input type='text' name='quantita'/></td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='posizione'>Posizione</label></td>\n";
		$a .= "<td><input type='text' name='posizione'/></td>\n";
		$a .= "<td>".$posizione."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='data'>Data</label></td>\n";
		$a .= "<td><input name='data' type='date' value='' class='date'/></td>\n";
		$a .= "<td>".$data_carico."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='note'>Note</label></td>\n";
		$a .= "<td><textarea rows='4' cols='auto' name='note'></textarea></td>\n";
		$a .= "<td>\n";
			$a .= "<p>Campo ad inserimento libero per dettagli vari mirati</p>\n";
			$a .= "<p>al corretto recupero di informazioni a posteriori</p>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='num_oda'>Numero ODA</label></td>\n";
		$a .= "<td><input type='text' name='num_oda'/></td>\n";
		$a .= "<td>".$num_oda."</td>\n";
		$a .= "</tr>\n";

	$a .= "</tbody>\n";

$a .= "</table>\n";
$a .= "</form>\n";



// 7. libero risorse
mysql_close($conn);
session_write_close();



// 8. stampo
echo "<div id=\"log\">\n".$log."</div>\n";
echo $a;


?>
