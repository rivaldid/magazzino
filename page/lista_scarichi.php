<?php

// inizializzo risorse

// variabili
$a = "";
$log = "";


if (isset($_GET["ultimi"]))
	$lista_scarichi = myquery::lista_scarichi($db,15);
else
	$lista_scarichi = myquery::lista_scarichi($db,NULL);


// risultati
$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";

$log .= remesg("Torna a <a href=\"?page=scarico\">scarico</a>","info");

$a .= "<thead><tr>\n";
	$a .= "<th>Utente</th>\n";
	$a .= "<th>Data effettiva</th>\n";
	$a .= "<th>Data di riferimento</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Quantita'</th>\n";
	$a .= "<th>Destinazione</th>\n";
	$a .= "<th>Note</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

foreach ($lista_scarichi AS $elemento) {
	$a .= "<tr>\n";
	$a .= "<td>".safetohtml($elemento['rete'])."</td>\n";
	$a .= "<td>".safetohtml($elemento['dataop'])."</td>\n";
	$a .= "<td>".safetohtml($elemento['data_doc'])."</td>\n";
	$a .= "<td>".safetohtml($elemento['tags'])."</td>\n";
	$a .= "<td>".safetohtml($elemento['quantita'])."</td>\n";
	$a .= "<td>".safetohtml($elemento['posizione'])."</td>\n";
	$a .= "<td>".safetohtml(strtolower($elemento['note']))."</td>\n";
	$a .= "</tr>\n";
}

$a .= "</tbody>\n</table>\n";

// stampo
echo makepage($a, $log);


?>

