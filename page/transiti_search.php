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
	$log .= remesg("Torna alla <a href=\"".$_SERVER['HTTP_REFERER']."\">visualizzazione merce per scarico</a>","action");
}
else {
	$id_merce = NULL;
	$log .= remesg("Torna alla <a href=\"?page=transiti\">visualizzazione transiti</a>","action");
}

$data = date("d/m/Y");
$utente = $_SERVER["PHP_AUTH_USER"];

/* test valori per ricerca veloce
	se dato un pattern
		vedi quale campo ho scelto tramite target
		quindi valorizza il campo scelto con il valore pattern
*/
if (isset($_POST['pattern'])AND(!empty($_POST['pattern']))) {
	if (isset($_POST['target'])AND(!empty($_POST['target']))) {

		if ($DEBUG) $log .= remesg("Valore variabile target: ".$_POST['target'],"debug");
		if ($DEBUG) $log .= remesg("Valore variabile pattern: ".$_POST['pattern'],"debug");

		switch ($_POST['target']) {
			case "merce":
				$_POST['tags'] = $_POST['pattern'];
				break;
			case "documento":
				$_POST['documento'] = $_POST['pattern'];
				break;
			case "posizione":
				$_POST['posizione'] = $_POST['pattern'];
				break;
			case "ordine":
				$_POST['ordine'] = $_POST['pattern'];
				break;
			case "note":
				$_POST['note'] = $_POST['pattern'];
				break;
			default:
				$log .= remesg("Errore nel passaggio del campo da ricerca. Torna alla <a href=\"?page=transiti\">visualizzazione transiti</a>","warn");
		}
	}
}

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
if (isset($_GET['tags'])AND(!empty($_GET['tags']))) {
	$log .= remesg("Torna alla <a href=\"".$_SERVER['HTTP_REFERER']."\">visualizzazione magazzino</a>","action");
	$tags = unserialize(base64_decode($_GET['tags']));
	$_POST['invia'] = true;
} else {
	if (isset($_POST['tags'])AND(!empty($_POST['tags'])))
		$tags = trim(epura_space2percent($_POST['tags']));
	else
		$tags = NULL;
}

// documento
if (isset($_POST['documento'])AND(!empty($_POST['documento'])))
	$documento = trim(epura_space2percent($_POST['documento']));
else
	$documento = NULL;

// posizione
if (isset($_POST['posizione'])AND(!empty($_POST['posizione'])))
	$posizione = trim(epura_space2percent($_POST['posizione']));
else
	$posizione = NULL;

// ordine
if (isset($_POST['ordine'])AND(!empty($_POST['ordine'])))
	$ordine = trim($_POST['ordine']);
else
	$ordine = NULL;

// note
if (isset($_POST['note'])AND(!empty($_POST['note'])))
	$note = trim(epura_space2percent($_POST['note']));
else
	$note = NULL;


// test invia
if (isset($_POST['invia'])) {

	$log .= remesg("Effettua una nuova <a href=\"?page=transiti_search\">ricerca</a> in transiti","search");

	if ($DEBUG) $log .= remesg("Chiamata per transiti_search con variabili: $id_merce,$data_min,$data_max,$tags,$documento,$posizione,$ordine,$note","debug");
	$query = myquery::transiti_search($db,$id_merce,$data_min,$data_max,$tags,$documento,$posizione,$ordine,$note);

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

			$riga .= "<td>".safetohtml($row['rete'])."</td>\n";
			$riga .= "<td>".safetohtml($row['dataop'])."</td>\n";
			$riga .= "<td>".safetohtml($row['status'])."</td>\n";
			$riga .= "<td>".safetohtml($row['posizione'])."</td>\n";
			$riga .= "<td>".$row['documento']."</td>\n";
			$riga .= "<td>".safetohtml($row['data_doc'])."</td>\n";
			$riga .= "<td>".safetohtml($row['tags'])."</td>\n";
			$riga .= "<td>".safetohtml($row['quantita'])."</td>\n";
			$riga .= "<td>".safetohtml(strtolower($row['note']))."</td>\n";
			$riga .= "<td>".$row['doc_ordine']."</td>\n";

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
			$a .= "<td>Intervallo</td>\n";
			$a .= "<td><input type='text' class='datepicker' name='data_min'/> - <input type='text' class='datepicker' name='data_max'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>Merce</td>\n";
			$a .= "<td><input type='text' name='tags'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>Documento</td>\n";
			$a .= "<td><input type='text' name='documento'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>Posizione</td>\n";
			$a .= "<td><input type='text' name='posizione'/></td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
			$a .= "<td>ODA</td>\n";
			$a .= "<td><input type='text' name='ordine'/></td>\n";
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

