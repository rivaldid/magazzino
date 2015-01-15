<?php

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
$a .= "<input type='submit' name='attivita' value='Azzera'/>\n";
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
		$a .= "<input type='submit' name='attivita' value='M'/>\n";
		$a .= "<input type='submit' name='attivita' value='S'/>\n";
		//$a .= "<input type='submit' name='attivita' value='A'/>\n";
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


logging2(occhiomalocchio(basename(__FILE__)),accesslog);

/*
 * 
 * ALGORITMO:
 * 
 * nella porzione di codice chiamata headphp
 * 	inizializzo risorse e variabili
 * 
 * nella porzione di codice chiamata footphp
 * 	ritorno termino e impagino i contenuti
 * 
 * nella porzione centrale chiamata bodyphp
 * 	ho due test
 * 	un primo test serve a capire se ho del lavoro da svolgere
 * 	quindi instrado il flusso verso la funzione indicata
 * 	nel secondo test verifico che il primo test non sia stato verificato
 * 	quindi stampo la lista delle voci da cui far partire le attivita
 *
 * _____________________________________________________________________
 *
 *		INDEX PAGE PHP
 * _____________________________________________________________________
 *
 *
 * 	[HEADPHP]
 * 		inizializzo risorse
 * 		inizializzo variabili
 *	[/HEADPHP]
 *
 *
 * 	[BODYPHP]
 *		test attivita and checklist
 *			switch attivita
 *		test contenuti vuoti
 *			select merce
 * 	[/BODYPHP]
 *
 *
 * 	[FOOTPHP]
 * 		ritorno contenuti
 * 		termino risorse
 * 		stampo output
 * 	[/FOOTPHP]
 * 
 * _____________________________________________________________________
 *
 * 
 */




// ******** HEADPHP ****************************************************

// inizializzo risorse
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// inizializzo variabili
$contents = "";
$log = "";
foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;




// ******** BODYPHP ****************************************************

// test attivita and checklist
if (isset($_SESSION['attivita']) AND (!empty($_SESSION['check_list']))) {

	// switch attivita
	switch ($_SESSION['attivita']) {

		case "M":
			vserv_magazzino_modifica();
			break;

		case "S":
			vserv_magazzino_scarico();
			break;

		case "Azzera":
			$log .= remesg($msg9,"msg");
			reset_sessione();
			break;

		default:
			$log .= remesg("Attivita' non pervenuta","err");

	}
}

// test contenuti vuoti
if (!isset($_SESSION['contents'])) {

	// mostra merce
	$log .= remesg("Magazzino in visualizzazione merce","msg");
	vserv_magazzino_select();

}



// ******** FOOTPHP ****************************************************

// ritorno contenuti
$contents .= $_SESSION['contents'];
$log .= $_SESSION['log'];

// termino risorse
unset($_SESSION['contents']);
unset($_SESSION['log']);
session_write_close();
mysql_close($conn);

// stampo output
echo "<div id='log'>\n";
echo remesg("Notifiche","tit");
if ($log == "") echo remesg($msg18,"msg");
else echo $log;
echo "</div>\n";
echo $contents;




?>

