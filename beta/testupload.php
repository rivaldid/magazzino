<html>
<head></head>
<body>
			
	<?php
	
		require_once 'lib/functions.php';
	
		// --> AVVIO RISORSE
		session_start();

		$conn = mysql_connect('localhost','magazzino','magauser');
		if (!$conn) die('Errore di connessione: '.mysql_error());

		$dbsel = mysql_select_db('magazzino', $conn);
		if (!$dbsel) die('Errore di accesso al db: '.mysql_error());
	
		$a = "";
		$q1 = "SELECT * FROM vserv_contatti;";
		$q2 = "SELECT * FROM vserv_tipodoc;";
		$q3 = "SELECT * FROM vserv_numdoc;";
		$q4 = "SELECT * FROM vserv_posizioni;";
		$q5 = "SELECT * FROM vserv_numoda;";
		$registro = "aaa";
		
		foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
		
		if (isset($_SESSION['submit'])) {
			
			$fornitore = safe($_SESSION['fornitore']);
			$tipo_doc = safe($_SESSION['tipo_doc']);
			$num_doc = safe($_SESSION['num_doc']);
			//if (isset($_SESSION['action']) AND ($_SESSION['action']=='upload')) {
			//if (isset($_SESSION['scansione'])) {
			
			//$a .= "provola: ".$_FILES['scansione']['error'];
			
			// ------> test scansione
			if ($_FILES['scansione']['size'] > 0) {
				
				$query_doc = "SELECT doc_exists('{$fornitore}','{$tipo_doc}','{$num_doc}') AS risultato";
				$res_query_doc = mysql_query($query_doc);
				if (!$res_query_doc) die('Errore nell\'interrogazione del db: '.mysql_error());
				$test_exists = mysql_fetch_assoc($res_query_doc);
				mysql_free_result($res_query_doc);
				
				//$a .= epura_specialchars($tipo_doc)."-".epura_specialchars($fornitore)."-".epura_specialchars($num_doc).".".getfilext($_FILES['scansione']['name']);
				
				switch ($test_exists['risultato']) {
					
					// se ritorna 0 devo aggiungere il file
					case 0:
						$nome_doc = epura_specialchars($tipo_doc)."-".epura_specialchars($fornitore)."-".epura_specialchars($num_doc).".".getfilext($_FILES['scansione']['name']);
						if (!(file_exists($registro."/".$nome_doc))) {
							$moved = move_uploaded_file($_FILES['scansione']['tmp_name'], $registro."/".$_FILES['scansione']['name']);
							if( $moved ) {
							  $a .= "Successfully uploaded: ".$registro."/".$nome_doc;         
							} else {
							  $a .= "Not uploaded ".$registro."/".$nome_doc;
							}
						}
						break;

					// altrimenti passo NULL alla stored procedure che collegherà il carico ad altro id_documento valorizzato
					case 1:
						$nome_doc = NULL;
						$data_doc = NULL;
						$a .= "<h3>Giaffatto!</h3>";
						break;

					default:
						$a .= "<h3>Rilevato un problema in fase di caricamento documento.</h3>\n".$test_exists;
				}
			
			} else $nome_doc = NULL;
			
			session_unset();
			session_destroy();
			
		}
		
	
	if (isset($_SESSION['fornitore']) AND (!empty($_SESSION['fornitore']))) 
		$fornitore = safe($_SESSION['fornitore']);
	else 
		$fornitore = myoptlst("fornitore",$q1);

	if (isset($_SESSION['tipo_doc']) AND (!empty($_SESSION['fornitore']))) 
		$tipo_doc = safe($_SESSION['tipo_doc']);
	else 
		$tipo_doc = myoptlst("tipo_doc",$q2);

	if (isset($_SESSION['num_doc'])AND (!empty($_SESSION['fornitore']))) 
		$num_doc = safe($_SESSION['num_doc']);
	else 
		$num_doc = myoptlst("num_doc",$q3);
		
		
		$a .= "<form name='carico' method='post' enctype='multipart/form-data' action='".htmlentities("testupload.php")."'>\n";
		
		$a .= "<table>\n";
	
		$a .= "<tr>\n";
		$a .= "<td><label for='fornitore'>Fornitore</label></td>\n";
		$a .= "<td><input type='text' name='fornitore'></td>\n";
		$a .= "<td>".$fornitore."</td>\n";
		$a .= "</tr>\n";

		$a .= "<tr>\n";
		$a .= "<td><label for='tipo_doc'>Tipo documento</label></td>\n";
		$a .= "<td><input type='text' name='tipo_doc'></td>\n";
		$a .= "<td>".$tipo_doc."</td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='num_doc'>Numero documento</label></td>\n";
		$a .= "<td><input type='text' name='num_doc'></td>\n";
		$a .= "<td>".$num_doc."</td>\n";
		$a .= "</tr>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='scansione'>Scansione documento</label></td>\n";
		$a .= "<td>\n";
			$a .= "<input type='file' name='scansione'>\n";
		$a .= "</td>\n";
		$a .= "<td></td>\n";
		$a .= "</tr>\n";
					
		$a .= "<tr>\n";
		$a .= "<td></td><td></td>\n";
		$a .= "<td>\n";
			$a .= "<input type='reset' name='reset' value='Clear'>\n";
			$a .= "<input type='submit' name='submit' value='Submit'>\n";
		$a .= "</td>\n";
		$a .= "</tr>\n";
		
		$a .= "</table>\n";
		
		$a .= "</form>\n";
		
		// --> FERMO RISORSE
		mysql_close($conn);
		session_write_close();

		// --> VISUALIZZO PAGINA
		echo $a;
		
	?>
	
</body>
</html>
