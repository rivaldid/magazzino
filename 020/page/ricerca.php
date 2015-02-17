<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializzo risorse

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// variabili
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;
	
$a = ""; $log = "";

$log .= remesg("PAGINA IN SVILUPPO","err");

if (isset($_POST['data1'])AND(!empty($_POST['data1']))) {
	$data1 = safe($_POST['data1']);
} else {
	$data1 = NULL;
}

if (isset($_POST['data2'])AND(!empty($_POST['data2']))) {
	$data2 = safe($_POST['data2']);
} else {
	$data2 = NULL;
}

if (isset($_POST['merce'])AND(!empty($_POST['merce']))) {
	$merce = safe($_POST['merce']);
} else {
	$merce = NULL;
}

if (isset($_POST['documento'])AND(!empty($_POST['documento']))) {
	$documento = safe($_POST['documento']);
} else {
	$documento = NULL;
}

if (isset($_POST['note'])AND(!empty($_POST['note']))) {
	$note = safe($_POST['note']);
} else {
	$note = NULL;
}

if (isset($_POST['posizione'])AND(!empty($_POST['posizione']))) {
	$posizione = safe($_POST['posizione']);
} else {
	$posizione = NULL;
}

if (isset($_POST['destinazione'])AND(!empty($_POST['destinazione']))) {
	$destinazione = safe($_POST['destinazione']);
} else {
	$destinazione = NULL;
}



// test invia
if (isset($_POST['invia'])) {
	
	if (isset($data1,$data2)) {
	
	
		//  ******************* PDF *******************
	
		// interrogazione
		$query = "SELECT doc_ingresso,doc_ordine,utente,DATE_FORMAT(data,'%d/%m/%Y'),status,posizione,documento,DATE_FORMAT(data_doc,'%d/%m/%Y'),tags,quantita,note,ordine FROM TRANSITI WHERE data BETWEEN '{$data1}' AND '{$data2}' OR data_doc BETWEEN '{$data1}' AND '{$data2}';";
		$res = mysql_query($query);
		if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());

		// risultati
		$pdf = "<div id='contenitore'>\n";
		$pdf .= "<table class='altrowstable' id='alternatecolor'>\n";

		$pdf .= "<thead><tr>\n";
			$pdf .= "<th>Utente</th>\n";
			$pdf .= "<th>Data transito</th>\n";
			$pdf .= "<th>Direzione</th>\n";
			$pdf .= "<th>Posizione</th>\n";
			$pdf .= "<th>Documento</th>\n";
			$pdf .= "<th>Data documento</th>\n";
			$pdf .= "<th>TAGS</th>\n";
			$pdf .= "<th>Quantita'</th>\n";
			$pdf .= "<th>Note</th>\n";
			$pdf .= "<th>ODA</th>\n";
		$pdf .= "</tr></thead>\n";
		
		$pdf .= "<tbody>\n";

		while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
						
			$pdf .= "<tr>\n";
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
							$pdf .= "<td><a href=\"".registro.$doc_ingresso."\">".safetohtml($cvalue)."</a></td>\n";
						else
							$pdf .= "<td>".safetohtml($cvalue)."</td>\n";
						break;

					case "10":
						if ($doc_ordine != NULL)
							$pdf .= "<td><a href=\"".registro.$doc_ordine."\">".safetohtml($cvalue)."</a></td>\n";
						else
							$pdf .= "<td>".safetohtml($cvalue)."</td>\n";
						break;

					default:
						$pdf .= "<td>".safetohtml($cvalue)."</td>\n";

				} // end switch

			$pdf .= "</tr>\n";	
			
		} // end while
		
		$pdf .= "</tbody>\n</table>\n</div>\n";
				
		
		// crea pdf
		$export = "<?php\n"."\$html = \"".addslashes($pdf)."\";\n";
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
		
		
		// salva pdf
		$nome_export_pdf = "ricerca__".date("Y-m-d__H.m.s")."__".rand().".php";
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$nome_export_pdf,"w");
		fwrite($fp,$export);
		fclose($fp);

		$log .= remesg("<a href=\"".ricerche.$nome_export_pdf."\">Ricerca</a> pronta per la stampa","msg");
		mysql_free_result($res);
		
		
		//  ******************* CSV  *******************
		
		// interrogazione
		$query = "SELECT utente,DATE_FORMAT(data,'%d/%m/%Y'),status,posizione,documento,DATE_FORMAT(data_doc,'%d/%m/%Y'),tags,quantita,note,ordine FROM TRANSITI WHERE data BETWEEN '{$data1}' AND '{$data2}' OR data_doc BETWEEN '{$data1}' AND '{$data2}';";
		$csv = array("Utente","Data transito","Direzione","Posizione","Documento","Data documento","TAGS","Quantita","Note","ODA");
		
		
		// risultati per csv
		print_r($csv);
		//download_send_headers("ricerca__".date("Y-m-d__H.m.s")."__".rand().".csv");
		//$log .= remesg(array2csv($csv),"msg");
		//die();
			
	} // end test date
	

} // end test invia



$a .= jsxtable;
//$a .= jsaltrows;
$a .= jsxdate;


// form ricerca documento
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=ricerca");
if ($DEBUG) $a .= "&debug";
$a .= "'>\n";
$a .= "<table class='altrowstable' id='alternatecolor' >\n";

$a .= "<thead><tr >\n";
	$a .= "<th colspan='2'>Ricerca</th>\n";
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
		$a .= "<td>Obiettivo ricerca</td>\n";
		$a .= "<td>".$obiettivi_ricerca."</td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td>numero documento o parte di esso</td>\n";
		$a .= "<td><input type='text' name='num_doc'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>mittente documento</td>\n";
		$a .= "<td><input type='text' name='mittente'/></td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";

	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td>per Mittente</td>\n";
		$a .= "<td><input type='text' name='mittente'/></td>\n";
	$a .= "</tr>\n";
	$a .= "<td><input type='text' class='datepicker' name='data_doc1'/> - <input type='text' class='datepicker' name='data_doc2'/></td>\n";

$a .= "</tbody>\n";

$a .= "</table></form>\n";


// form ricerca transito
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=ricerca");
if ($DEBUG) $a .= "&debug";
$a .= "'>\n";
$a .= "<table class='altrowstable' id='alternatecolor' >\n";

$a .= "<thead><tr >\n";
	$a .= "<th colspan='2'>Ricerca transito per criterio</th>\n";
$a .= "</tr></thead>\n";

$a .= "<tfoot>\n";
	$a .= "<tr>\n";
	$a .= "<td colspan='2'>\n";
		$a .= "<input type='reset' name='reset' value='Pulisci il foglio'/>\n";
		$a .= "<input type='submit' name='cerca_transito' value='Invia'/>\n";
	$a .= "</td>\n";
	$a .= "</tr>\n";
$a .= "</tfoot>\n";

$a .= "<tbody>\n";

	$a .= "<tr>\n";
		$a .= "<td>per Operatore</td>\n";
		$a .= "<td><input type='text' name='utente'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>per Intervallo Transito</td>\n";
		$a .= "<td><input type='text' class='datepicker' name='data_transito1'/> - <input type='text' class='datepicker' name='data_transito2'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>per Intervallo emissione Documento</td>\n";
		$a .= "<td><input type='text' class='datepicker' name='data_doc1'/> - <input type='text' class='datepicker' name='data_doc2'/></td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td>per Numero di documento (o ODA)</td>\n";
		$a .= "<td><input type='text' name='num_doc'/></td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td>per TAGS</td>\n";
		$a .= "<td><input type='text' name='tags'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>per Note</td>\n";
		$a .= "<td><input type='text' name='note'/></td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td>per Provenienza</td>\n";
		$a .= "<td><input type='text' name='provenienza'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>per Destinazione</td>\n";
		$a .= "<td><input type='text' name='destinazione'/></td>\n";
	$a .= "</tr>\n";

$a .= "</tbody>\n";

$a .= "</table></form>\n";


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);

?>
