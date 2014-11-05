<?php


/*
 * scarico merce da magazzino, script frontend per stored procedure
 * CALL SCARICO(utente, richiedente, id_merce, quantita, posizione, 
 * destinazione, data_doc_scarico, data_scarico, note_scarico,@myvar);
 * 
 * lo SCARICO ritorna un valore, 0 se andato a buon fine 1 altrimenti
 * 
 * 
 * 
 * ALGORITMO:		
 * 		1. definizione variabili
 * 		2. startup risorse
 * 			2a. $_SESSION
 * 			2b. mysql
 *		3. test risorse
 * 			3a. test $selezionato
 *			3b. test fine $_SESSION 
 * 
 *		4. se $selezionato true 
 * 			4a. inizializzo variabili
 *				4aa. da scarico step1
 *				4ab. data_scarico
 *				4ac. utente
 *				4ad. richiedente
 *				4ae. quantita
 *				4af. destinazione
 *				4ag. data_doc_scarico
 *				4ah. note
 * 			4b. test submit
 * 				4ba. validazione
 * 					4baa. utente
 * 					4bab. richiedente
 * 					4bac. quantita
 * 					4bad. destinazione
 * 					4bae. data_doc_scarico
 * 				4bb. test valid
 * 					4bba. SCARICO
 * 						4bbaa. logging
 *					4bbb. test ritorno SCARICO
 * 					4bbc. reset mysql connection
 * 					4bbd. ritorno MDS
 * 					4bbe. reset variabili
 *			4c. form scarico step2
 * 
 *		5. se $selezionato false
 *			5a. ricevo lista merce
 *			5b. form scarico step1
 * 
 * 		6. libero risorse
 * 		7. stampa
 * 
 * 
 */
	
	
	
// 1. definizione variabili
$a = "";
$log = "";
$selezionato = false;
$valid = true;
$registro_mds = "/magazzino/registro_mds/";

$q1 = "SELECT * FROM vserv_contatti;";
$q4 = "SELECT * FROM vserv_posizioni;";

$query_lista_merce = "SELECT * FROM vista_magazzino;";
$magamanager = "<select name='utente'>\n<option selected='selected' value=''>Blank</option>\n<option value='Piscazzi'>Piscazzi</option>\n<option value='Manzo'>Manzo</option>\n<option value='Muratore'>Muratore</option>\n</select>\n";

$msg1 = "Mancata selezione di un utente per l'attivita' in corso (1)";
$msg2 = "Mancata selezione di un richiedente per l'attivita' in corso (2)";
$msg3 = "Mancata selezione di una quantita' per l'attivita' in corso (3)";
$msg4 = "Quantita' richiesta superiore alla giacenza in magazzino per quella posizione (4)";
$msg5 = "Mancato inserimento di una destinazione per l'attivita' in corso (5)";
$msg6 = "Mancata selezione di una data per l'attivita' in corso (6)";

$msg9 = "Sessione terminata, tutti i campi sono stati azzerati";

$msg10 = "Scarico inviato al database";
$msg11 = "Scarico effettuato correttamente";
$msg12 = "Scarico non effettuato (12)";
$msg13 = "Persa risposta del database (13)";

$msg14 = "Scarico terminato, ripristino i valori di default";



// 2. startup risorse
$a .= jsxdate;

// 2a. $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// 2b. mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// 3. test risorse

// 3a. test $selezionato
if (isset($_POST['id_merce'])AND(!empty($_POST['id_merce']))) {
	
	// 3b. test fine $_SESSION
	if (isset($_POST['stop'])) {
	
	$selezionato = false;
	
	$log .= remesg($msg9,"msg");
	$_SESSION = array();
	session_unset();
	session_destroy();
	
	/* generate new session id and delete old session in store */
	session_regenerate_id(true);
	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
		
	} else {
		
		$selezionato = true;
		foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
		
	}
	
} else {
	
	$selezionato = false;
	
}


// 4. se $selezionato true
if ($selezionato == true) {
	
	
	// 4a. inizializzo variabili
	
	// 4aa. da scarico step1
	$id_merce = safe($_SESSION['id_merce']);
	$tags = safe($_SESSION['tags']);
	$posizione = safe($_SESSION['posizione']);
	$maxquantita = safe($_SESSION['maxquantita']);
	
	// 4ab. data_scarico
	$data_scarico = date("Y-m-d");
	
	// 4ac. utente
	if (isset($_SESSION['utente'])AND(!empty($_SESSION['utente']))) 
		$utente = safe($_SESSION['utente']);
	else
		$utente = null;
	
	// 4ad. richiedente
	if (isset($_SESSION['irichiedente'])AND(!empty($_SESSION['irichiedente'])))
		$richiedente = safe($_SESSION['irichiedente']);
	else {
		if (isset($_SESSION['srichiedente'])AND(!empty($_SESSION['srichiedente'])))
			$richiedente = safe($_SESSION['srichiedente']);
		else
			$richiedente = NULL;
	}

	// 4ae. quantita
	if (isset($_SESSION['iquantita'])AND(!empty($_SESSION['iquantita'])))
		$quantita = safe($_SESSION['iquantita']);
	else {
		if (isset($_SESSION['squantita'])AND(!empty($_SESSION['squantita'])))
			$quantita = safe($_SESSION['squantita']);
		else
			$quantita = NULL;
	}
	
	// 4af. destinazione
	if (isset($_SESSION['idestinazione'])AND(!empty($_SESSION['idestinazione'])))
		$destinazione = safe($_SESSION['idestinazione']);
	else {
		if (isset($_SESSION['sdestinazione'])AND(!empty($_SESSION['sdestinazione'])))
			$destinazione = safe($_SESSION['sdestinazione']);
		else
			$destinazione = NULL;
	}
	
	// 4ag. data_doc_scarico	
	if (isset($_SESSION['idata_doc_scarico'])AND(!empty($_SESSION['idata_doc_scarico'])))
		$data_doc_scarico = safe($_SESSION['idata_doc_scarico']);
	else {
		if (isset($_SESSION['sdata_doc_scarico'])AND(!empty($_SESSION['sdata_doc_scarico'])))
			$data_doc_scarico = safe($_SESSION['sdata_doc_scarico']);
		else
			$data_doc_scarico = NULL;
	}
	
	// 4ah. note
	if (isset($_SESSION['inote'])AND(!empty($_SESSION['inote'])))
		$note = safe($_SESSION['inote']);
	else {
		if (isset($_SESSION['snote'])AND(!empty($_SESSION['snote'])))
			$note = safe($_SESSION['snote']);
		else
			$note = NULL;
	}
	
	
	// 4b. test submit
	if (isset($_SESSION['submit'])) {
		
		
		// 4ba. validazione 
		
		// 4baa. utente
		if (is_null($utente) OR empty($utente)) {
			$log .= remesg($msg1,"err");
			$valid = false;
		}
		
		// 4bab. richiedente
		if (is_null($richiedente) OR empty($richiedente)) {
			$log .= remesg($msg2,"err");
			$valid = false;
		}
		
		// 4bac. quantita
		if (is_null($quantita) OR empty($quantita)) {
			$log .= remesg($msg3,"err");
			$valid = false;
		} else {
			if ($quantita>$maxquantita) {
				$log .= remesg($msg4,"err");
				$valid = false;
			}
		}
			
		// 4bad. destinazione
		if (is_null($destinazione) OR empty($destinazione)) {
			$log .= remesg($msg5,"err");
			$valid = false;
		} 
		
		// 4bae. data_doc_scarico
		if (is_null($data_doc_scarico) OR empty($data_doc_scarico)) {
			$log .= remesg($msg6,"err");
			$valid = false;
		}
		
		
		
		// 4bb. test valid
		if ($valid == true) {
			
			
			// 4bba. SCARICO
			$call = "CALL SCARICO('{$utente}','{$richiedente}','{$id_merce}','{$quantita}','{$posizione}','{$destinazione}','{$data_doc_scarico}','{$data_scarico}','{$note}',@myvar);";
			$log .= remesg($call,"msg");
			
			$result_scarico = mysql_query($call);
			
			if ($result_scarico)
				$log .= remesg($msg10,"msg");
			else
				die('Errore nell\'invio del comando di scarico al db: '.mysql_error());
			
			$ritorno = mysql_fetch_array($result_scarico, MYSQL_NUM);
			
			// 4bbaa. logging
			logging($call);
						
			// 4bbb. test ritorno SCARICO
			switch ($ritorno[0]) {
				
				case "0":
					$log .= remesg($msg11,"msg");
					break;
				
				case "1":
					$log .= remesg($msg12,"err");
					break;
					
				default:
					$log .= remesg($msg13,"err");
				
			}
			
			
			// 4bbc. reset mysql connection
			mysql_free_result($result_scarico);
			mysql_close($conn);
			
			$conn = mysql_connect('localhost','magazzino','magauser');
			if (!$conn) die('Errore di connessione: '.mysql_error());

			$dbsel = mysql_select_db('magazzino', $conn);
			if (!$dbsel) die('Errore di accesso al db: '.mysql_error());
			
			
			// 4bbd. ritorno MDS
			$report = "";
			$html = "";
			
			$html .= "<table>";
			$html .= "<caption>MODULO DI CONSEGNA MATERIALE</caption>";
			$html .= "<tbody>";
			$html .= "<tr><td></td><td></td></tr>\n";
			$html .= "<tr><td>TI/GSI/GI/TO</td><td></td></tr>\n";
			$html .= "<tr><td>DATA CENTER TORINO</td><td></td></tr>\n";
			$html .= "<tr><td>Corso Tazzoli 235/4</td><td></td></tr>\n";
			$html .= "<tr><td>10137 TORINO</td><td></td></tr>\n";
			$html .= "<tr><td></td><td></td></tr>\n";
			$html .= "<tr><td>Operatore di accessi</td><td>".$utente."</td></tr>";
			$html .= "<tr><td>Struttura richiedente</td><td>".$richiedente."</td></tr>";
			$html .= "<tr><td>Descrizione articolo</td><td>".$tags."</td></tr>";
			$html .= "<tr><td>Quantita'</td><td>".$quantita."</td></tr>";
			$html .= "<tr><td>Posizione di provenienza</td><td>".$posizione."</td></tr>";
			$html .= "<tr><td>Destinazione materiale</td><td>".$destinazione."</td></tr>";
			$html .= "<tr><td>Note</td><td>".$note."</td></tr>";
			$html .= "<tr><td>Data di riferimento scarico</td><td>".$data_doc_scarico."</td></tr>";
			$html .= "<tr><td>Torino il</td><td>".$data_scarico."</td></tr>";
			$html .= "<tr><td></td><td></td></tr>\n";
			$html .= "<tr><td>Firma</td><td></td></tr>";
			$html .= "</tbody>";
			$html .= "</table>";
			
			$report .= "<?php\n";
			$report .= "//==============================================================\n";
			$report .= "//==============================================================\n";
			$report .= "//==============================================================\n";
			$report .= "include(\"../beta/lib/MPDF57/mpdf.php\");\n";
			$report .= "\$mpdf=new mPDF();\n";
			$report .= "\$mpdf->WriteHTML(\"".$html."\");\n";
			$report .= "\$mpdf->Output();\n";
			$report .= "exit;\n";
			$report .= "//==============================================================\n";
			$report .= "//==============================================================\n";
			$report .= "//==============================================================\n";
			$report .= "?>\n";
			
			$filereport = $registro_mds."MDS-".$utente."-".epura_space2underscore($richiedente)."-".$data_doc_scarico.".php";
			$fp = fopen($_SERVER['DOCUMENT_ROOT'].$filereport,"w");
			fwrite($fp,$report);
			fclose($fp);
			
			$log .= remesg("<a href=\"".$filereport."\">Modulo di scarico</a> pronto per la stampa","msg");
						
			// 4bbe. reset variabili
			$selezionato = false;
			
	
			$log .= remesg($msg14,"msg");
			$_SESSION = array();
			session_unset();
			session_destroy();
			
			/* generate new session id and delete old session in store */
			session_regenerate_id(true);
			if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
			
		} // fine test valid
		
	} // fine test submit
	
	// 4c. form scarico step2
	if ($selezionato == true) {
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=scarico")."'>\n";
		$a .= "<table>\n";

		$a .= "<caption>SCARICO MERCE STEP2</caption>\n";

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
			$a .= "<td><label for='irichiedente'>Richiedente</label></td>\n";
			if (is_null($richiedente)) {
				$a .= "<td><input type='text' name='irichiedente'/></td>\n";
				$a .= "<td>".myoptlst("srichiedente",$q1)."</td>\n";
			} else {
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("srichiedente",$richiedente)."</td>\n";
			}
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='tags'>TAGS</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".noinput_hidden("id_merce",$id_merce).$tags."</td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='iquantita'>Quantita'</label></td>\n";
			if (is_null($quantita) OR ($quantita>$maxquantita)) {
				$a .= "<td><input type='text' name='iquantita'/></td>\n";
				$a .= "<td>Disponibilita': ".$maxquantita."</td>\n";
			} else {
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("squantita",$quantita)." di ".$maxquantita."</td>\n";
			}
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='posizione'>Posizione</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("posizione",$posizione)."</td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='idestinazione'>Destinazione</label></td>\n";
			if (is_null($destinazione)) {
				$a .= "<td><input type='text' name='idestinazione'/></td>\n";
				$a .= "<td>".myoptlst("sdestinazione",$q4)."</td>\n";
			} else {
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("sdestinazione",$destinazione)."</td>\n";
			}
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='idata_doc_scarico'>Data documento</label></td>\n";
			if (is_null($data_doc_scarico)) {
				$a .= "<td></td>\n";
				//$a .= "<td><input name='idata_doc_scarico' type='date' value='' class='date'/></td>\n";
				$a .= "<td><input type='text' class='datepicker' name='idata_doc_scarico'/></td>\n";
			} else {
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("sdata_doc_scarico",$data_doc_scarico)."</td>\n";
			}
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='inote'>Note</label></td>\n";
			if (is_null($note))
				$a .= "<td><textarea rows='4' cols='25' name='inote'></textarea></td>\n";
			else
				$a .= "<td>".input_hidden("snote",$note)."</td>\n";
			$a .= "<td>\n";
				$a .= remesg("Campo ad inserimento libero per dettagli vari mirati","msg");
				$a .= remesg("al corretto recupero di informazioni a posteriori","msg");
			$a .= "</td>\n";
			$a .= "</tr>\n";
			
		
		$a .= "</tbody>\n";

		$a .= "</table>\n";
		$a .= "</form>\n";
	}
	
} // fine $selezionato true 


// 5. se $selezionato false
if ($selezionato == false) {
	
	
	// 5a. ricevo lista merce
	
	$result_lista_merce = mysql_query($query_lista_merce);
	if (!$result_lista_merce) die('Errore in ricezione lista merce dal db: '.mysql_error());
	
	
	// 5b. form scarico step1
	
	$a .= jsxtable;
	$a .= "<table>\n";
	$a .= "<caption>SCARICO MERCE STEP1</caption>\n";
	$a .= "<thead><tr>\n";
		$a .= "<th>Posizione</th>\n";
		$a .= "<th>TAGS</th>\n";
		$a .= "<th>Quantita'</th>\n";
		$a .= "<th>Azione</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";
		
	while ($row = mysql_fetch_array($result_lista_merce, MYSQL_NUM)) {
		$a .= "<tr>\n";
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=scarico")."'>\n";
		foreach ($row as $cname => $cvalue)
			
			switch ($cname) {
				
				case 0:
					$a .= noinput_hidden("id_merce",$cvalue)."\n";
					break;
				
				case 1:
					$a .= "<td>".input_hidden("posizione",$cvalue)."</td>\n";
					break;
				
				case 2:
					$a .= "<td>".input_hidden("tags",$cvalue)."</td>\n";
					break;
				
				case 3:
					$a .= "<td>".input_hidden("maxquantita",$cvalue)."</td>\n";
					break;
			
				default:
					//$a .= "<td>".$cvalue."</td>\n";
					$a .= "";
			}
			
		$a .= "<td><input type='submit' name='submit' value='Scarico'/></td>\n";
		$a .= "</form>\n";
		$a .= "</tr>\n";
	}

	$a .= "</tbody>\n</table>\n";

	mysql_free_result($result_lista_merce);

	
} // fine $selezionato false





// 6. libero risorse
mysql_close($conn);
session_write_close();


// 7. stampo
echo "<div id=\"log\">\n";
echo remesg("Notifiche","tit");
if ($log == "")
	echo remesg("nessuna notifica da visualizzare","msg");
else
	echo $log;
echo "</div>\n";

echo $a;

?>

