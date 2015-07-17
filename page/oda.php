<?php

// inizializzazione

// variabili
if (isset($_GET["debug"]))
	$DEBUG=true;
else
	$DEBUG=false;

if (empty($_POST['id_operazioni']))
	$_POST['stop']=true;

if (isset($_POST['oda'])AND(!empty($_POST['oda'])))
	$numero = norm($_POST['oda']);
else
	$numero = NULL;

if (isset($_POST['data_oda'])AND(!empty($_POST['data_oda'])))
	$data_oda = $_POST['data_oda'];
else
	$data_oda = NULL;

if (isset($_POST['scansione'])AND(!empty($_POST['scansione'])))
	$scansione = $_POST['scansione'];
else
	$scansione = NULL;

$tipo='ODA';
$mittente='Poste Italiane S.p.A.';

$id_operazioni = $_POST['id_operazioni'];
$a = "";
$log = "";


// test bottoni: stop || add || save
if (isset($_POST['stop']))
	header('Location: ' . "?page=transiti");


if (isset($_POST['add'])) {

	$a .= "<form method='post' enctype='multipart/form-data' action='".htmlentities("?page=oda");
	if ($DEBUG) $a .= "&debug";
	$a .= "'>\n";
	$a .= jsxdate;

	$a .= noinput_hidden("id_operazioni",$id_operazioni);

	$row = myquery::transito_da_id($db,$id_operazioni);

	$a .= "<table class='altrowstable' id='alternatecolor'>\n";

		$a .= "<thead>\n";
		$a .= "<tr><th>Inserimento [ numero ODA - data documento - scansione ] su transito #$id_operazioni</th></tr>\n";
		$a .= "</thead>\n";

		$a .= "<tfoot>\n";
		$a .= "<tr><td>\n";
		$a .= "<input type='submit' name='save' value='Salva ODA'/>\n";
		$a .= "<input type='submit' name='stop' value='Esci senza salvare'/>\n";
		$a .= "</td></tr>\n";
		$a .= "</tfoot>\n";

		$a .= "<tbody>\n";
		$a .= "<tr><td>\n";
		$a .= safetohtml($row['rete'])." - ";
		$a .= safetohtml($row['dataop'])." - ";
		$a .= safetohtml($row['status'])." - ";
		$a .= safetohtml($row['posizione'])." - ";
		$a .= $row['documento']." - ";
		$a .= safetohtml($row['data_doc'])." - ";
		$a .= safetohtml($row['tags'])." - ";
		$a .= safetohtml($row['quantita'])." - ";
		$a .= safetohtml(strtolower($row['note']));
		$a .= "</td></tr>\n";
		$a .= "<tr><td>\n";
		$a .= "<input type='text' name='oda'/>\n";
		$a .= "<input type='text' class='datepicker' name='data_oda'/>\n";
		$a .= "<input type='file' name='scansione'/>\n";
		$a .= "</td></tr>\n";
		$a .= "</tbody>\n";

	$a .= "</table>\n";
	$a .= "</form>\n";

}

if (isset($_POST['save'])) {

	// test valid: numero
	if ($numero) {

		//scansione
		if (empty($_FILES['scansione']['name'])) {

			$log .= remesg("Nessun file selezionato","warn");

		} else {

			if ($_FILES['scansione']['size'] > 0) {

				$scansione = epura_specialchars(epura_space2underscore($tipo))."-".epura_specialchars(epura_space2underscore($mittente))."-".epura_specialchars(epura_space2underscore($numero)).".".getfilext($_FILES['scansione']['name']);
				$filename = $_SERVER['DOCUMENT_ROOT'].registro.$scansione;

				if (file_exists(registro.$scansione)) {

					$log .= remesg("Nessun file caricato perche' presente sul disco","warn");

				} else {

					if (move_uploaded_file($_FILES['scansione']['tmp_name'], $filename)) {

						$log .= remesg("Scansione del documento caricata correttamente","done");

					} else {

						$log .= remesg("Scansione del documento non caricata","err");
						$scansione = NULL;
					}
				}
			}
		}

		//database
		myquery::aggiorna_oda($db,$id_operazioni,$numero,$data_oda,$scansione);
		$log .= remesg("Ordine di tipo $tipo emesso da $mittente #$numero inserito","done");
		$log .= remesg("Torna alla <a href=\"?page=transiti\">visualizzazione transiti</a>","action");


	} // end test valid
}


// stampo
echo makepage($a, $log);


?>
