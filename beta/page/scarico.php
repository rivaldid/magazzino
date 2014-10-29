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
 * 		3. test fine $_SESSION 
 * 		4. test scarico iniziato
 * 
 *		5. se $selezionato true 
 * 			5a. inizializzo variabili
 * 			5b. test submit
 * 				5ba. validazione
 * 					5baa. utente
 * 					5bab. richiedente
 * 					5bac. quantita
 * 					5bad. destinazione
 * 					5bae. data_doc_scarico
 * 				5bb. test valid
 * 					5bba. SCARICO
 *					5bbb. test ritorno SCARICO
 * 					5bbc. reset variabili
 *			5c. form SCARICO
 * 
 *		6. se $selezionato false
 *			6a. ricevo lista merce
 *			6b. form lista merce
 * 
 * 		7. libero risorse
 * 		8. stampa
 * 
 * 
 */
	
	
	
// 1. definizione variabili
$a = "";
$log = "";
$selezionato = true;

$query_lista_merce = "SELECT * FROM vista_magazzino;";
$magamanager = "<select name='utente'>\n<option selected='selected' value=''>Blank</option>\n<option value='Piscazzi'>Piscazzi</option>\n<option value='Manzo'>Manzo</option>\n<option value='Muratore'>Muratore</option>\n</select>\n";

$msg1 = "Mancata selezione di un utente per l'attivita' in corso (1)";
$msg2 = "Mancata selezione di un richiedente per l'attivita' in corso (2)";
$msg3 = "Mancata selezione di una quantita' per l'attivita' in corso (3)";
$msg4 = "Quantita' richiesta superiore alla giacenza in magazzino per quella posizione(4)";
$msg5 = "Mancato inserimento di una destinazione per l'attivita' in corso(5)";
$msg6 = "Mancata selezione di una data per l'attivita' in corso(6)";

$msg9 = "Sessione terminata, tutti i campi sono stati azzerati";

$msg10 = "Scarico inviato al database";
$msg11 = "Scarico effettuato correttamente";
$msg12 = "Scarico non effettuato(12)";
$msg13 = "Persa risposta del database(13)";



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
	
	$selezionato = false;
	
} else {
	
	foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

}


// 4. test scarico iniziato
if (!isset($_SESSION['id_merce']) OR empty($_SESSION['id_merce'])) {
	
	$selezionato = false;
	
}


// 5. se $selezionato true
if ($selezionato) {
	
	
	// 5a. inizializzo variabili
	
	if (isset($_SESSION['utente'])AND(!empty($_SESSION['utente']))) 
		$utente = safe($_SESSION['utente']);
	else
		$utente = null;
		
	if (isset($_SESSION['richiedente'])AND(!empty($_SESSION['richiedente']))) 
		$richiedente = safe($_SESSION['richiedente']);
	else
		$richiedente = null;
		
	if (isset($_SESSION['id_merce'])AND(!empty($_SESSION['id_merce']))) 
		$id_merce = safe($_SESSION['id_merce']);
	else
		$id_merce = null;
	
	if (isset($_SESSION['quantita'])AND(!empty($_SESSION['quantita']))) 
		$quantita = safe($_SESSION['quantita']);
	else
		$quantita = null;
	
	if (isset($_SESSION['maxquantita'])AND(!empty($_SESSION['maxquantita']))) 
		$maxquantita = safe($_SESSION['maxquantita']);
	else
		$maxquantita = null;
		
	if (isset($_SESSION['posizione'])AND(!empty($_SESSION['posizione']))) 
		$posizione = safe($_SESSION['posizione']);
	else
		$posizione = null;
	
	if (isset($_SESSION['destinazione'])AND(!empty($_SESSION['destinazione'])))
		$destinazione = safe($_SESSION['destinazione']);
	else
		$destinazione = null;
	
	if (isset($_SESSION['data_doc_scarico'])AND(!empty($_SESSION['data_doc_scarico']))) 
		$data_doc_scarico = safe($_SESSION['data_doc_scarico']);
	else
		$data_doc_scarico = null;
	
	if (isset($_SESSION['note'])AND(!empty($_SESSION['note']))) 
		$note = safe($_SESSION['note']);
	else
		$note = null;
	
	
	// 5b. test submit
	if (isset($_SESSION['submit'])) {
		
		
		// 5ba. validazione 
		
		// 5baa. utente
		if (is_null($utente) OR empty($utente)) {
			$log .= remesg($msg1,"err");
			$valid = false;
		}
		
		// 5bab. richiedente
		if (is_null($richiedente) OR empty($richiedente)) {
			$log .= remesg($msg2,"err");
			$valid = false;
		}
		
		// 5bac. quantita
		if (is_null($quantita) OR empty($quantita)) {
			$log .= remesg($msg3,"err");
			$valid = false;
		} else {
			if ($quantita>$maxquantita) {
				$log .= remesg($msg4,"err");
				$valid = false;
			}
		}
			
		// 5bad. destinazione
		if (is_null($destinazione) OR empty($destinazione)) {
			$log .= remesg($msg5,"err");
			$valid = false;
		} 
		
		// 5bae. data_doc_scarico
		if (is_null($data_doc_scarico) OR empty($data_doc_scarico)) {
			$log .= remesg($msg6,"err");
			$valid = false;
		}
		
		
		// 5bb. test valid
		if ($valid) {
			
			
			// 5bba. SCARICO
			$call = "CALL SCARICO('{$utente}','{$richiedente}','{$id_merce}','{$quantita}','{$posizione}','{$destinazione}','{$data_doc_scarico}','{$data_scarico}','{$note}',@myvar);";
			$log .= remesg($call,"msg");
			
			$result_scarico = mysql_query($call);
			
			if ($result_scarico)
				$log .= remesg($msg10,"warn");
			else
				die('Errore nell\'interrogazione del db: '.mysql_error());
			
			$ritorno = mysql_fetch_array($result_scarico, MYSQL_NUM);
			
			
			// 5bbb. test ritorno SCARICO
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
			
			
			// 5bbc. reset variabili
			$selezionato = false;
			
		} // fine test valid
		
	} // fine test submit
	
	// 5c. form SCARICO
	
	
	
} // fine $selezionato true 


// 6. se $selezionato false
if (!($selezionato)) {
	
	
	// 6a. ricevo lista merce
	
	$result_lista_merce = mysql_query($query_lista_merce);
	if (!$result_lista_merce) die('Errore nell\'interrogazione del db: '.mysql_error());
	
	
	// 6b. form lista merce
	
	$a .= "<table>\n";
	$a .= "<caption>SCARICO MERCE</caption>\n";
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
				
				case 3:
					$a .= "<td>".input_hidden("maxquantita",$cvalue)."</td>\n";
					break;
			
				default:
					$a .= "<td>".$cvalue."</td>\n";
			}
			
		$a .= "<td><input type='submit' name='submit' value='Scarico'/></td>\n";
		$a .= "</tr>\n";
	}

	$a .= "</tbody>\n</table>\n";

	mysql_free_result($result_lista_merce);

	
} // fine $selezionato false





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

