<?php
class MysqlClass
{
	
	// variabili
	private $nomehost = "localhost";     
	private $nomeuser = "magazzino";          
	private $password = "magauser";
          
	
	public function connetti() {
		$connessione = mysql_connect($this->nomehost,$this->nomeuser,$this->password);
		if (!$connessione) {
			die('Errore di connessione: ' . mysql_error());
		}
		//echo 'Connessione eseguita...';
		
		mysql_select_db('magazzino', $connessione) or killemall("accesso al db");
		
	}

	public function disconnetti() {
        mysql_close();
		return true; 
	}
	
	
	public function myquery($sql) {
		$risultato = mysql_query($sql) or killemall("query");
		return $risultato;
	}
	
	
	public function pulizia($input) {
		mysql_free_result($input);
		return true;
	}


}
 
?>
