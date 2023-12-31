<?php

/*
 * You can then use it like this:

// Establish a connection.
$db = new DB('user', 'password', 'database');

// Create query, bind values and return a single row.
$row = $db->query('SELECT col1, col2, col3 FROM mytable WHERE id > ? LIMIT ?')
   ->bind(1, 2)
   ->bind(2, 1)
   ->single();

// Update the LIMIT and get a resultset.
$db->bind(2,2);
$rs = $db->resultset();

// Create a new query, bind values and return a resultset.
$rs = $db->query('SELECT col1, col2, col3 FROM mytable WHERE col2 = ?')
   ->bind(1, 'abc')
   ->resultset();

// Update WHERE clause and return a resultset.
$db->bind(1, 'def');
$rs = $db->resultset(); */

class DB {

    private $dbh;
    private $stmt;

    public function __construct($user, $pass, $dbname) {
		try {
			$this->dbh = new PDO(
				"mysql:host=localhost;dbname=$dbname",
				$user,
				$pass,
				array( PDO::ATTR_PERSISTENT => true )
			);
        }
        catch (PDOException $e) {
			error_handler($e->getMessage());
		}
    }

    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
        return $this;
    }

    public function bind($pos, $value, $type = null) {

        if( is_null($type) ) {
            switch( true ) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($pos, $value, $type);
        return $this;
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultset() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

	public function num_rows() {
		$this->execute();
		return $this->stmt->fetchColumn();
	}

}

class myquery extends DB {

	public function start() {
		try {
			return $db = new DB(Config::read('db.user'),Config::read('db.password'),Config::read('db.basename'));
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}


	// *********** test *****************************
	public function mysession_open($db) {
		try {

			$temp=array();

			$id = $_SERVER['PHP_AUTH_USER']."-".epura_specialchars($_GET['page']);
			session_id($id);
			session_start();

			// frees all session variables / empties the array but keeps the session alive
			session_unset();

			$db->query("CALL sh_read('?','?','?')")
				->bind(1,$_SERVER['PHP_AUTH_USER'])
				->bind(2,$_GET['page'])
				->bind(3,$temp)
				->resultset();

			//var_dump($temp);

			if (isset($temp)) {
				foreach ($temp AS $key => $value) $_SESSION[$key] = $value;
			}
			return true;

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function mysession_close($db) {
		try {

			$db->query("CALL sh_write('?','?','?','?')")
				->bind(1,$_SERVER['PHP_AUTH_USER'])
				->bind(2,$_GET['page'])
				->bind(3,$_SESSION)
				->bind(4,time())
				->resultset();

			session_write_close();

			return true;

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	// *********** fine test *****************************

	public function logger($db) {
		try {
			return $row = $db->query('CALL input_trace(?,?,?,?,?,?,?)')
				->bind(1,$_SERVER['REQUEST_TIME'])
				->bind(2,$_SERVER['REQUEST_URI'])
				->bind(3,$_SERVER['HTTP_REFERER'])
				->bind(4,$_SERVER['REMOTE_ADDR'])
				->bind(5,$_SERVER['REMOTE_USER'])
				->bind(6,$_SERVER['PHP_AUTH_USER'])
				->bind(7,$_SERVER['HTTP_USER_AGENT'])
			   ->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function get_cognome($db,$rete) {

		try {
			return $query = $db->query("SELECT get_cognome(?);")
				->bind(1,$rete)
				->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function permission($db) {

		try {
			return $query = $db->query("SELECT get_permission(?)")
				->bind(1,$_SERVER['PHP_AUTH_USER'])
				->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function transiti_pagination($db,$current_page) {

		// righe da visualizzare
		$per_page = 20;

		try {

			// righe tot
			$count = $db->query("SELECT COUNT(*) FROM vserv_transiti")->num_rows();

			// pagine totali
			$pages = ceil($count/$per_page);

			// test pagina corrente valida
			if (($current_page >= 1) AND ($current_page <= $pages)) {

				// pagina iniziale
				$start = ($current_page - 1) * $per_page;

			} else
				$current_page=1;

			// query
			$query = $db->query("SELECT * FROM vserv_transiti LIMIT ?, ?")
				->bind(1,$start)
				->bind(2,$per_page)
				->resultset();

			// pagination
			$pagination = "<div id='DIV-pagination'><ul class='paginate'>\n";

			// classe css per elemento selezionato
			if ($current_page)
			 $current='current';
			else
			 $current='single';

			// precedente
			if (($current_page-1)>1)
				$prev=$current_page-1;
			else
				$prev=1;

			// successivo
			if (($current_page+1)<$pages)
				$next=$current_page+1;
			else
				$next=$pages;

			// testa
			$current_page2 = $current_page;
			if ($current_page2>1)
				$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=$prev\"><i class='fa fa-backward'></i></a></li>\n";

			if ($current_page2 == '1')	$current='single';
			$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=1\">1</a></li>\n";
			$current='current';

			// corpo
			switch ($current_page) {

				case 1:
					$current_page+=4;
					break;

				case 2:
					$current_page+=3;
					break;

				case 3:
					$current_page+=2;
					break;

				case 4:
					$current_page+=1;
					break;

				case $pages-3:
					$current_page-=1;
					break;

				case $pages-2:
					$current_page-=2;
					break;

				case $pages-1:
					$current_page-=3;
					break;

				case $pages:
					$current_page-=4;
					break;

			}

			for ($i = $current_page-3; $i <= $current_page+3; $i++) {

				if ($current_page2 == $i) $current='single';
				$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=$i\">$i</a></li>\n";
				$current='current';

			}

			// coda
			if ($current_page2 == $pages)
				$current='single';

			$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=$pages\">$pages</a></li>\n";
			if ($current_page2<$pages)
				$pagination .= "<li class='".$current."'><a class='' href=\"?page=transiti&current_page=$next\"><i class='fa fa-forward'></i></a></li>\n";
			$pagination .= "</ul></div>\n";


			// ritorno pagination e query
			return array($pagination,$query);


		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function transiti_nopagination($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_transiti")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function transito_da_id($db,$id_operazioni) {

		try {
			return $query = $db->query("SELECT * FROM vserv_transiti WHERE id_operazioni=?")
				->bind(1,$id_operazioni)
				->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function aggiorna_oda($db,$id_operazioni,$oda,$data_oda,$scansione) {

		try {
			logging2("CALL aggiornamento_oda('$id_operazioni','$oda','$data_oda','$scansione');",splog);
			return $query = $db->query("CALL aggiornamento_oda(?,?,?,?);")
				->bind(1,$id_operazioni)
				->bind(2,$oda)
				->bind(3,$data_oda)
				->bind(4,$scansione)
				->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function report_transiti_mensile($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_report_transiti_mensile")->resultset();

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function transiti_search($db,$id_merce,$data_min,$data_max,$tags,$documento,$posizione,$ordine,$note) {

		try {

			$q = "SELECT * FROM vserv_transiti WHERE 1";
			if ($id_merce) $q .= " AND id_merce='$id_merce'";
			if ($data_min AND $data_max) $q .= " AND STR_TO_DATE(dataop, '%d/%m/%Y') BETWEEN STR_TO_DATE('$data_min', '%d/%m/%Y') AND STR_TO_DATE('$data_max', '%d/%m/%Y')";
			if ($tags) $q .= " AND tags LIKE '%$tags%'";
			if ($documento) $q .= " AND documento LIKE '%$documento%'";
			if ($posizione) $q .= " AND posizione LIKE '%$posizione%'";
			if ($ordine) $q .= " AND doc_ordine LIKE '%$ordine%'";
			if ($note) $q .= " AND note LIKE '%$note%'";

			return $query = $db->query($q)->resultset();

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function vista_transiti_revertibili($db) {

		try {

			return $query = $db->query("SELECT * FROM vserv_transiti WHERE STR_TO_DATE(dataop, '%d/%m/%Y') = STR_TO_DATE(?, '%d/%m/%Y')")->bind(1,date("d/m/Y"))->resultset();
			//return $query = $db->query("SELECT * FROM vserv_transiti WHERE STR_TO_DATE(dataop, '%d/%m/%Y') = STR_TO_DATE(?, '%d/%m/%Y')")->bind(1,"05/06/2015")->resultset();

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function revisione_revert($db,$id_operazioni) {

		try {

			return $query = $db->query("SELECT * FROM vserv_transiti WHERE id_operazioni = ? ")
				->bind(1,$id_operazioni)
				->resultset();

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function revert_do($db,$utente,$id_operazioni) {

		try {

			logging2("CALL revert('$utente','$id_operazioni');",splog);

			return $query = $db->query("CALL revert( ? , ? ) ")
				->bind(1,$utente)
				->bind(2,$id_operazioni)
				->single();

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function magazzino($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_magazzino")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function magazzino_simple($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_magazzino_simple")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function magazzino_detail($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_magazzino_detail")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function magazzino_contro($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_magazzino_contro")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function magazzino_search($db,$target,$pattern) {

		try {

			switch ($target) {

				case "id_merce":
					$query = $db->query("SELECT * FROM vserv_magazzino_detail WHERE id_merce='$pattern'")->resultset();
					break;

				case "merce":
				case "documento":
					$query = $db->query("SELECT * FROM vserv_magazzino_detail WHERE merce LIKE '%$pattern%'")->resultset();
					break;

				case "posizione":
					$query = $db->query("SELECT * FROM vserv_magazzino_detail WHERE posizione='$pattern'")->resultset();
					break;

				case "ordine":
					$query = $db->query("SELECT * FROM vserv_magazzino_detail WHERE note LIKE '%ODA%$pattern%'")->resultset();
					break;

				case "note":
					$query = $db->query("SELECT * FROM vserv_magazzino_detail WHERE note LIKE '%$pattern%'")->resultset();
					break;

				default:
					$query = $db->query("SELECT * FROM vserv_magazzino_detail")->resultset();

			}

			return $query;

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function magazzino_agg_posizione($db,$utente,$id_merce,$posizione,$nuova_posizione,$quantita,$data) {

		try {

			logging2("CALL aggiornamento_magazzino_posizione('$utente','$id_merce','$posizione','$nuova_posizione','$quantita','$data');",splog);
			return $query = $db->query("CALL aggiornamento_magazzino_posizione(?,?,?,?,?,?)")
				->bind(1,$utente)
				->bind(2,$id_merce)
				->bind(3,$posizione)
				->bind(4,$nuova_posizione)
				->bind(5,$quantita)
				->bind(6,$data)
				->single();

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function magazzino_agg_quantita($db,$utente,$id_merce,$posizione,$quantita,$nuova_quantita,$data) {

		try {

			logging2("CALL aggiornamento_magazzino_quantita('$utente','$id_merce','$posizione','$quantita','$nuova_quantita','$data');",splog);
			return $query = $db->query("CALL aggiornamento_magazzino_quantita(?,?,?,?,?,?)")
				->bind(1,$utente)
				->bind(2,$id_merce)
				->bind(3,$posizione)
				->bind(4,$quantita)
				->bind(5,$nuova_quantita)
				->bind(6,$data)
				->single();

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function next_reintegro_doc($db) {

		try {
			return $query = $db->query("SELECT next_reintegro_doc();")->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function contatti($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_contatti;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function tipi_doc($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_tipodoc;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function numdoc($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_numdoc;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function tags2($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_tags2;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function tags3($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_tags3;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function posizioni($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_posizioni;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function numoda($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_numoda;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function carico($db,$utente,$fornitore,$tipo_doc,$num_doc,$data_doc,$nome_doc,$tags,$quantita,$posizione,$data_carico,$note,$trasportatore,$num_oda) {

		try {
			$sql = "CALL CARICO('$utente','$fornitore','$tipo_doc','$num_doc','$data_doc','$nome_doc','$tags','$quantita','$posizione','$data_carico','$note','$trasportatore','$num_oda');";
			logging2($sql,splog);
			return $query = $db->query($sql)->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function next_mds_doc($db) {

		try {
			return $query = $db->query("SELECT next_mds_doc();")->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function scarico($db,$num_mds,$utente,$richiedente,$id_merce,$quantita,$posizione,$destinazione,$data_doc_scarico,$data_scarico,$note) {

		try {
			logging2("CALL SCARICO('$num_mds','$utente','$richiedente','$id_merce','$quantita','$posizione','$destinazione','$data_doc_scarico','$data_scarico','$note',@myvar);",splog);
			return $query = $db->query("CALL SCARICO(?,?,?,?,?,?,?,?,?,?,@myvar);")
				->bind(1,$num_mds)
				->bind(2,$utente)
				->bind(3,$richiedente)
				->bind(4,$id_merce)
				->bind(5,$quantita)
				->bind(6,$posizione)
				->bind(7,$destinazione)
				->bind(8,$data_doc_scarico)
				->bind(9,$data_scarico)
				->bind(10,$note)
				->single();

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function destinazioni($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_destinazioni;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function lista_scarichi($db,$limit) {

		try {

			if (isset($limit) AND is_int($limit)) {
				$query = $db->query("SELECT * FROM vserv_transiti_uscita LIMIT ?;")->bind(1,$limit)->resultset();
			} else {
				$query = $db->query("SELECT * FROM vserv_transiti_uscita;")->resultset();
			}
			return $query;

		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function gruppo_da_documento($db,$id_registro) {

		try {
			return $query = $db->query("SELECT get_gruppo_da_documento('?');")
				->bind(1,$id_registro)
				->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function prossimo_gruppo($db) {

		try {
			return $query = $db->query("SELECT get_next_gruppo();")->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function dati_per_aggiornamento_registro($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_dati_per_aggiornamento_registro;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function lista_documenti_con_id($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_documento_con_id;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function documento_da_id($db,$id_registro) {

		try {
			return $db->query("SELECT * FROM vserv_documento_con_id WHERE id_registro=$id_registro;")->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function aggiornamento_registro($db,$id_registro,$mittente,$tipo,$numero,$gruppo,$data,$scansione) {

		try {
			$sql = "CALL aggiornamento_registro('$id_registro','$mittente','$tipo','$numero','$gruppo','$data','$scansione',@myvar);";
			logging2($sql,splog);
			return $db->query("CALL aggiornamento_registro(?,?,?,?,?,?,?,@myvar)")
				->bind(1,$id_registro)
				->bind(2,$mittente)
				->bind(3,$tipo)
				->bind(4,$numero)
				->bind(5,$gruppo)
				->bind(6,$data)
				->bind(7,$scansione)
				->single();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function lista_accessi($db) {

		try {
			return $db->query("SELECT * FROM vserv_webtrace;")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

}


?>
