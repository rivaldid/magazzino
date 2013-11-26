<!DOCTYPE html>
<html lang="it">

<head>
	<title>Magazzino</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="icon" href="img/favicon.png" type="image/png">
</head>

<body>

<?php
	require_once("lib/chaos.php");
?>

<div id="header">
<h1>Gestione Magazzino</h1>
</div>

<<<<<<< Updated upstream
<div id="menu"><ul>
<li><a href="/main.php">/</a></li>
<li><a href="/magazzino">Home</a></li>
<li><a href="?page=ammi">Amministrazione</a></li>
<li><a href="?page=maga">Magazzino</a></li>
</ul></div>

<?php
	if ($_SERVER["QUERY_STRING"] != NULL) {
		if (!empty($_GET["page"]))
			$page = $_GET["page"];
	}
	else 
		$page = "home";
=======
<?php
	if ($_SERVER["QUERY_STRING"] != NULL) {
		if (!empty($_GET["page"]))
			$page = sprintf("pages/%s.php",$_GET["page"]);
			if (!file_exists($page)) $page = sprintf("pages/404.php");
		else $page = sprintf("pages/home.php");
		
		if (!empty($_GET["menu"]))
			$menu = sprintf("pages/%s.php",$_GET["menu"]);
			if (!file_exists($menu)) $menu = sprintf("pages/404.php");
		else $menu = sprintf("pages/menu_home.php");
	}
?>

	
<div id="cssmenu">
	<ul>
	   <li><a href="?page=#"  class="<?php
      if ($_GET['page']=="#") echo 'active';
      else echo 'style3';?>" id="normal" ><span>/\/\/\/\/\</span></a></li>
	   
	   <li><a href="?page=home"  class="<?php
      if ($_GET['page']=="home") echo 'active';
      else echo 'style3';?>" id="normal" ><span>Home</span></a></li>
	   
	   <li><a href="?page=ammi&menu=ammi"  class="<?php
      if ($_GET['page']=="ammi") echo 'active';
      else echo 'style3';?>" id="normal" ><span>Amministrazione</span></a></li>
	 
	   <li><a href="?page=maga&menu=maga"  class="<?php
      if ($_GET['page']=="maga") echo 'active';
      else echo 'style3';?>" id="normal" ><span>Magazzino</span></a></li>
	   
	   <li><a href="?page=admin#"  class="<?php
      if ($_GET['page']=="admin") echo 'active';
      else echo 'style3';?>" id="normal" ><span>Admin</span></a></li>
	</ul>
</div>


<!-- INIZIO<div id="contents">  CONTENTS -->
 <!-- INIZIO	<div class="left"> LEFT -->
	<!--	<div class="left_container">  INIZIO LEFT_CONTAINERS -->
		<!--	<p class="greyboxinfo"> --><?php echo date("d/m/Y : H:i:s", time()); ?> <!--</p>-->
	<!--	</div>   FINE LEFT CONTAINERS -->
>>>>>>> Stashed changes
	
		<!--<div class="left_container">--> <?php /* include $parte_sx; */ ?> <!--	</div>-->
	<!--</div>  FINE LEFT -->
	<!-- <div class="right"> -->
	
<<<<<<< Updated upstream
	if (!file_exists($parte_dx)) $parte_dx = sprintf("pages/404.php");
	if (!file_exists($parte_sx)) $parte_sx = sprintf("pages/404.php");
?>

<div id="contents">
<div class="left">
<div class="left_container">
<p class="greyboxinfo">
<?php 
	echo date('d/m/Y : H:i:s', time());
?>
</p>
</div>
<div class="left_container">
<?php
	include $parte_sx;
?>
</div>
</div>
<div class="right">
=======
	
	
	
	
	
	
	
<!-- CONTENT DELLA PARTE DESTRA TOTALE -->
<div style=" margin: 10px 10px 10px 10px;  height: 100%; width: 100%; border: 5px solid red;">
		
		
		<!-- content parte sx -->
		<div style=" float: left;  border: 2px solid green;">
			<?php //include $parte_sx; ?>
		</div>
		
		
		
		<!-- content parte destra -->	
		<div style="float: left;  border: 2px solid #CCCCCC;">
			<?php 	//include $parte_dx;?>
		</div>
	
	
</div> 	<!-- FINE CONTENT DELLA PARTE DESTRA TOTALE -->


	
	
	
>>>>>>> Stashed changes
<?php
	include("pages/footer.html");
?>

