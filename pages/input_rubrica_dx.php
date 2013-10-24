<?php

/*
 * $_POST['tipoRubrica']
 * $_POST['intestazione']
 * $_POST['pIva']
 * $_POST['codFiscale']
 * $_POST['indirizzo']
 * $_POST['cap']
 * $_POST['citta']
 * $_POST['nazione']
 * $_POST['tel']
 * $_POST['fax']
 * $_POST['sitoWeb']
 * $_POST['email']
 * 
 */

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['tipoRubrica']) OR !($_POST['tipoRubrica']) OR ($_POST['tipoRubrica'] == "NULL"))
		killemall("tipo di contatto");
	
	if (!isset($_POST['intestazione']) OR !($_POST['intestazione']) OR ($_POST['intestazione'] == "NULL"))
		killemall("intestazione contatto");
	
	if ((!isset($_POST['pIva']) OR !($_POST['pIva']) OR ($_POST['pIva'] == "NULL")) AND
		(!isset($_POST['codFiscale']) OR !($_POST['codFiscale']) OR ($_POST['codFiscale'] == "NULL")))
		killemall("partita iva o codice fiscale");
		
	$tipoRubrica = $_POST['tipoRubrica'];
	$intestazione = safe($_POST['intestazione']);
	$pIva = safe($_POST['pIva']);
	$codFiscale = safe($_POST['codFiscale']);
	$indirizzo = safe($_POST['indirizzo']);
	$cap = safe($_POST['cap']);
	$citta = safe($_POST['citta']);
	$nazione = safe($_POST['nazione']);
	$tel = safe($_POST['tel']);
	$fax = safe($_POST['fax']);
	$sitoWeb = safe($_POST['sitoWeb']);
	$email = safe($_POST['email']);
			
	$callsql = "CALL inserisciRubrica('{$tipoRubrica}','{$intestazione}','{$pIva}','{$codFiscale}','{$indirizzo}','{$cap}','{$citta}','{$nazione}','{$tel}','{$fax}','{$sitoWeb}','{$email}');";
	echo call_core("valorizzazione rubrica",$callsql);
 }
 else echo form_input_rubrica();
?>
