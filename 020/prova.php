<?php

	$a="";
	
	$utente="stochi";
	$richiedente="uno a caso";
	$tags="BRETELLA FO-LC-LC-50/125 3M";
	$quantita="999";
	$posizione="accanto di la";
	$destinazione="sala2 nel cesso del custode";
	$note="note notevoli";
	$data_doc_scarico="2014-01-01";
	$data_scarico="2015-01-01";

	
	$html = "";
	
$html .= "<div id=\"contenitore\">"; /*DIVcontenitore tutta la pagina*/

	$html .= "<div class=\"intestazione\">";
		$html .= "<div id=\"logo\"><span style=\"font: bold 28px verdana;\">Poste</span><span style=\"font: normal 24px verdana;\">Italiane</span></div>";
		$html .= "<h3>TI/GSI/GI/TO</h3>";
		$html .= "<h3>DATA CENTER TORINO</h3>";
		$html .= "<h3>Corso Tazzoli 235/4</h3>";
		$html .= "<h3>10137 TORINO</h3>";
	$html .= "</div>";
	
	$html .= "<table>";
		$html .= "<caption><h1>Modulo di consegna materiale</h1></caption>";
		$html .= "<tr><td class=\"bold\">Struttura richiedente</td><td class=\"normal\">".$richiedente."</td></tr>";
		$html .= "<tr><td class=\"bold\">Note</td><td class=\"normal\">".$note."</td></tr>";
		$html .= "<tr><td class=\"bold\">Operatore di accessi</td><td class=\"normal\">".$utente."</td></tr>";
		$html .= "<tr><td class=\"bold\">Data di riferimento scarico</td><td class=\"normal\">".$data_doc_scarico."</td></tr>";
	$html .= "</table>";
	
	$html .= "<table>\n";
	
		$html .= "<tr>\n";
			$html .= "<td class=\"bold\">Merce</td>\n<td>".$tags."</td><td class=\"bold\">Quantita'</td><td>".$quantita."</td>\n";
		$html .= "</tr>\n";
		
	$html .= "</table>\n";
	
	$html .= "<table>";	
		$html .= "<tr><td class=\"bold\">Descrizione articolo</td><td class=\"normal\">".$tags."</td></tr>";
		$html .= "<tr><td class=\"bold\">Quantita'</td><td class=\"normal\">".$quantita."</td></tr>";
		$html .= "<tr><td class=\"bold\">Posizione di provenienza</td><td class=\"normal\">".$posizione."</td></tr>";
		$html .= "<tr><td class=\"bold\">Destinazione materiale</td><td class=\"normal\">".$destinazione."</td></tr>";
	$html .= "</table>";
	
	$html .= "<table>";	
		$html .= "<tr><td>Torino il</td><td class=\"normal\">".$data_scarico."</td></tr>";
		$html .= "<tr><td class=\"firma\"\>Firma</td><td>_______________________________</td></tr>";
	$html .= "</table>";
	
$html .= "</div>"; /*FINE DIVcontenitore tutta la pagina*/

//==============================================================
//==============================================================
//==============================================================
include("lib/MPDF57/mpdf.php");
$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);

$stylesheet = file_get_contents('css/mds.css'); 
$mpdf->WriteHTML($stylesheet,1); 

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
//==============================================================
//==============================================================
//==============================================================
?>





