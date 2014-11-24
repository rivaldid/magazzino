<?php

/*
 * modifica di merce in magazzino, script frontend per stored procedure
 * aggiornamento_magazzino_merce(utente,
 * tags1,id1,posizione1,quantita1,
 * tags2,id2,posizione2,quantita2,
 * data);
 * 
 * ALGORITMO:
 * 	1. definizione variabili
 * 	2. startup risorse
 * 		2a. $_SESSION
 * 		2b. mysql
 * 	3. test risorse
 * 		3a. test $selezionato
 * 		3b. test fine $_SESSION
 * 		
 * 
 */



// 1. definizione variabili
$a = "";
$log = "";
$quantita = "";
$posizione = "";

$selezionato = false;



// 2. startup risorse

// 2a. $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// 2b. mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());



// 3. test risorse

// 3a. test $selezionato
if (isset($_POST['submit']) AND (!empty($_POST['edit_list']))) {
	
	// 3b. test fine $_SESSION
	if (isset($_POST['stop'])) {
		
		$selezionato = false;
		$log .= remesg($msg9,"msg");
		$_SESSION = array();
		session_unset();
		session_destroy();
	
		/* generate new session id and delete old session in store */
		session_regenerate_id(true);
		
		if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

	} else {

		$selezionato = true;
		foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;

	}

} else {

	$selezionato = false;

}
		



	
	
	
	
	
	
//print
$a .= "<table>\n";
$a .= "<caption>EDIT MAGAZZINO</caption>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>Descrizione</th>\n";
	$a .= "<th>Inserimento</th>\n";
	$a .= "<th>Suggerimento</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

foreach ($_POST['edit_list'] as $j) {
	
	$id_merce = $_POST['id_merce'][$j];
	$tags = $_POST['tags'][$j];
	$posizioni = explode(",",$_POST['posizioni'][$j]);
	
	$lista_items = "<select name='utente'>\n<option selected='selected' value=''>Blank</option>\n";
	foreach($posizioni as $item)
		$lista_items .= "<option value='".$item."'>".$item."</option>\n";
	$lista_items .= "</select>\n";
	
	
	// magazzino id-posizione-quantita
	/*
	$k=0;
	foreach($posizioni as $item) {
		$item = explode("(",$item);
		$vett_posizioni[$k]=$item[0];
		$vett_quantita[$k]=rtrim($item[1],")");
		$k++;
	}
	*/
	
	
	$a .= "<tr>\n";
	$a .= "<tr><td style='background-color:yellow;font-weight: bold;'>Modificando articolo #".$j."</td></tr>\n";
	$a .= "<td><label for='itags'>TAGS merce</label></td>\n";
	if (is_null($tags)) {
		$a .= "<td><textarea rows='4' cols='25' name='itags'></textarea></td>\n";
		$a .= "<td>\n";
			$a .= remesg("Per bretelle rame/fibra:","msg");
			$a .= input_hidden("tag1","BRETELLA")." \n";
			$a .= myoptlst("tag2",$vserv_tags2)." \n";
			$a .= myoptlst("tag3",$vserv_tags3)." \n";
		$a .= "</td>\n";
	} else {
		$a .= "<td></td>\n";
		$a .= "<td>".input_hidden("stags",$tags)."</td>\n";
	}
	$a .= "</tr>\n";
	
	$a .= "<tr>\n";
	$a .= "<td>Selezionare giacenza</td>\n";
	$a .= "<td></td>\n";
	$a .= "<td>".$lista_items."</td>\n";
	$a .= "</tr>\n";

	$a .= "<tr>\n";
	$a .= "<td><label for='iquantita'>Quantita'</label></td>\n";
	if (is_null($quantita) OR (!(testinteger($quantita)))) {
		$a .= "<td><input type='text' name='iquantita'/></td>\n";
		$a .= "<td></td>\n";
	} else {
		$a .= "<td></td>\n";
		$a .= "<td>".input_hidden("squantita",$quantita)."</td>\n";
	}
	$a .= "</tr>\n";

	$a .= "<tr>\n";
	$a .= "<td><label for='iposizione'>Posizione</label></td>\n";
	if (is_null($posizione)) {
		$a .= "<td><input type='text' name='iposizione'/></td>\n";
		$a .= "<td></td>\n";
	} else {
		$a .= "<td></td>\n";
		$a .= "<td>".input_hidden("sposizione",$posizione)."</td>\n";
	}
	$a .= "</tr>\n";


}

$a .= "</tbody>\n</table>\n";


//} else {




$query = "SELECT * FROM vserv_magazzino_id;";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());

//print
$a .= jsxtable;
$a .= "<table>\n";
$a .= "<caption>EDIT MAGAZZINO</caption>\n";
$a .= "<thead><tr>\n";
	$a .= "<th></th>\n";
	$a .= "<th></th>\n";
	$a .= "<th>ID</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Posizioni con parziali</th>\n";
	$a .= "<th>Tot</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

$i=0;

$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=magazzino_edit")."'>\n";
while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$a .= "<tr>\n";
	$a .= "<td><input type='checkbox' name='edit_list[]' value='".$i."'/></td>\n";
	$a .= "<td><input type='submit' name='submit' value='Modifica'/></td>\n";
	
	foreach ($row as $cname => $cvalue)
		$a .= "<td>".input_hidden($cname."[".$i."]",$cvalue)."</td>\n";
	
	$i++;	
	$a .= "</tr>\n";
}

$a .= "</form>\n";

$a .= "</tbody>\n</table>\n";

mysql_free_result($res);

// end mysql
mysql_close($conn);


//}

// chiudo $_SESSION
session_write_close();

// stampo
echo "<div id=\"log\">\n";
echo remesg("Notifiche","tit");
if ($log == "")
	echo remesg($msg18,"msg");
else
	echo $log;
echo "</div>\n";

echo $a;

?>

