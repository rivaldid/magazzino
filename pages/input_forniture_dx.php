<?php

/*
 * 
 * 		IN input_idRubricaFornitore INT, 
		IN input_tipoDocFornitura VARCHAR(45), 
		IN input_numDocFornitura VARCHAR(45),
		IN input_idRubricaOrdinante INT, 
		IN input_tipoDocOrdinante VARCHAR(45), 
		IN input_numDocOrdinante VARCHAR(45),
		IN input_idRubricaTrasp INT
 * 
 * 
 */

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['idRubricaTrasp']) OR !($_POST['idRubricaTrasp']) OR ($_POST['idRubricaTrasp'] == "NULL"))
		$idRubricaTrasp='0';
	else
		$idRubricaTrasp = safe($_POST['idRubricaTrasp']);
	
	if (!isset($_POST['idRubricaFornitore']) OR !($_POST['idRubricaFornitore']) OR ($_POST['idRubricaFornitore'] == "NULL"))
		killemall("fornitore");

	if (!isset($_POST['tipoDocFornitura']) OR !($_POST['tipoDocFornitura']) OR ($_POST['tipoDocFornitura'] == "NULL"))
		killemall("tipo documento fornitura");

	if (!isset($_POST['numDocFornitura']) OR !($_POST['numDocFornitura']) OR ($_POST['numDocFornitura'] == "NULL"))
		killemall("numero documento fornitura");

	if (!isset($_POST['idRubricaOrdinante']) OR !($_POST['idRubricaOrdinante']) OR ($_POST['idRubricaOrdinante'] == "NULL"))
		killemall("mittente");

	if (!isset($_POST['tipoDocOrdinante']) OR !($_POST['tipoDocOrdinante']) OR ($_POST['tipoDocOrdinante'] == "NULL"))
		killemall("tipo documento mittente");

	if (!isset($_POST['numDocOrdinante']) OR !($_POST['numDocOrdinante']) OR ($_POST['numDocOrdinante'] == "NULL"))
		killemall("numero documento mittente");
		
	$idRubricaFornitore = safe($_POST['idRubricaFornitore']);
	$tipoDocFornitura = safe($_POST['tipoDocFornitura']);
	$numDocFornitura = safe(epura2($_POST['numDocFornitura']));
	$idRubricaOrdinante = safe($_POST['idRubricaOrdinante']);
	$tipoDocOrdinante = safe($_POST['tipoDocOrdinante']);
	$numDocOrdinante = safe(epura2($_POST['numDocOrdinante']));
			
	$callsql = "CALL inserisciForniture('{$idRubricaFornitore}','{$tipoDocFornitura}','{$numDocFornitura}','{$idRubricaOrdinante}','{$tipoDocOrdinante}','{$numDocOrdinante}','{$idRubricaTrasp}');";
	echo call_core("inserimento fornitura",$callsql);
 }
 else echo form_input_forniture();
?>
