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

function logging2($mixed,$logfile) {
$flog = fopen($logfile,'a');
$a = $mixed."\n";
fwrite($flog,$a);
fclose($flog);
}
// logging2("UID: ".$_SERVER["AUTHENTICATE_UID"]." @ ".date('Y/m/d H:i:s')." on ".basename(__FILE__),accesslog);

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
	
/*
 * ALGORITMO:
 * 		1. definizione variabili (locali)
 * 		2. interrogazione
 * 		3. impaginazione
 * 		4. ritorno contenuti
 * 
 */


// definizione variabili (locali)
$a = "";
$log = "";


// interrogazione
$res = mysql_query(vserv_magazzino);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


// impaginazione
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
$valid = true;

$log .= remesg("Pagina per la modifica della merce presente in magazzino","msg");

// test submit
if (isset($_SESSION['submit'])) {
	
	// if true: validazione
	if(!(in_array($utente, $enabled_users))){
		$log .= remesg($msg17,"err");
		$valid = false;
	}
	
	
	
	
	


} else {

	// if not true: form
	$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=vserv")."'>\n";
	$a .= "<table class='altrowstable' id='alternatecolor'>\n";

	$a .= "<thead><tr>\n";
		$a .= "<th>MERCE</th>\n";
		$a .= "<th>CARATTERISTICHE</th>\n";
		$a .= "<th>AGGIORNAMENTO</th>\n";
	$a .= "</tr></thead>\n";

	$a .= "<tfoot>\n";
		$a .= "<tr>\n";
		$a .= "<td colspan='3'>\n";
			$a .= "<input type='reset' name='reset' value='Azzera'/>\n";
			$a .= "<input type='submit' name='submit' value='Invia'/>\n";
			$a .= "<input type='submit' name='attivita' value='Reset'/>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";
	$a .= "</tfoot>\n";

	$a .= "<tbody>\n";

	foreach ($_SESSION['check_list'] as $item) {
		
		// splitta
		$item = unserialize($item);
		$id_merce1 = safe($item['id_merce']);
		$tags1 = safe($item['tags']);
		$posizioni = safe($item['posizioni']);
		$tot = safe($item['tot']);
		
			
		$a .= "<tr>\n";
		
		$a .= "<td rowspan='2'>[".input_hidden("id_merce1",$id_merce1)."] ".$tags1."</td>\n";
			
		$coppie = explode(",",$posizioni);
		$a .= "<td rowspan='2'>\n";
			$a .= "<select name='coppia1'>\n<option selected='selected' value=''>Blank</option>\n";
			foreach ($coppie as $coppia) {
				$a .= "<option value='".$coppia."'>".$coppia."</option>\n";
			}
			$a .= "</select>\n";
		$a .= "</td>\n";
		
		$a .= "<td>Posizione <input type='text' name='posizione2'/></td>\n";
		$a .= "<tr><td>Quantita' <input type='text' name='quantita2'/></td></tr>\n";

		$a .= "</tr>\n";
	
	}
		
	$a .= "</tbody>\n</table>\n";
	$a .= "</form>\n";

}

// ritorno contenuti
$_SESSION['contents'] = $a;
$_SESSION['log'] = $log;
return true;
	
}


?>
