<?php
class autocomplete extends DB {

	public function contatti($db,$term) {

		try {
			return $query = $db->query("SELECT * FROM vserv_contatti WHERE label LIKE ?")
				->bind(1,$term.'%')
				->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function tipi_doc($db,$term) {

		try {
			return $query = $db->query("SELECT * FROM vserv_tipodoc WHERE label LIKE ?")
				->bind(1,$term.'%')
				->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}

	public function num_doc($db,$term) {

		try {
			return $query = $db->query("SELECT * FROM vserv_numdoc WHERE numero LIKE ?")
				->bind(1,$term.'%')
				->resultset();
		} catch (PDOException $e) {
			error_handler($e->getMessage());
		}
	}
	
}
?>
