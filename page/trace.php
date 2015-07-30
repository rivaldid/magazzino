<?php

// inizializzazione
$a = "";
$log = "";

$query = myquery::lista_accessi($db);

if ($query) {

$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

$a .= "<thead><tr>\n";
	$a .= "<th>Data</th>\n";
	$a .= "<th>Destinazione</th>\n";
	$a .= "<th>Provenienza</th>\n";
	$a .= "<th>Utente</th>\n";
	$a .= "<th>Browser</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

foreach ($query as $row) {
	$a .= "<tr>\n";

	/*for($i=0; $i<=5; $i++) {
		$a .= "<td>$row[$i]</td>\n";
	}*/

	$a .= "<td>".$row['0']."</td>\n";
	$a .= "<td>".$row['1']."</td>\n";
	$a .= "<td>".$row['2']."</td>\n";
	$a .= "<td>".$row['5']." (".$row['3'].")</td>\n";
	$a .= "<td>".getBrowser($row['6'])['name']." - ".getBrowser($row['6'])['version']."</td>\n";

	$a .= "</tr>\n";
}

$a .= "</tbody>\n</table>\n";

} else
	$a .= remesg("Nessun dato trovato.","warn");


// stampo
echo makepage($a, $log);

?>
