<?php

/*
 * scarico merce da magazzino, script frontend per stored procedure
 * CALL SCARICO(utente, richiedente, id_merce, quantita, posizione,
 * destinazione, data_doc_scarico, data_scarico, note_scarico,@myvar);
 *
 * lo SCARICO ritorna un valore, 0 se andato a buon fine 1 altrimenti
 *
 * valorizzazione variabili:
 * le variabili provengono da $_SESSION
 * possono essere valorizzare da ingresso diretto $_SESSION['myvar']
 * possono essere valorizzare da input manuale $_SESSION['imyvar']
 * possono essere valorizzare da input selezionato $_SESSION['smyvar']
 * $myvar proviene da $_SESSION['myvar'] in un primo istante
 * $myvar viene sovrascritto da $_SESSION['imyvar'] o $_SESSION['smyvar']
 * se $_SESSION['imyvar'] non definita
 * input fase: $_SESSION['myvar'] or $_SESSION['imyvar'] or $_SESSION['smyvar']
 * output fase: $myvar
 *
 *
 *
 * ALGORITMO:
 *
 *
 *	1. inizializzo risorse
 *
 * 		11. $_SESSION
 * 		12. mysql
 * 		13. variabili
 * 			131. generiche
 * 			132. data_scarico
 * 			133. utente
 * 			134. richiedente
 * 			135. quantita
 * 			136. destinazione
 * 			137. data_doc_scarico
 * 			138. note
 * 			139. id_merce - tags - posizione - maxquantita
 *
 *	2. test bottoni
 *
 * 		21. test stop
 * 			211. test MDS iniziato
 * 				2111. finish mds
 * 				2112. write mds
 * 			212. reset variabili server
 * 			213. alert
 * 		22. test add||save
 *			221. validazione
 * 				2211. id_merce(sentinella)
 * 				2212. utente
 * 				2213. richiedente
 * 				2214. quantita
 * 				2215. destinazione
 * 				2216. data_doc_scarico
 *			222. test valid
 * 				2221. SCARICO
 * 				2222. logging
 * 				2223. test ritorno
 * 				2224. MDS
 * 					22241. test not exists
 * 						222411. create
 * 					22242. append
 * 					22243. reset id_merce - tags - posizione - maxquantita
 * 					22244. reset quantita - destinazione
 * 				2225. test save
 * 					22251. finish mds
 * 					22252. write mds
 * 					22253. reset sessione server
 * 				2226. scarico non riuscito
 * 			223. reset mysql connection
 *			224. test not valid
 * 				2241. form input scarico
 *
 *	3. test contenuti
 *
 * 		31. ricevo lista merce
 * 		32. form selezione
 *
 * 	4. libero risorse
 *
 * 	5. stampo
 *
 *
 *
 */


// 1. inizializzo risorse

// 11. $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// 12. mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// 13. variabili

// 131. generiche
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

$a = "";
$log = "";
$valid = true;

$log .= $menu_scarico;

if ($DEBUG) $log .= remesg("DEBUG ATTIVO","debug");
if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

if ($DEBUG) $log .= "<pre>".var_dump($_POST)."</pre>";
if ($DEBUG) $log .= "<pre>".var_dump($_SESSION)."</pre>";

// 132. data_scarico
$data_scarico = date("Y-m-d");

// 133. utente
/*
 * if (isset($_SESSION['utente'])AND(!empty($_SESSION['utente'])))
 * 		$utente = safe($_SESSION['utente']);
 * else
 * 		$utente = NULL;
 */
$utente = $_SERVER["AUTHENTICATE_UID"];

// 134. richiedente
if (isset($_SESSION['irichiedente'])AND(!empty($_SESSION['irichiedente'])))
	$richiedente = epura_special2chars(safe($_SESSION['irichiedente']));
else {
	if (isset($_SESSION['srichiedente'])AND(!empty($_SESSION['srichiedente'])))
		$richiedente = safe($_SESSION['srichiedente']);
	else
		$richiedente = NULL;
}

if ($DEBUG) {
	if (isset($richiedente)) $log .= remesg("Valore richiedente: ".$richiedente,"debug");
	if (isset($irichiedente)) $log .= remesg("Inserimento richiedente: ".$irichiedente,"debug");
	if (isset($srichiedente)) $log .= remesg("Suggerimento richiedente: ".$srichiedente,"debug");
}

// 135. quantita
if (isset($_SESSION['iquantita'])AND(!empty($_SESSION['iquantita'])))
	$quantita = safe($_SESSION['iquantita']);
else {
	if (isset($_SESSION['squantita'])AND(!empty($_SESSION['squantita'])))
		$quantita = safe($_SESSION['squantita']);
	else
		$quantita = NULL;
}

if ($DEBUG) {
	if (isset($quantita)) $log .= remesg("Valore quantita: ".$quantita,"debug");
	if (isset($iquantita)) $log .= remesg("Inserimento quantita: ".$iquantita,"debug");
	if (isset($squantita)) $log .= remesg("Suggerimento quantita: ".$squantita,"debug");
}

// 136. destinazione
if (isset($_SESSION['idestinazione'])AND(!empty($_SESSION['idestinazione'])))
	$destinazione = epura_special2chars(safe($_SESSION['idestinazione']));
else {
	if (isset($_SESSION['sdestinazione'])AND(!empty($_SESSION['sdestinazione'])))
		$destinazione = safe($_SESSION['sdestinazione']);
	else
		$destinazione = NULL;
}

if ($DEBUG) {
	if (isset($destinazione)) $log .= remesg("Valore destinazione: ".$destinazione,"debug");
	if (isset($idestinazione)) $log .= remesg("Inserimento destinazione: ".$idestinazione,"debug");
	if (isset($sdestinazione)) $log .= remesg("Suggerimento destinazione: ".$sdestinazione,"debug");
}

// 137. data_doc_scarico
if (isset($_SESSION['idata_doc_scarico'])AND(!empty($_SESSION['idata_doc_scarico'])))
	$data_doc_scarico = safe($_SESSION['idata_doc_scarico']);
else {
	if (isset($_SESSION['sdata_doc_scarico'])AND(!empty($_SESSION['sdata_doc_scarico'])))
		$data_doc_scarico = safe($_SESSION['sdata_doc_scarico']);
	else
		$data_doc_scarico = NULL;
}

if ($DEBUG) {
	if (isset($data_doc_scarico)) $log .= remesg("Valore data_doc_scarico: ".$data_doc_scarico,"debug");
	if (isset($idata_doc_scarico)) $log .= remesg("Inserimento data_doc_scarico: ".$idata_doc_scarico,"debug");
	if (isset($sdata_doc_scarico)) $log .= remesg("Suggerimento data_doc_scarico: ".$sdata_doc_scarico,"debug");
}

// 138. note
if (isset($_SESSION['inote'])AND(!empty($_SESSION['inote'])))
	$note = safe($_SESSION['inote']);
else {
	if (isset($_SESSION['snote'])AND(!empty($_SESSION['snote'])))
		$note = safe($_SESSION['snote']);
	else
		$note = NULL;
}

if ($DEBUG) {
	if (isset($note)) $log .= remesg("Valore note: ".$note,"debug");
	if (isset($inote)) $log .= remesg("Inserimento note: ".$inote,"debug");
	if (isset($snote)) $log .= remesg("Suggerimento note: ".$snote,"debug");
}

// 139. id_merce - tags - posizione - maxquantita
if (isset($_SESSION['id_merce'])AND(!empty($_SESSION['id_merce'])))
	$id_merce = safe($_SESSION['id_merce']);
else {
	$id_merce = NULL;
}

if (isset($_SESSION['tags'])AND(!empty($_SESSION['tags'])))
	$tags = epura_special2chars(safe($_SESSION['tags']));
else {
	$tags = NULL;
}

if (isset($_SESSION['posizione'])AND(!empty($_SESSION['posizione'])))
	$posizione = epura_special2chars(safe($_SESSION['posizione']));
else {
	$posizione = NULL;
}

if (isset($_SESSION['maxquantita'])AND(!empty($_SESSION['maxquantita'])))
	$maxquantita = safe($_SESSION['maxquantita']);
else {
	$maxquantita = NULL;
}

if ($DEBUG) {
	if (isset($id_merce)) $log .= remesg("Valore id_merce: ".$id_merce,"debug");
	if (isset($tags)) $log .= remesg("Valore tags: ".$tags,"debug");
	if (isset($posizione)) $log .= remesg("Valore posizione: ".$posizione,"debug");
	if (isset($maxquantita)) $log .= remesg("Valore maxquantita: ".$maxquantita,"debug");
}

// 140. num_mds
if (isset($_SESSION['num_mds'])AND(!empty($_SESSION['num_mds'])))
	$num_mds = safe($_SESSION['num_mds']);
else
	$num_mds = single_field_query("SELECT next_mds_doc();");


// 2. test bottoni

// 21. test stop
if (isset($_SESSION['stop'])) {
	if ($DEBUG) $log .= remesg("Valore tasto STOP: ".$_SESSION['stop'],"debug");

	// 211. test MDS iniziato
	if (isset($_SESSION['mds'])AND(!empty($_SESSION['mds']))) {

		// 2111. finish mds
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

		if ($DEBUG) $log .= remesg("Coda MDS: <textarea name='testo' rows='10' cols='100'>".$corpo_html."</textarea>","debug");
		if ($DEBUG) $log .= remesg("MDS fin'ora: <textarea name='testo' rows='10' cols='100'>".$_SESSION['mds']."</textarea>","debug");

		unset($corpo_html);
		$log .= remesg("Terminato modulo di scarico","done");

		// 2112. write mds
		$nome_report = "MDS-".$utente."-".epura_space2underscore($richiedente)."-".$data_doc_scarico."_".rand().".php";
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].registro_mds.$nome_report,"w");
		fwrite($fp,$_SESSION['mds']);
		fclose($fp);

		$log .= remesg("<a href=\"".registro_mds.$nome_report."\">Modulo di scarico</a> pronto per la stampa","pdf");

	}

	// 212. reset variabili server
	reset_sessione();

	// 213. alert
	$log .= remesg("Sessione terminata","done");
}

// 22. test add||save
if ((isset($_SESSION['add'])) OR (isset($_SESSION['save']))) {

	if (isset($_SESSION['add']))
		if ($DEBUG) $log .= remesg("Valore tasto ADD: ".$_SESSION['add'],"debug");

	if (isset($_SESSION['save']))
		if ($DEBUG) $log .= remesg("Valore tasto SAVE: ".$_SESSION['save'],"debug");

	// 221. validazione

	// 2211. id_merce(sentinella)
	if (is_null($id_merce) OR empty($id_merce)) {
		$log .= remesg("Selezione merce non andata a buon fine, ricominciare l'attivita'","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// 2212. utente
	if (is_null($utente) OR empty($utente)) {
		$log .= remesg("Mancata selezione di un utente per l'attivita' in corso","err");
		$valid = false;
	}
	if(!(in_array($utente, $enabled_users))){
		$log .= remesg("Utente non abilitato per l'attivita' in oggetto","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// 2213. richiedente
	if (is_null($richiedente) OR empty($richiedente)) {
		//$log .= remesg("Mancata selezione di un richiedente per l'attivita' in corso","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// 2214. quantita
	if (is_null($quantita) OR empty($quantita)) {
		//$log .= remesg("Mancata selezione di una quantita' per l'attivita' in corso","err");
		$valid = false;
	} else {
		if ($quantita>$maxquantita) {
			$log .= remesg("Quantita' richiesta superiore alla giacenza in magazzino per quella posizione","err");
			$valid = false;
		}
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// 2215. destinazione
	if (is_null($destinazione) OR empty($destinazione)) {
		//$log .= remesg("Mancato inserimento di una destinazione per l'attivita' in corso","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// 2216. data_doc_scarico
	if (is_null($data_doc_scarico) OR empty($data_doc_scarico)) {
		//$log .= remesg("Mancata selezione di una data per l'attivita' in corso","err");
		$valid = false;
	}

	if ($DEBUG) $log .= remesg("Stato variabile VALID: ".(($valid) ? "true" : "false"),"debug");

	// 222. test valid
	if ($valid) {

		// 2221. SCARICO
		$call = "CALL SCARICO('{$num_mds}','{$utente}','{$richiedente}','{$id_merce}','{$quantita}','{$posizione}','{$destinazione}','{$data_doc_scarico}','{$data_scarico}','{$note}',@myvar);";
		if ($DEBUG) $log .= remesg($call,"debug");

		$res_scarico = mysql_query($call);

		if ($res_scarico)
			$log .= remesg("Scarico inviato al database","done");
		else
			die('Errore nell\'invio dei dati al db: '.mysql_error());

		$ritorno = mysql_fetch_array($res_scarico, MYSQL_NUM);
		mysql_free_result($res_scarico);

		// 2222. logging
		logging2($call,splog);

		// 2223. test ritorno
		if ($DEBUG) $log .= remesg("Ritorno sp: ".$ritorno[0],"debug");

		if ($ritorno[0]=="0") {

			$log .= remesg("Scarico completato correttamente","done");

			// 2224. MDS

			// 22241. test not exists
			if (!(isset($_SESSION['mds'])) OR empty($_SESSION['mds'])) {

				// 222411. create
				ob_start();
				include 'lib/template_mds1.php';
				$corpo_html = ob_get_clean();
				$_SESSION['mds'] = "<?php\n"."\$html = \"".addslashes($corpo_html)."\n";

				if ($DEBUG) $log .= remesg("Testa MDS: <textarea name='testo' rows='10' cols='100'>".$corpo_html."</textarea>","debug");
				if ($DEBUG) $log .= remesg("MDS fin'ora: <textarea name='testo' rows='10' cols='100'>".$_SESSION['mds']."</textarea>","debug");

				unset($corpo_html);
				$log .= remesg("Creato modulo di scarico","done");

			}

			// 22242. append
			ob_start();
			include 'lib/template_mds2.php';
			$corpo_html = ob_get_clean();
			$_SESSION['mds'] .= addslashes($corpo_html)."\n";

			if ($DEBUG) $log .= remesg("Corpo MDS: <textarea name='testo' rows='10' cols='100'>".$corpo_html."</textarea>","debug");
			if ($DEBUG) $log .= remesg("MDS fin'ora: <textarea name='testo' rows='10' cols='100'>".$_SESSION['mds']."</textarea>","debug");

			unset($corpo_html);
			$log .= remesg("Aggiunti valori al modulo di scarico","done");

			// 22243. reset id_merce - tags - posizione - maxquantita
			unset($_SESSION['id_merce'],$_SESSION['tags'],$_SESSION['posizione'],$_SESSION['maxquantita']);

			// 22244. reset quantita - destinazione
			unset($_SESSION['quantita'],$_SESSION['destinazione']);
			unset($_SESSION['iquantita'],$_SESSION['idestinazione']);
			unset($_SESSION['squantita'],$_SESSION['sdestinazione']);

			// lascio richiedente, data_doc_scarico, note

			// 2225. test save
			if(isset($_SESSION['save'])) {

				// 22251. finish mds
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

				if ($DEBUG) $log .= remesg("Coda MDS: <textarea name='testo' rows='10' cols='100'>".$corpo_html."</textarea>","debug");
				if ($DEBUG) $log .= remesg("MDS fin'ora: <textarea name='testo' rows='10' cols='100'>".$_SESSION['mds']."</textarea>","debug");

				unset($corpo_html);
				$log .= remesg("Terminato modulo di scarico","done");

				// 22252. write mds
				$nome_report = "MDS-".$utente."-".epura_space2underscore($richiedente)."-".$data_doc_scarico."_".rand().".php";
				$fp = fopen($_SERVER['DOCUMENT_ROOT'].registro_mds.$nome_report,"w");
				fwrite($fp,$_SESSION['mds']);
				fclose($fp);

				$log .= remesg("<a href=\"".registro_mds.$nome_report."\">Modulo di scarico</a> pronto per la stampa","pdf");

				// 22253. reset sessione server
				reset_sessione();

			}

		} else {

			// 2226. scarico non riuscito
			logging2("-- ultimo scarico non riuscito",splog);
			$log .= remesg("Scarico non riuscito, ripete l'operazione","err");

		}

	// 223. reset mysql connection
	mysql_close($conn);
	$conn = mysql_connect('localhost','magazzino','magauser');
	if (!$conn) die('Errore di connessione: '.mysql_error());
	$dbsel = mysql_select_db('magazzino', $conn);
	if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

	// 224. test not valid
	} else {

		// 2241. form input scarico
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=scarico");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		$a .= jsxdate;
		//$a .= jsaltrows;
		$a .= "<table class='altrowstable' id='alternatecolor'>\n";

		//$log .= remesg("Completare lo scarico sulla merce indicata","info");

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

			$a .= noinput_hidden("num_mds",$num_mds);

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
			//$a .= "<td><label for='irichiedente'>Richiedente</label></td>\n";
			if (isset($richiedente)) {
				$a .= "<td><label for='irichiedente'>Richiedente</label></td>\n";
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("srichiedente",$richiedente)."</td>\n";
			} else {
				$a .= "<td><label for='irichiedente'>Richiedente ".add_tooltip("Campo richiedente obbligatorio")."</label></td>\n";
				$a .= "<td><input type='text' name='irichiedente'/></td>\n";
				$a .= "<td>".$richiedenti_merce."</td>\n";
			}
			$a .= "</tr>\n";

			$a .= "<tr>\n";
			$a .= "<td><label for='tags'>TAGS</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".noinput_hidden("id_merce",$id_merce).$tags."</td>\n";
			$a .= "</tr>\n";

			$a .= "<tr>\n";
			//$a .= "<td><label for='iquantita'>Quantita'</label></td>\n";
			if (isset($quantita) AND ($quantita<$maxquantita)) {
				$a .= "<td><label for='iquantita'>Quantita'</label></td>\n";
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("squantita",$quantita)." di ".$maxquantita."</td>\n";
			} else {
				$a .= "<td><label for='iquantita'>Quantita' ".add_tooltip("Campo quantita' obbligatorio e al piu' uguale alla giacenza in magazzino per quella posizione")."</label></td>\n";
				$a .= "<td><input type='text' name='iquantita'/></td>\n";
				$a .= "<td>Disponibilita': ".$maxquantita."</td>\n";
			}
			$a .= "</tr>\n";

			$a .= "<tr>\n";
			$a .= "<td><label for='posizione'>Posizione</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".input_hidden("posizione",$posizione)."</td>\n";
			$a .= "</tr>\n";

			$a .= "<tr>\n";
			//$a .= "<td><label for='idestinazione'>Destinazione</label></td>\n";
			if (isset($destinazione)) {
				$a .= "<td><label for='idestinazione'>Destinazione</label></td>\n";
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("sdestinazione",$destinazione)."</td>\n";
			} else {
				$a .= "<td><label for='idestinazione'>Destinazione ".add_tooltip("Campo destinazione obbligatorio")."</label></td>\n";
				$a .= "<td><input type='text' name='idestinazione'/></td>\n";
				$a .= "<td>".myoptlst("sdestinazione",$vserv_posizioni)."</td>\n";
			}
			$a .= "</tr>\n";

			$a .= "<tr>\n";
			//$a .= "<td><label for='idata_doc_scarico'>Data documento</label></td>\n";
			if (isset($data_doc_scarico)) {
				$a .= "<td><label for='idata_doc_scarico'>Data documento</label></td>\n";
				$a .= "<td></td>\n";
				$a .= "<td>".input_hidden("sdata_doc_scarico",$data_doc_scarico)."</td>\n";
			} else {
				$a .= "<td><label for='idata_doc_scarico'>Data documento ".add_tooltip("Campo data documento obbligatorio")."</label></td>\n";
				$a .= "<td></td>\n";
				//$a .= "<td><input name='idata_doc_scarico' type='date' value='' class='date'/></td>\n";
				$a .= "<td><input type='text' class='datepicker' name='idata_doc_scarico'/></td>\n";
			}
			$a .= "</tr>\n";

			$a .= "<tr>\n";
			$a .= "<td><label for='inote'>Note</label></td>\n";
			if (isset($note))
				$a .= "<td>".input_hidden("snote",$note)."</td>\n";
			else
				$a .= "<td><textarea rows='4' cols='25' name='inote'></textarea></td>\n";
			$a .= "<td>\n";
				$a .= remesg("Campo ad inserimento libero per dettagli vari mirati","info");
				$a .= remesg("al corretto recupero di informazioni a posteriori","info");
			$a .= "</td>\n";
			$a .= "</tr>\n";

		$a .= "</tbody>\n";

		$a .= "</table>\n";
		$a .= "</form>\n";

	}

}


// 3. test contenuti
if (is_null($a) OR empty($a)) {

	// 31. ricevo lista merce
	$result_lista_merce = mysql_query("SELECT * FROM vista_magazzino_ng;");
	if (!$result_lista_merce) die('Errore in ricezione lista merce dal db: '.mysql_error());

	// 32. form selezione
	$a .= jsxtable;
	$a .= jsaltrows;
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";
	//$log .= remesg("Lista estesa del contenuto del magazzino","info");
	$a .= "<thead><tr>\n";
		$a .= "<th>Merce</th>\n";
		$a .= "<th>Posizione</th>\n";
		$a .= "<th>Quantita'</th>\n";
		$a .= "<th>Dettagli</th>\n";
		$a .= "<th>Azione</th>\n";
	$a .= "</tr></thead>\n";
	$a .= "<tbody>\n";

	while ($row = mysql_fetch_array($result_lista_merce, MYSQL_NUM)) {

		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=scarico");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";

		$a .= "<tr>\n";

			foreach ($row as $cname => $cvalue)

				switch ($cname) {

					case 0:
						$a .= noinput_hidden("id_merce",$cvalue)."\n";
						$id_merce=$cvalue;
						break;

					case 1:
						$a .= "<td>\n";
						$a .= input_hidden("tags",$cvalue)."\n";
						$a .= "<a href=\"?page=transiti_search&id_merce=$id_merce\">[dettagli]</a>\n";
						$a .= "</td>\n";
						break;

					case 2:
						$a .= "<td>".input_hidden("posizione",$cvalue)."</td>\n";
						break;

					case 3:
						$a .= "<td>".input_hidden("maxquantita",$cvalue)."</td>\n";
						break;

					default:
						$a .= "<td>".$cvalue."</td>\n";
				}

			$a .= "<td><input type='submit' name='add' value='Scarico'/></td>\n";

		$a .= "</tr>\n";

		$a .= "</form>\n";
	}

	$a .= "</tbody>\n</table>\n";

	mysql_free_result($result_lista_merce);

}


// 4. libero risorse
mysql_close($conn);
session_write_close();


// 5. stampo
echo makepage($a, $log);

?>

