<?php

/*
 * 
 * carico merce in magazzino, frontend per stored procedure 
 * 
 * 	CALL CARICO(utente, fornitore, tipo_doc, num_doc, data_doc, scansione, 
 * 				tags, quantita, posizione, data_carico, note_carico, trasportatore, num_oda);
 * 
 * $_SESSION permette carichi multi passando di pagina in pagina qualora definiti
 * 		fornitore - tipo_doc - num_doc - data_carico - note_carico - trasportatore - num_oda
 * 
 * $_SESSION permette anche il passaggio di altri campi obbligatori
 * qualora parzialmente forniti per agevolare il completamento
 * 		tags - quantita - posizione
 * 
 * ad ogni submit verrà ricaricata la pagina con un avviso visivo in caso di dati mancanti
 * recuperando da $_SESSION ciò che intanto l'utente ha inserito
 * 
 * 
 * STRUTTURA:
 * 		--> DEFINISCO VARIABILI
 * 		--> AVVIO RISORSE
 * 		--> INIZIALIZZO $_SESSION DA $_POST
 * 		--> TEST SUBMIT
 * 		------> inizializzo da $_SESSION
 * 		--> VALIDAZIONE DATI
 *		------> test input
 * 		------> test scansione
 * 		--> CARICO
 * 		--> PRE & FORM
 * 		--> FERMO RISORSE
 * 		--> VISUALIZZO PAGINA
 * 
 * 
 */

// --> DEFINISCO VARIABILI
$a = "";
$q1 = "SELECT * FROM vserv_contatti;";
$q2 = "SELECT * FROM vserv_tipodoc;";
$q3 = "SELECT * FROM vserv_numdoc;";
$q4 = "SELECT * FROM vserv_posizioni;";
$q5 = "SELECT * FROM vserv_numoda;";
$registro = "aaa";

// --> AVVIO RISORSE
session_start();

$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// --> INIZIALIZZO $_SESSION DA $_POST
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

// --> TEST SUBMIT
if (isset($_SESSION['submit'])) {
	if (isset($_SESSION['fornitore'],$_SESSION['tipo_doc'],$_SESSION['num_doc'],$_SESSION['data_carico'],$_SESSION['tags'],$_SESSION['quantita'],$_SESSION['posizione'])) {
		
		// ------> inizializzo da $_SESSION
		$fornitore = safe($_SESSION['fornitore']);
		$tipo_doc = safe($_SESSION['tipo_doc']);
		$num_doc = safe($_SESSION['num_doc']);
		$data_carico = safe($_SESSION['data_carico']);
		$tags = safe($_SESSION['tags']);
		$quantita = safe($_SESSION['quantita']);
		$posizione = safe($_SESSION['posizione']);
		
		// --> VALIDAZIONE DATI
		
		// ------> test input
		if (isset($_SESSION['note_carico']))
			$note_carico = safe($_SESSION['note_carico']);
		else
			$note_carico = NULL;
			
		if (isset($_SESSION['trasportatore']))
			$trasportatore = safe($_SESSION['trasportatore']);
		else
			$trasportatore = NULL;
			
		if (isset($_SESSION['num_oda']))
			$num_oda = safe($_SESSION['num_oda']);
		else
			$num_oda = NULL;
			
		if (isset($_SESSION['data_doc'])) 
			$data_doc = safe($_SESSION['data_doc']);
		else
			$data_doc = NULL;
		
		// ------> test scansione
		if ($_FILES['scansione']['size'] > 0) {
			
			$query_doc = "SELECT EXISTS(SELECT 1 FROM REGISTRO WHERE contatto='{$fornitore}' AND tipo='{$tipo_doc}' AND numero='{$num_doc}')";
			$res_query_doc = mysql_query($query_doc);
			if (!$res_query_doc) die('Errore nell\'interrogazione del db: '.mysql_error());
			
			switch ($res_query_doc) {
				
				// se ritorna 0 devo aggiungere il file
				case "0":
					$nome_doc = epura_specialchars($tipo_doc)."-".epura_specialchars($fornitore)."-".epura_specialchars($num_doc).".".getfilext($_FILES['scansione']['name']);
					if (!(file_exists($registro."/".$nome_doc)))
						move_uploaded_file($_FILES['scansione']['name'],$registro."/".$nome_doc);
					break;

				// altrimenti passo NULL alla stored procedure che collegherà il carico ad altro id_documento valorizzato
				case "1":
					$nome_doc = NULL;
					$data_doc = NULL;

				default:
					$a .= "<h3>Rilevato un problema in fase di caricamento documento.</h3>\n";
			}
		
			mysql_free_result($res_query_doc);
			
		} else $nome_doc = NULL;
		
		// --> CARICO
		$call = "CALL CARICO('Web','{$fornitore}','{$tipo_doc}','{$num_doc}','{$data_doc}','{$nome_doc}','{$tags}','{$quantita}','{$posizione}','{$data}','{$note}','{$trasportatore}','{$num_oda}');";
		$res_carico = mysql_query($call);
		if ($res_query) 
			$a .= "<h3>La query ".$call." e' andata a buon fine</h3>\n";
		else 
			die('Errore nell\'interrogazione del db: '.mysql_error());
		
		mysql_free_result($res_carico);
		
	} else
		$a .= "<h3>Validazione dati fallita, carico non eseguito completare con i dati mancanti.</h3>\n";
		
}

// --> PRE & FORM

if (isset($_SESSION['fornitore'])) 
	$fornitore = safe($_SESSION['fornitore']);
else 
	$fornitore = myoptlst("fornitore",$q1);

if (isset($_SESSION['tipo_doc'])) 
	$tipo_doc = safe($_SESSION['tipo_doc']);
else 
	$tipo_doc = myoptlst("tipo_doc",$q2);

if (isset($_SESSION['num_doc'])) 
	$num_doc = safe($_SESSION['num_doc']);
else 
	$num_doc = myoptlst("num_doc",$q3);

if (isset($_SESSION['trasportatore'])) 
	$trasportatore = safe($_SESSION['trasportatore']);
else 
	$trasportatore = myoptlst("trasportatore",$q1);

if (isset($_SESSION['num_oda'])) 
	$num_oda = safe($_SESSION['num_oda']);
else 
	$num_oda = myoptlst("num_oda",$q5);

$posizione = myoptlst("posizione",$q4);


$a .= "<form name='carico' method='post' enctype='multipart/form-data' action='".htmlentities("?page=car1")."'>\n";
$a .= "<table>\n";
	$a .= "<caption>CARICO MERCE</caption>\n";
	$a .= "<thead><tr>\n";
		$a .= "<th>Descrizione</th>\n";
		$a .= "<th>Inserimento</th>\n";
		$a .= "<th>Suggerimento</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";
	
		$a .= "<tr>\n";
		$a .= "<td><label for='fornitore'>Fornitore</label></td>\n";
		$a .= "<td><input type='text' name='fornitore'></td>\n";
		$a .= "<td>".$fornitore."</td>\n";
		$a .= "</tr>\n";
	
		$a .= "<tr>\n";
		$a .= "<td><label for='trasportatore'>Trasportatore</label></td>\n";
		$a .= "<td><input type='text' name='trasportatore'></td>\n";
		$a .= "<td>".$trasportatore."</td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='tipo_doc'>Tipo documento</label></td>\n";
		$a .= "<td><input type='text' name='tipo_doc'></td>\n";
		$a .= "<td>".$tipo_doc."</td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='num_doc'>Numero documento</label></td>\n";
		$a .= "<td><input type='text' name='num_doc'></td>\n";
		$a .= "<td>".$num_doc."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='data_doc'>Data documento</label></td>\n";
		$a .= "<td><input name='data_doc' type='date' value='' class='date demo'/></td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='scansione'>Scansione documento</label></td>\n";
		$a .= "<td>\n";
			$a .= "<input type='file' name='scansione'>\n";
			//$a .= "<input type='hidden' name='action' value='upload'>\n";
		$a .= "</td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='tags'>TAGS</label></td>\n";
		$a .= "<td><textarea rows='4' cols='auto' name='tags'></textarea>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='quantita'>Quantita'</label></td>\n";
		$a .= "<td><input type='text' name='quantita'></td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='posizione'>Posizione</label></td>\n";
		$a .= "<td><input type='text' name='posizione'></td>\n";
		$a .= "<td>".$posizione."</td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='data'>Data</label></td>\n";
		$a .= "<td><input name='data' type='date' value='' class='date demo'/></td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='note'>Note</label></td>\n";
		$a .= "<td><textarea rows='4' cols='auto' name='note'></textarea>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='num_oda'>Numero ODA</label></td>\n";
		$a .= "<td><input type='text' name='num_oda'></td>\n";
		$a .= "<td>".$num_oda."</td>\n";
		$a .= "</tr>\n";
	
	$a .= "</tbody>\n";
	
	$a .= "<tfoot>\n";
		$a .= "<tr>\n";
		$a .= "<td></td><td></td>\n";
		$a .= "<td>\n";
			$a .= "<input type='reset' name='reset' value='Clear'>\n";
			$a .= "<input type='submit' name='submit' value='Submit'>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";
	$a .= "</tfoot>\n";
	
$a .= "</table>\n";
$a .= "</form>\n";








// --> FERMO RISORSE
mysql_close($conn);
session_write_close();

// --> VISUALIZZO PAGINA
echo $a;

?>
