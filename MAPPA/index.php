<html>
	<head>
        	<meta charset="UTF-8">
        	<title></title>
		<link rel="stylesheet" href="style.css"/>
    	</head>	
	<body>
		<?php
                    require_once('GestioneMagazzino.php');		
                    $magazzino = new GestioneMagazzino('localhost','magazzino','magauser','magazzino'); 
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
			?>
		</div>
		
		
	</body>
</html>
