<?php

/*
 * $_POST['tag_l1']
 * $_POST['tag_l2']
 * $_POST['tag_l3']
 * $_POST['descrizione']
 * 
 */

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['tag_l1']) OR !($_POST['tag_l1']) OR ($_POST['tag_l1'] == "NULL"))
		killemall("tags");
	
	$tags = safe($_POST['tag_l1']);
	if ($_POST['tag_l2'] != "NULL") $tags .= " ".safe($_POST['tag_l2']);
	if ($_POST['tag_l3'] != "NULL") $tags .= " ".safe($_POST['tag_l3']);
	
	$descrizione = safe($_POST['descrizione']);
	
	$callsql = "CALL inserisciArticoli('{$tags}','{$descrizione}');";
	echo call_core("valorizzazione articolo",$callsql);
	
 } else echo form_input_articoli();

echo "<p><h2><a href=\"?page=inventario_articoli\">Nuovo inserimento</a></h2></p>";
echo table_articoli();
?>
