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
