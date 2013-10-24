<?php

/*
 * $_POST['idRubrica']
 * $_POST['tipoDoc']
 * $_POST['numDoc']
 * 
 * $_POST['datayear']
 * $_POST['datamonth']
 * $_POST['dataday']
 * 
 */

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['idRubrica']) OR !($_POST['idRubrica']) OR ($_POST['idRubrica'] == "NULL"))
		killemall("mittente documento");
	
	if (!isset($_POST['tipoDoc']) OR !($_POST['tipoDoc']) OR ($_POST['tipoDoc'] == "NULL"))
		killemall("tipo di documento");

	if (!isset($_POST['numDoc']) OR !($_POST['numDoc']) OR ($_POST['numDoc'] == "NULL"))
		killemall("numero di documento");
	
	$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
	if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
	
	$idRubrica = safe($_POST['idRubrica']);
	$tipoDoc = safe($_POST['tipoDoc']);
	$numDoc = safe(epura2($_POST['numDoc']));
	if (strcmp($numDoc,"Automatico") == 0) $numDoc="";
	
	$tipoRubrica = service_get_field("SELECT tipoRubrica FROM rubrica WHERE idRubrica={$idRubrica}","tipoRubrica");
	if (($tipoRubrica == "Fornitore") AND (($tipoDoc == "ODA") OR ($tipoDoc == "BDC")))
		killemall("fornitore in emissione ordine");
	
	$callsql = "CALL inserisciRegistro('{$idRubrica}','{$tipoDoc}','{$numDoc}','{$data}','');";
	echo call_core("inserimento documento",$callsql);
 }
 
 else echo form_input_registro_nofile();
 
?>
