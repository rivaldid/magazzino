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

	public function report_transiti_mensile($db) {
		
		try {
			return $query = $db->query("SELECT * FROM vserv_report_transiti_mensile")->resultset();

		} catch (PDOException $e) { 
			error_handler($e->getMessage());
		}
	}

	public function transiti_search($db,$id_merce,$data_min,$data_max,$tags,$documento,$oda,$note) {
		
		try {
			
			$q = "SELECT * FROM vserv_transiti WHERE 1";
			if ($id_merce) $q .= " AND id_merce='$id_merce'";
			if ($data_min AND $data_max) $q .= " AND STR_TO_DATE(dataop, '%d/%m/%Y') BETWEEN STR_TO_DATE('$data_min', '%d/%m/%Y') AND STR_TO_DATE('$data_max', '%d/%m/%Y')";
			if ($tags) $q .= " AND tags LIKE '%$tags%'";
			if ($documento) $q .= " AND documento LIKE '%$documento%'";
			if ($oda) $q .= " AND ordine LIKE '%$oda%'";
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
			
			return $query = $db->query("SELECT * FROM vserv_transiti WHERE id_operazioni = ? ")->bind(1,$id_operazioni)->resultset();
			
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
	
}


?>
