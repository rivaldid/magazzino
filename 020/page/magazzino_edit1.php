<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js" /></script>

<script type="text/javascript">
$(document).ready(function(){

$('input[name="all"],input[name="title"]').bind('click', function(){
var status = $(this).is(':checked');
$('input[type="checkbox"]', $(this).parent('li')).attr('checked', status);
});

});
</script>
<?php

$a = "";

if (isset($_POST['submit'])) {
if (!empty($_POST['edit_list'])) {
	
//print
$a .= "<table>\n";
$a .= "<caption>EDIT MAGAZZINO</caption>\n";
$a .= "<thead><tr>\n";
	$a .= "<th>ID</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Posizioni con parziali</th>\n";
	$a .= "<th>Tot</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

foreach ($_POST['edit_list'] as $j) {
	
	$a .= "<tr>\n";
	$a .= "<td>".$_POST['id_merce'][$j]."</td>\n";
	$a .= "<td>".$_POST['tags'][$j]."</td>\n";
	$a .= "<td>".$_POST['posizioni'][$j]."</td>\n";
	$a .= "<td>".$_POST['tot'][$j]."</td>\n";
	$a .= "</tr>\n";

	
}

$a .= "</tbody>\n</table>\n";

}

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
	//$a .= "<th >Seleziona Tutti</th>\n";
	$a .= "<th ><input type='checkbox' name='all' id='all'  /> Toggle All</th>\n";
	$a .= "<th></th>\n";
	$a .= "<th>ID</th>\n";
	$a .= "<th>TAGS</th>\n";
	$a .= "<th>Posizioni con parziali</th>\n";
	$a .= "<th>Tot</th>\n";
$a .= "</tr></thead>\n";
$a .= "<tbody>\n";

$i=0;
$a .= "<form method='post'  enctype='multipart/form-data' action='".htmlentities("?page=magazzino_edit")." '/>\n";
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

echo $a;



?>

