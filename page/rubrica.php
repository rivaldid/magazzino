<?php

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['intestazione']) OR !($_POST['intestazione']) OR ($_POST['intestazione'] == "NULL"))
		killemall("intestazione");
	$a = safe($_POST['intestazione']);
	
	if (!isset($_POST['attivita']) OR !($_POST['attivita']) OR ($_POST['attivita'] == "NULL"))
		killemall("attivita");
	$b = safe($_POST['attivita']);
	
	$c = safe($_POST['partita_iva']);
	$d = safe($_POST['codice_fiscale']);
	$e = safe($_POST['indirizzo']);
	$f = safe($_POST['telefono']);
	$g = safe($_POST['fax']);
	$h = safe($_POST['sito_web']);
	$i = safe($_POST['email']);
	
	$callsql = "CALL input_rubrica('{$a}','{$b}','{$c}','{$d}','{$e}','{$f}','{$g}','{$h}','{$i}');";
	echo call_core("aggiungi contatto",$callsql);
	echo "<p><h2><a href=\"?page=rubrica\">Nuovo inserimento</a></h2></p>";
	
} else echo "<div class=\"CSSTableGenerator\" >".form_rubrica()."</div>";

echo "<form method=\"post\" class=\"excelForm\" onSubmit=\"javascript:return getData()\">
<div class=\"CSSTableGenerator\" >".table_rubrica()."</div></form>";

?>

