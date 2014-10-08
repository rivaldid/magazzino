<?php


function form_registro() {
$a = atitolo."Aggiungi documento in registro".ctitolo.accapo;
$a .= "<form name=\"carico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=registro")."\">".accapo;
$a .= atable.accapo;

$a .= atr.accapo.atd."Mittente".ctd.atd.optionlist_fornitore().ctd.ctr.accapo;
$a .= atr.accapo.atd."Tipo e numero di documento".ctd.accapo.atd.optionlist_tipo_doc()."<input type=\"text\" name=\"numero\">".ctd.accapo.ctr.accapo;

$a .= atr.accapo.atd."Gruppo".ctd.accapo.atd."<input type=\"text\" name=\"gruppo\">".ctd.accapo.ctr.accapo;
$a .= atr.accapo.atd."Data".ctd.accapo.atd.date_picker("data").ctd.accapo.ctr.accapo;

$a .= atr.accapo.atd."Scansione".ctd.accapo.atd.accapo;
$a .= "<input type=\"file\" name=\"file1\">".accapo."<input type=\"hidden\" name=\"action\" value=\"upload\">".accapo;
$a .= ctd.accapo.ctr.accapo;

$a .= fine_form;
return $a;
}



function form_carico($b,$c,$d,$e,$f,$g,$h) {
$a = atitolo."Carico merce".ctitolo.accapo;
$a .= "<form name=\"carico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=carico")."\">".accapo;
$a .= atable.accapo;
	
	// b) fornitore 
	if ($b == "") $a .= atr.accapo.atd."Fornitore".ctd.atd.optionlist_fornitore().ctd.ctr.accapo;
	else $a .= atr.accapo.atd."Fornitore".ctd.atd.$b.ctd.ctr.accapo;
		
	// c) trasportatore
	if (($c == "") OR ($c == "NULL")) $a .= atr.accapo.atd."Trasportatore".ctd.atd.optionlist_trasportatore().ctd.ctr.accapo;
	else $a .= atr.accapo.atd."Trasportatore".ctd.atd.$c.ctd.ctr.accapo;
		
	// de) tipo_doc & num_doc
	if ($e == "") $a .= atr.accapo.atd."Tipo di documento".ctd.accapo.atd.optionlist_tipo_doc()." numero di documento <input type=\"text\" name=\"numero\">".ctd.accapo.ctr.accapo;
	else $a .= atr.accapo.atd."Tipo e numero di documento".ctd.accapo.atd.$d." ".$e.ctd.accapo.ctr;
	
	// f) data
	if ($f == "") $a .= atr.accapo.atd."Data carico".ctd.accapo.atd.date_picker("data").ctd.accapo.ctr.accapo;
	else $a .= atr.accapo.atd."Data carico".ctd.accapo.atd.$f.ctd.accapo.ctr.accapo;
	
	// g) note
	if ($g == "") $a .= atr.accapo.atd."Note carico".ctd.accapo.atd."<input type=\"text\" name=\"note\">".ctd.accapo.ctr.accapo;
	else $a .= atr.accapo.atd."Note carico".ctd.accapo.atd.$g.ctd.accapo.ctr.accapo;
	
	// h) ODA
	if ($h == "") $a .= atr.accapo.atd."Numero ODA".ctd.accapo.atd.accapo."<input type=\"text\" name=\"numero_ordine\">".accapo.ctd.accapo.ctr.accapo;
	else $a .= atr.accapo.atd."Numero ODA".ctd.accapo.atd.$h.ctd.accapo.ctr.accapo;

	//tags
	$a .= atr.accapo.atd."Tags (separati da spazio)".ctd.accapo.atd."<input type=\"text\" name=\"tags\">".ctd.accapo.ctr.accapo;

	// quantita
	$a .= atr.accapo.atd."Quantita'".ctd.accapo.atd."<input type=\"text\" name=\"quantita\">".ctd.accapo.ctr.accapo;

	// posizione
	$a .= atr.accapo.atd."Posizione".ctd.accapo.atd.accapo.optionlist_posizioni()." oppure <input type=\"text\" name=\"posizione\">".ctd.accapo.ctr.accapo;
	
$a .= fine_form;
return $a;
}







?>
