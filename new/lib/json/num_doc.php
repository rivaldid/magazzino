<?php
define("prefix","../../");
require_once(prefix."lib/init.php");

if (isset($_GET['term'])){
	
	$return = array();
	
	try {
		$db = basic::start();
		$return = autocomplete::num_doc($db,$_GET['term']);
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();

	}

	echo json_encode($return);
}

?>
