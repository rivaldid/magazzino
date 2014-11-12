<?php 

session_start();

if (isset($_POST['submit'])) {

	// fornitore
	if (!isset($_SESSION['fornitore']) OR !($_SESSION['fornitore']) OR ($_SESSION['fornitore'] == "NULL")) {
		if (!isset($_POST['fornitore']) OR !($_POST['fornitore']) OR ($_POST['fornitore'] == "NULL"))
			killemall("intestazione fornitore");
		$fornitore = safe($_POST['fornitore']);
		$_SESSION['fornitore'] = $fornitore;
	} else $fornitore = $_SESSION['fornitore'];
	
	// categoria
	if (!isset($_SESSION['tipo_doc']) OR !($_SESSION['tipo_doc']) OR ($_SESSION['tipo_doc'] == "NULL")) {
		if (!isset($_POST['tipo_doc']) OR !($_POST['tipo_doc']) OR ($_POST['tipo_doc'] == "NULL"))
			killemall("tipo di documento");
		$tipo_doc = safe($_POST['tipo_doc']);
		$_SESSION['tipo_doc'] = $tipo_doc;
	} else $tipo_doc = $_SESSION['tipo_doc'];
	
	// numero
	if (!isset($_SESSION['numero']) OR !($_SESSION['numero']) OR ($_SESSION['numero'] == "NULL")) {
		if (!isset($_POST['numero']) OR !($_POST['numero']) OR ($_POST['numero'] == "NULL"))
			killemall("numero di documento");
		$numero = safe($_POST['numero']);
		$_SESSION['numero'] = $numero;
	} else $numero = $_SESSION['numero'];
	
	// tags
	if (!isset($_POST['tags']) OR !($_POST['tags']) OR ($_POST['tags'] == "NULL"))
		killemall("quantita merce");
	$tags = safe($_POST['tags']);
	
	
	// quantita
	if (!isset($_POST['quantita']) OR !($_POST['quantita']) OR ($_POST['quantita'] == "NULL"))
		killemall("quantita merce");
	$quantita = safe(int_ok($_POST['quantita']));
	
	
	// posizioni
	if (!isset($_POST['posizioni']) OR !($_POST['posizioni']) OR ($_POST['posizioni'] == "NULL")) {
		if (!isset($_POST['posizione']) OR !($_POST['posizione']) OR ($_POST['posizione'] == "NULL"))
			killemall("posizione merce");
		$posizione = safe(epura($_POST['posizione']));
	} else $posizione = safe($_POST['posizioni']);
	
	
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
		} else $numero_ordine="";
	} else $numero_ordine = $_SESSION['numero_ordine'];
	
	// trasportatore
	if (!isset($_SESSION['trasportatore']) OR !($_SESSION['trasportatore']) OR ($_SESSION['trasportatore'] == "NULL")) {
		if (isset($_POST['trasportatore'])) {
			$trasportatore = safe($_POST['trasportatore']);
			$_SESSION['trasportatore'] = $trasportatore;
		}
		else 
			$trasportatore = NULL;
	} else $trasportatore = $_SESSION['trasportatore'];
	
	
	/* vecchio carico --> CALL CARICO('{$id_fornitore}','{$categoria}','{$numero}','{$categoria_ordine}','{$numero_ordine}','{$id_merce}','{$tags}','{$id_vendor}','{$descrizione_merce}','{$quantita}','{$posizione}','{$data}','{$note}','{$id_trasportatore}');
	 * 
	 * nuovo carico   --> CALL CARICO(utente,fornitore,tipo_doc,num_doc,data_doc, NULL, tags, quantita, posizione, data_carico, note_carico, trasportatore, oda);
	 */
	 
	echo $callsql = "CALL CARICO('Sistema','{$fornitore}','{$tipo_doc}','{$numero}','{$data}',NULL,'{$tags}','{$quantita}','{$posizione}','{$data}','{$note}','{$trasportatore}','{$numero_ordine}');";
	echo call_core("carico merce",$callsql);
	echo "<div class=\"CSSTableGenerator\" >".form_carico($fornitore,$trasportatore,$tipo_doc,$numero,$data,$note,$numero_ordine)."</div>";
	
	
} else {
	session_unset();
	session_destroy();
	echo "<div class=\"CSSTableGenerator\" >".form_carico("","","","","","","")."</div>";
}

session_write_close();
	
?>
