<html>
<head></head>
<body>
			
	<?php
	
		function getfilext($filename) {
			return substr($filename, strrpos($filename, '.')+1);
		}
	
		$a = "";
		
		foreach ($_POST AS $key => $value) $_SESSION[$key] = $value;
		
		if (isset($_SESSION['submit'])) {
			
			//if (isset($_SESSION['action']) AND ($_SESSION['action']=='upload')) {
			//if (isset($_SESSION['scansione'])) {
			
			//$a .= "provola: ".$_FILES['scansione']['error'];
			
			if ($_FILES['scansione']['size'] > 0) { 
			
			//$userfile_name = $_FILES['scansione']['name'];
			//$userfile_extn = substr($userfile_name, strrpos($userfile_name, '.')+1);
			
			$a .= "<h1>puzza la cacca!</h1>";
			$a .= "estensione: ".getfilext($_FILES['scansione']['name']);
			
			}	
			
		}
		
		
		$a .= "<form name='carico' method='post' enctype='multipart/form-data' action='".htmlentities("testupload.php")."'>\n";
		
		$a .= "<table>\n";
		
		$a .= "<tr>\n";
		$a .= "<td><label for='scansione'>Scansione documento</label></td>\n";
		$a .= "<td>\n";
			$a .= "<input type='file' name='scansione'>\n";
			//$a .= "<input type='hidden' name='action' value='upload'>\n";
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
		
		echo $a;
		
	?>
	
</body>
</html>
