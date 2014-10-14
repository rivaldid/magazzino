<?php
	
//begin mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
	
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

$query = "SELECT * FROM vserv_magazzino;";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());

$a = "";

//print
$a .= "<table>";
$a .= "<caption>STATO MAGAZZINO</caption>";
$a .= "<thead><tr>";
	$a .= "<th>TAGS</th>";
	$a .= "<th>Quantita'</th>";
	$a .= "<th>Lista posizioni</th>";
$a .= "</tr></thead>";
$a .= "<tbody>";
	
while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$a .= "<tr>";
	foreach ($row as $cname => $cvalue)
		$a .= "<td>".$cvalue."</td>";
	$a .= "</tr>";
}

$a .= "</tbody></table>";

echo $a;

mysql_free_result($res);

// end mysql
mysql_close($conn);
	
?>

