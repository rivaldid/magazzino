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


/*function split_record_posizioni($item) {
	
	// valorizzo $array_merce da $_SESSION['posizioni']
	$j=0;
	foreach ($_SESSION['check_list'] as $i) {
		
		$temp_merce = explode(",",$_SESSION['posizioni'][$i]);
		
		foreach ($temp_merce AS $items) {
			
			$items = explode("(",$items);

			$_SESSION[$j]['id_merce'] = $_SESSION['id_merce'][$i];
			$_SESSION[$j]['posizione'] = $items[0];
			$_SESSION[$j]['quantita'] = rtrim($items[1],")");
			$_SESSION[$j]['tot'] = $_SESSION['tot'][$i];
			
			$j++;
		}
	}
	
	// $_SESSION[indice] ([id_merce], [posizione], [quantita], [tot]);
	
}*/



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
			$a .= "<td><input type='checkbox' name='check_list[]' value='".$i."'/>".$cvalue."</td>\n";
			$a .= "<td>\n";
			$a .= "<input type='submit' name='modifica' value='Modifica'/>\n";
			$a .= "<input type='submit' name='scarica' value='Scarica'/>\n";
			$a .= "<input type='submit' name='stop' value='Reset'/>\n";
			$a .= noinput_hidden($cname."[".$i."]",$cvalue);
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


// ritorno contenuti
$_SESSION['contents'] = $a;
$_SESSION['log'] = $log;
return true;


}



function vserv_magazzino_scarico() {

// variabili
$a = "";
$log = "";


$_SESSION['log'] = remesg("scaricaaaaaaaaaaaaaaaaa!","warn");
$_SESSION['contents'] = "funzione scarico";




return true;
	
}


function vserv_magazzino_modifica() {
	
// variabili
$a = "";
$log = "";


$log .= remesg("Pagina di modifica merce presente in magazzino!","tit");

$a .= "<table>\n";
$a .= "<caption>MAGAZZINO</caption>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>Descrizione</th>\n";
	$a .= "<th>Inserimento</th>\n";
	$a .= "<th>Suggerimento</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

foreach ($_SESSION as $cname => $cvalue) {
	
	$a .= "<td>".$cname."</td>\n";
	$a .= "<td></td>\n";
	$a .= "<td>".$cvalue."</td>\n";
	
}

$a .= "</tbody>\n</table>\n";

// ritorno contenuti
$_SESSION['contents'] = $a;
$_SESSION['log'] = $log;
return true;
	
}


?>
