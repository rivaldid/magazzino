<?php

session_start();
$a = "";

// begin mysql
$conn = mysql_connect('localhost','magazzino','magauser');
if (!$conn) die('Errore di connessione: '.mysql_error());
	
$dbsel = mysql_select_db('magazzino', $conn);
if (!$dbsel) die('Errore di accesso al db: '.mysql_error());


// quello che mi serve...
$q1 = "SELECT * FROM vserv_contatti;";
$q2 = "SELECT * FROM vserv_tipodoc;";
$q3 = "SELECT * FROM vserv_numdoc;";
$q4 = "SELECT * FROM vserv_posizioni;";
$q5 = "SELECT * FROM vserv_numoda;";

$opt_fornitore = myoptlst("fornitore",$q1);
$opt_trasportatore = myoptlst("trasportatore",$q1);
$opt_tipo_doc = myoptlst("tipo_doc",$q2);
$opt_num_doc = myoptlst("num_doc",$q3);
$opt_posizione = myoptlst("posizione",$q4);
$opt_num_oda = myoptlst("num_oda",$q5);
// ...fine di quello che mi serve


// end mysql
mysql_close($conn);
	
if (isset($_POST['submit'])) {
	
	foreach ($_POST AS $key => $value) $_SESSION['post'][$key] = $value;
	
	// corpo
	$fornitore = $_SESSION['post']['fornitore'];
	$tipo_doc = $_SESSION['post']['tipo_doc'];
	$num_doc = $_SESSION['post']['num_doc'];
	$data_doc = $_SESSION['post']['data_doc'];
	$tags = $_SESSION['post']['tags'];
	$quantita = $_SESSION['post']['quantita'];
	$posizione = $_SESSION['post']['posizione'];
	$data = $_SESSION['post']['data'];
	$note = $_SESSION['post']['note'];
	$trasportatore = $_SESSION['post']['trasportatore'];
	$num_oda = $_SESSION['post']['num_oda'];
	
	$nome_doc = $tipo_doc.$fornitore.$num_doc;
	echo $chiamata = "CALL CARICO('Sistema','{$fornitore}','{$tipo_doc}','{$num_doc}','{$data_doc}','{$nome_doc}','{$tags}','{$quantita}','{$posizione}','{$data}','{$note}','{$trasportatore}','{$num_oda}');";
	
} else {
	
	$a .= "<form name='carico' method='post' enctype='multipart/form-data' action='".htmlentities("?page=carico")."'>\n";
	$a .= "<table>\n";
		$a .= "<caption>CARICO MERCE</caption>\n";
		$a .= "<thead><tr>\n";
			$a .= "<th>Descrizione</th>\n";
			$a .= "<th>Inserimento</th>\n";
			$a .= "<th>Suggerimento</th>\n";
		$a .= "</tr></thead>\n";
		$a .= "<tbody>\n";
		
			$a .= "<tr>\n";
			$a .= "<td><label for='fornitore'>Fornitore</label></td>\n";
			$a .= "<td><input type='text' name='fornitore'></td>\n";
			$a .= "<td>".$opt_fornitore."</td>\n";
			$a .= "</tr>\n";
		
			$a .= "<tr>\n";
			$a .= "<td><label for='trasportatore'>Trasportatore</label></td>\n";
			$a .= "<td><input type='text' name='trasportatore'></td>\n";
			$a .= "<td>".$opt_trasportatore."</td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='tipo_doc'>Tipo documento</label></td>\n";
			$a .= "<td><input type='text' name='tipo_doc'></td>\n";
			$a .= "<td>".$opt_tipo_doc."</td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='num_doc'>Numero documento</label></td>\n";
			$a .= "<td><input type='text' name='num_doc'></td>\n";
			$a .= "<td>".$opt_num_doc."</td>\n";
			$a .= "</tr>\n";

			$a .= "<tr>\n";
			$a .= "<td><label for='data_doc'>Data documento</label></td>\n";
			$a .= "<td><input name='data_doc' type='date' value='' class='date demo'/></td>\n";
			$a .= "<td></td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='scansione'>Scansione documento</label></td>\n";
			$a .= "<td><input type='file' name='scansione'>\n<input type='hidden' name='action' value='upload'></td>\n";
			$a .= "<td></td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='tags'>TAGS</label></td>\n";
			$a .= "<td><textarea rows='4' cols='auto' name='tags'></textarea>\n";
			$a .= "<td></td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='quantita'>Quantita'</label></td>\n";
			$a .= "<td><input type='text' name='quantita'></td>\n";
			$a .= "<td></td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='posizione'>Posizione</label></td>\n";
			$a .= "<td><input type='text' name='posizione'></td>\n";
			$a .= "<td>".$opt_posizione."</td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='data'>Data</label></td>\n";
			$a .= "<td><input name='data' type='date' value='' class='date demo'/></td>\n";
			$a .= "<td></td>\n";
			$a .= "</tr>\n";
			
			$a .= "<tr>\n";
			$a .= "<td><label for='note'>Note</label></td>\n";
			$a .= "<td><textarea rows='4' cols='auto' name='note'></textarea>\n";
			$a .= "<td></td>\n";
			$a .= "</tr>\n";

			$a .= "<tr>\n";
			$a .= "<td><label for='num_oda'>Numero ODA</label></td>\n";
			$a .= "<td><input type='text' name='num_oda'></td>\n";
			$a .= "<td>".$opt_num_oda."</td>\n";
			$a .= "</tr>\n";
		
		$a .= "</tbody>\n";
		
		$a .= "<tfoot>\n";
			$a .= "<tr>\n";
			$a .= "<td></td><td></td>\n";
			$a .= "<td>\n<input type='reset' name='reset' value='Clear'>\n";
			$a .= "<input type='submit' name='submit' value='Submit'>\n</td>\n";
			$a .= "</tr>\n";
		$a .= "</tfoot>\n";
		
	$a .= "</table>\n";
	$a .= "</form>\n";
	
	session_unset();
	session_destroy();
}

echo $a;
session_write_close();
	
?>

