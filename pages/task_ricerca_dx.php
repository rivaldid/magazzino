<?php

/*
 * $_POST['tags']
 * 
 */

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['tags']) OR !($_POST['tags']) OR ($_POST['tags'] == "NULL"))
		$tags = '';
	else
		$tags = safe($_POST['tags']);
	
	echo table_esitoRicercaTags($tags);
	
} else echo form_input_ricerca();
 
?>
