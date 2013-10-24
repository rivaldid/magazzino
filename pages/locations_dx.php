<?php

if (isset($_POST['submit'])) {
	
	/*
	 * $_POST['indicatore']
	 * $_POST['label]
	 */
	
	if (!isset($_POST['indicatore']) OR !($_POST['indicatore']) OR ($_POST['indicatore'] == "NULL")) 
		killemall("indicatore");
		
	if (!isset($_POST['label']) OR !($_POST['label']) OR ($_POST['label'] == "NULL")) 
		killemall("etichetta");

	$indicatore = safe($_POST['indicatore']);
	$label = safe(epura($_POST['label']));
	
	$callsql = "CALL inserisciLocations('{$indicatore}','{$label}');";
	echo call_core("valorizzazione posizione",$callsql);

				
} else echo form_input_locations();

echo "<p><h2><a href=\"?page=locations\">Nuovo inserimento</a></h2></p>";
echo table_locations();

?>
