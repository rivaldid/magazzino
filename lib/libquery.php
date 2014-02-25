<?php


function optionlist_intestazioni($attivita) {
$id_contatto = "id_contatto_".strtolower($attivita);
if ($attivita == "")
	$sql = "SELECT id_contatto,intestazione FROM RUBRICA ORDER BY intestazione;";
else
	$sql = "SELECT id_contatto,intestazione FROM RUBRICA WHERE attivita='{$attivita}' ORDER BY intestazione;";
return optionlist_core($sql,$id_contatto,"2");
}


function optionlist_merce() {
$sql = "SELECT id_merce,tags,id_vendor FROM MERCE ORDER BY tags,id_vendor;";
return optionlist_core($sql,"option_id_merce","3");
}

function optionlist_id_merce() {
$sql = "SELECT id_merce FROM MERCE ORDER BY id_merce;";
return optionlist_core_simple($sql,"id_merce","1");
}


function optionlist_merce_da_tag_in_magazzino($tags) {
$tags = explode(" ",epura_double($tags));
$foo='0';
foreach ($tags as $tag) {
	if ($foo=='0') {
		$sql = "SELECT id_merce,posizione,tags,id_vendor,quantita FROM MAGAZZINO LEFT JOIN MERCE USING(id_merce) WHERE tags LIKE '%{$tag}%'";
		$foo='1';
	} else
		$sql .= " UNION SELECT id_merce,posizione,tags,id_vendor,quantita FROM MAGAZZINO LEFT JOIN MERCE USING(id_merce) WHERE tags LIKE '%{$tag}%'";
}
$sql .= " ORDER BY posizione,tags,id_vendor";
return optionlist_core_double($sql,"option_id_merce_posizione","5");
}


function optionlist_merce_in_magazzino() {
$sql = "SELECT id_merce,posizione,tags,id_vendor,quantita FROM MAGAZZINO LEFT JOIN MERCE USING(id_merce);";
return optionlist_core_double($sql,"option_id_merce_posizione","5");
}


/* etichette:
 * [1-4] tags
 * 5 posizioni
 * 6 categorie documenti
 * 7 destinazioni
 */
function optionlist_etichette($sel) {
$sql = "SELECT label FROM etichette WHERE selettore=\"{$sel}\";";
return optionlist_core_simple($sql,"listaetichette{$sel}","1");
}


function input_etichetta($selettore,$label) {
$testsql = "SELECT label FROM etichette WHERE label='{$label}'";
$result = service_get_field($testsql,"label");
if (!isset($result)) {
	$actionsql = "CALL input_etichette('{$selettore}','{$label}');";
	call_core($label,$actionsql);
	switch ($selettore) {
		case 1:
			$a = "Tag1 {$label} aggiunto";
			break;
		case 2:
			$a = "Tag2 {$label} aggiunto";
			break;
		case 3:
			$a = "Tag3 {$label} aggiunto";
			break;
		case 4:
			$a = "Tag4 {$label} aggiunto";
			break;
		case 5:
			$a = "Posizione {$label} aggiunta";
			break;
		case 6:
			$a = "Tipo di documento {$label} aggiunto";
			break;
		case 7:
			$a = "Destinazione {$label} aggiunta";
			break;
		default:
			$a = "Probabile incongruenza di dati, riferire all'amministratore";
	}
} else $a = "Nessun inserimento effettuato";
return "<p>{$a}</p>";
}


function table_rubrica() {
$sql = "SELECT * FROM RUBRICA ORDER BY intestazione;";
$mask = "<th>id</th><th>Intestazione</th><th>Attivita'</th><th>Partita IVA</th><th>Codice Fiscale</th>
<th>Indirizzo</th><th>Telefono</th><th>FAX</th><th>Sito Web</th><th>@mail</th>";
return table_core("rubrica",$sql,$mask);
}


function table_registro() {
$sql = "SELECT intestazione,categoria,numero,gruppo,data,file FROM REGISTRO LEFT JOIN RUBRICA USING(id_contatto) ORDER BY data,intestazione,categoria,numero;";
$mask = "<th>Intestazione</th><th>Tipo</th><th>Numero</th><th>Gruppo</th><th>Data</th><th>File</th>";
return table_core("registro",$sql,$mask);
}


function table_etichette() {
$sql = "SELECT 'Tag1',label FROM etichette WHERE selettore='1' UNION
SELECT 'Tag2',label FROM etichette WHERE selettore='2' UNION
SELECT 'Tag3',label FROM etichette WHERE selettore='3' UNION
SELECT 'Tag4',label FROM etichette WHERE selettore='4' UNION
SELECT 'Posizione',label FROM etichette WHERE selettore='5' UNION
SELECT 'Destinazione',label FROM etichette WHERE selettore='7' UNION
SELECT 'Tipo di documento',label FROM magazzino.etichette WHERE selettore='6';";
$mask = "<th>Tipo di etichetta</th><th>Etichetta</th>";
return table_core("etichette",$sql,$mask);
}


function table_magazzino() {
$sql = "SELECT posizione,tags,id_vendor,quantita,descrizione FROM MAGAZZINO LEFT JOIN MERCE USING(id_merce) ORDER BY posizione,tags,id_vendor;";
$mask = "<th>Posizione</th><th>TAGS</th><th>Codice vendor</th><th>Giacenza</th><th>Descrizione</th>";
return table_core("magazzino",$sql,$mask);
}


function table_log() {
$sql = "SELECT data, status, posizione, 
		intestazione, categoria, numero, 
		tags, id_vendor, descrizione, 
		quantita, note 
		FROM view_log;";
		
$mask = "<th>Data</th><th>Status</th><th>Posizione</th>
		<th>Fornitore</th><th>Tipo</th><th>Numero</th>
		<th>TAGS</th><th>id Vendor</th><th>Descrizione merce</th>
		<th>Quantita'</th><th>Note</th>";
		
return table_core("transazioni",$sql,$mask);
}


function table_ordini() {
$sql = "SELECT data,status,posizione,
		intestazione,tipo_fornitura,numero_fornitura,
		tags,id_vendor,descrizione,
		quantita,note,
		tipo_ordine,numero_ordine,trasportatore 
		FROM view_log_ordini;";

$mask = "<th>Data</th><th>Status</th><th>Posizione</th>
<th>Fornitore</th><th>Tipo</th><th>Numero</th>
<th>TAGS</th><th>id Vendor</th><th>Descrizione merce</th>
<th>Quantita'</th><th>Note</th>
<th>Tipo ordine</th><th>Numero ordine</th><th>Trasportatore</th>";

return table_core("ordini",$sql,$mask);
}


function table_scarichi() {
$sql = "SELECT data,posizione,tags,id_vendor,quantita,intestazione,numero,note FROM OPERAZIONI
LEFT JOIN (SELECT id_merce,tags,id_vendor FROM MERCE) AS merce USING(id_merce)
LEFT JOIN (SELECT id_contatto,id_documento,numero FROM REGISTRO) AS registro USING(id_documento)
LEFT JOIN (SELECT id_contatto,intestazione FROM RUBRICA) as rubrica USING(id_contatto)
WHERE direzione='0' ORDER BY data DESC,posizione;";
$mask = "<th>Data</th><th>Posizione</th><th>TAGS</th><th>Id Vendor</th><th>Quantita</th><th>Richiedente</th><th>Scarico</th><th>Note</th>";
return table_core("scarichi",$sql,$mask);
}


function table_merce() {
$sql = "SELECT * FROM MERCE ORDER BY tags,id_vendor;";
$mask = "<th>id</th><th>TAGS</th><th>Codice vendor</th><th>Descrizione</th>";
return table_core("merce",$sql,$mask);
}


function table_operazioni_onclick() {
$sql = "SELECT * FROM view_log_ordini;";
$mask = "<th>Data</th><th>Status</th><th>Posizione</th><th>Intestazione</th><th>Tipo fornitura</th><th>Numero fornitura</th>
		<th>TAGS</th><th>id Vendor</th><th>Descrizione</th><th>Quantita'</th><th>Note</th>
		<th>Tipo ordine</th><th>Numero ordine</th><th>Trasportatore</th>";

$a = atitolo."Modifica valori in operazioni".ctitolo.accapo;
$a .= atable.accapo.athead.atr.$mask.ctr.cthead.accapo;

$classemysql = new MysqlClass();
$classemysql->connetti();

$resultset = $classemysql->myquery($sql);

while ($riga = mysql_fetch_row($resultset)) {
	$a .= atr.accapo;
	for ($i=4; $i<18; $i++)
		$a .= atd."<a href=\"?page=edit&id=".$riga[0]."\">".$riga[$i]."</a>".ctd.accapo;
	$a .= ctr.accapo;
}

$classemysql->pulizia($resultset);
$classemysql->disconnetti();

$a .= ctable.accapo;
return $a;
}


function table_operazioni_edit($id_operazione) {
$sql = "SELECT * FROM view_log_ordini WHERE id_operazione={$id_operazione};";
$mask = "<th>Stato attuale</th><th>Nuovo stato</th>";

$a = atitolo."Modifica l'operazione #".$id_operazione.ctitolo.accapo;
$a .= "<form name=\"merce\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=edit")."\">".accapo;
$a .= atable.accapo.athead.atr.$mask.ctr.cthead.accapo;

$classemysql = new MysqlClass();
$classemysql->connetti();

$resultset = $classemysql->myquery($sql);

while ($riga = mysql_fetch_row($resultset)) {
	$a .= atr.atd."<b>Status merce:</b> ".$riga[5].ctd.atd."Campo non modificabile".ctd.ctr.accapo;
	$a .= atr.atd."<b>Data attuale:</b> ".$riga[4].ctd.atd."<input name=\"check_data\" type=\"checkbox\"/> <b>Nuova data: </b> ".date_picker("data").ctd.ctr.accapo;
	$a .= atr.atd."<b>Posizione attuale:</b> ".$riga[6].ctd.atd."<input name=\"check_posizione\" type=\"checkbox\"/> <b> Nuova posizione:</b> ".optionlist_etichette("5")."<input type=\"text\" name=\"posizione\">".ctd.ctr.accapo;
	$a .= atr.atd."<b>Fornitura:</b> ".$riga[7]." - ".$riga[8]." - ".$riga[9].ctd.atd."<input name=\"check_fornitura\" type=\"checkbox\"/> <b>Nuova: </b>".optionlist_intestazioni("Fornitore").optionlist_etichette("6")."<input type=\"text\" name=\"numero\">".ctd.ctr.accapo;
	
	$a .= atr.atd."<b>Merce:</b> ".$riga[10]." - ".$riga[11]." - ".$riga[12].ctd.accapo.atd;
		$a .= "<input name=\"check_merce\" type=\"checkbox\"/> <b>Nuovo inserimento</b><br>".accapo;
		$a .= "Tag1 ".optionlist_etichette("1")."<input type=\"text\" name=\"testotag1\"><br>".accapo;
		$a .= "Tag2 ".optionlist_etichette("2")."<input type=\"text\" name=\"testotag2\"><br>".accapo;
		$a .= "Tag3 ".optionlist_etichette("3")."<input type=\"text\" name=\"testotag3\"><br>".accapo;
		$a .= "Tag4 ".optionlist_etichette("4")."<input type=\"text\" name=\"testotag4\"><br>".accapo;
		$a .= "id Vendor "."<input type=\"text\" name=\"id_vendor\"><br>".accapo;
		$a .= "Descrizione "."<input type=\"text\" name=\"descrizione_merce\">".accapo;
	$a .= ctd.accapo.ctr.accapo;
	
	$a .= atr.atd."<b>Quantita':</b> ".$riga[13].ctd.atd."<input name=\"check_quantita\" type=\"checkbox\"/> <b>Nuova quantita':</b> <input type=\"text\" name=\"quantita\">".ctd.ctr.accapo;
	$a .= atr.atd."<b>Note':</b> ".$riga[14].ctd.atd."<input name=\"check_note\" type=\"checkbox\"/> <b>Nuova nota:</b> <input type=\"text\" name=\"note\">".ctd.ctr.accapo;
		
	$a .= atr.atd."<b>Ordine:</b> ".$riga[15]." - ".$riga[16].ctd.atd."<input name=\"check_ordine\" type=\"checkbox\"/> <b>Nuovo ordine:</b> ".accapo;
	$a .= "<select name=\"tipo_ordine\"><option selected=\"selected\" value=\"NULL\">OFF</option><option value=\"ODA\">ODA</option><option value=\"BDC\">BDC</option>".accapo;
	$a .= "<input type=\"text\" name=\"numero_ordine\">".ctd.ctr.accapo;
	
	$a .= atr.atd."<b>Trasportatore:</b> ".$riga[17].ctd.atd."<input name=\"check_trasportatore\" type=\"checkbox\"/> <b>Nuovo trasportatore:</b> ".optionlist_intestazioni("Trasportatore").ctd.ctr.accapo;
	
	$a .= atr.atd.ctd.atd."<input name=\"check_delete\" type=\"checkbox\"/> <b>Elimina riga</b>".ctd.ctr.accapo;
	
	$a .= "<input type=\"hidden\" name=\"id_operazione\" value=\"".$riga[0]."\">".accapo;
}

$classemysql->pulizia($resultset);
//$classemysql->disconnetti();

$a .= atr.atd.ctd.atd."<input type=\"reset\" name=\"reset\" value=\"Clear\">\n<input type=\"submit\" name=\"submit\" value=\"Submit\">".ctd.ctr;
$a .= ctable.accapo;
$a .= "</form>".accapo;

return $a;
}


?>

