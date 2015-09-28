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
                    $magazzino->setPosizione($_GET['posizione']);
                    $magazzino->setLivello($_GET['livello']);
                    
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
                            if($magazzino->getPosizione() == "D1" || $magazzino->getPosizione() == "E1" || $magazzino->getPosizione() == "E2")
                                $magazzino->disegnoScaffale7P();
                            else
                                $magazzino->disegnoScaffale5P();
                            $magazzino->stampaMercePianoScaffale();
			?>
		</div>	
	</body>
</html>