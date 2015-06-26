<?php

echo remesg("Accesso limitato ai contenuti per la pagina richiesta","deny");
$done=false;

if (isset($_SERVER['HTTP_REFERER'])) {
	if (strpos($_SERVER['HTTP_REFERER'],'page') !== false) {
		$test = explode("page=",$_SERVER['HTTP_REFERER']);
		if (in_array($test[1],$lettura)) {
			include sprintf("page/%s.php",$test[1]);
		} else $done=true;
	} else $done=true;
} else $done=true;


if ($done) include sprintf("page/home.php");

?>
