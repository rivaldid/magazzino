<?php

/*
 * 
 * 		IN input_idRubricaOrdinante INT, 
		IN input_tipoDocOrdinante VARCHAR(45), 
		IN input_numDocOrdinante VARCHAR(45),
		IN input_idRubricaFornitore INT,
		IN input_codArticoloFornitore VARCHAR(45),
		IN input_idArticolo INT,
		IN input_quantita INT
 * 
 * 
 */

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['idRubricaOrdinante']) OR !($_POST['idRubricaOrdinante']) OR ($_POST['idRubricaOrdinante'] == "NULL"))
		killemall("intestazione mittente ordine");
	
	if (!isset($_POST['tipoDocOrdinante']) OR !($_POST['tipoDocOrdinante']) OR ($_POST['tipoDocOrdinante'] == "NULL"))
		killemall("tipo documento di ordine");

	if (!isset($_POST['numDocOrdinante']) OR !($_POST['numDocOrdinante']) OR ($_POST['numDocOrdinante'] == "NULL"))
		killemall("numero documento di ordine");

	if (!isset($_POST['idRubricaFornitore']) OR !($_POST['idRubricaFornitore']) OR ($_POST['idRubricaFornitore'] == "NULL"))
		killemall("intestazione fornitore");

	if (!isset($_POST['codArticoloFornitore']) OR !($_POST['codArticoloFornitore']) OR ($_POST['codArticoloFornitore'] == "NULL"))
		killemall("codice articolo del fornitore");
	
	if (!isset($_POST['idArticolo']) OR !($_POST['idArticolo']) OR ($_POST['idArticolo'] == "NULL"))
		killemall("articolo");
		
	if (!isset($_POST['quantita']) OR !($_POST['quantita']) OR ($_POST['quantita'] == "NULL"))
		killemall("quantita dell'ordine");
	
	$idRubricaOrdinante = safe($_POST['idRubricaOrdinante']);
	$tipoDocOrdinante = safe($_POST['tipoDocOrdinante']);
	$numDocOrdinante = safe(epura2($_POST['numDocOrdinante']));
	$idRubricaFornitore = safe($_POST['idRubricaFornitore']);
	$codArticoloFornitore = safe($_POST['codArticoloFornitore']);
	$idArticolo = safe($_POST['idArticolo']);
	$quantita = safe(int_ok($_POST['quantita']));
	
	$callsql = "CALL inserisciOrdini('{$idRubricaOrdinante}','{$tipoDocOrdinante}','{$numDocOrdinante}','{$idRubricaFornitore}','{$codArticoloFornitore}','{$idArticolo}','{$quantita}');";
	echo call_core("valorizzazione ordine",$callsql);
	
 }
 
 else echo form_input_ordini();
 
?>
