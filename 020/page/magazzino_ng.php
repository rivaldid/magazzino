<?php

// occhiomalocchio
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());
if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;
$logging = "CALL input_trace('{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_URI']}','{$_SERVER['HTTP_REFERER']}','{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REMOTE_USER']}','{$_SERVER['PHP_AUTH_USER']}','{$_SERVER['HTTP_USER_AGENT']}');";
mysql_query($logging);
mysql_close($conn);

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
$query = "SELECT * FROM vista_magazzino_ng_full;";

$log .= $menu_magazzino;
$log .= remesg("<a href=\"?page=magazzino\">Default</a>","action");


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
	$a .= "<th>ID MERCE</th>\n";
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
$file_export = "export_magazzino_ng.php";
$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$file_export,"w");
fwrite($fp,$export);
fclose($fp);

$log .= remesg("<a href=\"".ricerche.$file_export."\">Esporta in pdf</a>","pdf");

// stampo
echo makepage($a, $log);


?>

