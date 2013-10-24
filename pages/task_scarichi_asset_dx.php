<?php

/*
 *	$_POST['serialptNumber']
 * 
 *  
		IN input_seriale VARCHAR(45),
		IN input_ptNumber VARCHAR(45),
		IN input_idRubricaRich INT,
		IN input_tipoDocRich VARCHAR(45),
		IN input_numDocRich VARCHAR(45),
		IN input_posDestinazione VARCHAR(45),
		IN input_data DATE,
		IN input_note VARCHAR(45)
 * 
 *
 */


function step1() {
	
	if (!isset($_POST['serialptNumber']) OR !($_POST['serialptNumber']) OR ($_POST['serialptNumber'] == "NULL"))
		killemall("asset da scaricare");
			
	$serialptNumber = safe($_POST['serialptNumber']);
	$temp = explode(" ",$serialptNumber);
	echo "<h3>Serial / PT Number in scarico: {$temp[0]} / {$temp[1]}</h3>";
	echo form_input_scarichi_asset_step2($serialptNumber);
	return true;
}
				
function step2($serialptNumber) {

	if (!isset($_POST['idRubricaRich']) OR !($_POST['idRubricaRich']) OR ($_POST['idRubricaRich'] == "NULL"))
		killemall("richiedente merce");
	
	if (!isset($_POST['numDocRich']) OR !($_POST['numDocRich']) OR ($_POST['numDocRich'] == "NULL"))
		killemall("numero del modulo di scarico");

	if (!isset($_POST['posDestinazione']) OR !($_POST['posDestinazione']) OR ($_POST['posDestinazione'] == "NULL"))
		killemall("posizione di destinazione");
	
	$temp = explode(" ",$serialptNumber);
	$serial = $temp[0];
	$ptNumber = $temp[1];
	
	$idRubricaRich = safe($_POST['idRubricaRich']);
	$tipoDocRich = "MDS";
	$numDocRich = safe(epura2($_POST['numDocRich']));
	$posDestinazione = safe($_POST['posDestinazione']);
	
	$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
	if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
	
	$note = safe($_POST['note']);

	$callsql = "CALL taskScaricoAsset('{$serial}','{$ptNumber}','{$idRubricaRich}','{$tipoDocRich}','{$numDocRich}','{$posDestinazione}','{$data}','{$note}');";
	echo call_core("task Scarico Asset",$callsql);
	return true;
}


if (isset($_POST['submit'])) {
	
	if (isset($_POST['steps'])) {
		
		$steps = safe($_POST['steps']);
		
		switch ($steps) {
			
			case "step1" :
			
				step1();
				
				break;
		
			case "step2":
			
				step2($_POST['serialptNumber']);
				
				break;
		
			default:
			
				killemall("task scarico asset non andato a buon fine");
		
		}

	 }

} else echo form_input_scarichi_asset_step1();	
		
 
?>
