<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!--[if IE 7]><html lang="it" class="ie7"><![endif]-->
<!--[if IE 8]><html lang="it" class="ie8"><![endif]-->
<!--[if IE 9]><html lang="it" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><html lang="it"><![endif]-->
<!--[if !IE]><html lang="it-IT"><![endif]-->

<head>
<title>Gestione Magazzino</title>

<?php
	define("prefix","");
	define("libnpm","/lib/node_modules/");
	define("libbower","/lib/bower_components/");
	include(prefix."lib/header.php");
?>

</head>

<body>

<div id="banner" onclick="location.href='<?php echo $_SERVER['PHP_SELF'] ?>';">
<h1><a class="firstletter">G</a>estione <a class="firstletter">M</a>agazzino <a class="firstletter">D</a>C<a class="firstletter">T</a>O</h1>
</div>

<div id="contents">
<?php
	require_once(prefix."lib/init.php");

	$db = basic::start();
	if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;
	basic::logger($db);

	$query = basic::magazzino($db);

	//presentation
	$a = "<table id=\"magazzino\" class=\"display\">\n";

	$a .= "<thead>\n";
	$a .= "<tr>\n";
	$a .= "<th></th>\n";
	$a .= "<th>Merce</th>\n";
	$a .= "<th>Posizione</th>\n";
	$a .= "<th>Quantita'</th>\n";
	$a .= "</tr>\n";
	$a .= "</thead>\n";

	$a .= "<tfoot>\n";
	$a .= "<tr>\n";
	$a .= "<th></th>\n";
	$a .= "<th>Merce</th>\n";
	$a .= "<th>Posizione</th>\n";
	$a .= "<th>Quantita'</th>\n";
	$a .= "</tr>\n";
	$a .= "</tfoot>\n";

	$a .= "<tbody>\n";
	foreach ($query as $row) {
		$a .= "<tr>\n";
		$a .= "<td></td>\n";
		$a .= "<td>".$row['merce']."</td>\n";
		$a .= "<td>".$row['posizione']."</td>\n";
		$a .= "<td>".$row['quantita']."</td>\n";
		$a .= "</tr>\n";
	}
	$a .= "</tbody>\n";

	$a .= "</table>\n";

	echo $a;

?>
</div>

<?php 
	include(prefix."lib/forms.php");
	include(prefix."lib/footer.php");
?>

</body>

</html>