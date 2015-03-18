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


// documento: fornitore - tipo - numero
if (isset($_POST['documento'])AND(!empty($_POST['documento'])))
	$documento = trim(epura_space2percent(safe($_POST['documento'])));
else
	$documento = NULL;

// tags
if (isset($_POST['tags'])AND(!empty($_POST['tags'])))
	$tags = trim(epura_space2percent(safe($_POST['tags'])));
else
	$tags = NULL;

if (isset($_POST['posizione'])AND(!empty($_POST['posizione'])))
	$posizione = trim(safe($_POST['posizione']));
else
	$posizione = NULL;

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// test invia
if (isset($_POST['invia'])) {
	
	$log .= remesg("Effettua una nuova <a href=\"?page=transiti_search\">ricerca</a> in transiti","search");
	
	$q = "SELECT doc_ingresso,doc_ordine,utente,DATE_FORMAT(data,'%d/%m/%Y'),status,posizione,documento,DATE_FORMAT(data_doc,'%d/%m/%Y'),tags,quantita,note,ordine FROM TRANSITI WHERE 1";
	
	if ($documento) $q .= " AND documento LIKE '%".$documento."%' ";
	if ($tags) $q .= " AND tags LIKE '%".$tags."%' ";
	if ($posizione) $q .= " AND MAGAZZINO.posizione='".$posizione."' ";
	if ($data1 AND $data2) $q .= " AND data BETWEEN '2015-01-01' AND '2015-01-31' ORDER BY data DESC;";
	
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
			$riga .= "<td>".$cvalue."</td>\n";
		$riga .= "</tr>\n";
	}

	$a .= $riga;
	$a .= "</tbody>\n</table>\n";

	$export .= $riga;
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
	$file_export = "export_magazzino.php";
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
		$a .= "<th colspan='2'>Ricerca in magazzino</th>\n";
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
			$a .= "<td>per intervallo</td>\n";
			$a .= "<td><input type='text' class='datepicker' name='data_min'/> - <input type='text' class='datepicker' name='data_max'/></td>\n";
		$a .= "</tr>\n";

	$a .= "</tbody>\n";

	$a .= "</table></form>\n";
	
}


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);


?>

