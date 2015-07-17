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
	$a .= "<th>data</th>\n";
	$a .= "<th>Destinazione</th>\n";
	$a .= "<th>Provenienza</th>\n";
	$a .= "<th>Ospite</th>\n";
	$a .= "<th>Utente</th>\n";
	$a .= "<th>Utente autenticato</th>\n";
	$a .= "<th>User Agent</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

foreach ($query as $row) {
	$a .= "<tr>\n";
	
	for($i=0; $i<=6; $i++) {
		$a .= "<td>$row[$i]</td>\n";
	}
			
	$a .= "</tr>\n";
}

$a .= "</tbody>\n</table>\n";

} else
	$a .= remesg("Nessun dato trovato.","warn");


// stampo
echo makepage($a, $log);

?>
