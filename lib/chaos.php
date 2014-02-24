<?php

// *********************************************************************
// COSTANTI
define("atitolo","<h3>");
define("ctitolo","</h3>");

define("ap","<p class=\"insert_response\">");
define("cp","</p>");

define("atable","<table>");
define("ctable","</table>");

define("athead","<thead>");
define("cthead","</thead>");

define("atbody","<tbody>");
define("ctbody","</tbody>");

define("atr","<tr>");
define("ctr","</tr>");

define("atd","<td>");
define("ctd","</td>");

define("accapo","\n");
define("cs_line","<tr>\n<td></td><td><input type=\"reset\" name=\"reset\" value=\"Clear\">\n<input type=\"submit\" name=\"submit\" value=\"Submit\"></td>\n</tr>\n");

define("fine_form",cs_line.ctable."</form>".accapo);


require_once("lib/mysql.class.php"); 
include("lib/libform.php");
include("lib/libquery.php");


// *********************************************************************

function int_ok($mixed) {
	$message = "campo non numerico dove richiesto numerico";
	if (! preg_match( '/^\d*$/',$mixed) == 1)
		killemall($message);
	return $mixed;
}


function killemall($label) {
echo "<h3 class=\"risposta\"><img src=\"img/error.png\" /><span>Si e' verificata un'anomalia su dati o codice in ambito {$label}. Riferire all'amministratore.</span></h3>";
include("page/footer.php");
die();
}


function safe($value) {
	return mysql_real_escape_string($value);
}


function epura($string) {
	return preg_replace('/\s+/', '', $string);
}


function epura2($string) {
	return str_replace(' ', '_', $string);
}


function epura_double($string) {
	return trim(preg_replace('/\\s+/', ' ',$string));
}


function epura_specialchars($string) {
	return preg_replace('/[^A-Za-z0-9\. -]/', '', $string);
}


function accodalacoda($input,$i) {
	$j = $i;
	$output[$i] = $input[$i];
	while ($j < count($input)) {
		$j++;
		$output[$i] .= " ".$input[$j];
	}
	return trim($output[$i]);
}


function date_picker($name, $startyear=NULL, $endyear=NULL) {
    
    if($startyear==NULL) $startyear = date("Y")-10;
    if($endyear==NULL) $endyear=date("Y")+10; 

    $months=array('','Gennaio','Febbraio','Marzo','Aprile','Maggio',
    'Giugno','Luglio','Agosto', 'Settembre','Ottobre','Novembre','Dicembre');

    // Day dropdown
    $html = "<select name=\"".$name."day\">\n";
    $html .= "<option selected=\"selected\" value=\"NULL\">default</option>\n";
    for($i=1; $i<=31; $i++) {
       $html .= "<option value=\"$i\">$i</option>\n";
    }
    $html .= "</select>\n";
    
    // Month dropdown
    $html .= "<select name=\"".$name."month\">\n";
    $html .= "<option selected=\"selected\" value=\"NULL\">default</option>\n";
    for($i=1; $i<=12; $i++) {
       $html .= "<option value=\"$i\">$months[$i]</option>\n";
    }
    $html.="</select>\n";

    // Year dropdown
    $html .= "<select name=\"".$name."year\">\n";
    $html .= "<option selected=\"selected\" value=\"NULL\">default</option>\n";
    for($i=$startyear; $i<=$endyear; $i++) {      
      $html .= "<option value=\"$i\">$i</option>\n";
    }
    $html .= "</select>\n";

    return $html;
}


function _array2sql($table, $array, $insert = "INSERT INTO") {

  //Check if user wants to insert or update
  if ($insert != "UPDATE") {
    $insert = "INSERT INTO";
  }

  $columns = array();
  $data = array();
  
  foreach ($array as $key => $value) {
    $columns[] = $key;
    if ($value != "") {
      $data[] = "'" . $value . "'";
    } else {
      $data[] = "NULL";
    }
                
    //TODO: ensure no commas are in the values
  }
            
  //$cols = implode(",",$columns);
  $values = implode(",",$data);

$sql = <<<EOSQL
  $insert `$table`
  VALUES
  ($values)
EOSQL;
      return $sql;

}


function registro_upload_allegato($basename) { 

$path = sprintf("/registro/");
$max_file_size = 8388608; // 8,0 Mb
$allowedExts = array("jpeg", "jpg", "png", "tif", "pdf", "xls", "doc", "tif", "tiff", "doc", "docx");
$temp = explode(".", $_FILES['file1']["name"]);
$extension = end($temp);
$filename = $basename.".".$extension;

if ((($_FILES["file1"]["type"] == "image/gif")
|| ($_FILES["file1"]["type"] == "image/jpeg")
|| ($_FILES["file1"]["type"] == "image/jpg")
|| ($_FILES["file1"]["type"] == "image/pjpeg")
|| ($_FILES["file1"]["type"] == "image/x-png")
|| ($_FILES["file1"]["type"] == "image/png")
|| ($_FILES["file1"]["type"] == "image/tif")
|| ($_FILES["file1"]["type"] == "image/tiff")
|| ($_FILES["file1"]["type"] == "application/pdf")
|| ($_FILES["file1"]["type"] == "application/vnd.ms-excel")
|| ($_FILES["file1"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
|| ($_FILES["file1"]["type"] == "application/msword")
|| ($_FILES["file1"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"))
&& ($_FILES["file1"]["size"] < $max_file_size)
&& in_array($extension, $allowedExts)) {
  if ($_FILES["file1"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file1"]["error"] . "<br>";
  }
  else {
	echo "<p>Invio del documento ".$_FILES["file1"]["name"].", di tipo ".$_FILES["file1"]["type"]." e dimensione ".($_FILES["file1"]["size"] / 1024) . " kB...</p>";
	//echo "Temp file: " . $_FILES["file1"]["tmp_name"] . "<br>";

	if (file_exists(getcwd().$path.$filename))
		echo "<p>Nessun caricamento, il documento ".$basename." risulta gia' presente, continuo con quello inviato di tipo ".$_FILES["file1"]["type"].".</p>";
    elseif (move_uploaded_file($_FILES["file1"]["tmp_name"], getcwd().$path.$filename))
      echo "<p>Ok, documento rinominato in ".$path.$filename."</p>";
  }
}
else {
	killemall("file non supportato");
}

return $filename;
}


// *********************************************************************
// INTERFACCE PRIVATE QUERY

function call_core($label,$sql) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$a = ap."{$label}... ";
$tmp = $classemysql->myquery($sql);
if ($tmp) $a .= "ok!".cp;
$classemysql->disconnetti();
return $a;
}


/*
function insert_core($tabella,$valori,$action) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$sql = _array2sql($tabella, $valori, $action);
switch ($action) {
	case "INSERT INTO":
		$localecho = "Inserimento";
		break;
	case "UPDATE":
		$localecho = "Aggiornamento";
		break;
	default:
		killemall("Errore in accesso alle tabelle.");
}
$output[0] = "<p class=\"insert_response\">{$localecho} dati in tabella {$tabella}... ";
$tmp = $classemysql->myquery($sql);
if ($tmp) $output[0] .= "ok!</p>";
$output[1] = mysql_insert_id();
$classemysql->disconnetti();
return $output;
}
*/


function table_core($tabella,$sql,$mask) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$resultset = $classemysql->myquery($sql);
$output = "<h3>Contenuto dati in {$tabella}...</h3>";
$output .= "<input id=\"tData\" name=\"tableData\" type=\"hidden\" />
			<input value=\"Export to Excel\" type=\"submit\" />
			<table id=\"tblExport\" class=\"tablesorter\"><thead><tr>{$mask}</tr></thead><tbody>";
$numero_campi = mysql_num_fields($resultset);
while ($riga = mysql_fetch_array($resultset, MYSQL_BOTH)) {
	$output .= "<tr>";
	for ($i=0; $i<$numero_campi; $i++) {
		if (mysql_field_name($resultset,$i) == "file")
			$output .= "<td><a href=\"registro/{$riga[$i]}\">{$riga[$i]}</a></td>";
		else 
			$output .= "<td>{$riga[$i]}</td>";
	}
	$output .= "</tr>";
}
$output .= "</tbody></table>";
$classemysql->pulizia($resultset);
$classemysql->disconnetti();
return $output;	
}


function optionlist_core_simple($sql,$mask,$n) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$tmp = $classemysql->myquery($sql);
$output = "<select name=\"{$mask}\">\n";
$output .= "<option selected=\"selected\" value=\"NULL\">OFF</option>\n";
while ($riga = mysql_fetch_row($tmp)) {
	$output .= "<option value=\"";
	for ($i=0; $i<$n; $i++) {
		if ($i>0) $output .= " ";
		$output .= $riga[$i];
	}
	$output .= "\">";
	for ($i=0; $i<$n; $i++) {
		if ($i>0) $output .= " ";
		$output .= "[{$riga[$i]}]";
	}
	$output .= "</option>\n";
}
$output .= "</select>";
$classemysql->pulizia($tmp);
$classemysql->disconnetti();
return $output;
}


function optionlist_core($sql,$mask,$n) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$tmp = $classemysql->myquery($sql);
$output = "<select name=\"{$mask}\">\n";
$output .= "<option selected=\"selected\" value=\"NULL\">OFF</option>\n";
while ($riga = mysql_fetch_row($tmp)) {
	$output .= "<option value=\"{$riga[0]}\">";
	for ($i=1; $i<$n; $i++) {
		if ($i>1) $output .= " ";
		$output .= "[{$riga[$i]}]";
	}
	$output .= "</option>\n";
}
$output .= "</select>";
$classemysql->pulizia($tmp);
$classemysql->disconnetti();
return $output;
}


function optionlist_core_double($sql,$mask,$n) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$tmp = $classemysql->myquery($sql);
$output = "<select name=\"{$mask}\">\n";
$output .= "<option selected=\"selected\" value=\"NULL\">OFF</option>\n";
while ($riga = mysql_fetch_row($tmp)) {
	$output .= "<option value=\"{$riga[0]} {$riga[1]}\">";
	for ($i=1; $i<$n; $i++) {
		if ($i>1) $output .= " ";
		$output .= "[{$riga[$i]}]";
	}
	$output .= "</option>\n";
}
$output .= "</select>";
$classemysql->pulizia($tmp);
$classemysql->disconnetti();
return $output;
}


function service_get_field($sql,$target) {
	$classemysql = new MysqlClass();
	$classemysql->connetti();
	$resultset = $classemysql->myquery($sql);
	$row = mysql_fetch_assoc($resultset);
	$classemysql->pulizia($resultset);
	$classemysql->disconnetti();
	return $row[$target];	
}


?>

