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

// session
session_apri();

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
$utente = $_SERVER["PHP_AUTH_USER"];

// 134. richiedente
//$richiedente = norm($_SESSION['irichiedente']) ?: $_SESSION['srichiedente'] ?: $_SESSION['richiedente'] ?: NULL;
if (isset($_SESSION['irichiedente'])AND(!empty($_SESSION['irichiedente'])))
	$richiedente = norm($_SESSION['irichiedente']);
else {
	if (isset($_SESSION['srichiedente'])AND(!empty($_SESSION['srichiedente'])))
		$richiedente = $_SESSION['srichiedente'];
	else
		$richiedente = NULL;
}

if ($DEBUG) {
	if (isset($richiedente)) $log .= remesg("Valore richiedente: ".$richiedente,"debug");
	if (isset($irichiedente)) $log .= remesg("Inserimento richiedente: ".$irichiedente,"debug");
	if (isset($srichiedente)) $log .= remesg("Suggerimento richiedente: ".$srichiedente,"debug");
}

// 135. quantita
//$quantita = $_SESSION['iquantita'] ?: $_SESSION['squantita'] ?: $_SESSION['quantita'] ?: NULL;
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

// 136. destinazione
//$destinazione = norm($_SESSION['idestinazione']) ?: $_SESSION['sdestinazione'] ?: $_SESSION['destinazione'] ?: NULL;
if (isset($_SESSION['idestinazione'])AND(!empty($_SESSION['idestinazione'])))
	$destinazione = norm($_SESSION['idestinazione']);
else {
	if (isset($_SESSION['sdestinazione'])AND(!empty($_SESSION['sdestinazione'])))
		$destinazione = $_SESSION['sdestinazione'];
	else
		$destinazione = NULL;
}

if ($DEBUG) {
	if (isset($destinazione)) $log .= remesg("Valore destinazione: ".$destinazione,"debug");
	if (isset($idestinazione)) $log .= remesg("Inserimento destinazione: ".$idestinazione,"debug");
	if (isset($sdestinazione)) $log .= remesg("Suggerimento destinazione: ".$sdestinazione,"debug");
}

// 137. data_doc_scarico
//$data_doc_scarico = $_SESSION['idata_doc_scarico'] ?: $_SESSION['sdata_doc_scarico'] ?: $_SESSION['data_doc_scarico'] ?: NULL;
if (isset($_SESSION['idata_doc_scarico'])AND(!empty($_SESSION['idata_doc_scarico'])))
	$data_doc_scarico = $_SESSION['idata_doc_scarico'];
else {
	if (isset($_SESSION['sdata_doc_scarico'])AND(!empty($_SESSION['sdata_doc_scarico'])))
		$data_doc_scarico = $_SESSION['sdata_doc_scarico'];
	else
		$data_doc_scarico = NULL;
}

if ($DEBUG) {
	if (isset($data_doc_scarico)) $log .= remesg("Valore data_doc_scarico: ".$data_doc_scarico,"debug");
	if (isset($idata_doc_scarico)) $log .= remesg("Inserimento data_doc_scarico: ".$idata_doc_scarico,"debug");
	if (isset($sdata_doc_scarico)) $log .= remesg("Suggerimento data_doc_scarico: ".$sdata_doc_scarico,"debug");
}

// 138. note
//$note = norm($_SESSION['inote']) ?: $_SESSION['snote'] ?: $_SESSION['note'] ?: NULL;
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

// 139. id_merce - tags - posizione - maxquantita
//$id_merce = $_SESSION['id_merce'] ?: NULL;
if (isset($_SESSION['id_merce'])AND(!empty($_SESSION['id_merce'])))
	$id_merce = $_SESSION['id_merce'];
else {
	$id_merce = NULL;
}

//$merce = $_SESSION['merce'] ?: NULL;
if (isset($_SESSION['merce'])AND(!empty($_SESSION['merce'])))
	$merce = norm($_SESSION['merce']);
else {
	$merce = NULL;
}

//$posizione = $_SESSION['posizione'] ?: NULL;
if (isset($_SESSION['posizione'])AND(!empty($_SESSION['posizione'])))
	$posizione = norm($_SESSION['posizione']);
else {
	$posizione = NULL;
}

//$maxquantita = $_SESSION['maxquantita'] ?: NULL;
if (isset($_SESSION['maxquantita'])AND(!empty($_SESSION['maxquantita'])))
	$maxquantita = $_SESSION['maxquantita'];
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
//$num_mds = $_SESSION['num_mds'] ?: myquery::next_mds_doc($db)[0];
if (isset($_SESSION['num_mds'])AND(!empty($_SESSION['num_mds'])))
	$num_mds = $_SESSION['num_mds'];
else
	$num_mds = myquery::next_mds_doc($db)[0];


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
		$_SESSION['mds'] .= addslashes($corpo_html).";\n";
		$_SESSION['mds'] .= "//==============================================================\n";
		$_SESSION['mds'] .= "include(\"../../".lib_mpdf57."\");\n";
		$_SESSION['mds'] .= "\$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);\n";
		$_SESSION['mds'] .= "\$stylesheet = file_get_contents('../../css/mds.css');\n";
		$_SESSION['mds'] .= "\$mpdf->WriteHTML(\$stylesheet,1);\n";
		$_SESSION['mds'] .= "\$mpdf->WriteHTML(\"\$html\");\n";
		$_SESSION['mds'] .= "\$mpdf->Output();\n";
		$_SESSION['mds'] .= "exit;\n";
		$_SESSION['mds'] .= "//==============================================================\n";
		$_SESSION['mds'] .= "?>";

		if ($DEBUG) $log .= remesg("Coda MDS: <textarea name='testo' rows='10' cols='100'>".$corpo_html."</textarea>","debug");
		if ($DEBUG) $log .= remesg("MDS fin'ora: <textarea name='testo' rows='10' cols='100'>".$_SESSION['mds']."</textarea>","debug");

		unset($corpo_html);
		$log .= remesg("Terminato modulo di scarico","done");

		// 2112. write mds
		$nome_report = "MDS-".$utente."-".epura_space2underscore($richiedente)."-".$data_doc_scarico."_".time().".php";
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].registro_mds.$nome_report,"w");
		fwrite($fp,$_SESSION['mds']);
		fclose($fp);

		$log .= remesg("<a href=\"".registro_mds.$nome_report."\">Modulo di scarico</a> pronto per la stampa","pdf");

	}

	// 212. reset variabili server
	session_riavvia();

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
		$ritorno = myquery::scarico($db,$num_mds,$utente,$richiedente,$id_merce,$quantita,$posizione,$destinazione,$data_doc_scarico,$data_scarico,$note)[0];
	
		// 2223. test ritorno
		if ($DEBUG) $log .= remesg("Ritorno sp: ".$ritorno,"debug");

		if ($ritorno=="0") {

			$log .= remesg("Scarico completato correttamente","done");

			// 2224. MDS

			// 22241. test not exists
			if (!(isset($_SESSION['mds'])) OR empty($_SESSION['mds'])) {

				// 222411. create
				ob_start();
				include 'lib/template_mds1.php';
				$corpo_html = ob_get_clean();
				$_SESSION['mds'] = "<?php\n\$html = \"".addslashes($corpo_html)."\n";

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
				$_SESSION['mds'] .= "\$stylesheet = file_get_contents('../../css/mds.css');\n";
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
			}

		} else {

			// 2226. scarico non riuscito
			logging2("-- ultimo scarico non riuscito",splog);
			$log .= remesg("Scarico non riuscito, ripetere l'operazione","err");

		}
		
		// scarico riuscito o no, reset sessione server per ripetere attivita
		session_riavvia();

		// 224. test not valid
	} else {
		
		$lista_destinazioni = myquery::destinazioni($db);

		// 2241. form input scarico
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=scarico");
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

			$a .= noinput_hidden("num_mds",$num_mds);

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
			$a .= "<td><label for='merce'>Merce</label></td>\n";
			$a .= "<td></td>\n";
			$a .= "<td>".noinput_hidden("id_merce",$id_merce).$merce."</td>\n";
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
				$a .= "<td>".myoptlst("sdestinazione",$lista_destinazioni)."</td>\n";
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
	$lista_merce = myquery::magazzino_detail($db);

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

	foreach ($lista_merce AS $elemento) {
		
		$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=scarico");
		if ($DEBUG) $a .= "&debug";
		$a .= "'>\n";
		$a .= "<tr>\n";
		
		$a .= "<td>\n";
		$a .= noinput_hidden("id_merce",$elemento['id_merce'])."\n";
		$a .= input_hidden("merce",$elemento['merce']);
		$a .= "<a href=\"?page=transiti_search&id_merce=".$elemento['id_merce']."\">[dettagli]</a>\n";
		$a .= "</td>\n";
		$a .= "<td>".input_hidden("posizione",$elemento['posizione'])."</td>\n";
		$a .= "<td>".input_hidden("maxquantita",$elemento['quantita'])."</td>\n";
		$a .= "<td>".input_hidden("note",$elemento['note'])."</td>\n";
		
		$a .= "<td><input type='submit' name='add' value='Scarico'/></td>\n";

		$a .= "</tr>\n";
		$a .= "</form>\n";
	}

	$a .= "</tbody>\n</table>\n";

}


// 4. libero risorse
session_chiudi();


// 5. stampo
echo makepage($a, $log);

?>

