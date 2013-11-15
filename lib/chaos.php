<?php

// prova modifica

// STILI FORM
define("atitolo","<h3>");
define("ctitolo","</h3>");
define("adesc","<p class=\"greyboxinfo\">");
define("cdesc","</p>");

define("atable","<table>");
define("ctable","</table>");
define("atr","<tr>");
define("ctr","</tr>");
define("atd","<td>");
define("ctd","</td>");

define("accapo","\n");
define("apri_line",atr.accapo.atd);
define("chiudi_line",ctd.accapo.ctr.accapo);
define("cs_line","<tr>\n<td></td><td><input type=\"reset\" name=\"reset\" value=\"Clear\">\n<input type=\"submit\" name=\"submit\" value=\"Submit\"></td>\n</tr>\n");

define("fine_form",cs_line.ctable."</form>".accapo);


require_once("lib/mysql.class.php"); 
require_once("lib/upload.class.php");
include("lib/libform.php");
include("lib/libquery.php");



function int_ok($mixed) {
	$message = "campo non numerico dove richiesto numerico";
	if (! preg_match( '/^\d*$/',$mixed) == 1)
		killemall($message);
	return $mixed;
}


function killemall($label) {
echo "<h3>Si e' verificata un'anomalia su dati o codice in ambito {$label}. Riferire all'amministratore.</h3>";
include("pages/footer.html");
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


function registro_upload_allegato() { 
$message = "upload, file mancante o corrotto";
$path = sprintf("/magazzino/registro/");
$upload = new Upload;
$upload->uploadFile($path, 'md5', 10);
$filename = array_shift($upload->_files);
if (!(isset($filename)))
	killemall($message);
$newname =  sprintf("%d_%s",date("Ymd"),$filename);
$upload->fileRename($path, $filename, $newname);
return $newname;
}


?>

