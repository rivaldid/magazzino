<?php

session_start();
$a = "";

if (isset($_POST['submit'])) {
	
	//input
	//if (!isset($_POST['id']) OR !($_POST['id']) OR ($_POST['id'] == "NULL")) killemall("id");
	//if (!isset($_POST['posizione']) OR !($_POST['posizione']) OR ($_POST['posizione'] == "NULL")) killemall("posizione");
	//if (!isset($_POST['tags']) OR !($_POST['tags']) OR ($_POST['tags'] == "NULL")) killemall("tags");
	//if (!isset($_POST['quantita']) OR !($_POST['quantita']) OR ($_POST['quantita'] == "NULL")) killemall("quantita");
	
	$a .= atitolo."Scarica merce dal magazzino".ctitolo.accapo;
	$a .= "<form name=\"scarico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=scarico")."\">".accapo;
	$a .= atable.accapo;

	$a .= atr.accapo.atd."ID".ctd.atd.safe($_SESSION['id_merce']).ctd.ctr.accapo;
	$a .= atr.accapo.atd."Posizione".ctd.accapo.atd.safe($_SESSION['posizione']).ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."TAGS".ctd.accapo.atd.safe($_SESSION['tags']).ctd.accapo.ctr.accapo;
	
	$a .= atr.accapo.atd."Richiedente'".ctd.accapo.atd.optionlist_richiedente().ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."Destinazione'".ctd.accapo.atd.optionlist_destinazione().ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."Quantita'".ctd.accapo.atd."<input type=\"text\" name=\"quantita\"> (max ".safe($_SESSION['quantita']).")".ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."Data".ctd.accapo.atd.date_picker("data").ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."Note".ctd.accapo.atd."<input type=\"text\" name=\"note\">".ctd.accapo.ctr.accapo;
	
	$a .= fine_form;
	
	 
} else {
	
	$a .= "<div class=\"CSSTableGenerator\" >";
	
	$sql = "SELECT * FROM vista_magazzino;";
	$mask = "<th>ID</th><th>Posizione</th><th>TAGS</th><th>Quantita</th><th>Scarica</th>";
	$classemysql = new MysqlClass();

	$classemysql->connetti();
	$resultset = $classemysql->myquery($sql);
	$a .= "<h3>Contenuto dati in magazzino...</h3>";
	$a .= "<input id=\"tData\" name=\"tableData\" type=\"hidden\" /><input value=\"Export to Excel\" type=\"submit\" /><table id=\"tblExport\" class=\"tablesorter\"><thead><tr>{$mask}</tr></thead><tbody>";
	$numero_campi = mysql_num_fields($resultset);
	while ($riga = mysql_fetch_array($resultset, MYSQL_ASSOC)) {
		$a .= "<tr>";
		$a .= "<form name=\"scarico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=scarico")."\">".accapo;
		$a .= "<td>".$riga['id_merce']."</td>";
		$a .= "<td>".$riga['posizione']."</td>";
		$a .= "<td>".$riga['tags']."</td>";
		$a .= "<td>".$riga['quantita']."</td>";
		
		$_SESSION['id_merce'] = $riga['id_merce'];
		$_SESSION['posizione'] = $riga['posizione'];
		$_SESSION['tags'] = $riga['tags'];
		$_SESSION['quantita'] = $riga['quantita'];
		
		$a .= "<td><input type=\"submit\" name=\"submit\" value=\"Submit\"></td>";
		$a .= "</form>";
		$a .= "</tr>";
	}
	$a .= "</tbody></table>";
	$classemysql->pulizia($resultset);
	$classemysql->disconnetti();

	
	$a .= "</div>";
	
}

echo $a;
session_write_close();

?>
