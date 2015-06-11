<?php

// inizializzo risorse

// variabili
$a = ""; $log = "";
$riga = "";

$data = date("d/m/Y");
$utente = $_SERVER["PHP_AUTH_USER"];


if (isset($_GET["detail"])) {
	
	$query = myquery::magazzino_detail($db);
	
} elseif (isset($_GET["contro"])) {

	$query = myquery::magazzino_contro($db);

} else {
	
	$query = myquery::magazzino($db);
	
}

$log .= $menu_magazzino;



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
	$a .= "<th>Merce</th>\n";
	$a .= "<th>Posizione</th>\n";
	$a .= "<th>Quantita'</th>\n";
	if (isset($_GET["contro"]) OR isset($_GET["detail"])) $a .= "<th>Note</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

foreach ($query as $row) {
	$riga .= "<tr>\n";
	if (isset($_GET["id"])) $riga .= "<td>".$row['id_merce']."</td>\n";
	$riga .= "<td>".$row['merce']."</td>\n";
	$riga .= "<td>".$row['posizione']."</td>\n";
	$riga .= "<td>".$row['quantita']."</td>\n";
	if (isset($_GET["contro"]) OR isset($_GET["detail"])) $riga .= "<td>".$row['note']."</td>\n";
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

$log .= remesg("<a href=\"".ricerche.$file_export."\">Esporta in pdf</a>","pdf");

// stampo
echo makepage($a, $log);


?>

