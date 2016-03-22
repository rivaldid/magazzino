<div id="dialog_carico" title="Carico merce">

	<fieldset class="ui-widget ui-widget-content">
		<legend>Step1</legend>
		<div id="fornitore">
			<label for="fornitore">Fornitore</label>
			<input name="fornitore" autofocus/>
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
		</div>
		<div id="tipi_doc">
			<label for="tipi_doc">Tipo documento</label>
			<input name="tipo_doc" />
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
		</div>
		<div id="num_doc">
			<label for="num_doc">Numero documento</label>
			<input name="num_doc" />
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
		</div>
	</fieldset>

	<fieldset class="ui-widget ui-widget-content">
		<legend>Step2</legend>
		<div id="data_doc">
			<label for="data_doc">Data documento</label>
			<input name="data_doc" class="datepicker" type="text" />
		</div>
		<div id="scansione" name="scansione">Scansione</div>
	</fieldset>

	<fieldset class="ui-widget ui-widget-content">
		<legend>Step3</legend>
		<div id="merce">
			<label for="merce">Merce</label>
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
			<ul id="tags" name="merce">
				<li></li>
			</ul>
		</div>
		<div id="quantita">
			<label for="quantita">Quantita'</label>
			<input name="quantita" type="text" />
		</div>
		<div id="posizione">
			<label for="posizione">Posizione</label>
			<input name="posizione" type="text" />
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
		</div>
	</fieldset>

	<fieldset class="ui-widget ui-widget-content">
		<legend>Step4</legend>
		<div id="data_carico">
			<label for="data_carico">Data carico</label>
			<input name="data_carico" class="datepicker" type="text" />
		</div>
		<div id="note">
			<label for="note">Note</label>
			<textarea name="note" rows="3" cols="21"></textarea>
		</div>
		<div id="oda">
			<label for="oda">ODA</label>
			<input name="oda" type="text" />
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
		</div>
	</fieldset>

</div>
