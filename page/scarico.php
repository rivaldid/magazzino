<?php

if (isset($_POST['submit'])) {
	
	if (isset($_POST['steps'])) {
		
		$steps = safe($_POST['steps']);
		switch ($steps) {
			
			case "step2":
				if (!isset($_POST['tags']) OR !($_POST['tags']) OR ($_POST['tags'] == "NULL"))
					$tags = NULL;
				$tags = safe($_POST['tags']);
				echo "<div class=\"CSSTableGenerator\" >".form_scarico_step2($tags)."</div>";
				break;
		
			case "step3":
				if (!isset($_POST['option_id_merce_posizione']) OR !($_POST['option_id_merce_posizione']) OR ($_POST['option_id_merce_posizione'] == "NULL"))
					killemall("selezione merce per scarico");
				$option_id_merce_posizione = explode(" ",safe($_POST['option_id_merce_posizione']));
				$id_merce = $option_id_merce_posizione[0];
				$posizione = $option_id_merce_posizione[1];
				
				echo "<div class=\"CSSTableGenerator\" >".form_scarico_step3($id_merce,$posizione)."</div>";		
				break;
			
			case "step4":
				if (!isset($_POST['id_contatto_richiedente']) OR !($_POST['id_contatto_richiedente']) OR ($_POST['id_contatto_richiedente'] == "NULL"))
					killemall("selezione richiedente");
				$id_richiedente = safe($_POST['id_contatto_richiedente']);
				
				if (!isset($_POST['id_merce']) OR !($_POST['id_merce']) OR ($_POST['id_merce'] == "NULL"))
					killemall("passaggio di valori di merce");
				$id_merce = safe($_POST['id_merce']);
				
				if (!isset($_POST['quantita']) OR !($_POST['quantita']) OR ($_POST['quantita'] == "NULL"))
					killemall("quantita' merce per scarico");
				$quantita = safe($_POST['quantita']);
				
				if (!isset($_POST['posizione']) OR !($_POST['posizione']) OR ($_POST['posizione'] == "NULL"))
					killemall("passaggio di valori di posizione");
				$posizione = safe($_POST['posizione']);
				
				// test scarico regolare
				$test_sql = "SELECT test_scarico('{$id_merce}','{$posizione}','{$quantita}') AS risposta;";
				if (service_get_field($test_sql,"risposta") == '1')
					killemall("quantita' richiesta superiore alla giacenza");
				
				if (!isset($_POST['listaetichette7']) OR !($_POST['listaetichette7']) OR ($_POST['listaetichette7'] == "NULL")) {
					if (!isset($_POST['destinazione']) OR !($_POST['destinazione']) OR ($_POST['destinazione'] == "NULL"))
						killemall("destinazione merce");
					echo input_etichetta("7",safe(epura($_POST['destinazione'])));
					$destinazione = safe(epura($_POST['destinazione']));
				} else $destinazione = safe($_POST['listaetichette7']);

				$data = safe($_POST['datayear'])."-".safe($_POST['datamonth'])."-".safe($_POST['dataday']);
				if ($data === 'NULL-NULL-NULL') $data = date('Y-m-d');
				if (isset($_POST['note'])) $note = safe($_POST['note']);
				
				echo $callsql = "CALL SCARICO('{$id_richiedente}','{$id_merce}','{$quantita}','{$posizione}','{$destinazione}','{$data}','{$note}');";
				echo call_core("scarico merce",$callsql);			
				echo "<p><h2><a href=\"?page=scarico\">Nuovo scarico</a></h2></p>";
				break;
		
			default:
				killemall("Scarico non andato a buon fine");
		
		}
	 }
	 
} else echo "<div class=\"CSSTableGenerator\" >".form_scarico_step1()."</div>";

echo "<form method=\"post\" class=\"excelForm\" onSubmit=\"javascript:return getData()\">
<div class=\"CSSTableGenerator\" >".table_scarichi()."</div></form>";
	
?>
