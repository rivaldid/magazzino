<?php


/*
 * scarico merce da magazzino, script frontend per stored procedure
 * CALL SCARICO(utente, richiedente, id_merce, quantita, posizione, 
 * destinazione, data_doc_scarico, data_scarico, note_scarico,@myvar);
 * 
 * lo SCARICO ritorna un valore, 0 se andato a buon fine 1 altrimenti
 * 
 * ALGORITMO:
 * 		1. definizione variabili
 * 		2. startup risorse
 * 			2a. $_SESSION
 * 			2b. mysql
 * 		3. lista merce
 * 		4. form merce
 * 		5. libero risorse
 * 		6. stampo
 * 
 * 
 */
	
// 1. definizione variabili
$query_lista_merce = "SELECT * FROM vista_magazzino;";
$a = "";
$log = "";


// 2. startup risorse

// 2a. $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

// 2b. mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
	
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());

// 3. lista merce
$result_lista_merce = mysql_query($query_lista_merce);
if (!$result_lista_merce) die('Errore nell\'interrogazione del db: '.mysql_error());


// 4. form merce
$a .= "<table>\n";
$a .= "<caption>SCARICO MERCE</caption>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>Posizione</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Quantita'</th>\n";
	$a .= "<th>Azione</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";
	
while ($row = mysql_fetch_array($result_lista_merce, MYSQL_NUM)) {
	$a .= "<tr>\n";
	$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=scarico")."'>\n";
	foreach ($row as $cname => $cvalue)
		if ($cname == "0") 
			$a .= noinput_hidden("id_merce",$cvalue)."\n";
		else
			$a .= "<td>".$cvalue."</td>\n";
	$a .= "<td><input type='submit' name='submit' value='Scarico'/></td>\n";
	$a .= "</tr>\n";
}

$a .= "</tbody>\n</table>\n";

mysql_free_result($result_lista_merce);


// 5. libero risorse
mysql_close($conn);
session_write_close();


// 6. stampo
echo "<div id=\"log\">\n";
echo remesg("Notifiche","tit");
if ($log == "")
	echo remesg("nessuna notifica da visualizzare","msg");
else
	echo $log;
echo "</div>\n";

echo $a;




?>

