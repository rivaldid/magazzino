<?php

logging2(occhiomalocchio(basename(__FILE__)),accesslog);

?>

<h1> Magazzino Data Center </h1>
<hr>

<div style="text-align:justify; padding:20px 120px;">
	<p>Il lavoro si divide in due componenti: una web ed una database.</p>
	<p>La componente web utilizza script php per elaborare informazioni trasportate in post e consegnate alle sessioni, ogni sessione si chiude automaticamente quando l'operazione in corso si e' conclusa con esito positivo.</p>
	<p>La componente database utilizza strutture mysql come stored function e stored procedure.
	Essa e' organizzata ad oggetti e dispone di interfacce sia pubbliche, tramite le quali parla con la componente web, sia con interfacce private tramite le quali mette in comunicazione tabelle e funzionalita'.</p>
	<p>Il progetto e' stato supportato dal personale del datacenter di Torino e sviluppato da</p>
	<p style="text-align:center"><i class="fa fa-code"> <a href="mailto:VILARDID@posteitaliane.it">Dario Vilardi</a></i> e <i class="fa fa-instagram"> <a href="mailto:DALES177@posteitaliane.it">Davide D'Alessio</a></i></p>
</div>
