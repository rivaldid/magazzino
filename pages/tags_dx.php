<?php

if (isset($_POST['submit'])) {
	
	/*
	 * $_POST['livello']
	 * $_POST['label']
	 * 
	 */
	
	if (!isset($_POST['livello']) OR !($_POST['livello']) OR ($_POST['livello'] == "NULL")) 
		killemall("livello");
	
	if (!isset($_POST['label']) OR !($_POST['label']) OR ($_POST['label'] == "NULL")) 
		killemall("etichetta");

	$livello = safe($_POST['livello']);
	$label = safe(epura($_POST['label']));
	
	$callsql = "CALL inserisciTags('{$livello}','{$label}');";
	echo call_core("valorizzazione Tag",$callsql);
				
} else echo form_input_tags();

echo "<p><h2><a href=\"?page=tags\">Nuovo inserimento</a></h2></p>";
echo table_tags();

?>
