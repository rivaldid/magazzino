<?php

/*
 * $_POST['idArticolo']
 * $_POST['posOrigine']
 * $_POST['posizioni']
 * $_POST['piazzamenti']
 * 
 */
 
function test_compatibilita_destinazione($posizione,$idArticolo) {
	$tags_da_inserire = service_get_field("SELECT * FROM articoli WHERE idArticolo=\"{$idArticolo}\"","tags");
	$tags_presenti = service_get_field("CALL tags_in_data_posizione('{$posizione}');","tags");
	if (!empty($tags_presenti)) {
		if (strcmp($tags_da_inserire,$tags_presenti) <> 0)
			killemall("tentativo di riallocazione su posizione occupata da merce differente");
		else $a = "Posizione occupata da merce compatibile.";
	}
	return $a;
}


function step1() {
	
	if (!isset($_POST['idArticolo']) OR !($_POST['idArticolo']) OR ($_POST['idArticolo'] == "NULL"))
		killemall("articolo da scaricare");
			
	$idArticolo = safe($_POST['idArticolo']);
	$tags = service_get_field("SELECT * FROM articoli WHERE idArticolo=\"{$idArticolo}\"","tags");
	echo "<h3>Articolo selezionato: {$tags}</h3>";
	echo form_input_compattazione_step2($idArticolo);
	return true;
}
				
function step2($idArticolo) {

	if (!isset($_POST['posOrigine']) OR !($_POST['posOrigine']) OR ($_POST['posOrigine'] == "NULL"))
		killemall("provenienza");
	
	if (!isset($_POST['posizioni']) OR !($_POST['posizioni']) OR ($_POST['posizioni'] == "NULL"))
		killemall("posizione di destinazione");

	if (!isset($_POST['piazzamenti']) OR !($_POST['piazzamenti']) OR ($_POST['piazzamenti'] == "NULL"))
		killemall("piazzamenti di destinazione");
	
	$posOrigine = safe($_POST['posOrigine']);
	
	$posizioni = safe($_POST['posizioni']);
	$piazzamenti = safe($_POST['piazzamenti']);
	$posDestinazione = $posizioni." ".$piazzamenti;
	echo test_compatibilita_destinazione($posDestinazione,$idArticolo);
		
	$callsql = "CALL taskCompattazione('{$idArticolo}','{$posOrigine}','{$posDestinazione}');";
	echo call_core("task Compattazione",$callsql);
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
			
				step2($_POST['idArticolo']);
				
				break;
		
			default:
			
				killemall("task compattazione non andato a buon fine");
		
		}

	 }

} else echo form_input_compattazione_step1();	
		
 
?>
