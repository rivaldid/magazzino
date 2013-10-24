<?php

/*
 * 
 * 		IN input_seriale VARCHAR(45),
		IN input_ptNumber VARCHAR(45),
		IN input_note VARCHAR(45),
		IN input_idArticolo  INT,
		IN input_posizione VARCHAR(45),
		IN input_data DATE
 * 
 * 
 */

function asset_step1() {
	
	if (!isset($_POST['idArticolo']) OR !($_POST['idArticolo']) OR ($_POST['idArticolo'] == "NULL"))
		killemall("articolo da specializzare in asset");
			
	$idArticolo = safe($_POST['idArticolo']);
	$tags = service_get_field("SELECT * FROM articoli WHERE idArticolo=\"{$idArticolo}\"","tags");
	echo "<h3>Articolo selezionato: {$tags}</h3>";
	echo form_input_asset_step2($idArticolo);
	return true;
}


function asset_step2($idArticolo) {

	if (!isset($_POST['seriale']) OR !($_POST['seriale']) OR ($_POST['seriale'] == "NULL"))
		killemall("seriale");
	
	if (!isset($_POST['ptNumber']) OR !($_POST['ptNumber']) OR ($_POST['ptNumber'] == "NULL"))
		killemall("ptNumber");
	
	if (!isset($_POST['posOrigine']) OR !($_POST['posOrigine']) OR ($_POST['posOrigine'] == "NULL"))
		killemall("posizione");

	$seriale = safe(epura($_POST['seriale']));
	$ptNumber = safe(epura($_POST['ptNumber']));
	$note = safe($_POST['note']);
	$posizione = safe($_POST['posOrigine']);
		
	$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
	if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');

	$callsql = "CALL taskAsset('{$seriale}','{$ptNumber}','{$note}','{$idArticolo}','{$posizione}','{$data}');";
	echo call_core("task Asset",$callsql);
	return true;
}


if (isset($_POST['submit'])) {
	
	if (isset($_POST['steps'])) {
		
		$steps = safe($_POST['steps']);
		
		switch ($steps) {
			
			case "step1" :
			
				asset_step1();
				
				break;
		
			case "step2":
			
				asset_step2($_POST['idArticolo']);
				
				break;
		
			default:
			
				killemall("task asset non andato a buon fine");
		
		}

	 }

} else echo form_input_asset_step1();	
 
?>
