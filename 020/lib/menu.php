<div class="centeredMenuPre"> </div>
<div class="centeredmenu">
<?php $dest = (isset($_GET['page']) ? $_GET['page'] : null); ?>
	<a href="?page=home" class="<?php if ($dest=="home") echo 'current'; else echo 'style3';?>" id="normal" ><span>GM-DCTO</span></a>
	<a href="?page=transiti" class="<?php if ($dest=="transiti") echo 'current'; else echo 'style3';?>" id="normal" ><span>Transiti</span></a>
	<a href="?page=magazzino" class="<?php if ($dest=="magazzino") echo 'current'; else echo 'style3';?>" id="normal" ><span>Magazzino</span></a>
	<a href="?page=carico" class="<?php if ($dest=="carico") echo 'current'; else echo 'style3';?>" id="normal" ><span>Carico</span></a>
	<a href="?page=scarico" class="<?php if ($dest=="scarico") echo 'current'; else echo 'style3';?>" id="normal" ><span>Scarico</span></a>
	<a href="?page=documenti" class="<?php if ($dest=="documenti") echo 'current'; else echo 'style3';?>" id="normal" ><span>Documenti</span></a>
</div>
<div class="centeredMenuSub"> </div>
