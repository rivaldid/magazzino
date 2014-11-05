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
	
	$html .= "<table>";
	$html .= "<caption>MODULO DI CONSEGNA MATERIALE</caption>";
	$html .= "<tbody>";
	$html .= "<tr><td></td><td></td></tr>\n";
	$html .= "<tr><td>TI/GSI/GI/TO</td><td></td></tr>\n";
	$html .= "<tr><td>DATA CENTER TORINO</td><td></td></tr>\n";
	$html .= "<tr><td>Corso Tazzoli 235/4</td><td></td></tr>\n";
	$html .= "<tr><td>10137 TORINO</td><td></td></tr>\n";
	$html .= "<tr><td></td><td></td></tr>\n";
	$html .= "<tr><td>Operatore di accessi</td><td>".$utente."</td></tr>";
	$html .= "<tr><td>Struttura richiedente</td><td>".$richiedente."</td></tr>";
	$html .= "<tr><td>Descrizione articolo</td><td>".$tags."</td></tr>";
	$html .= "<tr><td>Quantita'</td><td>".$quantita."</td></tr>";
	$html .= "<tr><td>Posizione di provenienza</td><td>".$posizione."</td></tr>";
	$html .= "<tr><td>Destinazione materiale</td><td>".$destinazione."</td></tr>";
	$html .= "<tr><td>Note</td><td>".$note."</td></tr>";
	$html .= "<tr><td>Data di riferimento scarico</td><td>".$data_doc_scarico."</td></tr>";
	$html .= "<tr><td>Torino il</td><td>".$data_scarico."</td></tr>";
	$html .= "<tr><td></td><td></td></tr>\n";
	$html .= "<tr><td>Firma</td><td></td></tr>";
	$html .= "</tbody>";
	$html .= "</table>";


//==============================================================
//==============================================================
//==============================================================
include("lib/MPDF57/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
//==============================================================
//==============================================================
//==============================================================
?>





