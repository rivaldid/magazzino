<script type="text/javascript">
function altRows(id){
	if(document.getElementsByTagName){  
		
		var table = document.getElementById(id);  
		var rows = table.getElementsByTagName("tr"); 
		 
		for(i = 0; i < rows.length; i++){          
			if(i % 2 == 0){
				rows[i].className = "evenrowcolor";
			}else{
				rows[i].className = "oddrowcolor";
			}      
		}
	}
}
window.onload=function(){
	altRows('alternatecolor');
}
</script>


<br />
<br />
<!--First tooltip--> 
<a href="#" class="tooltip"> Tooltip <span> 

<img class="callout" src="imgs/callout.gif" /> 
<strong>Most Light-weight Tooltip</strong>


<br /> This is the easy-to-use Tooltip driven purely by CSS. </span> </a>



<div id="borderRadius">

  

	<?php function stampamsg($num) {

$msg[1]="Utente non abilitato per l'attivita' in oggetto (errore 17)";

$msg[2]="Mancata selezione di un fornitore per l'attivita' in corso (errore 2)";

$msg[3]="Mancata selezione di un tipo di documento per l'attivita' in corso (errore 3)";

$msg[4]="Mancata selezione di un numero di documento per l'attivita' in corso (errore 4)";

$msg[5]="Mancata selezione di una data cui far riferimento per l'attivita' in corso (errore 5)";

$msg[6]="Mancato inserimento di tags per contrassegnare la merce in carico (errore 6)";

$msg[7]="Mancato inserimento della quantita' per la merce in carico (errore 7)";

$msg[8]="Mancato inserimento della posizione in magazzino per la merce in carico (errore 8)";
 
return "
*<span>
<img class=\"callout\" src=\"imgs/callout.gif\" />".$msg[$num]."</span> </a>";
}   


?>	

<table class="altrowstable" id="alternatecolor">
    <thead>
       <tr>
        <th><span><img title="ordinamento" src="imgs/upDown.gif" /></span>First name</th>
        <th><span><img title="ordinamento" src="imgs/upDown.gif" /></span>Last name</th>
        <th><span><img title="ordinamento" src="imgs/upDown.gif" /></span>City</th>
        <th><span><img title="ordinamento" src="imgs/upDown.gif" /></span>Country</th>
        <th><span><img title="ordinamento" src="imgs/upDown.gif" /></span>Email</th>
        <th><span><img title="ordinamento" src="imgs/upDown.gif" /></span>Registered</th>
        <th><span><img title="ordinamento" src="imgs/upDown.gif" /></span>ID</th>
      </tr>
    </thead>
    <tbody>
      <tr>
	    <td>Emmanuel</td>
        <td >Owen <a href="#" class="tooltip"><?php echo stampamsg("1"); ?></td>
        <td>Needham<a href="#" class="tooltip"><?php echo stampamsg("2"); ?></td>
        <td>Pakistan<a href="#" class="tooltip"><?php echo stampamsg("3"); ?></td>
        <td>elit&#64;aliquetdiam.com<a href="#" class="tooltip"><?php echo stampamsg("4"); ?></td>
        <td>Nov 18, 2011<a href="#" class="tooltip"><?php echo stampamsg("5"); ?></td>
        <td>17321<a href="#" class="tooltip"><?php echo stampamsg("6"); ?></td>
      </tr>
      <tr>
        <td>Stewart</td>
        <td >Dillard</td>
        <td>South Portland</td>
        <td>Italy</td>
        <td>justo.Proin.non&#64;utmolestie.ca</td>
        <td>Dec 30, 2012</td>
        <td>94003</td>
      </tr>
      <tr>
        <td>Tana</td>
        <td >Villarreal</td>
        <td>Waltham</td>
        <td>Solomon Islands</td>
        <td>Proin.eget&#64;tinciduntvehicula.edu</td>
        <td>Mar 25, 2012</td>
        <td>44041</td>
      </tr>
      <tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr>
	  <tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr>
	  <tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr>
	  <tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr><tr>
        <td>Wendy</td>
        <td >Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td>arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr>

   </tbody>
 </table>
</div>

