<?php

function myoptlst($name,$query) {
$opt = "<select name='".$name."'>\n";
$opt .= "<option selected='selected' value='blank'>Blank</option>\n";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());
while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$opt .= "<option value='".$row[0]."'>".$row[0]."</option>\n";
}
mysql_free_result($res);
$opt .= "</select>";
return $opt;
}

function safe($value) {
	return mysql_real_escape_string($value);
}

?>
