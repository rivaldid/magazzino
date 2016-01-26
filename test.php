<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!--[if IE 7]><html lang="it" class="ie7"><![endif]-->
<!--[if IE 8]><html lang="it" class="ie8"><![endif]-->
<!--[if IE 9]><html lang="it" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><html lang="it"><![endif]-->
<!--[if !IE]><html lang="it-IT"><![endif]-->

<head>
<title>Gestione Magazzino</title>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" href="css/main2.css" type="text/css" />
<link rel="stylesheet" href="css/tabella2.css" type="text/css" />
<link rel="stylesheet" href="/lib/bower_components/dynatable/jquery.dynatable.css" type="text/css" />
<link rel="stylesheet" href="/lib/bower_components/font-awesome-bower/css/font-awesome.css" type="text/css" />
<script type="text/javascript" src="/lib/bower_components/jquery/dist/jquery.js"></script>
<script type="text/javascript" src="/lib/bower_components/dynatable/jquery.dynatable.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('#magazzino').dynatable({
		features: {
			paginate: false
		},
		input: {
			queries: $('#merce, #posizione')
		},
		table: {
			defaultColumnIdStyle: 'trimDash'
		}
	})
});
</script>

</head>

<body>

<div id="banner">
<h1><a class="firstletter">G</a>estione <a class="firstletter">M</a>agazzino <a class="firstletter">D</a>C<a class="firstletter">T</a>O</h1>
</div>

<div id="contents">
<?php
	if ($_SERVER["QUERY_STRING"] != NULL) {
		if (!empty($_GET["page"])) $page = sprintf("page/%s.php",$_GET["page"]);
		if (!file_exists($page)) $page = sprintf("page/404.php");
	} else $page = sprintf("page/home.php");

	require_once 'lib/init.php';

	$db = myquery::start();
	if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;
	myquery::logger($db);
	
	$query = myquery::magazzino($db);

	//presentation
	$a = "<table id=\"magazzino\">\n";
	
	$a .= "<thead>\n";
	$a .= "<tr>\n";
	$a .= "<th data-dynatable-column=\"merce\">Merce</th>\n";
	$a .= "<th data-dynatable-column=\"posizione\">Posizione</th>\n";
	$a .= "<th data-dynatable-column=\"quantita\">Quantita'</th>\n";
	$a .= "</tr>\n";
	$a .= "</thead>\n";
	
	$a .= "<tbody>\n";
	foreach ($query as $row) {
		$riga .= "<tr>\n";
		$riga .= "<td>".$row['merce']."</td>\n";
		$riga .= "<td>".$row['posizione']."</td>\n";
		$riga .= "<td>".$row['quantita']."</td>\n";
		$riga .= "</tr>\n";
	}
	$a .= $riga;
	$a .= "</tbody>\n";
	
	$a .= "</table>\n";
	
	echo $a;
	
?>
</div>

<div id="footer">
<h3>&copy; Poste italiane 2016 - Specifiche tecniche</h3>
</div>

</body>

</html>