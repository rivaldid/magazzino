<?php

if (isset($_GET['id'])) {
	
	echo "<div class=\"CSSTableGenerator\" >".table_operazioni_edit($_GET['id'])."</div>";
	
} elseif (isset($_POST['submit'])) {
		
	if (isset($_POST['check_data'])) {
		$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
		if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');		
	} else $data = NULL;
	
	if (isset($_POST['check_posizione'])) {
		if (!isset($_POST['listaetichette5']) OR !($_POST['listaetichette5']) OR ($_POST['listaetichette5'] == "NULL")) {
			if (!isset($_POST['posizione']) OR !($_POST['posizione']) OR ($_POST['posizione'] == "NULL"))
				killemall("posizione merce");
			echo input_etichetta("5",safe(epura($_POST['posizione'])));
			$posizione = safe(epura($_POST['posizione']));
		} else $posizione = safe($_POST['listaetichette5']);
	} else $posizione = NULL;
	
	
	
	
	
	
	if (isset($_POST['check_fornitore'])) {
		if (!isset($_POST['id_contatto_fornitore']) OR !($_POST['id_contatto_fornitore']) OR ($_POST['id_contatto_fornitore'] == "NULL"))
			killemall("intestazione fornitore");
		$id_fornitore = safe($_POST['id_contatto_fornitore']);
	} else $id_fornitore = NULL;

	if (isset($_POST['check_fornitura'])) {
		if (!isset($_POST['listaetichette6']) OR !($_POST['listaetichette6']) OR ($_POST['listaetichette6'] == "NULL"))
			killemall("tipo di fornitura");
		$categoria = safe($_POST['listaetichette6']);
		if (!isset($_POST['numero']) OR !($_POST['numero']) OR ($_POST['numero'] == "NULL"))
			killemall("numero di fornitura");
		$numero = safe($_POST['numero']);
	} else {
		$categoria = NULL;
		$numero = NULL;
	}
	
	if (isset($_POST['check_merce'])) {
		if (!isset($_POST['listaetichette1']) OR !($_POST['listaetichette1']) OR ($_POST['listaetichette1'] == "NULL")) {
			if (!isset($_POST['testotag1']) OR !($_POST['testotag1']) OR ($_POST['testotag1'] == "NULL"))
				killemall("inserimento tag in mancanza di un modello");
			echo input_etichetta("1",safe(epura($_POST['testotag1'])));
			$tags = safe(epura($_POST['testotag1']));
		} else $tags = safe($_POST['listaetichette1']);
			
		if (!isset($_POST['listaetichette2']) OR !($_POST['listaetichette2']) OR ($_POST['listaetichette2'] == "NULL")) {
			if (isset($_POST['testotag2']) AND (!empty($_POST['testotag2']))) {
				echo input_etichetta("2",safe(epura($_POST['testotag2'])));
				$tags .= " ".safe(epura($_POST['testotag2']));
			}
		} else $tags .= " ".safe($_POST['listaetichette2']);

		if (!isset($_POST['listaetichette3']) OR !($_POST['listaetichette3']) OR ($_POST['listaetichette3'] == "NULL")) {
			if (isset($_POST['testotag3']) AND (!empty($_POST['testotag3']))) {
				echo input_etichetta("3",safe(epura($_POST['testotag3'])));
				$tags .= " ".safe(epura($_POST['testotag3']));
			}
		} else $tags .= " ".safe($_POST['listaetichette3']);
		
		if (!isset($_POST['listaetichette4']) OR !($_POST['listaetichette4']) OR ($_POST['listaetichette4'] == "NULL")) {
			if (isset($_POST['testotag4']) AND (!empty($_POST['testotag4']))) {
				echo input_etichetta("4",safe($_POST['testotag4']));
				$tags .= " ".safe($_POST['testotag4']);
			}
		} else $tags .= " ".safe($_POST['listaetichette4']);
		
		//$id_merce = NULL;
		if (isset($_POST['id_vendor'])) $id_vendor = safe($_POST['id_vendor']);
		if (isset($_POST['descrizione_merce'])) $descrizione_merce = safe($_POST['descrizione_merce']);
	} else {
		//$id_merce = NULL;
		$tags = NULL;
		$id_vendor = NULL;
		$descrizione_merce = NULL;
	}
	
	if (isset($_POST['check_quantita'])) {
		if (!isset($_POST['quantita']) OR !($_POST['quantita']) OR ($_POST['quantita'] == "NULL"))
			killemall("quantita merce");
		$quantita = safe(int_ok($_POST['quantita']));
	} else $quantita = NULL;
	
	if (isset($_POST['check_note'])) {
		if (!isset($_POST['note']) OR !($_POST['note']) OR ($_POST['note'] == "NULL"))
			killemall("note carico");
		$note = safe($_POST['note']);
	} else $note = NULL;
	
	if (isset($_POST['check_ordine'])) {
		if (!isset($_POST['tipo_ordine']) OR !($_POST['tipo_ordine']) OR ($_POST['tipo_ordine'] == "NULL"))
			killemall("tipo di ordine");
		$categoria_ordine = safe($_POST['tipo_ordine']);
		if (!isset($_POST['numero_ordine']) OR !($_POST['numero_ordine']) OR ($_POST['numero_ordine'] == "NULL"))
			killemall("numero di ordine");
		$numero_ordine = safe($_POST['numero_ordine']);
	} else {
		$categoria_ordine = NULL;
		$numero_ordine = NULL;
	}
	
	if (isset($_POST['check_trasportatore'])) {
		if (!isset($_POST['id_contatto_trasportatore']) OR !($_POST['id_contatto_trasportatore']) OR ($_POST['id_contatto_trasportatore'] == "NULL"))
			killemall("intestazione trasportatore");
		$id_trasportatore = safe($_POST['id_contatto_trasportatore']);
	} else $id_trasportatore = NULL;
	
	// quelli fissi...
	$id_operazione = safe($_POST['id_operazione']);
	//$id_merce = safe($_POST['id_merce']);			// della merce attuale
	//$id_contatto = safe($_POST['id_contatto']);   // del fornitore attuale
	//$id_documento = safe($_POST['id_documento']);
	//$status = safe($_POST['status']);
	//$posizione = safe($_POST['posizione']);
	
	/* $data 
	 * $id_fornitore $categoria $numero 
	 * $id_merce ($tags $id_vendor $descrizione_merce)
	 * $quantita $note 
	 * $categoria_ordine $numero_ordine $id_trasportatore 
	 * 
	 * tabelle implicate: MERCE REGISTRO OPERAZIONI, MAGAZZINO anche a posteriori (fix_magazzino)
	 * 
	 */
	 
	echo $callsql = "CALL EDIT('{$id_operazione}','{$tags}','{$id_vendor}','{$descrizione_merce}','{$id_fornitore}','{$categoria}','{$numero}','{$quantita}','{$data}','{$note}','{$categoria_ordine}','{$numero_ordine}','{$id_trasportatore}');";
	echo call_core("aggiornamento operazione",$callsql);
	

} else echo "<form method=\"post\" class=\"excelForm\" onSubmit=\"javascript:return getData()\">
<div class=\"CSSTableGenerator\" >".table_operazioni_onclick()."</div></form>";

?>

