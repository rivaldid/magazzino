<?php

// definizione variabili
$a = "";
$log = "";
$quantita = "";
$posizione = "";

// startup $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

if (isset($_POST['submit']) AND (!empty($_POST['edit_list']))) {
	
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
	$a .= "<td>Merce</td>\n";
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


} else {


//begin mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

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


}

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

