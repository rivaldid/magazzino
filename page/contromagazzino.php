<?php

// inizializzo risorse

// variabili
$a = "";
$log = "";

// mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());

$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

$log .= remesg("Torna alla <a href=\"?page=magazzino\">visualizzazione magazzino</a>","action");

// query
$query = "SELECT MERCE.tags AS merce,sum(seljoin.quantita) AS tot,GROUP_CONCAT(DISTINCT CONCAT(seljoin.posizione,'(',seljoin.quantita,')')) AS destinazioni FROM
(SELECT sel1.id_merce,sel1.quantita,sel1.posizione FROM
(SELECT id_merce,quantita,posizione FROM OPERAZIONI JOIN REGISTRO USING(id_registro) WHERE direzione=0 AND contatto!='Aggiornamento') AS sel1
LEFT JOIN
(SELECT id_merce,quantita,posizione FROM MAGAZZINO WHERE quantita>0) AS sel2
ON CONCAT(sel1.id_merce,sel1.quantita,sel1.posizione)=CONCAT(sel2.id_merce,sel2.quantita,sel2.posizione)
WHERE CONCAT(sel2.id_merce,sel2.quantita,sel2.posizione) IS NULL) AS seljoin
JOIN MERCE ON seljoin.id_merce=MERCE.id_merce GROUP BY MERCE.tags ORDER BY MERCE.tags";

// interrogazione
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());


// risultati
$a .= jsxtable;
$a .= jsaltrows;
$a .= "<table class='altrowstable' id='alternatecolor'>\n";
$a .= "<thead><tr>\n";
	if (isset($_GET["id"])) $a .= "<th>ID</th>\n";
	$a .= "<th>MERCE</th>\n";
	$a .= "<th>Quantita</th>\n";
	$a .= "<th>Destinazioni</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
	$a .= "<tr>\n";
	foreach ($row as $cname => $cvalue)
		$a .= "<td>".$cvalue."</td>\n";
	$a .= "</tr>\n";
}

$a .= "</tbody>\n</table>\n";
mysql_free_result($res);


// termino risorse
mysql_close($conn);


// stampo
echo makepage($a, $log);


?>

