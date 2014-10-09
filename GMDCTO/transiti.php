<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//IT" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>GMDCTO</title>
	<link rel="stylesheet" href="css/menu.css" type="text/css" />
	<link rel="stylesheet" href="css/tabella.css" type="text/css" />
	<link rel="stylesheet" href="css/footer.css" type="text/css" />
</head>
<body>
	<?php
	
		include 'menu.html';
		
		//begin mysql
		$conn = mysql_connect('localhost','magazzino','magauser');
		if (!$conn) die('Errore di connessione: '.mysql_error());
			
		$dbsel = mysql_select_db('magazzino', $conn);
		if (!$dbsel) die('Errore di accesso al db: '.mysql_error());
		
		$query = "SELECT data,status,posizione,documento,tags,quantita,note,ordine,trasportatore FROM TRANSITI;";
		$res = mysql_query($query);
		if (!$res) die('Errore nell\'interrogazione del db: '.mysql_error());
		
		$a = "";
		
		//print
		$a .= "<table>";
		$a .= "<caption>TRANSITI REGISTRATI</caption>";
		$a .= "<thead><tr>";
			$a .= "<th>Data</th>";
			$a .= "<th>Direzione</th>";
			$a .= "<th>Posizione</th>";
			$a .= "<th>Documento</th>";
			$a .= "<th>TAGS</th>";
			$a .= "<th>Quantita'</th>";
			$a .= "<th>Note</th>";
			$a .= "<th>ODA</th>";
			$a .= "<th>Trasportatore</th>";
		$a .= "</tr></thead>";
		$a .= "<tbody>";
			
		while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
			$a .= "<tr>";
			foreach ($row as $cname => $cvalue)
				$a .= "<td>".$cvalue."</td>";
			$a .= "</tr>";
		}
		
		$a .= "</tbody></table>";
		
		echo $a;
		
		mysql_free_result($res);
		
		// end mysql
		mysql_close($conn);
		
		include 'footer.html';
		
	?>
	
</body>
</html>
