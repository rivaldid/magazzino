<div id="footer">
	<ul>
		<li>
			<a href="?page=sviluppo"><i class="fa fa-database"></i> Magazzino</a>
			
			<?php
			
			if ($permission==2)
				echo "| <a href=\"?page=trace\"><i class=\"fa fa-book\"></i> Registro Accessi</a>\n";
				
			?>
			
		</li>
	</ul>
</div>
