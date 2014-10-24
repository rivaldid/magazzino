<?php
	
//begin mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
	
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

$query = "SELECT * FROM vista_magazzino;";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());

$a = "";

//print
$a .= "<table>\n";
$a .= "<caption>STATO MAGAZZINO</caption>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>ID MERCE</th>\n";
	$a .= "<th>Posizione</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Quantita'</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";
	
while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$a .= "<tr>\n";
	foreach ($row as $cname => $cvalue)
		$a .= "<td>".$cvalue."</td>\n";
	$a .= "</tr>\n";
}

$a .= "</tbody>\n</table>\n";

echo $a;

mysql_free_result($res);

// end mysql
mysql_close($conn);
	
?>

