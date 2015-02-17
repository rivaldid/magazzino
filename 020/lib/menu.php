<div class="centeredMenuPre"> </div>
<div class="centeredmenu">
<?php $dest = (isset($_GET['page']) ? $_GET['page'] : null); ?>
	<a href="http://10.98.2.159/GMDCTO/020/?page=home" class="<?php if ($dest=="home") echo 'current'; else echo 'style3';?>" id="normal" ><span><i class="fa fa-home"></i> GM-DCTO</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=transiti" class="<?php if ($dest=="transiti") echo 'current'; else echo 'style3';?>" id="normal" ><span><i class="fa fa-retweet"></i> Transiti</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=magazzino" class="<?php if ($dest=="magazzino") echo 'current'; else echo 'style3';?>" id="normal" ><span><i class="fa fa-archive"></i> Magazzino</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=carico" class="<?php if ($dest=="carico") echo 'current'; else echo 'style3';?>" id="normal" ><span><i class="fa fa-cloud-upload"></i> Carico</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=scarico" class="<?php if ($dest=="scarico") echo 'current'; else echo 'style3';?>" id="normal" ><span><i class="fa fa-cloud-download"></i> Scarico</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=documenti" class="<?php if ($dest=="documenti") echo 'current'; else echo 'style3';?>" id="normal" ><span><i class="fa fa-book"></i> Documenti</span></a>
	<a href="http://10.98.2.159/GMDCTO/020/?page=ricerca" class="<?php if ($dest=="ricerca") echo 'current'; else echo 'style3';?>" id="normal" ><span><i class="fa fa-search"></i> Ricerca</span></a>
</div>
<div class="centeredMenuSub"> </div>
