<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializzo risorse

// variabili
$a = ""; $log = "";
$riga = ""; 

$data = date("d/m/Y");
$utente = $_SERVER["AUTHENTICATE_UID"];

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// log
$query = "SELECT * FROM vista_magazzino_ng;";


$log .= remesg("<a href=\"?page=magazzino\">Visualizzazione di default</a>","action");
$log .= remesg("<a href=\"?page=magazzino_update\">Aggiornamenti</a> sul magazzino","action");
$log .= remesg("<a href=\"?page=contromagazzino\">Contromagazzino</a>","action");
$log .= remesg("Effettua una <a href=\"?page=magazzino_search\">ricerca</a> nel magazzino","search");


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
	$a .= "<th>TAGS e Documenti</th>\n";
	$a .= "<th>Posizione</th>\n";
	$a .= "<th>Quantita'</th>\n";
	$a .= "<th>Ordine e Note</th>\n";
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


// termino risorse
mysql_close($conn);
mysql_free_result($res);


// salvo export pdf
$file_export = "export_magazzino.php";
$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$file_export,"w");
fwrite($fp,$export);
fclose($fp);

$log .= remesg("Scarica <a href=\"".ricerche.$file_export."\">dati</a> in pdf","pdf");

// stampo
echo makepage($a, $log);


?>

