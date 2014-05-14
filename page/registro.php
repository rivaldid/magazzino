<?php

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['id_contatto_']) OR !($_POST['id_contatto_']) OR ($_POST['id_contatto_'] == "NULL"))
		killemall("mittente documento");
	$id_contatto = safe($_POST['id_contatto_']);
	
	if (!isset($_POST['listaetichette6']) OR !($_POST['listaetichette6']) OR ($_POST['listaetichette6'] == "NULL"))
		killemall("tipo di documento");
	$categoria = safe($_POST['listaetichette6']);
	
	if (!isset($_POST['numero']) OR !($_POST['numero']) OR ($_POST['numero'] == "NULL"))
		killemall("numero di documento");
	$numero = safe($_POST['numero']);
	
	if (isset($_POST['gruppo'])) $gruppo = safe($_POST['gruppo']);
	else $gruppo = NULL;
	
	$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
	if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
	
	if (isset($_POST['action']) AND ($_POST['action'] == 'upload')) {
		$basename = sprintf("%s_%s_%s",$id_contatto,epura_specialchars($categoria),epura_specialchars($numero));
		if (($filename = registro_upload_allegato($basename)) === false)
			killemall("caricamento scansione documento");
	}
	
	$callsql = "CALL input_registro('{$id_contatto}','{$categoria}','{$numero}','{$gruppo}','{$data}','{$filename}');";
	echo call_core("aggiungi documento",$callsql);
	echo "<p><h2><a href=\"?page=registro\">Nuovo inserimento</a></h2></p>";
	
} else echo "<div class=\"CSSTableGenerator\" >".form_registro()."</div>";

echo "<form method=\"post\" class=\"excelForm\" onSubmit=\"javascript:return getData()\">
<div class=\"CSSTableGenerator\" >".table_registro()."</div></form>";
	
?>

