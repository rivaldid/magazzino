<?php


//  FORM AMMINISTRAZIONE


function form_input_rubrica() {
	$a = atitolo."Inserisci contatto in rubrica".ctitolo.accapo;
	$a .= "<form name=\"input_rubrica\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=input_rubrica")."\">".accapo;
	$a .= atable.accapo;
	
	// tipo_rubrica
	$a .= atr.accapo.atd."Tipo di contatto".ctd.accapo;
	$a .= atd.accapo;
	$a .= "<select name=\"tipoRubrica\">".accapo;
	$a .= "<option selected=\"selected\"></option>".accapo;
	$a .= "<option value=\"Fornitore\">Fornitore</option>".accapo;
	$a .= "<option value=\"Trasportatore\">Trasportatore</option>".accapo;
	$a .= "<option value=\"Richiedente\">Richiedente</option>".accapo;
	$a .= "</select>".accapo;
	$a .= ctd.accapo;
	$a .= ctr.accapo;
	
	// CAMPI
	$a .= apri_line."Intestazione".ctd.accapo.atd."<input type=\"text\" name=\"intestazione\">".chiudi_line;
	$a .= apri_line."Partita IVA".ctd.accapo.atd."<input type=\"text\" name=\"pIva\">".chiudi_line;
	$a .= apri_line."Codice fiscale".ctd.accapo.atd."<input type=\"text\" name=\"codFiscale\">".chiudi_line;
	$a .= apri_line."Indirizzo".ctd.accapo.atd."<input type=\"text\" name=\"indirizzo\">".chiudi_line;
	$a .= apri_line."CAP".ctd.accapo.atd."<input type=\"text\" name=\"cap\">".chiudi_line;
	$a .= apri_line."Citta'".ctd.accapo.atd."<input type=\"text\" name=\"citta\">".chiudi_line;
	$a .= apri_line."Nazione".ctd.accapo.atd."<input type=\"text\" name=\"nazione\">".chiudi_line;
	$a .= apri_line."Numero di telefono".ctd.accapo.atd."<input type=\"text\" name=\"tel\">".chiudi_line;
	$a .= apri_line."Numero di Fax".ctd.accapo.atd."<input type=\"text\" name=\"fax\">".chiudi_line;
	$a .= apri_line."Sito Web".ctd.accapo.atd."<input type=\"text\" name=\"sitoWeb\">".chiudi_line;
	$a .= apri_line."eMail".ctd.accapo.atd."<input type=\"text\" name=\"email\">".chiudi_line;
	
	$a .= fine_form;
	return $a;
}


function form_input_registro() {
	$a = atitolo."Inserisci un documento".ctitolo.accapo;
	$a .= "<form name=\"input_ordini\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=input_registro")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."Mittente documento".ctd.accapo.atd.optionlist_intestazioni().chiudi_line;
	
	// tipo_documento
	/*
	$a .= apri_line."Tipo documento".ctd.accapo.atd.accapo;
	$a .= "<select name=\"tipoDoc\">".accapo;
	$a .= "<option selected=\"selected\"></option>".accapo;
	$a .= "<option value=\"ODA\">ODA</option>".accapo;
	$a .= "<option value=\"BDC\">BDC</option>".accapo;
	$a .= "<option value=\"DDT\">DDT</option>".accapo;
	$a .= "<option value=\"MDS\">Modulo di scarico (MDS)</option>".accapo;
	$a .= "<option value=\"LDV\">Lettera di vettura (LDV)</option>".accapo;
	$a .= "<option value=\"EMAIL\">eMail</option>".accapo;
	$a .= "</select>".accapo.chiudi_line;
	*/
	$a .= apri_line."Tipo di documento".ctd.accapo.atd.optionlist_tipoDoc().chiudi_line;
	
	// campi
	$a .= apri_line."Numero documento".ctd.accapo.atd."<input type=\"text\" name=\"numDoc\" value=\"Automatico\">".chiudi_line;
	$a .= apri_line."Data".ctd.accapo.atd.date_picker("data").chiudi_line;
	
	// file
	$a .= apri_line."Scansione".ctd.accapo.atd.accapo;
	$a .= "<input type=\"file\" name=\"file1\">".accapo."<input type=\"hidden\" name=\"action\" value=\"upload\">".accapo.chiudi_line;	
	
	$a .= fine_form;
	return $a;
}


function form_input_registro_nofile() {
	$a = atitolo."Inserisci un documento".ctitolo.accapo;
	$a .= "<form name=\"input_ordini\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=input_registro_nofile")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."Mittente documento".ctd.accapo.atd.optionlist_intestazioni().chiudi_line;
	
	// tipo_documento
	$a .= apri_line."Tipo di documento".ctd.accapo.atd.optionlist_tipoDoc().chiudi_line;
	
	// campi
	$a .= apri_line."Numero documento".ctd.accapo.atd."<input type=\"text\" name=\"numDoc\" value=\"Automatico\">".chiudi_line;
	$a .= apri_line."Data".ctd.accapo.atd.date_picker("data").chiudi_line;
	
	$a .= fine_form;
	return $a;
}


function form_input_ordini() {
	$a = atitolo."Inserisci un ordine".ctitolo.accapo;
	$a .= adesc."In seguito ad una consegna, inserire un ordine collegando un articolo ad un ordine, un codice dato dal fornitore ed una quantita'".cdesc.accapo;
	$a .= "<form name=\"input_ordini\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=input_ordini")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= "<tr>\n<td>Mittente ordine</td>\n<td>".optionlist_intestazioneMittentiOrdine()."</td>\n</tr>\n";
	$a .= apri_line."Tipo di documento".ctd.accapo.atd.optionlist_tipoDoc_ordine().accapo;
	$a .= "<input type=\"text\" name=\"numDocOrdinante\">".chiudi_line;
	
	$a .= apri_line."Fornitore".ctd.accapo.atd.optionlist_fornitori().chiudi_line;
	$a .= apri_line."Articoli".ctd.accapo.atd.optionlist_articoli().chiudi_line;
	$a .= apri_line."Codice articolo del fornitore".ctd.accapo.atd."<input type=\"text\" name=\"codArticoloFornitore\">".chiudi_line;
	$a .= apri_line."Quantita'".ctd.accapo.atd."<input type=\"text\" name=\"quantita\">".chiudi_line;

	$a .= fine_form;
	return $a;
}


function form_input_forniture() {
	$a = atitolo."Inserisci una fornitura".ctitolo.accapo;
	$a .= adesc."Crea un'associazione fornitore - richiedente - trasportatore, collegando un ordine ad una consegna".cdesc.accapo;
	$a .= "<form name=\"input_forniture\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=input_forniture")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."Fornitore".ctd.accapo.atd.optionlist_fornitori().chiudi_line;
	$a .= apri_line."Numero fornitura".ctd.accapo.atd;
	$a .= "<select name=\"tipoDocFornitura\">".accapo;
	$a .= "<option selected=\"selected\"></option>".accapo;
	$a .= "<option value=\"DDT\">DDT</option>".accapo;
	$a .= "</select>".accapo;
	$a .= "<input type=\"text\" name=\"numDocFornitura\">".chiudi_line;
	$a .= apri_line."Mittente ordine".ctd.accapo.atd.optionlist_intestazioneMittentiOrdine().chiudi_line;
	
	$a .= apri_line."Numero ordine".ctd.accapo.atd;
	$a .= "<select name=\"tipoDocOrdinante\">".accapo;
	$a .= "<option selected=\"selected\"></option>".accapo;
	$a .= "<option value=\"ODA\">ODA</option>".accapo;
	$a .= "<option value=\"BDC\">BDC</option>".accapo;
	$a .= "</select>".accapo;
	$a .= "<input type=\"text\" name=\"numDocOrdinante\">".chiudi_line;
	
	$a .= apri_line."Trasportatore".ctd.accapo.atd.optionlist_trasportatori().chiudi_line;

	$a .= fine_form;
	return $a;
}


function form_input_contabilita() {
	$a = "<h3>Ricerca contabilita' per ordine</h3>\n";
	$a .= "<form name=\"input_contabilita\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_ricerca_contabilita");
	$a .= "\">\n<table>\n";
	
	$a .= "<tr>\n<td>Mittente ordine</td>\n<td>".optionlist_intestazioneMittentiOrdine()."</td>\n</tr>\n";
	$a .= "<tr>\n<td>Tipo e Numero ordine</td>\n<td>".optionlist_TipoNumOrdiniEmessi()."</td>\n</tr>\n";
	
	$a .= fine_form;
	return $a;
}


function form_input_ricerca_doc() {
	$a = atitolo."Ricerca documenti".ctitolo.accapo;
	$a .= adesc."Seleziona e compila: 1)niente, 2)data, 3)tipo, 4)data-tipo, 5)numero".cdesc.accapo;
	$a .= adesc."NB: solo data crea un intervallo -7 +7".cdesc.accapo;
	$a .= "<form name=\"input_ricerca_doc\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=task_ricerca_doc")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."Tipo di ricerca".ctd.accapo.atd.accapo;
	$a .= "<select name=\"tiposearch\">".accapo;
	$a .= "<option selected=\"selected\"></option>".accapo;
	$a .= "<option value=\"100\">per data</option>".accapo;
	$a .= "<option value=\"010\">per tipo</option>".accapo;
	$a .= "<option value=\"110\">per data e tipo</option>".accapo;
	$a .= "<option value=\"001\">per numero</option>".accapo;
	$a .= "</select>".accapo.chiudi_line;
	
	$a .= apri_line."Centro dell'intervallo".ctd.accapo.atd.date_picker("data").chiudi_line;
	$a .= apri_line."Tipo di documento".ctd.accapo.atd.optionlist_tipoDoc().chiudi_line;
	$a .= apri_line."Numero del documento".ctd.accapo.atd."<input type=\"text\" name=\"numDoc\">".chiudi_line;

	
	$a .= fine_form;
	return $a;
}


function form_input_tipoDoc() {
	$a = atitolo."Inserisci un tipo di documento".ctitolo.accapo;
	$a .= "<form name=\"input_tipodoc\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=tipodoc")."\">".accapo;
	$a .= atable.accapo;
	$a .= apri_line."Ambito".ctd.accapo.atd.accapo;
	$a .= "<input type=\"radio\" name=\"ambito\" value=\"ordine\">ordine".accapo;
	$a .= "<input type=\"radio\" name=\"ambito\" value=\"fornitura\">fornitura".accapo;
	$a .= "<input type=\"radio\" name=\"ambito\" value=\"altro\">altro".accapo.chiudi_line;
	$a .= apri_line."Etichetta".ctd.accapo.atd."<input type=\"text\" name=\"label\">".chiudi_line;
	
	$a .= fine_form;
	return $a;
}


function form_input_collo() {
	$a = atitolo."Raggruppa documenti con progressivo (ex colli)".ctitolo.accapo;
	$a .= "<form name=\"input_colli\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=colli")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."Numero progressivo assegnato".ctd.accapo.atd."<input type=\"text\" name=\"idColli\">".chiudi_line;
	$a .= apri_line."Documento".ctd.accapo.atd.optionlist_doc_non_in_colli().chiudi_line;
	
	$a .= fine_form;
	return $a;	
}


// FORM MAGAZZINO


function form_input_tags() {
	$a = atitolo."Inserisci un TAG".ctitolo.accapo;
	$a .= adesc."Livello etichette: da 1 a 3 in base all'importanza della caratteristica rappresentata".cdesc.accapo;
	$a .= "<form name=\"input_tags\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=tags")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."Livello".ctd.accapo.atd.accapo;
	$a .= "<input type=\"radio\" name=\"livello\" value=\"1\">".accapo;
	$a .= "<input type=\"radio\" name=\"livello\" value=\"2\">".accapo;
	$a .= "<input type=\"radio\" name=\"livello\" value=\"3\">".accapo.chiudi_line;
	$a .= apri_line."Etichetta".ctd.accapo.atd."<input type=\"text\" name=\"label\">".chiudi_line;
	
	$a .= fine_form;
	return $a;
}


function form_input_locations() {
	$a = atitolo."Inserisci una posizione".ctitolo.accapo;
	$a .= adesc."Indicatore 1 per posizioni tra scaffali, 2 per piazzamenti negli scaffali, 3 per destinazioni di merce in uscita".cdesc.accapo;
	$a .= "<form name=\"input_locations\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=locations")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."Indicatore".ctd.accapo.atd.accapo;
	$a .= "<input type=\"radio\" name=\"indicatore\" value=\"1\">".accapo;
	$a .= "<input type=\"radio\" name=\"indicatore\" value=\"2\">".accapo;
	$a .= "<input type=\"radio\" name=\"indicatore\" value=\"3\">".accapo;
	$a .= chiudi_line;
	$a .= apri_line."Etichetta".ctd.accapo.atd."<input type=\"text\" name=\"label\">".chiudi_line;
	
	$a .= fine_form;
	return $a;
}


function form_input_articoli() {
	$a = atitolo."Insererisci articoli in inventario".ctitolo.accapo;
	$a .= adesc."Raggruppa insiemi di etichette per rappresentare modelli per merci da assumere".cdesc.accapo;
	$a .= "<form name=\"input_articoli\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=inventario_articoli")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."TAGS".ctd.accapo.atd.optionlist_tags("1").optionlist_tags("2").optionlist_tags("3").chiudi_line;
	$a .= apri_line."Note".ctd.accapo.atd."<input type=\"text\" name=\"descrizione\">".chiudi_line;
	
	$a .= fine_form;
	return $a;
}


function form_input_asset_step1() {
	$a = atitolo."Specializza un articolo come asset".ctitolo.accapo;
	$a .= "<form name=\"input_asset\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=input_asset")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= apri_line."Articoli".ctd.accapo.atd.optionlist_disponibilitaArticoliSenzaAsset().chiudi_line;
	$a .= "<input type=\"hidden\" name=\"steps\" value=\"step1\">";
	
	$a .= fine_form;
	return $a;
}


function form_input_asset_step2($idArticolo) {
	$a = "<h3>Specializza un articolo come asset</h3>\n";
	$a .= "<form name=\"input_asset\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=input_asset");
	$a .= "\">\n<table>\n";
	
	$a .= apri_line."Seriale".ctd.accapo.atd."<input type=\"text\" name=\"seriale\">".chiudi_line;
	$a .= apri_line."PT Number".ctd.accapo.atd."<input type=\"text\" name=\"ptNumber\">".chiudi_line;
	$a .= apri_line."Posizioni".ctd.accapo.atd.optionlist_posizioniOccupate($idArticolo).chiudi_line;
	$a .= apri_line."Note".ctd.accapo.atd."<input type=\"text\" name=\"note\">".chiudi_line;
	$a .= apri_line."Data".ctd.accapo.atd.date_picker("data").chiudi_line;
	
	$a .= "<input type=\"hidden\" name=\"idArticolo\" value=\"{$idArticolo}\">";
	$a .= "<input type=\"hidden\" name=\"steps\" value=\"step2\">";
	
	$a .= fine_form;
	return $a;
}


function form_input_carichi1() {
	$a = atitolo."Insererisci un carico".ctitolo.accapo;
	$a .= adesc."Viene richiesto il numero del DDT, inserirlo con massima attenzione".cdesc.accapo;
	$a .= "<form name=\"input_carichi1\" method=\"post\" enctype=\"multipart/form-data\" action=\"".htmlentities("?page=task_carichi")."\">".accapo;
	$a .= atable.accapo;
	
	$a .= "<tr>\n";
		$a .= "<td><input type=\"radio\" name=\"tipo_ins\" value=\"new\">Nuovo articolo</td>\n";
		$a .= "<td>\n";
			$a .= "<table>\n";
				$a .= "<tr>\n<td>TAGS</td>\n<td>".optionlist_tags("1").optionlist_tags("2").optionlist_tags("3")."</td>\n</tr>\n";
				$a .= "<tr>\n<td>Note</td>\n<td><input type=\"text\" name=\"descrizione\"></td>\n</tr>\n";
			$a .= "</table>\n";
		$a .= "</td>\n";
	$a .= "</tr>";
	
	$a .= "<td><input type=\"radio\" name=\"tipo_ins\" value=\"old\">Da modello</td>\n<td>".optionlist_articoli()."</td>\n</tr>\n";
	$a .= "<tr>\n<td>Quantita'</td>\n<td><input type=\"text\" name=\"quantita\"></td>\n</tr>\n";
	
	$a .= "<td>Fornitore</td>\n<td>".optionlist_fornitori()."</td>\n</tr>\n";
	$a .= "<td>numero DDT</td>\n<td><input type=\"text\" name=\"numDocFornitura\"></td>\n</tr>\n";
	$a .= "<td>Destinazione</td>\n<td>".optionlist_posizioni().optionlist_piazzamenti()."</td>\n</tr>\n";
	
	$a .= "<tr>\n<td>Data</td>\n<td>".date_picker("data")."</td>\n</tr>\n";
	$a .= "<tr>\n<td>Note</td>\n<td><input type=\"text\" name=\"note\"></td>\n</tr>\n";
	
	$a .= fine_form;
	return $a;
}


function form_input_scarichi_step1() {
	$a = "<h3>Insererisci uno scarico step1</h3>\n";
	$a .= "<form name=\"input_scarichi\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_scarichi");
	$a .= "\">\n<table>\n";
	
	$a .= "<tr>\n<td>Articolo</td>\n<td>".optionlist_disponibilitaArticoliSenzaAsset()."</td>\n</tr>\n";
	$a .= "<input type=\"hidden\" name=\"steps\" value=\"step1\">";
	
	$a .= fine_form;
	return $a;	
}


function form_input_scarichi_step2($idArticolo) {
	$a = "<h3>Insererisci uno scarico step2</h3>\n";
	$a .= "<form name=\"input_scarichi\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_scarichi");
	$a .= "\">\n<table>\n";
	
	$a .= "<tr>\n<td>Richiedenti</td>\n<td>".optionlist_intestazioni_richiedenti()."</td>\n</tr>\n";
	$a .= "<td>numero documento di scarico</td>\n<td><input type=\"text\" name=\"numDocRich\"></td>\n</tr>\n";
	$a .= "<td>Quantita'</td>\n<td><input type=\"text\" name=\"quantita\"></td>\n</tr>\n";
	
	$a .= "<td>Provenienza</td>\n<td>".optionlist_posizioniOccupate($idArticolo)."</td>\n</tr>\n";
	$a .= "<td>Destinazione</td>\n<td>".optionlist_destinazioni()."</td>\n</tr>\n";
	$a .= "<tr>\n<td>Data</td>\n<td>".date_picker("data")."</td>\n</tr>\n";
	$a .= "<td>Note</td>\n<td><input type=\"text\" name=\"note\"></td>\n</tr>\n";
	
	$a .= "<input type=\"hidden\" name=\"idArticolo\" value=\"{$idArticolo}\">";
	$a .= "<input type=\"hidden\" name=\"steps\" value=\"step2\">";
	
	$a .= fine_form;
	return $a;
}


function form_input_ricerca() {
	$a = "<h3>Ricerca per TAGS articolo</h3>\n";
	$a .= "<form name=\"ricerca\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_ricerca");
	$a .= "\">\n<table>\n";
	
	$a .= "<tr>\n<td>Tags</td>\n<td>".optionlist_tagsDisponibili()."</td>\n</tr>\n";
	
	$a .= fine_form;
	return $a;
}


function form_input_ricerca_asset() {
	$a = "<h3>Ricerca per TAGS articolo</h3>\n";
	$a .= "<form name=\"ricerca\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_ricerca_asset");
	$a .= "\">\n<table>\n";
	
	$a .= "<td>Serial o PT Number</td>\n<td><input type=\"text\" name=\"input\"></td>\n</tr>\n";
	
	$a .= fine_form;
	return $a;
}


function form_input_compattazione_step1() {
	$a = "<h3>Compatta posizioni in magazzino</h3>\n";
	$a .= "<form name=\"compattazione\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_compattazione");
	$a .= "\">\n<table>\n";
	
	$a .= "<tr>\n<td>Tags</td>\n<td>".optionlist_disponibilitaArticoliSenzaAsset()."</td>\n</tr>\n";
	$a .= "<input type=\"hidden\" name=\"steps\" value=\"step1\">";
	
	$a .= fine_form;
	return $a;	
}


function form_input_compattazione_step2($idArticolo) {
	$a = "<h3>Compatta merce in magazzino</h3>\n";
	$a .= "<form name=\"compattazione\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_compattazione");
	$a .= "\">\n<table>\n";
	
	$a .= "<td>Provenienza</td>\n<td>".optionlist_posizioniOccupate($idArticolo)."</td>\n</tr>\n";
	$a .= "<td>Destinazione</td>\n<td>".optionlist_posizioni().optionlist_piazzamenti()."</td>\n</tr>\n";
	
	$a .= "<input type=\"hidden\" name=\"idArticolo\" value=\"{$idArticolo}\">";
	$a .= "<input type=\"hidden\" name=\"steps\" value=\"step2\">";
	
	$a .= fine_form;
	return $a;	
}


function form_input_scarichi_asset_step1() {
	$a = "<h3>Scarica asset dal magazzino</h3>\n";
	$a .= "<form name=\"scarichi_asset\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_scarichi_asset");
	$a .= "\">\n<table>\n";
	
	$a .= "<td>Seleziona Asset</td>\n<td>".optionlist_assetSerialptNumber()."</td>\n</tr>\n";
	$a .= "<input type=\"hidden\" name=\"steps\" value=\"step1\">";
	
	$a .= fine_form;
	return $a;	
}


function form_input_scarichi_asset_step2($serialptNumber) {
	$a = "<h3>Scarica asset dal magazzino</h3>\n";
	$a .= "<form name=\"scarichi_asset\" method=\"post\" enctype=\"multipart/form-data\" action=\"";
	$a .= htmlentities("?page=task_scarichi_asset");
	$a .= "\">\n<table>\n";
	
	$a .= "<td>Richiedente</td>\n<td>".optionlist_intestazioni_richiedenti()."</td>\n</tr>\n";
	$a .= "<td>Numero documento di scarico</td>\n<td><input type=\"text\" name=\"numDocRich\"></td>\n</tr>\n";
	$a .= "<td>Destinazione</td>\n<td>".optionlist_destinazioni()."</td>\n</tr>\n";
	$a .= "<tr>\n<td>Data</td>\n<td>".date_picker("data")."</td>\n</tr>\n";
	$a .= "<td>Note</td>\n<td><input type=\"text\" name=\"note\"></td>\n</tr>\n";
	
	$a .= "<input type=\"hidden\" name=\"serialptNumber\" value=\"{$serialptNumber}\">";
	$a .= "<input type=\"hidden\" name=\"steps\" value=\"step2\">";
	
	$a .= fine_form;
	return $a;	
}


?>
