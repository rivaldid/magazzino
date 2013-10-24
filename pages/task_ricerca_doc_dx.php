<?php

/*
 * $_POST['tiposearch']
 * 
 * $_POST['datayear']
 * $_POST['datamonth']
 * $_POST['dataday']
 * 
 * $_POST['tipoDoc']
 * $_POST['numDoc']
 */

if (isset($_POST['submit'])) {
		
	$tiposearch = safe($_POST['tiposearch']);
	switch ($tiposearch) {
		
		// ricerca per data
		case "100":
			$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
			if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
			$tipoDoc = "";
			$numDoc = "";
			break;
		
		// ricerca per tipo
		case "010":
			if (!isset($_POST['tipoDoc']) OR !($_POST['tipoDoc']) OR ($_POST['tipoDoc'] == "NULL"))
				killemall("tipo di documento");
			$data = "";
			$tipoDoc = safe($_POST['tipoDoc']);
			$numDoc = "";
			break;
		
		// ricerca data e tipo
		case "110":
			if (!isset($_POST['tipoDoc']) OR !($_POST['tipoDoc']) OR ($_POST['tipoDoc'] == "NULL"))
				killemall("tipo di documento");
			$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
			if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
			$tipoDoc = safe($_POST['tipoDoc']);
			$numDoc = "";
			break;
		
		// ricerca per numero
		case "001":
			if (!isset($_POST['numDoc']) OR !($_POST['numDoc']) OR ($_POST['numDoc'] == "NULL"))
				killemall("numero del documento");
			$data = "";
			$tipoDoc = "";
			$numDoc = safe(epura2($_POST['numDoc']));
			break;
			
		// tutto
		default:
			$data = "";
			$tipoDoc = "";
			$numDoc = "";
	}
	
	echo table_esitoRicercaDoc($data,$tipoDoc,$numDoc);
	
} else echo form_input_ricerca_doc();
 
?>
