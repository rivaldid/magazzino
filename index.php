<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//IT" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Gestione Magazzino DC-TO - Poste Italiane S.p.A.</title>
	<link rel="stylesheet" href="css/index_css.css" type="text/css" />	
	<link rel="stylesheet" href="css/navi_menu_top.css" type="text/css" />
	<link rel="icon" href="img/favicon.png" type="image/png">
	<!-- librerie js per filtro tablelle -->
		<script src="lib/jquery.min.js"></script>
		<script src="lib/jquery.filtertable.min.js"></script>
	<!-- librerie js per order tablelle -->
	<script src="lib/jquery.tablesorter.js"></script>
	<!-- <script src="lib/jquery.tablesorter.pager.js"></script> -->
	
	
<script type='text/javascript'>

	/** js per filtro           **/

		$(document).ready(function() {
			$('table').filterTable();
		});
	
	/** js per export in excel **/
	
		function getData() {
		$("#tData").val($("<span>").append ($("#tblExport").eq(0).clone()).html());
		return true;
		}
	/** js per order      **/

		$(document).ready(function() 
			{ 
				$("#tblExport").tablesorter(); 
			}); 		
</script>		



	<style>
	td.alt { background-color: #ffc; background-color: #91BBEB; }	
	</style>
    
	<?php
		require_once("lib/chaos.php");

		if ($_SERVER["QUERY_STRING"] != NULL) {
			if (!empty($_GET["page"])) $page = sprintf("page/%s.php",$_GET["page"]);
			if (!file_exists($page)) $page = sprintf("page/404.php");
		} else $page = sprintf("page/home.php");
		
	/** header per export in excel **/
	if(isset($_REQUEST['tableData']))
	{
		header('Content-Type: application/force-download');
		header('Content-disposition: attachment; filename=csv_excel.xls');
		header("Pragma: ");
		header("Cache-Control: ");
		echo $_REQUEST['tableData'];
		exit();
	}
		
	?>
	
</head>
<body>
<div class="mask"> <!-- start mask --> 

	<?php 
		include ('page/header.php');
		include ('page/menu_top.php'); 
	?>
		
<div class="colleft"> <!-- start colleft -->
	<div class="col1"> <!-- start col1 -->	
			<?php include($page);?>	
	</div> <!-- end col1 -->
		
		<!--<div class="col2">  start col2 -->
		<!--</div>  end col2 -->
	</div> <!-- end colleft -->
	
<?php include ('page/footer.php'); ?>


