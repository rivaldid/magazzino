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
        $this->dbh = new PDO(
            "mysql:host=localhost;dbname=$dbname",
            $user,
            $pass,
            array( PDO::ATTR_PERSISTENT => true )
        );
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
		return $this->fetchColumn();
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
	
}


?>
