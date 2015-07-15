<?php

// inizializza risorse

// variabili
$a = "";
$log = "";
$riga = "";

if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

// menu
$log .= $menu_transiti;


// test current
if (isset($_GET["current_page"])) {
	$current_page = $_GET["current_page"];
} else
	$current_page = 1;

// se intero ritorno pagination
if (testinteger($current_page)) {
	$array_return = myquery::transiti_pagination($db,$current_page);
	$index_pagination = $array_return[0];
	$query = $array_return[1];

// altrimenti ritorno nopagination
} else {
	$query = myquery::transiti_nopagination($db);
}


// stampo indice
if (isset($index_pagination)) $a .= $index_pagination;


// inizializzo pdf
ob_start();
$export = "<?php\n\$html = \"";
include 'lib/template_export_pdf.php';
$export .= addslashes(ob_get_clean());

// form ricerca
$a .= jsx_select_target_pattern;
$log .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=transiti_search");
if ($DEBUG) $log .= "&debug";
$log .= "'>\n";

$log .= "<fieldset>\n";
$log .= "<legend>Ricerca in transiti</legend>\n";
$log .= "<select id='target' name='target'>\n";
	$log .= "<option selected='selected' value=''>Seleziona...</option>\n";
	$log .= "<option value='merce'>Merce</option>\n";
	$log .= "<option value='documento'>Documento</option>\n";
	$log .= "<option value='posizione'>Posizione</option>\n";
	$log .= "<option value='ordine'>ODA</option>\n";
	$log .= "<option value='note'>Note</option>\n";
$log .= "</select>\n";

$log .= "<input type='text' id='pattern' name='pattern' disabled='disabled' />\n";
$log .= "<input type='submit' id='invia' name='invia' value='Invia' disabled='disabled' />\n";
$log .= "</fieldset>\n</form>\n";


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


// stampo indice
if (isset($index_pagination)) $a .= $index_pagination;


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
$file_export = "export_transiti.php";
$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$file_export,"w");
fwrite($fp,$export);
fclose($fp);

$log .= remesg("<a href=\"".ricerche.$file_export."\">Esporta in pdf</a> ","pdf");


// stampo
echo makepage($a, $log);

?>

