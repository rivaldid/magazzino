<?php

/*
 * 
 * 		IN input_idRubricaOrdinante INT, 
		IN input_tipoDocOrdinante VARCHAR(45), 
		IN input_numDocOrdinante VARCHAR(45)
 * 
 * 
 */


if (isset($_POST['submit'])) {
	
	if (!isset($_POST['idRubricaOrdinante']) OR !($_POST['idRubricaOrdinante']) OR ($_POST['idRubricaOrdinante'] == "NULL"))
		killemall("mittente");

	if (!isset($_POST['tipoNumOrdini']) OR !($_POST['tipoNumOrdini']) OR ($_POST['tipoNumOrdini'] == "NULL"))
		killemall("documento mittente");
		
	$idRubricaOrdinante = safe($_POST['idRubricaOrdinante']);
	
	$foo = safe($_POST['tipoNumOrdini']);
	$tipoNum = explode(" ",$foo);
	$tipoDoc = $tipoNum[0];
	$numDoc = $tipoNum[1];
	
	echo table_contabilita($idRubricaOrdinante,$tipoDoc,$numDoc);

} else echo form_input_contabilita();	

?>
