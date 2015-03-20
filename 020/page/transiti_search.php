<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializzo risorse

// variabili
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;
	
$a = ""; $log = "";
$riga = "";  $export = "";

$data = date("d/m/Y");
$utente = $_SERVER["AUTHENTICATE_UID"];

$log .= remesg("Torna alla <a href=\"?page=transiti\">visualizzazione transiti</a>","action");


// data_min data_max
if (isset($_POST['data_min'])AND(!empty($_POST['data_min'])))
	$data_min = safe($_POST['data_min']);
else
	$data_min = NULL;
if (isset($_POST['data_max'])AND(!empty($_POST['data_max'])))
	$data_max = safe($_POST['data_max']);
else
	$data_max = NULL;
	
// tags
if (isset($_POST['tags'])AND(!empty($_POST['tags'])))
	$tags = trim(epura_space2percent(safe($_POST['tags'])));
else
	$tags = NULL;
	
// documento
if (isset($_POST['documento'])AND(!empty($_POST['documento'])))
	$documento = trim(epura_space2percent(safe($_POST['documento'])));
else
	$documento = NULL;

// ODA
if (isset($_POST['oda'])AND(!empty($_POST['oda'])))
	$oda = trim(safe($_POST['oda']));
else
	$oda = NULL;

//note
if (isset($_POST['note'])AND(!empty($_POST['note'])))
	$note = trim(epura_space2percent(safe($_POST['note'])));
else
	$note = NULL;


// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// test invia
if (isset($_POST['invia'])) {
	
	$log .= remesg("Effettua una nuova <a href=\"?page=transiti_search\">ricerca</a> in transiti","search");
	
	$q = "SELECT doc_ingresso,doc_ordine,utente,DATE_FORMAT(data,'%d/%m/%Y'),status,posizione,documento,DATE_FORMAT(data_doc,'%d/%m/%Y'),tags,quantita,note,ordine FROM TRANSITI WHERE 1";
	
	if ($data_min AND $data_max) $q .= " AND data BETWEEN '$data_min' AND '$data_max'";
	if ($tags) $q .= " AND tags LIKE '%$tags%'";
	if ($documento) $q .= " AND documento LIKE '%$documento%'";
	if ($oda) $q .= " AND ordine LIKE '%$oda%'";
	if ($note) $q .= " AND note LIKE '%$note%'";
	
	// interrogazione
	$res = mysql_query($q);
	if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


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

	while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$riga .= "<tr>\n";
	foreach ($row as $cname => $cvalue)
		switch ($cname) {

			case "0":
				$doc_ingresso = $cvalue;
				break;

			case "1":
				$doc_ordine = $cvalue;
				break;

			case "6":
				if ($doc_ingresso != NULL)
					$riga .= "<td><a href=\"".registro.$doc_ingresso."\">".safetohtml($cvalue)."</a></td>\n";
				else
					$riga .= "<td>".safetohtml($cvalue)."</td>\n";
				break;

			case "10":
				if ($doc_ordine != NULL)
					$riga .= "<td><a href=\"".registro.$doc_ordine."\">".safetohtml($cvalue)."</a></td>\n";
				else
					$riga .= "<td>".safetohtml($cvalue)."</td>\n";
				break;

			default:
				$riga .= "<td>".safetohtml($cvalue)."</td>\n";

		} // end switch

	$riga .= "</tr>\n";

	} // end while
	

	$a .= $riga;
	$a .= "</tbody>\n</table>\n";

	$export .= addslashes($riga);
	$export .= "</table>\n</div>\";\n";
	$export .= "//==============================================================\n";
	$export .= "include(\"".lib_mpdf57."\");\n";
	$export .= "\$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);\n";
	$export .= "\$stylesheet = file_get_contents('../020/css/mds.css');\n";
	$export .= "\$mpdf->WriteHTML(\$stylesheet,1);\n";
	$export .= "\$mpdf->WriteHTML(\"\$html\");\n";
	$export .= "\$mpdf->Output();\n";
	$export .= "exit;\n";
	$export .= "//==============================================================\n";
	$export .= "?>";
	
	mysql_free_result($res);
	
	
	// salvo export pdf
	$file_export = "export_transiti.php";
	$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$file_export,"w");
	fwrite($fp,$export);
	fclose($fp);
	$log .= remesg("Scarica <a href=\"".ricerche.$file_export."\">dati</a> in pdf","pdf");

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


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);


?>

