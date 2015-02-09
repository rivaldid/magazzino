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
	
		// interrogazione
		$query = "SELECT doc_ingresso,doc_ordine,utente,DATE_FORMAT(data,'%d/%m/%Y'),status,posizione,documento,DATE_FORMAT(data_doc,'%d/%m/%Y'),tags,quantita,note,ordine FROM TRANSITI WHERE data BETWEEN '{$data1}' AND '{$data2}' OR data_doc BETWEEN '{$data1}' AND '{$data2}';";
		$res = mysql_query($query);
		if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());

		// risultati
		$search = "<div id='contenitore'>\n";
		$search .= "<table class='altrowstable' id='alternatecolor'>\n";

		$search .= "<thead><tr>\n";
			$search .= "<th>Utente</th>\n";
			$search .= "<th>Data transito</th>\n";
			$search .= "<th>Direzione</th>\n";
			$search .= "<th>Posizione</th>\n";
			$search .= "<th>Documento</th>\n";
			$search .= "<th>Data documento</th>\n";
			$search .= "<th>TAGS</th>\n";
			$search .= "<th>Quantita'</th>\n";
			$search .= "<th>Note</th>\n";
			$search .= "<th>ODA</th>\n";
		$search .= "</tr></thead>\n";
		
		$search .= "<tbody>\n";

		while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
			$search .= "<tr>\n";
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
							$search .= "<td><a href=\"".registro.$doc_ingresso."\">".safetohtml($cvalue)."</a></td>\n";
						else
							$search .= "<td>".safetohtml($cvalue)."</td>\n";
						break;

					case "10":
						if ($doc_ordine != NULL)
							$search .= "<td><a href=\"".registro.$doc_ordine."\">".safetohtml($cvalue)."</a></td>\n";
						else
							$search .= "<td>".safetohtml($cvalue)."</td>\n";
						break;

					default:
						$search .= "<td>".safetohtml($cvalue)."</td>\n";

				} // end switch

			$search .= "</tr>\n";	
			
		} // end while
		
		$search .= "</tbody>\n</table>\n</div>\n";
		
		
		// crea pdf
		$export = "<?php\n"."\$html = \"".addslashes($search)."\";\n";
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
		$nome_export = "ricerca__".date("Y-m-d__H.m.s")."__".rand().".php";
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$nome_export,"w");
		fwrite($fp,$export);
		fclose($fp);

		$log .= remesg("<a href=\"".ricerche.$nome_export."\">Ricerca</a> pronta per la stampa","msg");


			
	} // end test date
	

} // end test invia

// form ricerca
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=ricerca");
if ($DEBUG) $a .= "&debug";
$a .= "'>\n";
$a .= jsxtable;
$a .= jsaltrows;
$a .= jsxdate;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";
	
$a .= "<thead><tr>\n";
	$a .= "<th colspan='2'>Ricerca</th>\n";
$a .= "</tr></thead>\n";

$a .= "<tfoot>\n";
	$a .= "<tr>\n";
	$a .= "<td colspan='2'>\n";
		$a .= "<input type='reset' name='reset' value='Pulisci il foglio'/>\n";
		$a .= "<input type='submit' name='invia' value='Avvia'/>\n";
	$a .= "</td>\n";
	$a .= "</tr>\n";
$a .= "</tfoot>\n";

$a .= "<tbody>\n";

	$a .= "<tr>\n";
		$a .= "<td>Per intervallo</td>\n";
		$a .= "<td><input type='text' class='datepicker' name='data1'/> - <input type='text' class='datepicker' name='data2'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>Per merce</td>\n";
		$a .= "<td><input type='text' name='merce'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>Per documento</td>\n";
		$a .= "<td><input type='text' name='documento'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>Per note</td>\n";
		$a .= "<td><input type='text' name='note'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>Per posizioni in magazzino</td>\n";
		$a .= "<td><input type='text' name='posizione'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>Per destinazioni fuori magazzino</td>\n";
		$a .= "<td><input type='text' name='destinazione'/></td>\n";
	$a .= "</tr>\n";

$a .= "</tbody>\n";

$a .= "</table>\n";
$a .= "</form>\n";



// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);

?>
