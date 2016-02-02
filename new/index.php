<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!--[if IE 7]><html lang="it" class="ie7"><![endif]-->
<!--[if IE 8]><html lang="it" class="ie8"><![endif]-->
<!--[if IE 9]><html lang="it" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><html lang="it"><![endif]-->
<!--[if !IE]><html lang="it-IT"><![endif]-->

<head>
<title>Gestione Magazzino</title>
<?php define("prefix","../") ?>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" href="<?php echo prefix ?>css/main_new.css" type="text/css" />

<link rel="stylesheet" href="/lib/bower_components/font-awesome-bower/css/font-awesome.css" type="text/css" />
<link rel="stylesheet" href="/lib/bower_components/datatables.net-dt/css/jquery.dataTables.min.css" type="text/css" />
<link rel="stylesheet" href="/lib/bower_components/datatables.net-buttons-dt/css/buttons.dataTables.min.css" type="text/css" />
<link rel="stylesheet" href="/lib/bower_components/datatables.net-select-dt/css/select.dataTables.min.css" type="text/css" />
<link rel="stylesheet" href="/lib/bower_components/datatables.net-fixedheader-dt/css/fixedHeader.dataTables.min.css" type="text/css" />

<script type="text/javascript" charset="utf8" src="/lib/bower_components/jquery/dist/jquery.js"></script>
<script type="text/javascript" charset="utf8" src="/lib/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="/lib/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="/lib/bower_components/datatables.net-select/js/dataTables.select.min.js"></script>
<script type="text/javascript" charset="utf8" src="/lib/bower_components/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>


<script type="text/javascript">
$(document).ready(function() {

	var tabella = $('#magazzino').DataTable({
		"iDisplayLength": 25,
        fixedHeader: {
            header: true,
            footer: true
        },
		columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
		select: {
            style: 'multi',
            selector: 'td:first-child'
        }
	});
	
	
	$('#magazzino').on('click','a.input_scarico',function (scarico_do) {
		scarico_do.preventDefault();
		
		form_scarico
			alert("Puzza la cacca!");
	});
	
});

</script>

</head>

<body>

<div id="banner" onclick="location.href='<?php echo $_SERVER['PHP_SELF'] ?>';">
<h1><a class="firstletter">G</a>estione <a class="firstletter">M</a>agazzino <a class="firstletter">D</a>C<a class="firstletter">T</a>O</h1>
</div>

<div id="contents">
<!-- <input type="button" value="add" id="addbtn" /> -->
<?php
	if ($_SERVER["QUERY_STRING"] != NULL) {
		if (!empty($_GET["page"])) $page = sprintf(prefix."page/%s.php",$_GET["page"]);
		if (!file_exists($page)) $page = sprintf(prefix."page/404.php");
	} else $page = sprintf(prefix."page/home.php");

	require_once(prefix."lib/init.php");

	$db = myquery::start();
	if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;
	myquery::logger($db);
	
	$query = myquery::magazzino($db);

	//presentation
	$a = "<table id=\"magazzino\">\n";
	
	$a .= "<thead>\n";
	$a .= "<tr>\n";
	$a .= "<th>Check</th>\n";
	$a .= "<th>Merce</th>\n";
	$a .= "<th>Posizione</th>\n";
	$a .= "<th>Quantita'</th>\n";
	$a .= "</tr>\n";
	$a .= "</thead>\n";
	
	$a .= "<tfoot>\n";
	$a .= "<tr>\n";
	$a .= "<th>Check</th>\n";
	$a .= "<th>Merce</th>\n";
	$a .= "<th>Posizione</th>\n";
	$a .= "<th>Quantita'</th>\n";
	$a .= "</tr>\n";
	$a .= "</tfoot>\n";
	
	$a .= "<tbody>\n";
	foreach ($query as $row) {
		$riga .= "<tr>\n";
		$riga .= "<td></td>\n";
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