<?php

/*
 * $_POST['tipo_ins']
 * 
 * if tipo_ins=new
 * $_POST['tag_l1']
 * $_POST['tag_l2']
 * $_POST['tag_l3']
 * $_POST['noteArticoli']
 * 
 * if tipo_ins=old
 * $_POST['idArticolo']
 * 
 * $_POST['idRubricaFornitore']
 * $_POST['numDocFornitura']
 * $_POST['quantita']
 * $_POST['posizioni']
 * $_POST['piazzamenti']
 * 
 * $_POST['datayear']
 * $_POST['datamonth']
 * $_POST['dataday']
 * 
 * $_POST['note']
 * 
 * 
 * taskCarico1
 * 	IN input_idRubricaFornitore INT, 
	IN input_tipoDocFornitura VARCHAR(45), 
	IN input_numDocFornitura VARCHAR(45),
	IN input_quantita INT,
	IN input_posizione VARCHAR(45),
	IN input_data date,
	IN input_note VARCHAR(45),
	IN input_idArticolo INT,
	IN input_tags VARCHAR(45),
	IN input_noteArticoli VARchar(45)
 *
 */

if (isset($_POST['submit'])) {
	
	if (!isset($_POST['tipo_ins']) OR !($_POST['tipo_ins']) OR ($_POST['tipo_ins'] == "NULL"))
		killemall("tipo di inserimento");
	$tipo_ins = safe($_POST['tipo_ins']);
	
	
	switch($tipo_ins) {
		
		case "new":
		
			$idArticolo = 0;
	
			if (!isset($_POST['tag_l1']) OR !($_POST['tag_l1']) OR ($_POST['tag_l1'] == "NULL"))
				killemall("tags");
	
			$tags = safe($_POST['tag_l1']);
			if ($_POST['tag_l2'] != "NULL") $tags .= " ".safe($_POST['tag_l2']);
			if ($_POST['tag_l3'] != "NULL") $tags .= " ".safe($_POST['tag_l3']);
	
			if (isset($_POST['noteArticoli']))
				$noteArticoli = safe($_POST['noteArticoli']);
			else 
				$noteArticoli = '';
			
			break;

		case "old":
		
			if (!isset($_POST['idArticolo']) OR !($_POST['idArticolo']) OR ($_POST['idArticolo'] == "NULL"))
				killemall("selezione articolo per inserimento da modello");
			
			$idArticolo = safe($_POST['idArticolo']);
			$tags = '';
			$noteArticoli = '';
			
			break;
			
		default:
			
			killemall("tipo di inserimento non valido");
		
	 } // output: $idArticoli, $tags, $noteArticoli
	
	
	if (!isset($_POST['idRubricaFornitore']) OR !($_POST['idRubricaFornitore']) OR ($_POST['idRubricaFornitore'] == "NULL"))
		killemall("fornitore");
	
	if (!isset($_POST['numDocFornitura']) OR !($_POST['numDocFornitura']) OR ($_POST['numDocFornitura'] == "NULL"))
		killemall("numero di DDT");
	
	if (!isset($_POST['quantita']) OR !($_POST['quantita']) OR ($_POST['quantita'] == "NULL"))
		killemall("quantita del carico");
		
	if (!isset($_POST['posizioni']) OR !($_POST['posizioni']) OR ($_POST['posizioni'] == "NULL"))
		killemall("posizione");
		
	if (!isset($_POST['piazzamenti']) OR !($_POST['piazzamenti']) OR ($_POST['piazzamenti'] == "NULL"))
		killemall("piazzamento nella posizione");
		
	$quantita = safe(int_ok($_POST['quantita']));
	$idRubricaFornitore = safe($_POST['idRubricaFornitore']);
	$tipoDocFornitura = "DDT";
	$numDocFornitura = safe(epura2($_POST['numDocFornitura']));
	$posizione = safe($_POST['posizioni'])." ".safe($_POST['piazzamenti']);
	
	$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
	if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
	
	$note = safe($_POST['note']);

		
	echo $callsql = "CALL taskCarico1('{$idRubricaFornitore}','{$tipoDocFornitura}','{$numDocFornitura}','{$quantita}','{$posizione}','{$data}','{$note}','{$idArticolo}','{$tags}','{$noteArticoli}');";
	echo call_core("task carico",$callsql);
	
 }
 
 else echo form_input_carichi1();
 
?>
