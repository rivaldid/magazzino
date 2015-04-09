<?php

// pre
function safe($value) {
	return mysql_real_escape_string($value);
}

function epura_space2underscore($string) {
	return str_replace(' ', '_', $string);
}

function epura_space2percent($string) {
	return str_replace(' ', '%', $string);
}

function epura_specialchars($string) {
	return preg_replace('/[^A-Za-z0-9\. -]/', '', $string);
}

function getfilext($filename) {
	return substr($filename, strrpos($filename, '.')+1);
}

// trasforma special chars in &codice
function safetohtml($value) {
	return htmlspecialchars($value);
}

// trasforma &codice in special chars
function safefromhtml($value) {
	return htmlspecialchars_decode($value);
}

function testinteger($mixed) {
	return preg_match('/^[\d]*$/',$mixed);
}

function nextpage($offset) {
	return $offset+30;
}

// fun
function myoptlst($name,$query) {
$opt = "<select name='".$name."'>\n";
$opt .= "<option selected='selected' value=''>Blank</option>\n";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db su '.$query.' con errore '.mysql_error());
while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$opt .= "<option value='".safetohtml($row[0])."'>".safetohtml($row[0])."</option>\n";
}
mysql_free_result($res);
$opt .= "</select>";
return $opt;
}

function single_field_query($query) {
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db su '.$query.' con errore '.mysql_error());
$output = mysql_fetch_array($res, MYSQL_NUM);
mysql_free_result($res);
return $output[0];
}


function isoptlst($value) {
	//if (preg_match("<option selected='selected' value=''>Blank</option>",$value))
	$mypattern = "<option selected='selected' value=''>Blank</option>";
	if (strpos($value,$mypattern) !== false)
		return true;
	else
		return false;
}

function remesg($msg,$classe) {

	$out = "<p class=\"".$classe."\">";

	switch ($classe) {

		case "info":
			$out .= "<i class=\"fa fa-info\"></i>";
			break;

		case "action":
			$out .= "<i class=\"fa fa-pencil\"></i>";
			break;

		case "done":
			$out .= "<i class=\"fa fa-check\"></i>";
			break;

		case "warn":
			$out .= "<i class=\"fa fa-exclamation\"></i>";
			break;

		case "err":
			$out .= "<i class=\"fa fa-exclamation-triangle\"></i>";
			break;

		case "tit":
			$out .= "<i class=\"fa fa-cogs\"></i>";
			break;

		case "debug":
			$out .= "<i class=\"fa fa-bug\"></i>";
			break;

		case "out":
			$out .= "<i class=\"fa fa-thumbs-o-up\"></i>";
			break;

		case "pdf":
			$out .= "<i class=\"fa fa-file-pdf-o\"></i>";
			break;

		case "search":
			$out .= "<i class=\"fa fa-search\"></i>";
			break;

		default:
			$out .= "";

	}

	$out .= " ".$msg."</p>\n";
	return $out;
}

function input_hidden($name,$value) {
	return "<input type='hidden' name='".$name."' value='".$value."'/>".$value;
}

function noinput_hidden($name,$value) {
	return "<input type='hidden' name='".$name."' value='".$value."'/>";
}

function logging($mixed) {
$flog = fopen(splog,'a');
$a = $mixed."\n";
fwrite($flog,$a);
fclose($flog);
}

function logging2($mixed,$logfile) {
$flog = fopen($logfile,'a');
$a = $mixed.PHP_EOL;
fwrite($flog,$a);
fclose($flog);
}

function occhiomalocchio($path) {
return "UID: ".$_SERVER["AUTHENTICATE_UID"]." @ ".date('Y/m/d H:i:s')." on ".basename($path,".php");
} // logging2(occhiomalocchio(basename(__FILE__)),accesslog);

function reset_sessione() {
// reset $_SESSION
$_SESSION = array();
session_unset(); // tronca solo i dati
session_destroy(); // distrugge lato server

/* generate new session id and delete old session in store */
session_regenerate_id(true);
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

return true;
}

function user2name($utente) {
foreach (array("Piscazzi","Manzo","Muratore","Lorusso","Vilardi") as $name)
	if (strcasecmp(substr($utente,0,4),substr($name,0,4)) == 0)
		return $name;
}

function add_tooltip($msg) {
return "<a class=\"tooltip\">*<span><img class=\"callout\" src=\"imgs/callout.gif\" />".$msg."</span></a>";
}

function makepage($a, $log) {
$o = "<div id=\"log\">\n";
$o .= "<span class=\"tit\"><i class=\"fa fa-cogs fa-2x\"></i> Strumenti</span><span class=\"wellcomePage\"><i class=\"fa fa-check fa-2x\"></i> ".$_SERVER["AUTHENTICATE_UID"]."</span>";
$o .= "<hr class=\"divisore_log\" />\n";

if (isset($log)) {
	if (empty($log))
		$o .= remesg("Nessuna notifica da visualizzare","info");
	else
		$o .= $log;
}
$o .= "</div>\n";
$o .= $a;
return $o;
}


/*
function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();

   $df = fopen("php://output",'w');

   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}
*/


function stampe($template,$query) {

// inizializzo risorse
$a = "";
$data = date("d/m/Y");
$utente = $_SERVER["AUTHENTICATE_UID"];
ob_start();
include 'lib/template_ricerca1.php';


// interrogazione
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


// risultati
$corpo_html = ob_get_clean();
$a .= "<?php\n"."\$html = \"".addslashes($corpo_html);

while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$a .= "<tr>\n";
	foreach ($row as $cname => $cvalue)
		$a .= "<td>".$cvalue."</td>\n";
	$a .= "</tr>\n";
}

$a .= "</table>\n</div>\";\n";
$a .= "//==============================================================\n";
$a .= "include(\"".lib_mpdf57."\");\n";
$a .= "\$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);\n";
$a .= "\$stylesheet = file_get_contents('../020/css/mds.css');\n";
$a .= "\$mpdf->WriteHTML(\$stylesheet,1);\n";
$a .= "\$mpdf->WriteHTML(\"\$html\");\n";
$a .= "\$mpdf->Output();\n";
$a .= "exit;\n";
$a .= "//==============================================================\n";
$a .= "?>";


// salvo risultato
$nome_export_pdf = "ricerca__".$utente."__".date("Y-m-d__H.m.s")."__".rand().".php";
$fp = fopen($_SERVER['DOCUMENT_ROOT'].ricerche.$nome_export_pdf,"w");
fwrite($fp,$a);
fclose($fp);


// termino risorse
mysql_free_result($res);

return $nome_export_pdf;

}



?>
