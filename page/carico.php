<?php 

session_start();

if (isset($_POST['submit'])) {

	// fornitore
	if (!isset($_SESSION['fornitore']) OR !($_SESSION['fornitore']) OR ($_SESSION['fornitore'] == "NULL")) {
		if (!isset($_POST['fornitore']) OR !($_POST['fornitore']) OR ($_POST['fornitore'] == "NULL"))
			killemall("intestazione fornitore");
		$id_fornitore = safe($_POST['fornitore']);
		$_SESSION['fornitore'] = $id_fornitore;
	} else $id_fornitore = $_SESSION['fornitore'];
	
	// categoria
	if (!isset($_SESSION['tipo_doc']) OR !($_SESSION['tipo_doc']) OR ($_SESSION['tipo_doc'] == "NULL")) {
		if (!isset($_POST['tipo_doc']) OR !($_POST['tipo_doc']) OR ($_POST['tipo_doc'] == "NULL"))
			killemall("tipo di documento");
		$categoria = safe($_POST['tipo_doc']);
		$_SESSION['tipo_doc'] = $tipo_doc;
	} else $tipo_doc = $_SESSION['tipo_doc'];
	
	// numero
	if (!isset($_SESSION['numero']) OR !($_SESSION['numero']) OR ($_SESSION['numero'] == "NULL")) {
		if (!isset($_POST['numero']) OR !($_POST['numero']) OR ($_POST['numero'] == "NULL"))
			killemall("numero di documento");
		$numero = safe($_POST['numero']);
		$_SESSION['numero'] = $numero;
	} else $numero = $_SESSION['numero'];
	
	// ***CORE
	if (!isset($_POST['option_id_merce']) OR !($_POST['option_id_merce']) OR ($_POST['option_id_merce'] == "NULL")) {
			
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
		
		$id_merce = NULL;
		if (isset($_POST['id_vendor'])) $id_vendor = safe($_POST['id_vendor']);
		if (isset($_POST['descrizione_merce'])) $descrizione_merce = safe($_POST['descrizione_merce']);
					
	} else {
		$id_merce = safe($_POST['option_id_merce']);
		$tags = ""; 
		$id_vendor = ""; 
		$descrizione_merce = ""; 
	}
	// ***CORE
	
	if (!isset($_POST['quantita']) OR !($_POST['quantita']) OR ($_POST['quantita'] == "NULL"))
		killemall("quantita merce");
	$quantita = safe(int_ok($_POST['quantita']));
	
	if (!isset($_POST['listaetichette5']) OR !($_POST['listaetichette5']) OR ($_POST['listaetichette5'] == "NULL")) {
		if (!isset($_POST['posizione']) OR !($_POST['posizione']) OR ($_POST['posizione'] == "NULL"))
			killemall("posizione merce");
		echo input_etichetta("5",safe(epura($_POST['posizione'])));
		$posizione = safe(epura($_POST['posizione']));
	} else $posizione = safe($_POST['listaetichette5']);
	
	// data
	if (!isset($_SESSION['data']) OR !($_SESSION['data']) OR ($_SESSION['data'] == "NULL")) {
		$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
		if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
		$_SESSION['data'] = $data;
	} else $data = $_SESSION['data'];
	
	// note
	if (!isset($_SESSION['note']) OR !($_SESSION['note']) OR ($_SESSION['note'] == "NULL")) {		
		if (isset($_POST['note'])) {
			$note = safe($_POST['note']);
			$_SESSION['note'] = $note;
		}
	} else $note = $_SESSION['note'];
	
	// ordini
	if (!isset($_SESSION['numero_ordine']) OR !($_SESSION['numero_ordine']) OR ($_SESSION['numero_ordine'] == "NULL")) {
		if (isset($_POST['numero_ordine'])) {
			$numero_ordine = safe($_POST['numero_ordine']);
			$_SESSION['numero_ordine'] = $numero_ordine;
			if (!isset($_POST['categoria_ordine']) OR !($_POST['categoria_ordine']) OR ($_POST['categoria_ordine'] == "NULL"))
				killemall("inserimento tipo ordine");
			$categoria_ordine = safe($_POST['categoria_ordine']);
			$_SESSION['categoria_ordine'] = $categoria_ordine;
		} else {
			$numero_ordine="";
			$categoria_ordine="";
		}
	} else {
		$categoria_ordine = $_SESSION['categoria_ordine'];
		$numero_ordine = $_SESSION['numero_ordine'];
	}
	
	// trasportatore
	if (!isset($_SESSION['id_trasportatore']) OR !($_SESSION['id_trasportatore']) OR ($_SESSION['id_trasportatore'] == "NULL")) {
		if (isset($_POST['id_contatto_trasportatore'])) {
			$id_trasportatore = safe($_POST['id_contatto_trasportatore']);
			$_SESSION['id_trasportatore'] = $id_trasportatore;
		}
		else 
			$id_trasportatore = NULL;
	} else $id_trasportatore = $_SESSION['id_trasportatore'];
	
	
	// VARIAZIONI
	$fornitore = service_get_field("SELECT * FROM RUBRICA WHERE id_contatto=\"{$id_fornitore}\"","intestazione");
	$trasportatore = service_get_field("SELECT * FROM RUBRICA WHERE id_contatto=\"{$id_trasportatore}\"","intestazione");
	// CHIUSE VARIAZIONI
	
	
	/* vecchio carico --> CALL CARICO('{$id_fornitore}','{$categoria}','{$numero}','{$categoria_ordine}','{$numero_ordine}','{$id_merce}','{$tags}','{$id_vendor}','{$descrizione_merce}','{$quantita}','{$posizione}','{$data}','{$note}','{$id_trasportatore}');
	 * 
	 * nuovo carico   --> CALL CARICO(utente,fornitore,tipo_doc,num_doc,data_doc, NULL, tags, quantita, posizione, data_carico, note_carico, trasportatore, oda);
	 */
	 
	echo $callsql = "CALL CARICO('Sistema','{$fornitore}','{$tipo_doc}','{$numero}','{$data}',NULL,'{$tags}','{$quantita}','{$posizione}','{$data}','{$note}','{$trasportatore}','{$numero_ordine}');";
	echo call_core("carico merce",$callsql);
	echo "<div class=\"CSSTableGenerator\" >".form_carico($id_fornitore,$id_trasportatore,$categoria,$numero,$data,$note,$categoria_ordine,$numero_ordine)."</div>";
	
	
} else {
	session_unset();
	session_destroy();
	echo "<div class=\"CSSTableGenerator\" >".form_carico("","","","","","","","")."</div>";
}

session_write_close();
	
?>
