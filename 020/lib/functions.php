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


function reset_sessione() {
// reset $_SESSION
$_SESSION = array();
session_unset();
session_destroy();

/* generate new session id and delete old session in store */
session_regenerate_id(true);
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

return true;
}


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
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>ID</th>\n";
	$a .= "<th>Azione</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Posizioni</th>\n";
	$a .= "<th>Quantita'</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=vserv")."'>\n";
while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$a .= "<tr>\n";
	
	// primo td: la checkbox
	foreach ($row as $cname => $cvalue) {
		
		switch ($cname) {
			case "id_merce":
				$id_merce = $cvalue;
				break;
			case "tags":
				$tags = $cvalue;
				break;
			case "posizioni":
				$posizioni = $cvalue;
				break;
			case "tot":
				$tot = $cvalue;
				break;
		}
		
		$item[$cname] = $cvalue;
	}	
	$value = htmlentities(serialize($item));
	$a .= "<td><input type='checkbox' name='check_list[]' value='".$value."'/>".$id_merce."</td>\n";

	
	// secondo td: i bottoni azione
	$a .= "<td>\n";
		$a .= "<input type='submit' name='attivita' value='Modifica'/>\n";
		$a .= "<input type='submit' name='attivita' value='Scarica'/>\n";
		$a .= "<input type='submit' name='attivita' value='Reset'/>\n";
	$a .= "</td>\n";
	
	// terzo td in poi: tags posizioni e tot
	$a .= "<td>".$tags."</td>\n";
	$a .= "<td>".$posizioni."</td>\n";
	$a .= "<td>".$tot."</td>\n";
	
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

$_SESSION['begin'] = true;




$_SESSION['log'] = remesg("scaricaaaaaaaaaaaaaaaaa!","warn");
$_SESSION['contents'] = "funzione scarico";
return true;
	
}


function vserv_magazzino_modifica() {
	
// variabili
$a = "";
$log = "";
$utente = $_SERVER["AUTHENTICATE_UID"];
$i=0;

$log .= remesg("Pagina per la modifica di merce presente in magazzino","msg");

$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>ID</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Posizione</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

foreach ($_SESSION['check_list'] as $item) {
	
	// splitta
	$item = unserialize($item);
	$id_merce = safe($item['id_merce']);
	$tags = safe($item['tags']);
	$posizioni = safe($item['posizioni']);
	$tot = safe($item['tot']);
	
		
	// riga dati attuali
	$a .= "<tr>\n";
	
	$a .= "<td>".$id_merce."</td>\n";
	
	if (is_null($tags)) {
		$a .= "<td><textarea rows='4' cols='25' name='itags'></textarea></td>\n";
		$a .= "<td>\n";
			$a .= remesg("Per bretelle rame/fibra:","msg");
			$a .= input_hidden("tag1","BRETELLA")." \n";
			$a .= myoptlst("tag2",$vserv_tags2)." \n";
			$a .= myoptlst("tag3",$vserv_tags3)." \n";
		$a .= "</td>\n";
	} else {
		$a .= "<td>".input_hidden("stags",$tags)."</td>\n";
	}
	

	
	$coppie = explode(",",$posizioni);
	$a .= "<td>\n";
		$a .= "<select name='posizione1'>\n<option selected='selected' value=''>Blank</option>\n";
		foreach ($coppie as $coppia) {
			$a .= "<option value='".$coppia."'>".$coppia."</option>\n";
		}
		$a .= "</select>\n";
	$a .= "</td>\n";

	$a .= "</tr>\n";
}
	
$a .= "</tbody>\n</table>\n";



// ritorno contenuti
$_SESSION['contents'] = $a;
$_SESSION['log'] = $log;
return true;
	
}


?>
