<?php
		
// pre
function safe($value) {
	return mysql_real_escape_string($value);
}

function epura_space2underscore($string) {
	return str_replace(' ', '_', $string);
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

// fun
function myoptlst($name,$query) {
$opt = "<select name='".$name."'>\n";
$opt .= "<option selected='selected' value=''>Blank</option>\n";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());
while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$opt .= "<option value='".safetohtml($row[0])."'>".safetohtml($row[0])."</option>\n";
}
mysql_free_result($res);
$opt .= "</select>";
return $opt;
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
	return "<p class=\"".$classe."\">".$msg."</p>\n";
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

?>
