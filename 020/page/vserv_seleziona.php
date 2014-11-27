<?php

// query	
$query = "SELECT * FROM vserv_magazzino_id;";
$res = mysql_query($query);
if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());



// tabella
$a .= jsxtable;
$a .= "<table>\n";
$a .= "<caption>MAGAZZINO</caption>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>ID</th>\n";
	$a .= "<th>Azione</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Posizioni con parziali</th>\n";
	$a .= "<th>Tot</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

$i=0;

$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=vserv")."'>\n";
while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$a .= "<tr>\n";
	
	foreach ($row as $cname => $cvalue) {
		if ($cname == 'id_merce') {
			$a .= "<td>\n<input type='checkbox' name='check_list[]' value='".$i."'/>\n".input_hidden($cname."[".$i."]",$cvalue)."\n</td>\n";
			$a .= "<td>\n";
			$a .= "<input type='submit' name='modifica' value='Modifica'/>\n";
			$a .= "<input type='submit' name='scarica' value='Scarica'/>\n";
			$a .= "<input type='submit' name='stop' value='Reset'/>\n";
			$a .= "</td>\n";
		} else
			$a .= "<td>".input_hidden($cname."[".$i."]",$cvalue)."</td>\n";
	}
	
	$i++;	
	$a .= "</tr>\n";
}
$a .= "</form>\n";

$a .= "</tbody>\n</table>\n";

mysql_free_result($res);

?>
