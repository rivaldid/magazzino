<?php
//==============================================================
//==============================================================
//==============================================================
include("../beta/lib/MPDF57/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML("<table><caption>MODULO DI CONSEGNA MATERIALE</caption><tbody><tr><td></td><td></td></tr>
<tr><td>TI/GSI/GI/TO</td><td></td></tr>
<tr><td>DATA CENTER TORINO</td><td></td></tr>
<tr><td>Corso Tazzoli 235/4</td><td></td></tr>
<tr><td>10137 TORINO</td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td>Operatore di accessi</td><td>Piscazzi</td></tr><tr><td>Struttura richiedente</td><td>FACILITY</td></tr><tr><td>Descrizione articolo</td><td>APPARATO PER COSS</td></tr><tr><td>Quantita'</td><td>1</td></tr><tr><td>Posizione di provenienza</td><td>P08</td></tr><tr><td>Destinazione materiale</td><td>SALA2</td></tr><tr><td>Note</td><td></td></tr><tr><td>Data di riferimento scarico</td><td>2014-11-05</td></tr><tr><td>Torino il</td><td>2014-11-05</td></tr><tr><td></td><td></td></tr>
<tr><td>Firma</td><td></td></tr></tbody></table>");
$mpdf->Output();
exit;
//==============================================================
//==============================================================
//==============================================================
?>
