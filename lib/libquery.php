<?php


//**********************************************************************

// PRIVATE/SERVICE


function call_core($label,$sql) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$a = "<p class=\"insert_response\"> {$label}... ";
$tmp = $classemysql->myquery($sql);
if ($tmp) $a .= "ok!</p>";
$classemysql->disconnetti();
return $a;
}


function selectstar_core($tabel) {
	return "SELECT * FROM {$tabel};";
}


/*
function insert_core($tabella,$valori,$action) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$sql = _array2sql($tabella, $valori, $action);
switch ($action) {
	case "INSERT INTO":
		$localecho = "Inserimento";
		break;
	case "UPDATE":
		$localecho = "Aggiornamento";
		break;
	default:
		killemall("Errore in accesso alle tabelle.");
}
$output[0] = "<p class=\"insert_response\">{$localecho} dati in tabella {$tabella}... ";
$tmp = $classemysql->myquery($sql);
if ($tmp) $output[0] .= "ok!</p>";
$output[1] = mysql_insert_id();
$classemysql->disconnetti();
return $output;
}
*/


function table_core($tabella,$sql,$mask) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$resultset = $classemysql->myquery($sql);
$output = "<h3>Contenuto dati in {$tabella}...</h3>";
$output .= "<table><tr>{$mask}</tr>";
$numero_campi = mysql_num_fields($resultset);
while ($riga = mysql_fetch_array($resultset, MYSQL_BOTH)) {
	$output .= "<tr>";
	for ($i=0; $i<$numero_campi; $i++) {
		if (mysql_field_name($resultset,$i) == "file")
			$output .= "<td><a href=\"registro/{$riga[$i]}\">{$riga[$i]}</a></td>";
		else 
			$output .= "<td>{$riga[$i]}</td>";
	}
	$output .= "</tr>";
}
$output .= "</table>";
$classemysql->pulizia($resultset);
$classemysql->disconnetti();
return $output;	
}


function optionlist_core_simple($sql,$mask,$n) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$tmp = $classemysql->myquery($sql);
$output = "<select name=\"{$mask}\">\n";
$output .= "<option selected=\"selected\" value=\"NULL\"></option>\n";
while ($riga = mysql_fetch_row($tmp)) {
	$output .= "<option value=\"";
	for ($i=0; $i<$n; $i++) {
		if ($i>0) $output .= " ";
		$output .= $riga[$i];
	}
	$output .= "\">";
	for ($i=0; $i<$n; $i++) {
		if ($i>0) $output .= " ";
		$output .= "[{$riga[$i]}]";
	}
	$output .= "</option>\n";
}
$output .= "</select>";
$classemysql->pulizia($tmp);
$classemysql->disconnetti();
return $output;
}


function optionlist_core($sql,$mask,$n) {
$classemysql = new MysqlClass();
$classemysql->connetti();
$tmp = $classemysql->myquery($sql);
$output = "<select name=\"{$mask}\">\n";
$output .= "<option selected=\"selected\" value=\"NULL\"></option>\n";
while ($riga = mysql_fetch_row($tmp)) {
	$output .= "<option value=\"{$riga[0]}\">";
	for ($i=1; $i<$n; $i++) {
		if ($i>1) $output .= " ";
		$output .= "[{$riga[$i]}]";
	}
	$output .= "</option>\n";
}
$output .= "</select>";
$classemysql->pulizia($tmp);
$classemysql->disconnetti();
return $output;
}



function service_get_field($sql,$target) {
	$classemysql = new MysqlClass();
	$classemysql->connetti();
	$resultset = $classemysql->myquery($sql);
	$row = mysql_fetch_assoc($resultset);
	$classemysql->pulizia($resultset);
	$classemysql->disconnetti();
	return $row[$target];	
}




//**********************************************************************


// PUBLIC OPTIONLIST AMMINISTRAZIONE

function optionlist_intestazioni() { //REGISTRO
return optionlist_core(selectstar_core("listaIntestazioni"),"idRubrica","3");
}

function optionlist_fornitori() { //ORDINI FORNITURE
return optionlist_core(selectstar_core("listaFornitori"),"idRubricaFornitore","2");
}

function optionlist_DocOrdini() { //ORDINI FORNITURE
return optionlist_core_simple(selectstar_core("listaDocOrdini"),"docOrdine","2");
}

function optionlist_intestazioneMittentiOrdine() { //ORDINI FORNITURE
return optionlist_core(selectstar_core("listaIntestazioneMittentiOrdine"),"idRubricaOrdinante","2");
}

function optionlist_trasportatori() { //FORNITURE
return optionlist_core(selectstar_core("listaTrasportatori"),"idRubricaTrasportatore","2");
}

function optionlist_NumDDT() { //FORNITURE
return optionlist_core_simple(selectstar_core("listaNumDDT"),"numDocFornitura","1");
}

function optionlist_TipoNumOrdiniEmessi() { //CONTABILITA'
return optionlist_core_simple(selectstar_core("listaTipoNumOrdiniEmessi"),"tipoNumOrdini","2");
}

function optionlist_tipoDoc() {
$sql = "SELECT label FROM tipoDoc;";
return optionlist_core_simple($sql,"tipoDoc","1");
}

function optionlist_tipoDoc_ordine() {
$sql = "SELECT label FROM tipoDoc WHERE ambito=\"ordine\";";
return optionlist_core_simple($sql,"tipoDoc","1");
}


// PUBLIC OPTIONLIST MAGAZZINO

function optionlist_tags($livello) {
return optionlist_core_simple("SELECT label FROM tags WHERE livello=\"{$livello}\";","tag_l{$livello}","1");
}

function optionlist_articoli() {
return optionlist_core(selectstar_core("articoli"),"idArticolo","3");
}

function optionlist_posizioni() {
return optionlist_core_simple(selectstar_core("posizioni"),"posizioni","1");
}

function optionlist_piazzamenti() {
return optionlist_core_simple(selectstar_core("piazzamenti"),"piazzamenti","1");
}

function optionlist_disponibilitaArticoli() {
return optionlist_core("CALL disponibilitaArticoli();","idArticolo","3");
}

function optionlist_disponibilitaArticoliSenzaAsset() {
return optionlist_core("CALL disponibilitaArticoliSenzaAsset();","idArticolo","3");
}

function optionlist_intestazioni_richiedenti() {
return optionlist_core(selectstar_core("listaRichiedenti"),"idRubricaRich","2");
}

function optionlist_destinazioni() {
return optionlist_core_simple(selectstar_core("destinazioni"),"posDestinazione","1");
}

function optionlist_posizioniOccupate($idArticolo) {
$tags = service_get_field("SELECT * FROM articoli WHERE idArticolo=\"{$idArticolo}\"","tags");
return optionlist_core_simple("CALL posizioniOccupate('{$tags}');","posOrigine","1");
}

function optionlist_tagsDisponibili() {
return optionlist_core_simple("CALL tagsDisponibili();","tags","1");
}

function optionlist_assetSerialptNumber() {
return optionlist_core_simple(selectstar_core("listaAssetIN_SerialptNumber"),"serialptNumber","2");
}

function optionlist_doc_non_in_colli() {
$sql = "SELECT data,idRubrica,tipoDoc,numDoc,intestazione FROM registro JOIN rubrica USING (idRubrica)
WHERE (idRubrica,tipoDoc,numDoc) NOT IN (SELECT idRubrica,tipoDoc,numDoc FROM Colli) ORDER BY data;";
return optionlist_core_simple($sql,"documento","5");
}



//**********************************************************************


// PUBLIC TABLE AMMINISTRAZIONE 


function table_rubrica() {
$sql = "SELECT * FROM stampaRubrica;";
$mask = "<th>Tipo contatto</th>
		<th>Intestazione</th>
		<th>Partita IVA</th>
		<th>Codice fiscale</th>
		<th>Indirizzo</th>
		<th>CAP</th>
		<th>Citta'</th>
		<th>Nazione</th>
		<th>Telefono</th>
		<th>FAX</th>
		<th>Sito Web</th>
		<th>eMail</th>";
return table_core("rubrica",$sql,$mask);
}


function table_registro() {
$sql = "SELECT * FROM stampaRegistro;";
$mask = "<th>Attivita'</th>
		<th>Mittente</th>
		<th>Tipo</th>
		<th>Numero</th>
		<th>Data</th>
		<th>Scansione</th>";
return table_core("registro",$sql,$mask);
}


function table_ordini() {
$mask = "<th>Data</th><th>Ordinante</th><th>Tipo</th><th>Numero</th>
<th>Fornitore</th><th>Codice fornitore</th><th>TAGS</th><th>Quantita'</th><th>Scansione ordine</th>";
return table_core("ordini",selectstar_core("listaOrdini"),$mask);
}


function table_forniture() {
$mask = "<th>Tipo fornitura</th><th>Numero fornitura</th><th>Data</th><th>Fornitore</th>
<th>Tipo ordine</th><th>Numero ordine</th><th>Richiedente</th><th>Scansione doc fornitura</th>";
return table_core("forniture",selectstar_core("listaForniture"),$mask);
}


function table_log() {
$mask = "<th>Merce</th><th>Data</th><th>Evento</th><th>Quantita'</th>";
return table_core("log","CALL log();",$mask);
}


function table_contabilita($idRubricaOrdinante,$tipoDocOrdinante,$numDocOrdinante) {
$mask = "<th>Merce</th><th>TAGS</th><th>Ordine</th><th>Consegna</th><th>Rimanenza</th>";
$callsql = "CALL taskContabilitaMerceOrdinata('{$idRubricaOrdinante}','{$tipoDocOrdinante}','{$numDocOrdinante}');";
$intestazione = service_get_field("SELECT intestazione FROM rubrica WHERE idRubrica=\"{$idRubricaOrdinante}\"","intestazione");
$a = "<h3><i>Riassunto contabilita per ordine {$tipoDocOrdinante} numero {$numDocOrdinante} emesso da {$intestazione}</i></h3>";
$a .= table_core("contabilita",$callsql,$mask);
return $a;
}


function table_esitoRicercaDoc($data,$tipoDoc,$numDoc) {
$mask = "<th>Data</th><th>Progressivo</th><th>Tipo</th><th>Numero</th><th>Scansione</th><th>Intestazione</th><th>Contatto</th>";
return table_core("ricercaDoc","CALL taskRicercaDoc('{$data}','{$tipoDoc}','{$numDoc}');",$mask);
}


function table_tipodoc() {
$sql = "SELECT * FROM tipoDoc ORDER BY ambito;";
return table_core("tipoDoc",$sql,"<th>Etichetta</th><th>Ambito</th>");
}


function table_next_codint() {
$mask = "<th>NEXT</th>";
return table_core("prossimo codice interno libero","SELECT task_IncrementaStringa();",$mask);
}


function table_Colli() {
$sql = "SELECT idColli,tipoDoc,numDoc,data,file,intestazione,tipoRubrica FROM Colli JOIN registro USING (idRubrica,tipoDoc,numDoc) JOIN rubrica USING (idRubrica);";
$mask = "<th>Progressivo</th><th>Documento</th><th>Numero</th><th>Data</th><th>Scansione</th><th>Intestazione</th><th>Attivita'</th>";
return table_core("documenti raggruppati (ex colli)",$sql,$mask);
}


// PUBLIC TABLE MAGAZZINO

function table_tags() {
return table_core("tags",selectstar_core("tags"),"<th>Livello</th><th>Etichetta</th>");
}

function table_locations() {
return table_core("locations",selectstar_core("listaLocations"),"<th>Indicatore</th><th>Etichetta</th>");
}


function table_articoli() {
$sql = "SELECT tags,note FROM articoli;";
$mask = "<th>TAGS</th><th>Note</th>";
return table_core("articoli",$sql,$mask);
}

function table_esitoRicercaTags($tags) {
$callsql = "CALL taskRicercaArticolo(\"{$tags}\")";
$mask = "<th>TAGS</th><th>Articoli disponibili</th><th>Posizione</th>";
return table_core("articoli",$callsql,$mask);
}


function table_asset() {
$mask = "<th>TAGS</th><th>Posizione</th><th>Fornitura</th>
<th>Serial</th><th>PT Number</th><th>Note</th><th>Data</th>";
return table_core("asset",selectstar_core("listaAsset"),$mask);
}


function table_esitoRicercaAsset($input) {
$mask = "<th>Serial</th><th>PT Number</th><th>Note</th><th>Posizione</th>";
return table_core("asset","CALL taskRicercaAsset('{$input}');",$mask);
}


function table_NumOrfani() {
$sql = "SELECT provenienza,tipoRubrica,intestazione,tipoDoc,numDoc FROM
(SELECT * FROM sublista_numdoc_in_task WHERE (idRubrica,tipoDoc,numDoc) NOT IN (SELECT idRubrica,tipoDoc,numDoc FROM registro)) AS sel2
JOIN rubrica USING (idRubrica);";
$mask = "<th>Provenienza</th><th>tipo</th><th>Intestazione</th><th>DOCUMENTO</th><th>NUMERO</th>";
return table_core("tasks",$sql,$mask);
}


function table_DocOrfani() {
$sql = "SELECT tipoRubrica,intestazione,tipoDoc,numDoc,data,file FROM listaDocOrfani JOIN rubrica USING (idRubrica);";
$mask = "<th>Attivita</th><th>Intestazione</th><th>Tipo</th><th>Numero</th><th>Data</th><th>Scansione</th>";

/*
SELECT * FROM registro WHERE (idRubrica,tipoDoc,numDoc)
NOT IN
(SELECT idRubricaOrdinante AS idRubrica,tipoDocOrdinante AS tipoDoc,numDocOrdinante AS numDoc FROM ordini
UNION
SELECT idRubricaFornitore,tipoDocFornitura,numDocFornitura FROM forniture
UNION
SELECT idRubricaOrdinante,tipoDocOrdinante,numDocOrdinante FROM forniture
UNION
SELECT idRubricaFornitore,tipoDocFornitura,numDocFornitura FROM consegne
UNION
SELECT idRubricaFornitore,tipoDocFornitura,numDocFornitura FROM carichi
UNION
SELECT idRubricaRich,tipoDocRich,numDocRich FROM scarichi)
*/

return table_core("documenti",$sql,$mask);
}


function table_ddt() {
$mask = "<th>Data</th><th>Progressivo</th><th>Tipo</th><th>Numero</th><th>Scansione</th><th>Intestazione</th><th>Contatto</th>";
return table_core("DDT","CALL taskRicercaDoc('','DDT','');",$mask);
}


function table_mds() {
$mask = "<th>Data</th><th>Progressivo</th><th>Tipo</th><th>Numero</th><th>Scansione</th><th>Intestazione</th><th>Contatto</th>";
return table_core("MDS","CALL taskRicercaDoc('','MDS','');",$mask);	
}


function table_next_mds() {
$mask = "<th>NEXT</th>";
return table_core("prossimo mds libero","SELECT task_IncrementaStringaMDS();",$mask);
}
 
 
?>
