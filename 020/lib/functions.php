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



// *********************************************************************
// ************* FUNZIONI PER PAGINA MAGAZZINO *************************
// *********************************************************************

function vserv_magazzino_select() {


// variabili
$a = "";
$log = "";

	
// apro connessione
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// interrogo
$res = mysql_query(vserv_magazzino);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


// impagino
$a .= jsxtable;
$a .= "<table>\n";
$a .= "<caption>MAGAZZINO</caption>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>ID</th>\n";
	$a .= "<th>Azione</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Posizioni con parziali</th>\n";
	$a .= "<th>Tot</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

$i=0;

$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=vserv")."'>\n";
while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$a .= "<tr>\n";
	
	foreach ($row as $cname => $cvalue) {
		if ($cname == 'id_merce') {
			$a .= "<td>\n<input type='checkbox' name='check_list[]' value='".$i."'/>\n".input_hidden($cname."[".$i."]",$cvalue)."\n</td>\n";
			$a .= "<td>\n";
			$a .= "<input type='submit' name='modifica' value='Modifica'/>\n";
			$a .= "<input type='submit' name='scarica' value='Scarica'/>\n";
			$a .= "<input type='submit' name='stop' value='Reset'/>\n";
			$a .= "</td>\n";
		} else
			$a .= "<td>".input_hidden($cname."[".$i."]",$cvalue)."</td>\n";
	}
	
	$i++;	
	$a .= "</tr>\n";
}
$a .= "</form>\n";

$a .= "</tbody>\n</table>\n";
mysql_free_result($res);


// chiudo connessione
mysql_close($conn);


// ritorno pagina
$_SESSION['contents'] = $a;
$_SESSION['log'] = $log;
return true;


}




function vserv_magazzino_scarico() {
	
	print_r($_SESSION);
	
}


function vserv_magazzino_modifica() {
	
// variabili
$a = "";
$log = "";
$i=0;

$a .= "<table>\n";

//foreach ($array_session as $item) {

for ($i=0; $i<count($array_session['check_list']); $i++) {
	
$a .= "<tr><td style='background-color:yellow;font-weight: bold;'>Modificando articolo #".$i."</td></tr>\n";
$a .= "<tr><td>".$array_session[$i]['id_merce']."</td></tr>\n";
//$a .= "<tr><td>".$array_session[$i]['tags']."</td></tr>\n";
$a .= "<tr><td>".$array_session[$i]['posizione']."</td></tr>\n";
$a .= "<tr><td>".$array_session[$i]['quantita']."</td></tr>\n";
$a .= "<tr><td>".$array_session[$i]['tot']."</td></tr>\n";
	
}

$a .= "</table>\n";

// ritorno pagina
$_SESSION['contents'] = $a;
$_SESSION['log'] = $log;
return $_SESSION;
	
}


?>
