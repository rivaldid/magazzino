<?php
	$message=shell_exec("/var/www/scripts/testscript 2>&1");
	print_r($message);
?>  