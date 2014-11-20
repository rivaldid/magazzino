<?php

$a = "";


if (isset($_SESSION['submit'])) {


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

$a .= "<tr>\n";
$a .= "<td></td><td></td>\n";
$a .= "<td>".$_POST['id_merce']."</td>\n";
$a .= "<td>".$_POST['tags']."</td>\n";
$a .= "<td>".$_POST['posizioni']."</td>\n";
$a .= "<td>".$_POST['tot']."</td>\n";
$a .= "</tr>\n";

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
	$a .= "<td><input type='checkbox' name='sel".$i."' /></td>\n";
	$i++;
	
	foreach ($row as $cname => $cvalue)	
		$a .= "<td>".input_hidden($cname,$cvalue)."</td>\n";
	
	$a .= "</tr>\n";
}

$a .= "<td><input type='submit' name='submit' value='Modifica'/></td>\n";
$a .= "</form>\n";

$a .= "</tbody>\n</table>\n";

mysql_free_result($res);

// end mysql
mysql_close($conn);


}

echo $a;

?>

