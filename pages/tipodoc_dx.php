<?php

if (isset($_POST['submit'])) {
	
	/*
	 * $_POST['label']
	 * $_POST['ambito']
	 * 
	 */
	
	if (!isset($_POST['label']) OR !($_POST['label']) OR ($_POST['label'] == "NULL")) 
		killemall("etichetta tipo di documento");
		
	if (!isset($_POST['ambito']) OR !($_POST['ambito']) OR ($_POST['ambito'] == "NULL")) 
		killemall("ambito del documento");
	
	$label = safe(epura2($_POST['label']));
	$ambito = safe($_POST['ambito']);
	
	$callsql = "CALL inserisciTipoDoc('{$label}','{$ambito}');";
	echo call_core("valorizzazione TipoDoc",$callsql);
				
} else echo form_input_tipodoc();

echo "<p><h2><a href=\"?page=tipodoc\">Nuovo inserimento</a></h2></p>";
echo table_tipodoc();

?>
