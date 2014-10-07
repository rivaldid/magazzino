<?php

echo "<form method=\"post\" class=\"excelForm\" onSubmit=\"javascript:return getData()\">
<div class=\"CSSTableGenerator\" >".table_magazzino()."</div></form>";

/*
if (isset($_POST['submit'])) {
	
	// merce
	if (!isset($_POST['option_id_merce_posizione']) OR !($_POST['option_id_merce_posizione']) OR ($_POST['option_id_merce_posizione'] == "NULL"))
		killemall("selezione merce per scarico");
	$option_id_merce_posizione = explode(" ",safe($_POST['option_id_merce_posizione']));
	$id_merce = $option_id_merce_posizione[0];
	$posizione_iniziale = $option_id_merce_posizione[1];
	
	// nuova posizione
	if (!isset($_POST['listaetichette5']) OR !($_POST['listaetichette5']) OR ($_POST['listaetichette5'] == "NULL")) {
		if (!isset($_POST['posizione_finale']) OR !($_POST['posizione_finale']) OR ($_POST['posizione_finale'] == "NULL"))
			killemall("posizione finale moving");
		echo input_etichetta("5",safe(epura($_POST['posizione_finale'])));
		$posizione_finale = safe(epura($_POST['posizione_finale']));
	} else $posizione_finale = safe($_POST['listaetichette5']);
	
	echo $callsql = "CALL moving_magazzino('{$id_merce}','{$posizione_iniziale}','{$posizione_finale}');";
	echo call_core("moving merce",$callsql);
	echo "<p><h2><a href=\"?page=magazzino\">Nuovo moving</a></h2></p>";

} else echo "<div class=\"CSSTableGenerator\" >".form_moving()."</div>";
*/
	
?>

