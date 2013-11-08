<?php

/*
 * $_POST['idArticolo']
 * 
 * $_POST['idRubricaRich']
 * $_POST['numDocRich']
 * $_POST['quantita']
 * $_POST['posOrigine']
 * $_POST['posDestinazione']
 * 
 * $_POST['datayear']
 * $_POST['datamonth']
 * $_POST['dataday']
 * 
 * $_POST['note']
 * 
 * 
 * taskScarico
		IN input_idArticolo INT,
		IN input_idRubricaRich INT,
		IN input_tipoDocRich VARCHAR(45),
		IN input_numDocRich VARCHAR(45),
		IN input_quantita  VARCHAR(45),
		IN input_posOrigine VARCHAR(45),
		IN input_posDestinazione VARCHAR(45),
		IN input_data DATE,
		IN input_note VARCHAR(45)
 *
 */


function scarico_step1() {
	
	if (!isset($_POST['idArticolo']) OR !($_POST['idArticolo']) OR ($_POST['idArticolo'] == "NULL"))
		killemall("articolo da scaricare");
			
	$idArticolo = safe($_POST['idArticolo']);
	$tags = service_get_field("SELECT * FROM articoli WHERE idArticolo=\"{$idArticolo}\"","tags");
	echo "<h3>Articolo selezionato: {$tags}</h3>";
	echo form_input_scarichi_step2($idArticolo);
	return true;
}
				
function scarico_step2($idArticolo) {

	if (!isset($_POST['idRubricaRich']) OR !($_POST['idRubricaRich']) OR ($_POST['idRubricaRich'] == "NULL"))
		killemall("richiedente merce");
	
	if (!isset($_POST['numDocRich']) OR !($_POST['numDocRich']) OR ($_POST['numDocRich'] == "NULL"))
		killemall("numero del modulo di scarico");

	if (!isset($_POST['quantita']) OR !($_POST['quantita']) OR ($_POST['quantita'] == "NULL"))
		killemall("quantita di merce per lo scarico");
	
	if (!isset($_POST['posOrigine']) OR !($_POST['posOrigine']) OR ($_POST['posOrigine'] == "NULL"))
		killemall("posizione di partenza");
	
	if (!isset($_POST['posDestinazione']) OR !($_POST['posDestinazione']) OR ($_POST['posDestinazione'] == "NULL"))
		killemall("posizione di destinazione");
	

	$idRubricaRich = safe($_POST['idRubricaRich']);
	$tipoDocRich = "MDS";
	$numDocRich = safe(epura2($_POST['numDocRich']));
	$quantita = safe(int_ok($_POST['quantita']));
	
	$tags = service_get_field("SELECT * FROM articoli WHERE idArticolo=\"{$idArticolo}\"","tags");
	$giacenza = service_get_field("CALL giacenzeTags(\"{$tags}\");","giacenza");
	
	if ($quantita > $giacenza) killemall("quantita richiesta maggiore della giacenza");
	
	$posOrigine = safe($_POST['posOrigine']);
	$posDestinazione = safe($_POST['posDestinazione']);
	
	$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
	if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
	
	$note = safe($_POST['note']);

	echo $callsql = "CALL taskScarico('{$idArticolo}','{$idRubricaRich}','{$tipoDocRich}','{$numDocRich}','{$quantita}','{$posOrigine}','{$posDestinazione}','{$data}','{$note}');";
	echo call_core("task Scarico",$callsql);
	return true;
}


if (isset($_POST['submit'])) {
	
	if (isset($_POST['steps'])) {
		
		$steps = safe($_POST['steps']);
		
		switch ($steps) {
			
			case "step1" :
			
				scarico_step1();
				
				break;
		
			case "step2":
			
				scarico_step2($_POST['idArticolo']);
				
				break;
		
			default:
			
				killemall("task scarico non andato a buon fine");
		
		}

	 }

} else echo form_input_scarichi_step1();	
		
 
?>
