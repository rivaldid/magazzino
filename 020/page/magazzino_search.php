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

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// log
$log .= remesg("Aggiornamento <a href=\"?page=aggiornamento_magazzino\">posizione o quantita'</a> in magazzino","action");
$log .= remesg("<a href=\"?page=contromagazzino\">Contromagazzino</a>","action");


// test invia
if (isset($_POST['invia'])) {
	
	if (isset($_GET["id"])) {
		$query = "SELECT * FROM vserv_magazzino_id;";
		$log .= remesg("Visualizzazione senza <a href=\"?page=magazzino\">ID</a>","msg");
	} else {
		$query = "SELECT * FROM vserv_magazzino;";
		$log .= remesg("Visualizzazione con <a href=\"?page=magazzino&id\">ID</a>","msg");
	}

	// interrogazione
	$res = mysql_query($query);
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
		if (isset($_GET["id"])) $a .= "<th>ID</th>\n";
		$a .= "<th>TAGS</th>\n";
		$a .= "<th>Posizioni con parziali</th>\n";
		$a .= "<th>Tot</th>\n";
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




// form input
$a .= jsxdate;
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=ricerca");
if ($DEBUG) $a .= "&debug";
$a .= "'>\n";
$a .= "<table class='altrowstable' id='alternatecolor' >\n";

$a .= "<thead><tr>\n";
	$a .= "<th colspan='2'>Visualizza il magazzino</th>\n";
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
		$a .= "<td>Filtra per intervallo</td>\n";
		$a .= "<td><input type='text' class='datepicker' name='data_doc1'/> - <input type='text' class='datepicker' name='data_doc2'/></td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td>Filtra per ODA</td>\n";
		$a .= "<td><input type='text' name='num_doc'/></td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td>Filtra per tags</td>\n";
		$a .= "<td><input type='text' name='tags'/></td>\n";
	$a .= "</tr>\n";

$a .= "</tbody>\n";

$a .= "</table></form>\n";


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);


?>

