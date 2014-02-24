<?php

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['selettore']) OR !($_POST['selettore']) OR ($_POST['selettore'] == "NULL"))
		killemall("selettore");
	$a = safe($_POST['selettore']);
	
	if (!isset($_POST['label']) OR !($_POST['label']) OR ($_POST['label'] == "NULL"))
		killemall("label");
	if ($a == '4') 
		$b = safe($_POST['label']);
	elseif ($a == '6')
		$b = safe(epura2($_POST['label']));
	else
		$b = safe(epura($_POST['label']));
	
	$callsql = "CALL input_etichette('{$a}','{$b}');";
	echo call_core("aggiungi etichetta",$callsql);
	echo "<p><h2><a href=\"?page=etichette\">Nuovo inserimento</a></h2></p>";
	
} else echo "<div class=\"CSSTableGenerator\" >".form_etichette()."</div>";

echo "<form method=\"post\" class=\"excelForm\" onSubmit=\"javascript:return getData()\">
<div class=\"CSSTableGenerator\" >".table_etichette()."</div></form>";
	
?>

