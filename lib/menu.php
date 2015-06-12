<div class="centeredMenuPre"> </div>
<div class="centeredmenu">
<?php $dest = (isset($_GET['page']) ? $_GET['page'] : null); ?>
	<a href="?page=home" class="<?php if ($dest=="home") echo 'current';?>"><span><i class="fa fa-home"></i> Home</span></a>
	<a href="?page=transiti" class="<?php if ($dest=="transiti") echo 'current';?>"><span><i class="fa fa-exchange"></i> Transiti</span></a>
	<a href="?page=magazzino" class="<?php if ($dest=="magazzino") echo 'current';?>"><span><i class="fa fa-archive"></i> Magazzino</span></a>
	<a href="?page=documenti" class="<?php if ($dest=="documenti") echo 'current';?>"><span><i class="fa fa-file-text"></i> Documenti</span></a>
	<a href="?page=carico" class="<?php if ($dest=="carico") echo 'current';?>"><span><i class="fa fa-cloud-upload"></i> Carico</span></a>
	<a href="?page=scarico" class="<?php if ($dest=="scarico") echo 'current';?>"><span><i class="fa fa-cloud-download"></i> Scarico</span></a>
</div>
<div class="centeredMenuSub"> </div>

<?php

$menu_home = "";
$menu_transiti = "";
$menu_magazzino = "";
$menu_carico = "";
$menu_scarico = "";
$menu_revert = "";


// MENU TRANSITI
$menu_transiti .= remesg("<a href=\"?page=transiti&current_page=all\"\>Visualizza tutti i transiti</a>","action");
$menu_transiti .= remesg("<a href=\"?page=transiti_search\">Ricerca nei transiti</a>","search");
$menu_transiti .= remesg("<a href=\"?page=transiti_revert\">Annulla un transito registrato oggi</a>","search");
$menu_transiti .= remesg("<a href=\"lib/report_transiti_excel.php\">Report transiti mese precedente in excel</a>","excel");

// MENU MAGAZZINO
$menu_magazzino .= remesg("<a href=\"?page=magazzino\">Merce presente (default)</a>","action");
$menu_magazzino .= remesg("<a href=\"?page=magazzino&detail\">Merce presente dettagliata</a>","action");
$menu_magazzino .= remesg("<a href=\"?page=magazzino&contro\">Merce scaricata</a>","action");
$menu_magazzino .= remesg("<a href=\"?page=magazzino_update\">Aggiornamenti di posizione o quantita'</a>","action");
//$menu_magazzino .= remesg("<a href=\"lib/report_magazzino_excel.php\">Report magazzino in excel</a>","excel");

// MENU CARICO
$menu_carico .= remesg("<a href=\"?page=carico&reintegro\">Reintegro merce</a>","action");

// MENU SCARICO
$menu_scarico .= remesg("<a href=\"?page=lista_scarichi&ultimi\">Ultimi scarichi</a>","action");
$menu_scarico .= remesg("<a href=\"?page=lista_scarichi\">Scarichi</a>","action");
$menu_scarico .= remesg("<a href=\"".registro_mds."\">Moduli di scarico</a>","pdf");

// MENU REVERT
$menu_revert .= remesg("<a href=\"?page=transiti\">Torna alla visualizzazione dei transiti</a>","action");

?>
