<?php


function vserv_magazzino_scarico($utente, array $array_session) {

// inizializzo variabili
$a = "";
$log = "";
$i=0;


// valorizzo da $array_session

$id_merce = $array_session['id_merce'];
$tags = $array_session['tags'];
$posizione = $array_session['posizione'];
$maxquantita = $array_session['maxquantita'];

// data_scarico
$data_scarico = date("Y-m-d");

// richiedente
if (isset($array_session['irichiedente'])AND(!empty($array_session['irichiedente'])))
	$richiedente = safe($array_session['irichiedente']);
else {
	if (isset($array_session['srichiedente'])AND(!empty($array_session['srichiedente'])))
		$richiedente = safe($array_session['srichiedente']);
	else
		$richiedente = NULL;
}

// quantita
if (isset($array_session['iquantita'])AND(!empty($array_session['iquantita'])))
	$quantita = safe($array_session['iquantita']);
else {
	if (isset($array_session['squantita'])AND(!empty($array_session['squantita'])))
		$quantita = safe($array_session['squantita']);
	else
		$quantita = NULL;
}

// destinazione
if (isset($array_session['idestinazione'])AND(!empty($array_session['idestinazione'])))
	$destinazione = safe($array_session['idestinazione']);
else {
	if (isset($array_session['sdestinazione'])AND(!empty($array_session['sdestinazione'])))
		$destinazione = safe($array_session['sdestinazione']);
	else
		$destinazione = NULL;
}

// data_doc_scarico
if (isset($array_session['idata_doc_scarico'])AND(!empty($array_session['idata_doc_scarico'])))
	$data_doc_scarico = safe($array_session['idata_doc_scarico']);
else {
	if (isset($array_session['sdata_doc_scarico'])AND(!empty($array_session['sdata_doc_scarico'])))
		$data_doc_scarico = safe($array_session['sdata_doc_scarico']);
	else
		$data_doc_scarico = NULL;
}

// note
if (isset($array_session['inote'])AND(!empty($array_session['inote'])))
	$note = safe($array_session['inote']);
else {
	if (isset($array_session['snote'])AND(!empty($array_session['snote'])))
		$note = safe($array_session['snote']);
	else
		$note = NULL;
}


// test submit
if (isset($array_session['submit'])) {


		// 4ba. validazione

		// 4baa. utente
		if (is_null($utente) OR empty($utente)) {
			$log .= remesg($msg1,"err");
			$valid = false;
		}
		if(!(in_array($utente, $enabled_users))){
			$log .= remesg($msg17,"err");
			$valid = false;
		}

		// 4bab. richiedente
		if (is_null($richiedente) OR empty($richiedente)) {
			$log .= remesg($msg24,"err");
			$valid = false;
		}

		// 4bac. quantita
		if (is_null($quantita) OR empty($quantita)) {
			$log .= remesg($msg25,"err");
			$valid = false;
		} else {
			if ($quantita>$maxquantita) {
				$log .= remesg($msg26,"err");
				$valid = false;
			}
		}

		// 4bad. destinazione
		if (is_null($destinazione) OR empty($destinazione)) {
			$log .= remesg($msg27,"err");
			$valid = false;
		}

		// 4bae. data_doc_scarico
		if (is_null($data_doc_scarico) OR empty($data_doc_scarico)) {
			$log .= remesg($msg28,"err");
			$valid = false;
		}



		// 4bb. test valid
		if ($valid == true) {


			// 4bba. SCARICO
			$call = "CALL SCARICO('{$utente}','{$richiedente}','{$id_merce}','{$quantita}','{$posizione}','{$destinazione}','{$data_doc_scarico}','{$data_scarico}','{$note}',@myvar);";
			$log .= remesg($call,"msg");

			$result_scarico = mysql_query($call);

			if ($result_scarico)
				$log .= remesg($msg29,"msg");
			else
				die('Errore nell\'invio del comando di scarico al db: '.mysql_error());

			$ritorno = mysql_fetch_array($result_scarico, MYSQL_NUM);

			// 4bbaa. logging
			logging($call);

			// 4bbb. test ritorno SCARICO
			switch ($ritorno[0]) {

				case "0":
					$log .= remesg($msg30,"msg");
					break;

				case "1":
					$log .= remesg($msg31,"err");
					break;

				default:
					$log .= remesg($msg32,"err");

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

			// 4bbda. definizione dati			
			ob_start();
			include 'lib/template_mds.php';
			$corpo_html = ob_get_clean();

			// 4bbdb. definizione pagina
			$report .= "<?php\n";
			$report .= "\$html = \"".addslashes($corpo_html)."\";";
			$report .= "//==============================================================\n";
			$report .= "include(\"".lib_mpdf57."\");\n";
			$report .= "\$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);\n";
			$report .= "\$stylesheet = file_get_contents('../020/css/mds.css');\n";
			$report .= "\$mpdf->WriteHTML(\$stylesheet,1);\n";			
			$report .= "\$mpdf->WriteHTML(\"\$html\");\n";
			$report .= "\$mpdf->Output();\n";
			$report .= "exit;\n";
			$report .= "//==============================================================\n";
			$report .= "?>\n";

			// 4bbdc. scrittura contenuti
			$nome_report = "MDS-".$utente."-".epura_space2underscore($richiedente)."-".$data_doc_scarico.".php";
			$fp = fopen($_SERVER['DOCUMENT_ROOT'].registro_mds.$nome_report,"w");
			fwrite($fp,$report);
			fclose($fp);

			$log .= remesg("<a href=\"".registro_mds.$nome_report."\">Modulo di scarico</a> pronto per la stampa","msg");

			// 4bbe. reset variabili
			$selezionato = false;


			$log .= remesg($msg33,"msg");
			$array_session = array();
			session_unset();
			session_destroy();

			/* generate new session id and delete old session in store */
			session_regenerate_id(true);
			if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

		} // fine test valid

	} // fine test submit

	// 4c. form scarico
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
				$a .= "<input type='submit' name='submit' value='Scarico'/>\n";
				$a .= "<input type='submit' name='stop' value='Fine'/>\n";
			$a .= "</td>\n";
			$a .= "<td>\n";
				$a .= remesg($msg21,"msg");
				$a .= remesg($msg22,"msg");
				$a .= remesg($msg23,"msg");
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

// ritorno pagina
$array_return[0] = $a;
$array_return[1] = $log;
return $array_return;


}

?>
