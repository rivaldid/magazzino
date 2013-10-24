<?php
if (isset($_POST['submit'])) {
	
	/*
	 * $_POST['idColli']
	 * $_POST['documento] multi x5 - data,idRubrica,tipoDoc,numDoc,intestazione
	 */
	
	if (!isset($_POST['documento']) OR !($_POST['documento']) OR ($_POST['documento'] == "NULL")) 
		killemall("selezione documento per collo");
		
	if (isset($_POST['idColli']))
		$idColli = safe($_POST['idColli']);
	else
		$idColli="0";
	
	$documento = explode(" ",safe($_POST['documento']));
	$idRubrica = $documento[1];
	$tipoDoc = $documento[2];
	$numDoc = $documento[3];
	
	$callsql = "CALL inserisciColli('{$idColli}','{$idRubrica}','{$tipoDoc}','{$numDoc}');";
	echo call_core("raggruppamento doc.",$callsql);

				
} else echo form_input_collo();

echo "<p><h2><a href=\"?page=colli\">Nuovo inserimento</a></h2></p>";
echo table_Colli();
?>
