<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);

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
 * 						4bbda. definizione dati
 * 						4bbdb. definizione pagina
 * 						4bbdc. scrittura contenuti
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
$a = "";
$log = "";

$valid = true;

foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

// data_scarico
$data_scarico = date("Y-m-d");

// utente
/*
 * if (isset($_SESSION['utente'])AND(!empty($_SESSION['utente'])))
 * 		$utente = safe($_SESSION['utente']);
 * else
 * 		$utente = NULL;
 */
$utente = $_SERVER["AUTHENTICATE_UID"];

// richiedente
if (isset($_SESSION['irichiedente'])AND(!empty($_SESSION['irichiedente'])))
	$richiedente = safe($_SESSION['irichiedente']);
else {
	if (isset($_SESSION['srichiedente'])AND(!empty($_SESSION['srichiedente'])))
		$richiedente = safe($_SESSION['srichiedente']);
	else
		$richiedente = NULL;
}

// quantita
if (isset($_SESSION['iquantita'])AND(!empty($_SESSION['iquantita'])))
	$quantita = safe($_SESSION['iquantita']);
else {
	if (isset($_SESSION['squantita'])AND(!empty($_SESSION['squantita'])))
		$quantita = safe($_SESSION['squantita']);
	else
		$quantita = NULL;
}

// destinazione
if (isset($_SESSION['idestinazione'])AND(!empty($_SESSION['idestinazione'])))
	$destinazione = safe($_SESSION['idestinazione']);
else {
	if (isset($_SESSION['sdestinazione'])AND(!empty($_SESSION['sdestinazione'])))
		$destinazione = safe($_SESSION['sdestinazione']);
	else
		$destinazione = NULL;
}

// data_doc_scarico
if (isset($_SESSION['idata_doc_scarico'])AND(!empty($_SESSION['idata_doc_scarico'])))
	$data_doc_scarico = safe($_SESSION['idata_doc_scarico']);
else {
	if (isset($_SESSION['sdata_doc_scarico'])AND(!empty($_SESSION['sdata_doc_scarico'])))
		$data_doc_scarico = safe($_SESSION['sdata_doc_scarico']);
	else
		$data_doc_scarico = NULL;
}

// note
if (isset($_SESSION['inote'])AND(!empty($_SESSION['inote'])))
	$note = safe($_SESSION['inote']);
else {
	if (isset($_SESSION['snote'])AND(!empty($_SESSION['snote'])))
		$note = safe($_SESSION['snote']);
	else
		$note = NULL;
}


// 2. test bottoni

// stop
if (isset($_SESSION['stop'])) {
	// reset variabili server
	reset_sessione();
}

// add||save
//if ((isset($_SESSION['add'])) OR (isset($_SESSION['save']))) {
if (isset($_SESSION['id_merce'],$_SESSION['posizione'],$_SESSION['maxquantita'])) {
	
	// validazione
	
	// id_merce - tags - posizione - maxquantita
	$id_merce = safe($_SESSION['id_merce']);
	$tags = safe($_SESSION['tags']);
	$posizione = safe($_SESSION['posizione']);
	$maxquantita = safe($_SESSION['maxquantita']);
	/*if (isset($_SESSION['id_merce'])AND(!empty($_SESSION['id_merce'])))
		$id_merce = safe($_SESSION['id_merce']);
	else
		$valid = false;

	if (isset($_SESSION['tags'])AND(!empty($_SESSION['tags'])))
		$tags = safe($_SESSION['tags']);
	else
		$valid = false;
	
	if (isset($_SESSION['posizione'])AND(!empty($_SESSION['posizione'])))
		$posizione = safe($_SESSION['posizione']);
	else
		$valid = false;

	if (isset($_SESSION['maxquantita'])AND(!empty($_SESSION['maxquantita'])))
		$maxquantita = safe($_SESSION['maxquantita']);
	else
		$valid = false;
	*/
	
	// utente
	if (is_null($utente) OR empty($utente)) {
		$log .= remesg($msg1,"err");
		$valid = false;
	}
	if(!(in_array($utente, $enabled_users))){
		$log .= remesg($msg17,"err");
		$valid = false;
	}
	
	// richiedente
	if (is_null($richiedente) OR empty($richiedente)) {
		$log .= remesg($msg24,"err");
		$valid = false;
	}
	
	// quantita
	if (is_null($quantita) OR empty($quantita)) {
		$log .= remesg($msg25,"err");
		$valid = false;
	} else {
		if ($quantita>$maxquantita) {
			$log .= remesg($msg26,"err");
			$valid = false;
		}
	}
	
	// destinazione
	if (is_null($destinazione) OR empty($destinazione)) {
		$log .= remesg($msg27,"err");
		$valid = false;
	}

	// data_doc_scarico
	if (is_null($data_doc_scarico) OR empty($data_doc_scarico)) {
		$log .= remesg($msg28,"err");
		$valid = false;
	}

	
	// 3. test valid
	if ($valid) {

		// SCARICO
		$call = "CALL SCARICO('{$utente}','{$richiedente}','{$id_merce}','{$quantita}','{$posizione}','{$destinazione}','{$data_doc_scarico}','{$data_scarico}','{$note}',@myvar);";
		//$log .= remesg($call,"msg");

		$result_scarico = mysql_query($call);

		if ($result_scarico)
			$log .= remesg("Scarico inviato al database","msg");
		else
			die('Errore nell\'invio del comando di scarico al db: '.mysql_error());

		$ritorno = mysql_fetch_array($result_scarico, MYSQL_NUM);

		// logging
		logging2($call,splog);

		// ritorno SCARICO
		switch ($ritorno[0]) {

			case "0":
				$log .= remesg("Scarico effettuato correttamente","msg");
				break;

			case "1":
				$log .= remesg("Scarico non effettuato (errore 31)","err");
				break;

			default:
				$log .= remesg("Persa risposta del database (errore 32)","err");

		}

		// reset mysql connection
		mysql_free_result($result_scarico);
		mysql_close($conn);

		$conn = mysql_connect('localhost','magazzino','magauser');
		if (!$conn) die('Errore di connessione: '.mysql_error());

		$dbsel = mysql_select_db('magazzino', $conn);
		if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


		// MDS
		
		
		if (isset($_SESSION['mds'])AND(!empty($_SESSION['mds']))) {
			
			ob_start();
			include 'lib/template_mds2.php';
			$corpo_html = ob_get_clean();
			$_SESSION['mds'] .= addslashes($corpo_html)."\n";
			
		} else {
			
			ob_start();
			include 'lib/template_mds1.php';
			$corpo_html = ob_get_clean();
			$_SESSION['mds'] = "<?php\n"."\$html = \"".addslashes($corpo_html)."\n";
			
		}
		
		// reset variabili client
		$log .= remesg("Ripristino i valori di default","msg");
		unset($_SESSION['id_merce'],$_SESSION['posizione'],$_SESSION['maxquantita']);
		unset($_SESSION['quantita'],$_SESSION['destinazione']);
		
		// test save
		if(isset($_SESSION['save'])) {
			ob_start();
			include 'lib/template_mds3.php';
			$corpo_html = ob_get_clean();
			$_SESSION['mds'] .= addslashes($corpo_html)."\";\n";
			$_SESSION['mds'] .= "//==============================================================\n";
			$_SESSION['mds'] .= "include(\"".lib_mpdf57."\");\n";
			$_SESSION['mds'] .= "\$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);\n";
			$_SESSION['mds'] .= "\$stylesheet = file_get_contents('../020/css/mds.css');\n";
			$_SESSION['mds'] .= "\$mpdf->WriteHTML(\$stylesheet,1);\n";			
			$_SESSION['mds'] .= "\$mpdf->WriteHTML(\"\$html\");\n";
			$_SESSION['mds'] .= "\$mpdf->Output();\n";
			$_SESSION['mds'] .= "exit;\n";
			$_SESSION['mds'] .= "//==============================================================\n";
			
			$nome_report = "MDS-".$utente."-".epura_space2underscore($richiedente)."-".$data_doc_scarico."_".rand().".php";
			$fp = fopen($_SERVER['DOCUMENT_ROOT'].registro_mds.$nome_report,"w");
			fwrite($fp,$_SESSION['mds']);
			fclose($fp);

			$log .= remesg("<a href=\"".registro_mds.$nome_report."\">Modulo di scarico</a> pronto per la stampa","msg");
			
			// reset altre variabili client
			unset($_SESSION['richiedente'],$_SESSION['data_doc_scarico'],$_SESSION['note']);
			
			// reset variabili server
			reset_sessione();

		}
		

	// altrimenti per input non ancora valido ritorna al form
	} else {
		
		// form input scarico
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=scarico")."'>\n";
		$a .= jsxdate;
		$a .= jsaltrows;
		$a .= "<table class='altrowstable' id='alternatecolor'>\n";

		$log .= remesg("Completare lo scarico sulla merce indicata","msg");

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
				$a .= "<td>".$richiedenti_merce."</td>\n";
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
				$a .= "<td>".myoptlst("sdestinazione",$vserv_posizioni)."</td>\n";
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
	
}
//}


// 4. test pagina vuota
if (empty($a)) {

	// ricevo lista merce

	$result_lista_merce = mysql_query($vista_magazzino);
	if (!$result_lista_merce) die('Errore in ricezione lista merce dal db: '.mysql_error());


	// form selezione per scarico

	$a .= jsxtable;
	$a .= jsaltrows;
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";
	$log .= remesg("Lista estesa del contenuto del magazzino","msg");
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

		$a .= "<td><input type='submit' name='add' value='Scarico'/></td>\n";
		$a .= "</form>\n";
		$a .= "</tr>\n";
	}

	$a .= "</tbody>\n</table>\n";

	mysql_free_result($result_lista_merce);


}



// 5. libero risorse
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

