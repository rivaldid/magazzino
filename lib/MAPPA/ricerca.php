<html>
	<head>
        	<meta charset="UTF-8">
        	<title></title>
		<link rel="stylesheet" href="style.css"/>
    	</head>	
	<body>
		<?php
                    require_once('GestioneMagazzino.php');		
                    $magazzino = new GestioneMagazzino('localhost','root','','magazzino');
                    $magazzino->setRicerca($_GET['merce']);
		?>
		<div id="mappa">
			<?php
                            $magazzino->pulisciMappa();			
                            $magazzino->disegnoMappa();
			?>
		</div>		
		<div id="ricerca" >
			<?php
                          $magazzino->listaRicerca();
                          $magazzino->stampaMerceTrovata($_GET['merce']);
			?>
		</div>
		
		
	</body>
</html>
