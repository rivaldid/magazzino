<div class="centeredMenuPre"> </div>
<div class="centeredmenu">
<?php $dest = (isset($_GET['page']) ? $_GET['page'] : null); ?>
	<a href="http://10.98.2.159/GMDCTO/020/?page=home" class="<?php if ($dest=="home") echo 'current';?>"><span><i class="fa fa-home"></i> Home</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=transiti" class="<?php if ($dest=="transiti") echo 'current';?>"><span><i class="fa fa-exchange"></i> Transiti</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=magazzino" class="<?php if ($dest=="magazzino") echo 'current';?>"><span><i class="fa fa-archive"></i> Magazzino</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=documenti" class="<?php if ($dest=="documenti") echo 'current';?>"><span><i class="fa fa-file-text"></i> Documenti</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=carico" class="<?php if ($dest=="carico") echo 'current';?>"><span><i class="fa fa-cloud-upload"></i> Carico</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=scarico" class="<?php if ($dest=="scarico") echo 'current';?>"><span><i class="fa fa-cloud-download"></i> Scarico</span></a>
</div>
<div class="centeredMenuSub"> </div>

<?php

$menu_transiti = "";
$menu_magazzino = "";
//$menu_documenti = "";
$menu_carico = "";
$menu_scarico = "";


// MENU TRANSITI
$menu_transiti .= remesg("<a href=\"?page=transiti&current_page=all\"\>Visualizza tutti</a>","action");
$menu_transiti .= remesg("<a href=\"?page=transiti_search\">Ricerca</a>","search");

// MENU MAGAZZINO
//$menu_magazzino .= remesg("<a href=\"?page=magazzino_ng\">Visualizzazione con documenti</a>","action");
$menu_magazzino .= remesg("<a href=\"?page=magazzino_update\">Aggiornamenti</a>","action");
$menu_magazzino .= remesg("<a href=\"?page=contromagazzino\">Merce scaricata</a>","action");
$menu_magazzino .= remesg("<a href=\"?page=magazzino_search\">Ricerca</a>","search");

// MENU CARICO
$menu_carico .= remesg("<a href=\"?page=carico&reintegro\">Reintegro merce</a>","action");

// MENU SCARICO
$menu_scarico .= remesg("<a href=\"?page=lista_scarichi&ultimi\">Ultimi scarichi</a>","action");
$menu_scarico .= remesg("<a href=\"?page=lista_scarichi\">Scarichi</a>","action");
$menu_scarico .= remesg("<a href=\"".registro_mds."\">Moduli di scarico</a>","pdf");

?>
