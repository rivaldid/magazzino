<?php
class basic extends DB {

	public function start() {
		try {
			return $db = new DB(Config::read('db.user'),Config::read('db.password'),Config::read('db.basename'));
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

	public function magazzino($db) {

		try {
			return $query = $db->query("SELECT * FROM vserv_magazzino")->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

}
?>
