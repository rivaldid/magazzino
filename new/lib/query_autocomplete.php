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

}
?>
