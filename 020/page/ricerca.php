<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);


// inizializzo risorse

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// variabili
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;
	
$a = ""; $log = "";

$log .= remesg("PAGINA IN SVILUPPO","err");

if (isset($_POST['predefinito'])AND(!empty($_POST['predefinito']))) {
	$predefinito = safe($_POST['predefinito']);
} else {
	$predefinito = NULL;
}
if (isset($_POST['obiettivo'])AND(!empty($_POST['obiettivo']))) {
	$obiettivo = safe($_POST['obiettivo']);
} else {
	$obiettivo = NULL;
}
if (isset($_POST['num_doc'])AND(!empty($_POST['num_doc']))) {
	$num_doc = safe($_POST['num_doc']);
} else {
	$num_doc = NULL;
}






// test invia
if (isset($_POST['invia'])) {

if (isset($predefinito)) {
	
	switch ($predefinito) {
		
		case "pred1":
			$query = "SELECT tags,posizioni,tot FROM vserv_magazzino_id;";
			break;
		
		case "pred2":
			$query = "SELECT CONCAT_WS(' - ',contatto,tipo,numero),tags,MAGAZZINO.quantita,MAGAZZINO.posizione FROM MAGAZZINO JOIN MERCE USING(id_merce) LEFT JOIN (SELECT * FROM OPERAZIONI WHERE direzione='1') AS OPERAZIONI USING(id_merce,posizione) JOIN REGISTRO USING(id_registro) WHERE MAGAZZINO.quantita>'0';";
			break;
		
		default:
			$log .= remesg("Mancata selezione di una ricerca","warn");
	}
	
} elseif (isset($obiettivo)) {

	switch ($obiettivo) {
		
		case "magazzino":
			break;
		
		case "documenti":
			break;
		
		case "transiti":
			break;
		
		default:
			$log .= remesg("Mancata selezione di una ricerca","warn");
	}

}

$ricerca = stampe("lib/template_ricerca1.php",$query);
$log .= remesg("<a href=\"".ricerche.$ricerca."\">Risultati ricerca magazzino</a> pronta per la stampa","out");

} // end test invia



$a .= jsxtable;
//$a .= jsaltrows;
$a .= jsxdate;


// form ricerca documento
$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=ricerca");
if ($DEBUG) $a .= "&debug";
$a .= "'>\n";
$a .= "<table class='altrowstable' id='alternatecolor' >\n";

$a .= "<thead><tr>\n";
	$a .= "<th colspan='3'>Ricerca</th>\n";
$a .= "</tr></thead>\n";

$a .= "<tfoot>\n";
	$a .= "<tr>\n";
	$a .= "<td colspan='3'>\n";
		$a .= "<input type='reset' name='reset' value='Pulisci il foglio'/>\n";
		$a .= "<input type='submit' name='invia' value='Invia'/>\n";
	$a .= "</td>\n";
	$a .= "</tr>\n";
$a .= "</tfoot>\n";

$a .= "<tbody>\n";
	
	$a .= "<tr>\n";
		$a .= "<td rowspan='2'>Obiettivo</td>\n";
		$a .= "<td>Predefinite</td>\n";
		$a .= "<td style='text-align:justify;'>\n";
			$a .= "<input type='radio' name='predefinito' value='pred1'/>Semplice vista sul magazzino<br>\n";
			$a .= "<input type='radio' name='predefinito' value='pred2'/>Vista sul magazzino con documenti di ingresso\n";
		$a .= "</td>\n";

	$a .= "<tr>\n";
		$a .= "<td>Selezione</td>\n";
		$a .= "<td>".$obiettivi_ricerca."</td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td rowspan='7'>Transiti</td>\n";
		$a .= "<td>utente</td>\n";
		$a .= "<td>".$magamanager."</td>\n";
	$a .= "</tr>\n";	
	
	$a .= "<tr>\n";
		$a .= "<td>data</td>\n";
		$a .= "<td><input type='text' class='datepicker' name='data_transito1'/> - <input type='text' class='datepicker' name='data_transito2'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>direzione</td>\n";
		$a .= "<td>".$direzioni."</td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>TAGS</td>\n";
		$a .= "<td><input type='text' name='tags'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>Note</td>\n";
		$a .= "<td><input type='text' name='note'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>Posizione in magazzino</td>\n";
		$a .= "<td><input type='text' name='posizione'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>Destinazione merce</td>\n";
		$a .= "<td><input type='text' name='destinazione'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td rowspan='3'>Documento</td>\n";
		$a .= "<td>numero</td>\n";
		$a .= "<td><input type='text' name='num_doc'/></td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
		$a .= "<td>mittente</td>\n";
		$a .= "<td><input type='text' name='mittente'/></td>\n";
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
		$a .= "<td>data</td>\n";
		$a .= "<td><input type='text' class='datepicker' name='data_doc1'/> - <input type='text' class='datepicker' name='data_doc2'/></td>\n";
	$a .= "</tr>\n";

$a .= "</tbody>\n";

$a .= "</table></form>\n";


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);

?>
