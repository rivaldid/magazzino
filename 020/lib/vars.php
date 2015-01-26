<?php

// costanti js
define("jsxtable","<script type='text/javascript'>\n$(document).ready(function() { $('table').filterTable(); });\n$(document).ready(function() { $('table').tablesorter(); });\n</script>\n");
define("jsxdate","<script type='text/javascript'>\n $(function() {\n $('.datepicker').datepicker($.datepicker.regional['it']);\n });\n </script>\n");
define("jsaltrows","<script type='text/javascript'>\n  function altRows(id){ if(document.getElementsByTagName){ var table = document.getElementById(id); var rows = table.getElementsByTagName(\"tr\"); for(i = 0; i < rows.length; i++){ if(i % 2 == 0){ rows[i].className = \"evenrowcolor\"; }else{ rows[i].className = \"oddrowcolor\"; } } } } window.onload=function(){ altRows('alternatecolor'); } </script>\n");
define("jsxtop","<script type='text/javascript'>\n $(function() { $(window).scroll(function() { if($(this).scrollTop() != 0) { $('#top').fadeIn(); } else { $('#top').fadeOut(); } }); $('#top').click(function() { $('body,html').animate({scrollTop:0},800); }); }); </script>\n");


// costanti path
define("registro","/GMDCTO/registro/");
define("registro_mds","/GMDCTO/registro_mds/");
define("splog",$_SERVER['DOCUMENT_ROOT']."/GMDCTO/log/sp.log");
define("accesslog",$_SERVER['DOCUMENT_ROOT']."/GMDCTO/log/login.log");
define ("lib_mpdf57","../020/lib/MPDF57/mpdf.php");

// query
define("vserv_magazzino", "SELECT * FROM vserv_magazzino_id;");

define("vserv_tags2", "SELECT * FROM vserv_tags2;");
define("vserv_tags3", "SELECT * FROM vserv_tags3;");

$vserv_contatti = "SELECT * FROM vserv_contatti;";
$vserv_tipodoc = "SELECT * FROM vserv_tipodoc WHERE label!='Sistema';";
$vserv_numdoc = "SELECT * FROM vserv_numdoc;";
$vserv_posizioni = "SELECT * FROM vserv_posizioni;";
$vserv_numoda = "SELECT * FROM vserv_numoda;";

$vista_magazzino = "SELECT * FROM vista_magazzino;";
//$vserv_magazzino = "SELECT * FROM vserv_magazzino_id;";

// variabili
$magamanager = "<select name='utente'>\n<option selected='selected' value=''>Blank</option>\n<option value='Piscazzi'>Piscazzi</option>\n<option value='Manzo'>Manzo</option>\n<option value='Muratore'>Muratore</option>\n</select>\n";
$richiedenti_merce = "<select name='srichiedente'>\n<option selected='selected' value=''>Blank</option>\n<option value='Facility'>Facility</option>\n<option value='Immobiliare'>Immobiliare</option>\n<option value='PostemobileTLC'>PostemobileTLC</option>\n<option value='PostemobileTec'>PostemobileTec</option></select>\n";
$enabled_users = array("PISCAZZI","MANZOGI9","MURATO48","LORUSSO6","VILARDID");

// messaggi carico
$msg1 = "Mancata selezione di un utente per l'attivita' in corso (errore 1)"; //carico
$msg2 = "Mancata selezione di un fornitore per l'attivita' in corso (errore 2)"; //carico
$msg3 = "Mancata selezione di un tipo di documento per l'attivita' in corso (errore 3)"; //carico
$msg4 = "Mancata selezione di un numero di documento per l'attivita' in corso (errore 4)"; //carico
$msg5 = "Mancata selezione di una data cui far riferimento per l'attivita' in corso (errore 5)"; //carico

$msg6 = "Mancato inserimento di tags per contrassegnare la merce in carico (errore 6)"; //carico
$msg7 = "Mancato inserimento della quantita' per la merce in carico (errore 7)";//carico
$msg8 = "Mancato inserimento della posizione in magazzino per la merce in carico (errore 8)";//carico

$msg9 = "Sessione terminata, tutti i campi sono stati azzerati";

$msg10 = "Nessun file selezionato";
$msg11 = "Nessun file caricato perche' presente sul db (errore 11)";
$msg12 = "Nessun file caricato perche' presente sul disco (errore 12)";
$msg13 = "Scansione del documento caricata correttamente";
$msg14 = "Scansione del documento non caricata (errore 14)";

$msg15 = "Carico inserito correttamente";
$msg16 = "Inserimento errato del campo quantita' (errore 16)"; //carico

$msg17 = "Utente non abilitato per l'attivita' in oggetto (errore 17)";//s

$msg18 = "Nessuna notifica da visualizzare";

$msg19 = "Campo ad inserimento libero per dettagli vari mirati";
$msg20 = "al corretto recupero di informazioni a posteriori";

$msg21 = "<b>Azzera</b> per il reset dei dati inseriti";
$msg22 = "<b>Invia</b> per l'invio dei dati inseriti";
$msg23 = "<b>Fine</b> per terminare l'attivita' in corso";

// messaggi scarico
$msg24 = "Mancata selezione di un richiedente per l'attivita' in corso (errore 24)";//s
$msg25 = "Mancata selezione di una quantita' per l'attivita' in corso (errore 25)";
$msg26 = "Quantita' richiesta superiore alla giacenza in magazzino per quella posizione (errore 26)";
$msg27 = "Mancato inserimento di una destinazione per l'attivita' in corso (errore 27)";
$msg28 = "Mancata selezione di una data per l'attivita' in corso (errore 28)";
$msg29 = "Scarico inviato al database";
$msg30 = "Scarico effettuato correttamente";
$msg31 = "Scarico non effettuato (errore 31)";
$msg32 = "Persa risposta del database (errore 32)";

$msg33 = "Scarico terminato, ripristino i valori di default";

?>
