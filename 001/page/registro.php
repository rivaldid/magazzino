<?php

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['contatto']) OR !($_POST['contatto']) OR ($_POST['contatto'] == "NULL"))
		killemall("mittente documento");
	$contatto = safe($_POST['contatto']);
	
	if (!isset($_POST['tipo_doc']) OR !($_POST['tipo_doc']) OR ($_POST['tipo_doc'] == "NULL"))
		killemall("tipo di documento");
	$tipo_doc = safe($_POST['tipo_doc']);
	
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
	
	$callsql = "CALL input_registro('{$contatto}','{$tipo_doc}','{$numero}','{$gruppo}','{$data}','{$filename}','@myvar');";
	echo call_core("aggiungi documento",$callsql);
	echo "<p><h2><a href=\"?page=registro\">Nuovo inserimento</a></h2></p>";
	
} else echo "<div class=\"CSSTableGenerator\" >".form_registro()."</div>";

echo "<form method=\"post\" class=\"excelForm\" onSubmit=\"javascript:return getData()\">
<div class=\"CSSTableGenerator\" >".table_registro()."</div></form>";
	
?>

