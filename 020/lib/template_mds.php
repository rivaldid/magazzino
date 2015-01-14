<div id="contenitore">

<div class="intestazione">
	<div id="logo"><span style="font: bold 28px verdana;">Poste</span><span style="font: normal 24px verdana;">Italiane</span></div>
	<h3>TI/GSI/GI/TO</h3>
	<h3>DATA CENTER TORINO</h3>
	<h3>Corso Tazzoli 235/4</h3>
	<h3>10137 TORINO</h3>
</div>
	<table>
		<caption><h1>Modulo di consegna materiale</h1></caption>
		<tr><td class="bold">Struttura richiedente</td><td class="normal"><?php echo $richiedente;?></td></tr>
		<tr><td class="bold">Note</td><td class="normal"><?php echo $note;?></td></tr>
		<tr><td class="bold">Operatore di accessi</td><td class="normal"><?php echo $utente;?></td></tr>
		<tr><td class="bold">Data di riferimento scarico</td><td class="normal"><?php echo $data_doc_scarico;?></td></tr>
	</table>
	<table>
		<tr>
			<td class="bold"><?php echo $quantita;?></td>
			<td class="normal"><?php echo $tags;?></td>
			<td class="normal">da <?php echo $posizione;?></td>
			<td class="bold">a <?php echo $destinazione;?></td>
		</tr>
	</table>
	<table>
		<tr>
			<td class="normal">Torino, <?php echo $data_scarico;?></td></tr>
			<td class="firma">Firma</td>
		</tr>
	</table>

</div>
