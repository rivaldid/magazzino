<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializza risorse

//  mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

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


$sql = vserv_transiti;
$query_count = mysql_query($sql);

$per_page = 20;
$count = mysql_num_rows($query_count);
$pages = ceil($count/$per_page);

if (isset($_GET["current_page"]))
	$current_page = $_GET['current_page'];
else
	$current_page = 1;

if ((testinteger($current_page)) AND ($current_page >= 1) AND ($current_page <= $pages)) {
	$start = ($current_page - 1) * $per_page;
	$sql = $sql." LIMIT $start,$per_page";
} else
	$current_page=1;

$query = mysql_query($sql);
if (!$query) die('Errore nell\'interrogazione del db: '.mysql_error());


// pagination
$pagination = "<div id='DIV-pagination'><ul class='paginate'>\n";

// stabilisco che se $current_page esiste, class sia uguale a CURRENT
if ($current_page)
 $current='current'; 
else 
 $current='single';
 
 
if (($current_page-1)>1)
	$prev=$current_page-1;
else
	$prev=1;

if (($current_page+1)<$pages)
	$next=$current_page+1;
else
	$next=$pages;

// testa
$current_page2 = $current_page;
if ($current_page2>1) 
	$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=$prev\"><i class='fa fa-backward'></i></a></li>\n";
	
if ($current_page2 == '1')	$current='single';
$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=1\">1</a></li>\n";
$current='current';
 
// corpo
switch ($current_page) {
	
	case 1:
		$current_page+=4;
		break;
	
	case 2:
		$current_page+=3;
		break;
	
	case 3:
		$current_page+=2;
		break;
	
	case 4:
		$current_page+=1;
		break;
	
	case $pages-3:
		$current_page-=1;
		break;
	
	case $pages-2:
		$current_page-=2;
		break;
	
	case $pages-1:
		$current_page-=3;
		break;
	
	case $pages:
		$current_page-=4;
		break;		

}

for ($i = $current_page-3; $i <= $current_page+3; $i++) {
	
	if ($current_page2 == $i) $current='single'; 	
	$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=$i\">$i</a></li>\n";
	$current='current'; 
	
}

// coda
if ($current_page2 == $pages) 
	$current='single'; 

$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=$pages\">$pages</a></li>\n";
if ($current_page2<$pages) 	
	$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=$next\"><i class='fa fa-forward'></i></a></li>\n";
$pagination .= "</ul></div>\n";

$a .= $pagination;


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

while ($row = mysql_fetch_array($query, MYSQL_NUM)) {
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
				$riga .= "<td>".safetohtml(strtolower($cvalue))."</td>\n";
				break;

			case "11":
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

$a .= $pagination;


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


// termino risorse
mysql_free_result($query);
mysql_free_result($query_count);
mysql_close($conn);


// salvo export pdf
$file_export = "export_transiti.php";
$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$file_export,"w");
fwrite($fp,$export);
fclose($fp);

$log .= remesg("<a href=\"".ricerche.$file_export."\">Esporta in pdf</a> ","pdf");


// stampo
echo makepage($a, $log);


?>

