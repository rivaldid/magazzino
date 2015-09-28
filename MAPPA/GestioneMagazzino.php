<?php
	class GestioneMagazzino
	{
		private $conn=null;
		private $posizione="";
		private $livello="";
		private $vettPos = array("M1","M2","M3","M4","M5","M6","M7","M8","M9","M10","M11","M12","M13",
                                 "P1","P2","P3","P4","P5","P6","P7","P8","P9",
                                 "P10","P11","P12","P13","P14","P15","P16","P17","P18",
                                 "P19","P20","P21","P22","P23","P24","P25","P26","P27","P28",
                                 "P29","P30","P31","P32","P33","P34","P35","P36","P37","P38",
                                 "A1","A2","B1","B2","C1","C2","D1","E1","E2","Z1","Z2");
                
		
		private $vetListaIdPianoScaffale=array("A","B","C","D","E","F","G","H","I","L","M","N","O","P","Q","R","S","T","U","V","Z");
		private $vetValore5P=array("L11","L6","L1","L12","L7","L2","L13","L8","L3","L14","L9","L4","L15","L10","L5");
		private $vetValore7P=array("L15","L8","L1","L16","L9","L2","L17","L10","L3","L18","L11","L4","L19","L12","L5","L20","L13","L6","L21","L14","L7");
                private $vetPosRicerca=null;
		private $vetLivelloRicerca=null;
		
		public function __construct($severname,$username,$password,$dbname)
		{
			$this->conn=mysqli_connect($severname,$username,$password,$dbname);
			if(!$this->conn)
			{
				die("connessione fallita");			
			}
		}
		
		
		//funzione che genera uno spazio bianco sulla mappa
		private function spazioBianco($id)
		{
			echo "<form>";
				echo "<input type=\"button\" id=\"".$id."\" class=\"vuoto\"  disabled/>";
			echo "</form>";
		}

		public function pulisciMappa()
		{
			for($i=1; $i<=10; $i++)
			{
				$nomeSpazio="bianco".$i;
				$this->spazioBianco($nomeSpazio);
			}
		}

		/*metodo di disegno di una cella del magazzino 
	     	@ $id ID:oggetto
	     	@ $classe:tipo Oggetto
	     	@ $pagina: nome della pagina da caricare
	     	*/	
		private function disegnoCella($id,$classe,$pagina)
		{
			$sql="SELECT GROUP_CONCAT(merce) AS titolo FROM vserv_mappa WHERE pos='".$id."' GROUP BY pos;";
			$result=mysqli_query($this->conn,$sql);
			$row = mysqli_fetch_assoc($result);
                        if($this->cellaSelezionata($id))
                            $classe=$classe."Selezionato";  
                        else if($id == $this->posizione)
                            $classe=$classe."Selezionato";                        
                        else if($row!="")
                            $classe=$classe."Occupato";
			echo "<form action=\"".$pagina."\" method=\"GET\">";			
				echo "<input type=\"submit\" id=\"".$id."\" class=\"".$classe."\" name=\"cella\" value=\"".$id."\" title=\"".$row["titolo"]."\"/>";		
			echo "</form>";
                       
                        
		}

                private function cellaSelezionata($id)
                {
                    
                    if($this->vetPosRicerca != null)
                    {
                        for($j=0; $j <count($this->vetPosRicerca); $j++)
                            if($id == $this->vetPosRicerca[$j] )
                                return 1; 
                    }
                    return 0;
                    
                }


		private function cellaSelezionataScaffale($id)
                {
                    
                    if($this->vetLivelloRicerca != null)
                    {
                        for($j=0; $j <count($this->vetLivelloRicerca); $j++)
                            if($id == $this->vetLivelloRicerca[$j] )
                                return 1; 
                    }
                    return 0;
                    
                }
		
		/*metodo che permette di disegnare tutte le celle contenute nella mappa */
		public function disegnoMappa()
		{
			//muro			
			for($i=0; $i<13; $i++)
			{
				$j=$i +1;				
				$id = "M".$j;
				$this->disegnoCella($id,'muro','mercePavimento.php');
			}
			
			//pavimento alto
			for($i=0; $i<18; $i++)
			{
				$j=$i +1;				
				$id = "P".$j;
				$this->disegnoCella($id,'pavimento','mercePavimento.php');
			}
		
			//pavimneto basso
			for($i=18; $i<38; $i++)
			{
				$j=$i +1;				
				$id = "P".$j;
				$this->disegnoCella($id,'pavimento_Basso','mercePavimento.php');
			}

			//scaffali			
			$this->disegnoCella('A1','scaffale','merceScaffale.php');
			$this->disegnoCella('A2','scaffale','merceScaffale.php');
			$this->disegnoCella('B1','scaffale','merceScaffale.php');
			$this->disegnoCella('B2','scaffale','merceScaffale.php');
			$this->disegnoCella('C1','scaffale','merceScaffale.php');
			$this->disegnoCella('C2','scaffale','merceScaffale.php');
			$this->disegnoCella('D1','scaffale','merceScaffale.php');
			$this->disegnoCella('E1','scaffale','merceScaffale.php');
			$this->disegnoCella('E2','scaffale','merceScaffale.php');
			$this->disegnoCella('Z1','scaffale','merceScaffale.php');
			$this->disegnoCella('Z2','scaffale','merceScaffale.php');
			
	    		//Area di scarto
            		echo "<form>";
                	echo "<input type=\"button\" id=\"areaScarto\" class=\"scarto\"  value=\"Area di scarto\" disabled/>";
            		echo "</form>";
		}
		
		//metodo che disegna la option list di ricerca;		
		public function listaRicerca()
		{
			$sql="SELECT DISTINCT merce FROM vserv_mappa ORDER BY merce";
			$result=mysqli_query($this->conn,$sql);			
			echo "<form action=\"ricerca.php\">";  
                	echo "	<fieldset>";
			echo "  	<legend>Ricerca</legend>";
			echo "    	<select name=\"merce\" onchange=\"this.form.submit()\">";   
			echo "			<option></option>";
			while($row = mysqli_fetch_assoc($result))
			{			
						echo "<option>".$row["merce"]."</option>";	
			}					
			echo "		</select>";
			echo "	 </fieldset>"; 
            		echo "</form>";
				
		}
		 
	    
		/*metodo che stampa il contenuto di una cella
			@pos: poszione della merce
			@livello: coordinata del piano		
		*/
		public function stampaMercePianale()
		{
			$sql="SELECT DISTINCT merce,sum(quantita) AS \"quantita\" FROM vserv_mappa WHERE pos='".$this->posizione."' GROUP BY merce ORDER BY merce";
			$result=mysqli_query($this->conn,$sql);
                        if($result->num_rows > 0)
                        {
                            echo "<form>";  
                                    echo "<fieldset id=\"Risultato\">";  
                                            echo "<legend>Dettaglio Merce</legend>";   
                                            echo "<table>";
                                            echo "	<tr>";
                                            echo "		<th>Merce</th>";
                                            echo "		<th>Quantita</th>";
                                            echo "	</tr>";
                                            while($row = mysqli_fetch_assoc($result))
                                            {
                                                    echo "<tr>";
                                                    echo "	<td>".$row["merce"]."</td>";
                                                    echo "	<td>".$row["quantita"]."</td>";
                                                    echo "</tr>";										
                                            }
                                    echo "</fieldset>";
                            echo "</form>"; 
                        }
		}

		public function stampaMercePianoScaffale()
		{
			$sql="SELECT merce,sum(quantita) AS quantita FROM vserv_mappa WHERE pos='".$this->posizione."' AND livello='".$this->livello."' GROUP BY merce ORDER BY merce;";
			$result=mysqli_query($this->conn,$sql);						
			echo "<form>";  
                		echo "<fieldset id=\"Risultato\">";  
                    			echo "<legend>Dettaglio Merce</legend>";   
                    			echo "<table>";
					echo "	<tr>";
					echo "		<th>Merce</th>";
					echo "		<th>Quantita</th>";
					echo "	</tr>";
					while($row = mysqli_fetch_assoc($result))
					{
						echo "<tr>";
						echo "	<td>".$row["merce"]."</td>";
						echo "	<td>".$row["quantita"]."</td>";
						echo "</tr>";										
					}
                		echo "</fieldset>";
            		echo "</form>"; 
		}
		
		public function setPosizione($str)
		{
			$this->posizione=$str;		
		}
		public function getPosizione()
		{
			return $this->posizione;		
		}
		
		public function setLivello($str)
		{
			$this->livello=$str;		
		}
		public function getLivello()
		{
			return $this->livello;		
		}
	    
		/*metodo che permette di disegnare un piano dello scaffale
		@id: id del piano da disegnare
		@valore: etichetta del piano		
		*/		
		private function disegnoPiano($id,$valore,$pagina)
		{
			$sql="SELECT GROUP_CONCAT(merce) AS titolo FROM vserv_mappa WHERE pos='".$this->posizione."' AND livello='".$valore."' GROUP BY pos,livello;";
			$result=mysqli_query($this->conn,$sql);
			$row = mysqli_fetch_assoc($result);	
					
			echo "<form action=\"".$pagina."\" method=\"GET\">";
                        $classe = "pianoScaffale";
                        if($this->cellaSelezionataScaffale($valore))
                            $classe=$classe."Selezionato";
                        else if($valore == $this->livello)
                            $classe=$classe."Selezionato";
                        else if($row!="")
                            $classe=$classe."Occupato";
                	echo "<input type=\"submit\" id=\"".$id."\" class=\"".$classe."\" name=\"livello\" value=\"".$valore."\" title=\"".$row["titolo"]."\">";
                        echo "<input type=\"hidden\" name=\"posizione\" value=\"".$this->posizione."\">";
            		echo "</form>";
		}

		//metodo che permette di disegnare una scaffale a 5 piani		
		public function disegnoScaffale5P()
		{
		        	
			for($i=0; $i<15; $i++)
			{
				$this->disegnoPiano($this->vetListaIdPianoScaffale[$i],$this->vetValore5P[$i],'pianoScaffale.php');
			}
		}

		//metodo che permette di disegnare una scaffale a 7 piani		
		public function disegnoScaffale7P()
		{
					
			for($i=0; $i<21; $i++)
			{
				$this->disegnoPiano($this->vetListaIdPianoScaffale[$i],$this->vetValore7P[$i],'pianoScaffale.php');
			}
		}
                /** metodo che salva la merce da ricercare
                 * creo i vettori contenenti posizione e livello
                 * @param type $ricerca 
                 */
                public function setRicerca($ricerca)
                {
                   $sql="SELECT DISTINCT pos FROM vserv_mappa WHERE merce='".$ricerca."'";
                   $result=mysqli_query($this->conn,$sql);
                   $this->vetPosRicerca=array();
                   
                   while($row = mysqli_fetch_assoc($result))
                   {
                       array_push($this->vetPosRicerca, $row['pos']);
                   }
                   
                   if(count($this->vetPosRicerca) == 1)
                   {
                        if(($this->vetPosRicerca[0] == "A1") || ($this->vetPosRicerca[0] == "A2") || ($this->vetPosRicerca[0] == "B1") || ($this->vetPosRicerca[0] == "B2")||
                           ($this->vetPosRicerca[0] == "C1") || ($this->vetPosRicerca[0] == "C2") || ($this->vetPosRicerca[0] == "D1") || ($this->vetPosRicerca[0] == "E1")||
                           ($this->vetPosRicerca[0] == "E2") || ($this->vetPosRicerca[0] == "Z1") || ($this->vetPosRicerca[0] == "Z2"))
                        {
                            $sql="SELECT DISTINCT livello FROM vserv_mappa WHERE merce='".$ricerca."'";
                            $result=mysqli_query($this->conn,$sql);
                            $this->vetLivelloRicerca=array();
                            while($row = mysqli_fetch_assoc($result))
                            {
                                array_push($this->vetLivelloRicerca, $row['livello']);
                            }
                            
                        }
                        $this->setPosizione($this->vetPosRicerca[0]);
                        
                   }
		   
                }
                
               public function stampaMerceTrovata($ricerca)
               {
                   
                    $sql="SELECT merce,quantita,pos,livello FROM vserv_mappa WHERE merce='".$ricerca."';";
                    $result=mysqli_query($this->conn,$sql);

		    if($this->vetPosRicerca !=null)
		    {
		    	if(count($this->vetPosRicerca) == 1)
			{
				if(($this->vetPosRicerca[0]=="A1")||($this->vetPosRicerca[0]=="A2")||($this->vetPosRicerca[0]=="B1")||($this->vetPosRicerca[0]=="B2")||
				   ($this->vetPosRicerca[0]=="C1")||($this->vetPosRicerca[0]=="C2")||($this->vetPosRicerca[0]=="Z1")||($this->vetPosRicerca[0]=="Z2"))
						$this->disegnoScaffale5P();
				else if(($this->vetPosRicerca[0]=="D1")||($this->vetPosRicerca[0]=="E1")||($this->vetPosRicerca[0]=="E2"))	
						$this->disegnoScaffale7P();
			}
		    }
					
                    echo "<form>";  
                            echo "<fieldset id=\"Risultato\">";  
                                    echo "<legend>Dettaglio Merce</legend>";   
                                    echo "<table>";
                                    echo "	<tr>";
                                    echo "		<th>Merce</th>";
                                    echo "		<th>Quantita</th>";
                                    echo "		<th>Posizione</th>";
                                    echo "		<th>Livello</th>";
                                    echo "	</tr>";
                                    while($row = mysqli_fetch_assoc($result))
                                    {
                                            echo "<tr>";
                                            echo "	<td>".$row["merce"]."</td>";
                                            echo "	<td>".$row["quantita"]."</td>";
                                            echo "	<td>".$row["pos"]."</td>";
                                            echo "	<td>".$row["livello"]."</td>";
                                            echo "</tr>";										
                                    }
                            echo "</fieldset>";
                    echo "</form>"; 
                    
               }
	}
?>
