<?php

/*
 * $_POST['input']
 * 
 */

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['input']) OR !($_POST['input']) OR ($_POST['input'] == "NULL"))
		$tags = '';
	else
		$input = safe($_POST['input']);
	
	echo table_esitoRicercaAsset($input);
	
} else echo form_input_ricerca_asset();
 
?>
