<?php

	$a="";
	
	$utente="stochi";
	$richiedente="nessuno";
	$tags="aa bb cccc";
	$quantita="999";
	$posizione="accanto di la";
	$destinazione="aaaaa";
	$note="sdcvdsvvbbf";
	$data_doc_scarico="2014-01-01";
	$data_scarico="2015-01-01";
	
	
			$b = "";
			$b .= "<table>\n";
			$b .= "<caption>MERCE SCARICATA</caption>\n";
			
			$b .= "<tbody>\n";
			
			$b .= "<tr>\n<td>Operatore di accessi</td>\n<td>".$utente."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Struttura richiedente</td>\n<td>".$richiedente."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Descrizione articolo</td>\n<td>".$tags."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Quantita'</td>\n<td>".$quantita."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Posizione di provenienza</td>\n<td>".$posizione."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Destinazione scarico</td>\n<td>".$destinazione."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Note</td>\n<td>".$note."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Data di riferimento scarico</td>\n<td>".$data_doc_scarico."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Torino il</td>\n<td>".$data_scarico."</td>\n</tr>\n";
			$b .= "<tr>\n<td>Firma</td>\n<td></td>\n</tr>\n";
			
			$b .= "</tbody>\n";
			
			$b .= "</table>\n";
			
			
	/*$a .= "<script type='text/javascript'>\n";
	//$a .= "function getCSVData(){\n";
	$a .= "var csv_value=$('".$b."').table2CSV({delivery:'value'});\n";
	$a .= "$(\"#csv_text\").val(csv_value);\n";
	$a .= "}\n";
	//$a .= "</script>\n";
	*/
	
	$file = $_REQUEST['fileName'];
    $filename = $file."_".date("Y-m-d_H-i",time());
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
    header( "Content-disposition: filename=".$filename.".csv");
    
    //$csv_output=stripcslashes($_REQUEST['csv_text']);
    $csv_output=$b;
    
    print $csv_output;
    exit; 
	
	echo $a;
?>
