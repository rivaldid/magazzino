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
$q1 = "SELECT * FROM vserv_contatti;";
$q2 = "SELECT * FROM vserv_tipodoc;";
$q3 = "SELECT * FROM vserv_numdoc;";
$q4 = "SELECT * FROM vserv_posizioni;";
$q5 = "SELECT * FROM vserv_numoda;";
$q6 = "SELECT * FROM vserv_utenti;";
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
	if (is_null($utente) OR empty($utente)) {
		$a .= "<h3>Mancata selezione utente per attivita' in corso</h3>\n";
		$valid = false;
	}

	// 5ab. tripla fornitore - tipo_doc - num_doc
	if (is_null($fornitore) OR empty($fornitore)) {
		$a .= "<h3>Mancata selezione di un fornitore per attivita' in corso</h3>\n";
		$valid = false;
	}

	if (is_null($tipo_doc) OR empty($tipo_doc)) {
		$a .= "<h3>Mancata selezione di un tipo di documento per attivita' in corso</h3>\n";
		$valid = false;
	}

	if (is_null($num_doc) OR empty($num_doc)) {
		$a .= "<h3>Mancata selezione di un numero di documento per attivita' in corso</h3>\n";
		$valid = false;
	}

	// 5ac. data carico
	if (is_null($data_carico) OR empty($data_carico)) {
		$a .= "<h3>Mancata selezione di una data alla quale far riferimento per attivita' in corso</h3>\n";
		$valid = false;
	}

	// 5ad. tripla tags - quantita' - posizione
	if (is_null($tags) OR empty($tags)) {
		$a .= "<h3>Mancata selezione di tags per contrassegnare attivita' in corso</h3>\n";
		$valid = false;
	}

	if (is_null($quantita) OR empty($quantita)) {
		$a .= "<h3>Mancata selezione di una quantita' per attivita' in corso</h3>\n";
		$valid = false;
	}

	if (is_null($posizione) OR empty($posizione)) {
		$a .= "<h3>Mancata selezione di una posizione in magazzino per attivita' in corso</h3>\n";
		$valid = false;
	}

	// 5ae. scansione
	if (!(isset($_FILE['scansione'])) AND ($valid == true))
		$a .= "<h3>Nessun file selezionato</h3>\n";
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
				$a .= "<h3>La scansione del documento risultata gia' presente sul db, controllare</h3>\n";
				$upload = false;
			}

			// 5aeb. exists_file
			$nome_doc = epura_specialchars($tipo_doc)."-".epura_specialchars($fornitore)."-".epura_specialchars($num_doc).".".getfilext($_FILES['scansione']['name']);
			if (!(file_exists($registro."/".$nome_doc))) {
				$a .= "<h3>La scansione del documento risulta gia' presente sulla cartella remota, controllare</h3>\n";
				$upload = false;
			}

			// 5aec. upload
			if ($upload == true) {
				$moved = move_uploaded_file($_FILES['scansione']['tmp_name'], $registro."/".$nome_doc);
				if( $moved )
				  $a .= "<h3>Invio con successo del documento ".$nome_doc."</h3>\n";
				else
				  $a .= "<h3>Documento ".$nome_doc." non inviato</h3>\n";
			} else
				$nome_doc = NULL;
		}

	}

	// 5b. CARICO
	if ($valid == true) {
		echo $call = "CALL CARICO('{$utente}','{$fornitore}','{$tipo_doc}','{$num_doc}','{$data_doc}','{$nome_doc}','{$tags}','{$quantita}','{$posizione}','{$data_carico}','{$note}','{$trasportatore}','{$num_oda}');";
		$res_carico = mysql_query($call);
		if ($res_query)
			$a .= "<h3>La query ".$call." e' andata a buon fine</h3>\n";
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
echo $a;


?>
