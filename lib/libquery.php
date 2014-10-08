<?php


// nuova
function optionlist_proprieta($livello) {
if ($livello == "")
	$sql = "SELECT label FROM proprieta ORDER BY label;";
else
	$sql = "SELECT label FROM proprieta WHERE sel={$livello} ORDER BY label;";
return optionlist_core_simple($sql,"proprieta".$livello,"1");
}

function optionlist_fornitore() {
$sql = "SELECT label FROM proprieta WHERE sel='5' ORDER BY label;";
return optionlist_core_simple($sql,"fornitore","1");
}
function optionlist_trasportatore() {
$sql = "SELECT label FROM proprieta WHERE sel='5' ORDER BY label;";
return optionlist_core_simple($sql,"trasportatore","1");
}
function optionlist_tipo_doc() {
$sql = "SELECT label FROM proprieta WHERE sel='4' ORDER BY label;";
return optionlist_core_simple($sql,"tipo_doc","1");
}
function optionlist_posizioni() {
$sql = "SELECT label FROM proprieta WHERE sel='2' ORDER BY label;";
return optionlist_core_simple($sql,"posizioni","1");
}

function optionlist_intestazioni($attivita) {
$id_contatto = "id_contatto_".strtolower($attivita);
if ($attivita == "")
	$sql = "SELECT id_contatto,intestazione FROM RUBRICA ORDER BY intestazione;";
else
	$sql = "SELECT id_contatto,intestazione FROM RUBRICA WHERE attivita='{$attivita}' ORDER BY intestazione;";
return optionlist_core($sql,$id_contatto,"2");
}


function optionlist_merce() {
$sql = "SELECT * FROM MERCE ORDER BY tags;";
return optionlist_core($sql,"option_id_merce","2");
}

function optionlist_id_merce() {
$sql = "SELECT id_merce FROM MERCE ORDER BY id_merce;";
return optionlist_core_simple($sql,"id_merce","1");
}

function table_transiti() {
$mask = "<th>Data</th><th>Direzione</th><th>Posizione</th><th>Documento</th>
		<th>TAGS</th><th>Quantita</th><th>Note</th><th>ODA</th><th>Trasportatore</th>";
$sql = "select data,status,posizione,documento,tags,quantita,note,ordine,trasportatore from TRANSITI;";
return table_core("transiti",$sql,$mask);
}







function optionlist_merce_da_tag_in_magazzino($tags) {
$tags = explode(" ",epura_double($tags));
$foo='0';
foreach ($tags as $tag) {
	if ($foo=='0') {
		$sql = "SELECT id_merce,posizione,tags,id_vendor,quantita FROM MAGAZZINO LEFT JOIN MERCE USING(id_merce) WHERE tags LIKE '%{$tag}%' AND quantita>0";
		$foo='1';
	} else
		$sql .= " UNION SELECT id_merce,posizione,tags,id_vendor,quantita FROM MAGAZZINO LEFT JOIN MERCE USING(id_merce) WHERE tags LIKE '%{$tag}%' AND quantita>0";
}
$sql .= " ORDER BY posizione,tags,id_vendor";
return optionlist_core_double($sql,"option_id_merce_posizione","5");
}


function optionlist_merce_in_magazzino() {
$sql = "SELECT id_merce,posizione,tags,id_vendor,quantita FROM MAGAZZINO LEFT JOIN MERCE USING(id_merce) WHERE quantita>0";
return optionlist_core_double($sql,"option_id_merce_posizione","5");
}



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




function table_registro() {
$sql = "SELECT contatto,tipo,numero,gruppo,data,file FROM REGISTRO ORDER BY data,contatto,tipo,numero;";
$mask = "<th>Contatto</th><th>Tipo</th><th>Numero</th><th>Gruppo</th><th>Data</th><th>File</th>";
return table_core("registro",$sql,$mask);
}




function table_magazzino() {
//$sql = "SELECT posizione,tags,id_vendor,quantita,descrizione FROM MAGAZZINO LEFT JOIN MERCE USING(id_merce) WHERE quantita>0 ORDER BY posizione,tags,id_vendor;";
$sql = "SELECT * FROM vista_magazzino2;";
$mask = "<th>TAGS</th><th>Quantita</th><th>Lista posizioni</th>";
return table_core("magazzino",$sql,$mask);
}





function table_scarica() {
$sql = "SELECT * FROM vista_magazzino;";
$mask = "<th>ID</th><th>Posizione</th><th>TAGS</th><th>Quantita</th><th>Scarica</th>";
$classemysql = new MysqlClass();

$classemysql->connetti();
$resultset = $classemysql->myquery($sql);
$output = "<h3>Contenuto dati in magazzino...</h3>";
$output .= "<input id=\"tData\" name=\"tableData\" type=\"hidden\" />
			<input value=\"Export to Excel\" type=\"submit\" />
			<table id=\"tblExport\" class=\"tablesorter\"><thead><tr>{$mask}</tr></thead><tbody>";
$numero_campi = mysql_num_fields($resultset);
while ($riga = mysql_fetch_array($resultset, MYSQL_BOTH)) {
	$output .= "<tr>";
	for ($i=0; $i<$numero_campi; $i++) {
		if (mysql_field_name($resultset,$i) == "file")
			$output .= "<td><a href=\"registro/{$riga[$i]}\">{$riga[$i]}</a></td>";
		else 
			$output .= "<td>{$riga[$i]}</td>";
	}
	$output .= "<td><input type=\"button\" value=\"Scarica\" name=\"doscarico\"></td>";
	$output .= "</tr>";
}
$output .= "</tbody></table>";
$classemysql->pulizia($resultset);
$classemysql->disconnetti();
return $output;	
}


function table_merce() {
$sql = "SELECT * FROM MERCE ORDER BY tags,id_vendor;";
$mask = "<th>id</th><th>TAGS</th><th>Codice vendor</th><th>Descrizione</th>";
return table_core("merce",$sql,$mask);
}




?>

