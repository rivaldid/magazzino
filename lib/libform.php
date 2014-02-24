<?php

function form_etichette() {
$a = atitolo."Aggiungi etichetta".ctitolo.accapo;
$a .= "<form name=\"etichette\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=etichette")."\">".accapo;
$a .= atable.accapo;

	$a .= atr.accapo.atd."Selettore".ctd.accapo.atd.accapo;
	$a .= "<input type=\"radio\" name=\"selettore\" value=\"1\">Tag1".accapo;
	$a .= "<input type=\"radio\" name=\"selettore\" value=\"2\">Tag2".accapo;
	$a .= "<input type=\"radio\" name=\"selettore\" value=\"3\">Tag3".accapo;
	$a .= "<input type=\"radio\" name=\"selettore\" value=\"4\">Tag4".accapo;
	$a .= "<input type=\"radio\" name=\"selettore\" value=\"5\">Posizione".accapo;
	$a .= "<input type=\"radio\" name=\"selettore\" value=\"7\">Destinazione".accapo;
	$a .= "<input type=\"radio\" name=\"selettore\" value=\"6\">Tipo di documento".accapo;
	$a .= ctd.accapo.ctr.accapo;
	
	$a .= atr.accapo.atd."Etichetta".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"label\">".ctd.accapo.ctr.accapo;
	
$a .= fine_form;
return $a;
}

function form_rubrica() {
$a = atitolo."Aggiungi contatto in rubrica".ctitolo.accapo;
$a .= "<form name=\"carico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=rubrica")."\">".accapo;
$a .= atable.accapo;
	// intestazione
	$a .= atr.accapo.atd."Intestazione".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"intestazione\">".ctd.accapo.ctr.accapo;
	// attivita
	$a .= atr.accapo.atd."Attivita'".ctd.accapo.atd.accapo;
	$a .= "<input type=\"radio\" name=\"attivita\" value=\"Fornitore\">Fornitore".accapo;
	$a .= "<input type=\"radio\" name=\"attivita\" value=\"Trasportatore\">Trasportatore".accapo;
	$a .= "<input type=\"radio\" name=\"attivita\" value=\"Richiedente\">Richiedente".accapo;
	$a .= ctd.accapo.ctr.accapo;
	// partita iva
	$a .= atr.accapo.atd."Partita IVA".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"partita_iva\">".ctd.accapo.ctr.accapo;
	// codice fiscale
	$a .= atr.accapo.atd."Codice Fiscale".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"codice_fiscale\">".ctd.accapo.ctr.accapo;
	// indirizzo
	$a .= atr.accapo.atd."Indirizzo".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"indirizzo\">".ctd.accapo.ctr.accapo;
	// telefono
	$a .= atr.accapo.atd."Telefono".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"telefono\">".ctd.accapo.ctr.accapo;
	// fax
	$a .= atr.accapo.atd."Fax".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"fax\">".ctd.accapo.ctr.accapo;
	// sito web
	$a .= atr.accapo.atd."Sito Web".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"sito_web\">".ctd.accapo.ctr.accapo;
	// email
	$a .= atr.accapo.atd."@mail".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"email\">".ctd.accapo.ctr.accapo;
$a .= fine_form;
return $a;
}


function form_registro() {
$a = atitolo."Aggiungi documento in registro".ctitolo.accapo;
$a .= "<form name=\"carico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=registro")."\">".accapo;
$a .= atable.accapo;

$a .= atr.accapo.atd."Mittente".ctd.atd.optionlist_intestazioni("").ctd.ctr.accapo;
$a .= atr.accapo.atd."Tipo e numero di documento".ctd.accapo;
$a .= atd.optionlist_etichette("6")."<input type=\"text\" name=\"numero\">".ctd.accapo.ctr.accapo;

$a .= atr.accapo.atd."Progressivo (collo)".ctd.accapo.atd."<input type=\"text\" name=\"gruppo\">".ctd.accapo.ctr.accapo;
$a .= atr.accapo.atd."Data".ctd.accapo.atd.date_picker("data").ctd.accapo.ctr.accapo;

$a .= atr.accapo.atd."Scansione".ctd.accapo.atd.accapo;
$a .= "<input type=\"file\" name=\"file1\">".accapo."<input type=\"hidden\" name=\"action\" value=\"upload\">".accapo;
$a .= ctd.accapo.ctr.accapo;

$a .= fine_form;
return $a;
}


function form_moving() {
$a = atitolo."Sposta e compatta le aree del magazzino".ctitolo.accapo;
$a .= "<form name=\"carico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=magazzino")."\">".accapo;
$a .= atable.accapo;

$a .= atr.accapo.atd."Seleziona merce da spostare".ctd.accapo.atd.optionlist_merce_in_magazzino().ctd.accapo.ctr.accapo;
$a .= atr.accapo.atd."Nuova posizione".ctd.accapo.atd.accapo;
$a .= optionlist_etichette("5").accapo."<input type=\"text\" name=\"posizione_finale\">".accapo;
$a .= ctd.accapo.ctr.accapo;

$a .= fine_form;
return $a;
}


function form_aggiorna_merce() {
$a = atitolo."Aggiorna merce inserita a sistema".ctitolo.accapo;
$a .= "<form name=\"merce\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=merce")."\">".accapo;
$a .= atable.accapo;

	$a .= atr.accapo.atd."Identificativo merce da aggiornare".ctd.accapo.atd.accapo.optionlist_id_merce().accapo.ctd.accapo.ctr.accapo;

	$a .= atr.accapo.atd."tag1 (cosa e')".ctd.accapo.atd.optionlist_etichette("1")."<input type=\"text\" name=\"testotag1\">".ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."tag2 (come e')".ctd.accapo.atd.optionlist_etichette("2")."<input type=\"text\" name=\"testotag2\">".ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."tag3 (quanto e')".ctd.accapo.atd.optionlist_etichette("3")."<input type=\"text\" name=\"testotag3\">".ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."lista altri tags".ctd.accapo.atd.optionlist_etichette("4")."<input type=\"text\" name=\"testotag4\">".ctd.accapo.ctr.accapo;

	$a .= atr.accapo.atd."Codice vendor".ctd.accapo.atd."<input type=\"text\" name=\"id_vendor\">".ctd.accapo.ctr.accapo;

	$a .= atr.accapo.atd."Descrizione merce".ctd.accapo.atd."<input type=\"text\" name=\"descrizione_merce\">".ctd.accapo.ctr.accapo;

$a .= fine_form;
return $a;
}


function form_carico($b,$c,$d,$e,$f,$g,$h,$i) {
$a = atitolo."Carico merce".ctitolo.accapo;
$a .= "<form name=\"carico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=carico")."\">".accapo;
$a .= atable.accapo;
	
	// b) id_fornitore 
	if ($b == "")
		$a .= atr.accapo.atd."Fornitore".ctd.atd.optionlist_intestazioni("Fornitore").ctd.ctr.accapo;
	else {
		$bb = service_get_field("SELECT * FROM RUBRICA WHERE id_contatto=\"{$b}\"","intestazione");
		$a .= atr.accapo.atd."Fornitore".ctd.atd.$bb.ctd.ctr.accapo;
	}
		
	// c) id_trasportatore
	if (($c == "") OR ($c == "NULL"))
		$a .= atr.accapo.atd."Trasportatore".ctd.atd.optionlist_intestazioni("Trasportatore").ctd.ctr.accapo;
	else {
		$cc = service_get_field("SELECT * FROM RUBRICA WHERE id_contatto=\"{$c}\"","intestazione");
		$a .= atr.accapo.atd."Trasportatore".ctd.atd.$cc.ctd.ctr.accapo;
	}
		
	// de) categoria & numero
	if ($e == "") {
		$a .= atr.accapo.atd."Tipo e numero di documento".ctd.accapo;
		$a .= atd.optionlist_etichette("6")."<input type=\"text\" name=\"numero\">".ctd.accapo.ctr.accapo;
	}
	else 
		$a .= atr.accapo.atd."Tipo e numero di documento".ctd.accapo.atd.$d." ".$e.ctd.accapo.ctr;
	
	// f) data
	if ($f == "") {
		$a .= atr.accapo.atd."Data carico".ctd.accapo;
		$a .= atd.date_picker("data").ctd.accapo.ctr.accapo;
	}
	else 
		$a .= atr.accapo.atd."Data carico".ctd.accapo.atd.$f.ctd.accapo.ctr.accapo;
	
	// g) note
	if ($g == "") {
		$a .= atr.accapo.atd."Note carico".ctd.accapo;
		$a .= atd."<input type=\"text\" name=\"note\">".ctd.accapo.ctr.accapo;
	}
	else 
		$a .= atr.accapo.atd."Note carico".ctd.accapo.atd.$g.ctd.accapo.ctr.accapo;
	
	// hi) ordine
	if ($i == "") {
		$a .= atr.accapo.atd."Tipo e numero ordine".ctd.accapo.atd.accapo;
		$a .= "<input type=\"radio\" name=\"categoria_ordine\" value=\"ODA\" checked=\"checked\">ODA".accapo;
		$a .= "<input type=\"radio\" name=\"categoria_ordine\" value=\"BDC\">BDC".accapo;
		$a .= "<input type=\"text\" name=\"numero_ordine\">".accapo.ctd.accapo.ctr.accapo;
	}
	else
		$a .= atr.accapo.atd."Tipo e numero ordine".ctd.accapo.atd.$h." ".$i.ctd.accapo.ctr.accapo;
	

	
	// ******************** CARICO PARTE STATICA ***************************
	
	// id_merce da modello
	$a .= atr.accapo.atd."Scegli modello tags merce".ctd.accapo;
	$a .= atd.accapo.optionlist_merce().accapo.ctd.accapo.ctr.accapo;
	
	// nuovo inserimento merce
	//tags
	$a .= atr.accapo.atd."Nuovo modello tags merce".ctd.accapo.atd.accapo;
	$a .= atable.accapo;
	$a .= atr.accapo.atd."tag1 (cosa e')".ctd.accapo.atd.optionlist_etichette("1")."<input type=\"text\" name=\"testotag1\">".ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."tag2 (come e')".ctd.accapo.atd.optionlist_etichette("2")."<input type=\"text\" name=\"testotag2\">".ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."tag3 (quanto e')".ctd.accapo.atd.optionlist_etichette("3")."<input type=\"text\" name=\"testotag3\">".ctd.accapo.ctr.accapo;
	$a .= atr.accapo.atd."lista altri tags".ctd.accapo.atd.optionlist_etichette("4")."<input type=\"text\" name=\"testotag4\">".ctd.accapo.ctr.accapo;

	// id_vendor
	$a .= atr.accapo.atd."Codice vendor".ctd.accapo.atd."<input type=\"text\" name=\"id_vendor\">".ctd.accapo.ctr.accapo;

	// descrizione_merce
	$a .= atr.accapo.atd."Descrizione merce".ctd.accapo.atd."<input type=\"text\" name=\"descrizione_merce\">".ctd.accapo.ctr.accapo;
	$a .= ctable.accapo;
	$a .= ctd.accapo.ctr.accapo;
	
	// quantita
	$a .= atr.accapo.atd."Quantita'".ctd.accapo;
	$a .= atd."<input type=\"text\" name=\"quantita\">".ctd.accapo.ctr.accapo;

	// posizione
	$a .= atr.accapo.atd."Posizione".ctd.accapo.atd.accapo;
	$a .= optionlist_etichette("5")."<input type=\"text\" name=\"posizione\">";
	$a .= ctd.accapo.ctr.accapo;
	
$a .= fine_form;
return $a;
}




function form_scarico_step1() {
$a = atitolo."Step 1: ricerca merce per tag".ctitolo.accapo;
$a .= "<form name=\"scarico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=scarico")."\">".accapo;
$a .= atable.accapo.atr.accapo.atd."Tags da ricercare".ctd.accapo;
$a .= atd."<input type=\"text\" name=\"tags\">".ctd.accapo.ctr;
$a .= "<input type=\"hidden\" name=\"steps\" value=\"step2\">";
$a .= fine_form;
return $a;
}


function form_scarico_step2($tags) {
$a = atitolo."Step 2: scegli merce da scaricare".ctitolo.accapo;
$a .= "<form name=\"scarico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=scarico")."\">".accapo;
$a .= atable.accapo.atr.accapo.atd."Tags da ricercare".ctd.accapo;
$a .= atd;

if (isset($tags))
	$a .= optionlist_merce_da_tag_in_magazzino($tags);
else
	$a .= optionlist_merce_in_magazzino();

$a .= ctd.accapo.ctr.accapo;
$a .= "<input type=\"hidden\" name=\"steps\" value=\"step3\">";
$a .= fine_form;
return $a;
}


function form_scarico_step3($id_merce,$posizione) {
$titolo = "Step3: completa lo scarico per ";
$tags = service_get_field("SELECT * FROM MERCE WHERE id_merce=\"{$id_merce}\"","tags");
$id_vendor = service_get_field("SELECT * FROM MERCE WHERE id_merce=\"{$id_merce}\"","id_vendor");
$titolo .= $tags;
if (isset($id_vendor)) $titolo .= " ".$id_vendor;
$giacenza = service_get_field("SELECT * FROM MAGAZZINO WHERE id_merce=\"{$id_merce}\" AND posizione=\"{$posizione}\"","quantita");
$titolo .= " ({$giacenza} disponibili in posizione {$posizione})";

$a = atitolo.$titolo.ctitolo.accapo;
$a .= "<form name=\"scarico\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=scarico")."\">".accapo;
$a .= atable.accapo.atr.accapo.atd."Seleziona  il richiedente".ctd.accapo;
$a .= atd.optionlist_intestazioni("Richiedente").ctd.accapo;
// quantita
$a .= atr.accapo.atd."Quantita'".ctd.accapo;
$a .= atd."<input type=\"text\" name=\"quantita\">".ctd.accapo.ctr.accapo;
// destinazione
$a .= atr.accapo.atd."Destinazione".ctd.accapo.atd.accapo;
$a .= optionlist_etichette("7")."<input type=\"text\" name=\"destinazione\">";
$a .= ctd.accapo.ctr.accapo;
// data
$a .= atr.accapo.atd."Data".ctd.accapo;
$a .= atd.date_picker("data").ctd.accapo.ctr.accapo;
// note
$a .= atr.accapo.atd."Note".ctd.accapo;
$a .= atd."<input type=\"text\" name=\"note\">".ctd.accapo.ctr.accapo;

$a .= "<input type=\"hidden\" name=\"steps\" value=\"step4\">";
$a .= "<input type=\"hidden\" name=\"id_merce\" value=\"{$id_merce}\">";
$a .= "<input type=\"hidden\" name=\"posizione\" value=\"{$posizione}\">";

$a .= fine_form;
return $a;
}



?>
