<div id="dialog_carico" title="Carico merce">
	<span id="fornitore">
		<label for="fornitore">Fornitore</label>
		<input name="fornitore" autofocus/>
		<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
	</span>
	<span id="tipi_doc">
		<label for="tipi_doc">Tipo documento</label>
		<input name="tipo_doc" />
		<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
	</span>
	<span id="num_doc">
		<label for="num_doc">Numero documento</label>
		<input name="num_doc" />
		<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
	</span>

	<div>
		<span><label>Data documento</label><input name="data_doc" class="datepicker" type="text" /></span>
		<span><label>Scansione</label><input name="scansione" type="text" /></span>
	</div>
	<div>
		<span><label>Merce</label><input name="merce" type="text" /></span>
		<span><label>Quantita'</label><input name="quantita" type="text" /></span>
		<span><label>Posizione</label><input name="posizione" type="text" /></span>
	</div>
	<div>
		<span><label>Data carico</label><input name="data_carico" class="datepicker" type="text" /></span>
		<span><label>Note'</label><input name="note" type="text" /></span>
		<span><label>ODA</label><input name="oda" type="text" /></span>
	</div>
</div>
