<div id="dialog_carico" title="Carico merce">

	<fieldset class="ui-widget ui-widget-content">
		<legend>Step1</legend>
		<div id="fornitore">
			<label for="fornitore">Fornitore</label>
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
			<input name="fornitore" autofocus/>
		</div>
		<div id="tipi_doc">
			<label for="tipi_doc">Tipo documento</label>
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
			<input name="tipo_doc" />
		</div>
		<div id="num_doc">
			<label for="num_doc">Numero documento</label>
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
			<input name="num_doc" />
		</div>
	</fieldset>

	<fieldset class="ui-widget ui-widget-content">
		<legend>Step2</legend>
		<div id="data_doc">
			<label for="data_doc">Data documento</label>
			<input name="data_doc" class="datepicker" type="text" />
		</div>
		<div id="scansione">
			<label for="scansione">Scansione</label>
			<input name="scansione" type="text" />
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content">
		<legend>Step3</legend>
		<div id="merce">
			<label for="merce">Merce</label>
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
			<ul id="tags">
				<li></li>
			</ul>
		</div>
		<div id="quantita">
			<label for="quantita">Quantita'</label>
			<input name="quantita" type="text" />
		</div>
		<div id="posizione">
			<label for="posizione">Posizione</label>
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
			<input name="posizione" type="text" />
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
			<input name="note" type="text" />
		</div>
		<div id="oda">
			<label for="oda">ODA</label>
			<img src="<?php echo prefix ?>imgs/loader.gif" alt="Loading" class="spinner">
			<input name="oda" type="text" />
		</div>
	</fieldset>
	
</div>
