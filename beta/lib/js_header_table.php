<!-- librerie js per filtro tablelle -->
<script src="lib/jquery.min.js"></script>
<script src="lib/jquery.filtertable.min.js"></script>
<!-- librerie js per order tablelle -->
<script src="lib/jquery.tablesorter.js"></script>

<script type='text/javascript'>
	
	/** js per filtro           **/

		$(document).ready(function() {
			$('table').filterTable();
		});
	
	/** js per order      **/

		$(document).ready(function() 
			{ 
				$("table").tablesorter(); 
			}); 		
</script>	
