<?php

// inizializzo risorse

// variabili
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

$a = ""; $log = "";
$riga = "";  $export = "";

// test id_merce in GET
if (isset($_GET['id_merce'])) {
	$id_merce = $_GET['id_merce'];
	$_POST['invia']="Invia";
	$log .= remesg("Torna alla <a href=\"".$_SERVER['HTTP_REFERER']."\">visualizzazione scarichi</a>","action");
}
else {
	$id_merce = NULL;
	$log .= remesg("Torna alla <a href=\"?page=transiti\">visualizzazione transiti</a>","action");
}

$data = date("d/m/Y");
$utente = $_SERVER["PHP_AUTH_USER"];

// data_min data_max
if (isset($_POST['data_min'])AND(!empty($_POST['data_min'])))
	$data_min = date("d/m/Y", strtotime($_POST['data_min']));
else
	$data_min = NULL;
if (isset($_POST['data_max'])AND(!empty($_POST['data_max'])))
	$data_max = date("d/m/Y", strtotime($_POST['data_max']));
else
	$data_max = NULL;

// tags
if (isset($_POST['tags'])AND(!empty($_POST['tags'])))
	$tags = trim(epura_space2percent($_POST['tags']));
else
	$tags = NULL;

// documento
if (isset($_POST['documento'])AND(!empty($_POST['documento'])))
	$documento = trim(epura_space2percent($_POST['documento']));
else
	$documento = NULL;

// ODA
if (isset($_POST['oda'])AND(!empty($_POST['oda'])))
	$oda = trim($_POST['oda']);
else
	$oda = NULL;

//note
if (isset($_POST['note'])AND(!empty($_POST['note'])))
	$note = trim(epura_space2percent($_POST['note']));
else
	$note = NULL;


// test invia
if (isset($_POST['invia'])) {

	$log .= remesg("Effettua una nuova <a href=\"?page=transiti_search\">ricerca</a> in transiti","search");

	$query = myquery::transiti_search($db,$id_merce,$data_min,$data_max,$tags,$documento,$oda,$note);

	// test ritorno valori
	if ($query) {
	
		// inizializzo pdf
		ob_start();
		$export = "<?php\n\$html = \"";
		include 'lib/template_export_pdf.php';
		$export .= addslashes(ob_get_clean());


		// risultati
		$a .= jsxtable;
		$a .= jsaltrows;
		$a .= "<table class='altrowstable' id='alternatecolor'>\n";

		$a .= "<thead><tr>\n";
			$a .= "<th>Utente</th>\n";
			$a .= "<th>Data transito</th>\n";
			$a .= "<th>Direzione</th>\n";
			$a .= "<th>Posizione</th>\n";
			$a .= "<th>Documento</th>\n";
			$a .= "<th>Data documento</th>\n";
			$a .= "<th>TAGS</th>\n";
			$a .= "<th>Quantita'</th>\n";
			$a .= "<th>Note</th>\n";
			$a .= "<th>ODA</th>\n";
		$a .= "</tr></thead>\n";
		$a .= "<tbody>\n";


		foreach ($query as $row) {
			$riga .= "<tr>\n";

			//print_r($row);

			$riga .= "<td>".safetohtml($row['2'])."</td>\n";
			$riga .= "<td>".safetohtml($row['3'])."</td>\n";
			$riga .= "<td>".safetohtml($row['4'])."</td>\n";
			$riga .= "<td>".safetohtml($row['5'])."</td>\n";

			if (isset($row['0']) AND ($row['0']!= NULL))
				$riga .= "<td><a href=\"".registro.$row['0']."\">".safetohtml($row['6'])."</a></td>\n";
			else
				$riga .= "<td>".safetohtml($row['6'])."</td>\n";

			$riga .= "<td>".safetohtml($row['7'])."</td>\n";
			$riga .= "<td>".safetohtml($row['8'])."</td>\n";
			$riga .= "<td>".safetohtml($row['9'])."</td>\n";
			$riga .= "<td>".safetohtml(strtolower($row['10']))."</td>\n";

			if (isset($row['1']) AND ($row['1']!= NULL))
				$riga .= "<td><a href=\"".registro.$row['1']."\">".safetohtml($row['11'])."</a></td>\n";
			else
				$riga .= "<td>".safetohtml($row['11'])."</td>\n";

			$riga .= "</tr>\n";

		} // fine stampa risultati


		$a .= $riga;
		$a .= "</tbody>\n</table>\n";

		$export .= addslashes($riga);
		$export .= "</table>\n</div>\";\n";
		$export .= "//==============================================================\n";
		$export .= "include(\"../../".lib_mpdf57."\");\n";
		$export .= "\$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);\n";
		$export .= "\$stylesheet = file_get_contents('../../css/mds.css');\n";
		$export .= "\$mpdf->WriteHTML(\$stylesheet,1);\n";
		$export .= "\$mpdf->WriteHTML(\"\$html\");\n";
		$export .= "\$mpdf->Output();\n";
		$export .= "exit;\n";
		$export .= "//==============================================================\n";
		$export .= "?>";


		// salvo export pdf
		$file_export = "export_ricerca_transiti.php";
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$file_export,"w");
		fwrite($fp,$export);
		fclose($fp);
		$log .= remesg("<a href=\"".ricerche.$file_export."\">Esporta in pdf</a> ","pdf");
	
	// altrimenti avvisa
	} else
		$a .= remesg("Nessun risultato trovato, ridefinire la ricerca con diversi parametri.","warn");

} // end test invia



// test contenuti
if (is_null($a) OR empty($a)) {

	// form input
	$a .= jsxdate;
	$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=transiti_search");
	if ($DEBUG) $a .= "&debug";
	$a .= "'>\n";
	$a .= "<table class='altrowstable' id='alternatecolor' >\n";

	$a .= "<thead><tr>\n";
		$a .= "<th>Criterio</th>\n";
		$a .= "<th>Valori</th>\n";
	$a .= "</tr></thead>\n";

	$a .= "<tfoot>\n";
		$a .= "<tr>\n";
		$a .= "<td colspan='2'>\n";
			$a .= "<input type='reset' name='reset' value='Pulisci il foglio'/>\n";
			$a .= "<input type='submit' name='invia' value='Invia'/>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";
	$a .= "</tfoot>\n";

	$a .= "<tbody>\n";

		$a .= "<tr>\n";
			$a .= "<td>intervallo</td>\n";
			$a .= "<td><input type='text' class='datepicker' name='data_min'/> - <input type='text' class='datepicker' name='data_max'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>tags</td>\n";
			$a .= "<td><input type='text' name='tags'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>documento</td>\n";
			$a .= "<td><input type='text' name='documento'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>ordine</td>\n";
			$a .= "<td><input type='text' name='oda'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>Note</td>\n";
			$a .= "<td><input type='text' name='note'/></td>\n";
		$a .= "</tr>\n";

	$a .= "</tbody>\n";

	$a .= "</table></form>\n";

}

// stampo
echo makepage($a, $log);

?>

