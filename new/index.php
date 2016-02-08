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
	define("prefix","../"); 
	define("libnpm","/lib/node_modules/");  
	define("libbower","/lib/bower_components/")
?>

<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" href="<?php echo prefix ?>css/main_new.css" type="text/css" />

<link rel="stylesheet" href="<?php echo libnpm ?>jquery-ui/themes/flick/jquery-ui.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo libnpm ?>jquery-ui/themes/flick/jquery.ui.theme.css" type="text/css" />

<link rel="stylesheet" href="<?php echo libnpm ?>datatables.net-jqui/css/dataTables.jqueryui.css" type="text/css" />

<link rel="stylesheet" href="<?php echo libnpm ?>datatables.net-buttons-jqui/css/buttons.jqueryui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo libnpm ?>datatables.net-select-jqui/css/select.jqueryui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo libnpm ?>datatables.net-fixedheader-jqui/css/fixedHeader.jqueryui.css" type="text/css" />


<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>jquery/dist/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>jqueryui/jquery-ui.min.js"></script>

<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net-jqui/js/dataTables.jqueryui.js"></script>

<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net-select/js/dataTables.select.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net-fixedheader/js/dataTables.fixedHeader.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net-buttons/js/dataTables.buttons.js"></script>

<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net-buttons-jqui/js/buttons.jqueryui.js"></script>

<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net-buttons/js/buttons.flash.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net-buttons/js/buttons.html5.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo libnpm ?>datatables.net-buttons/js/buttons.print.js"></script>

<script type="text/javascript" charset="utf8" src="<?php echo libbower ?>jszip/dist/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo libbower ?>pdfmake/build/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo libbower ?>pdfmake/build/vfs_fonts.js"></script>



<script type="text/javascript">
$(document).ready(function() {
	
	$("#dialog_carico").dialog({
		autoOpen: false,
		show: { effect: "blind", duration: 1000 },
		hide: {	effect: "blind", duration: 1000	},
		open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }
	});

	var table = $('#magazzino').DataTable({
		"iDisplayLength": 25,
        fixedHeader: {
            header: true,
            footer: true
        },
		columnDefs: [ {
            orderable: true,
            className: 'select-checkbox',
            targets:   0
        } ],
		select: {
            style: 'multi',
            selector: 'td:first-child'
        }
	});

    $('#magazzino tbody')
        .on( 'mouseenter', 'td', function () {
            var colIdx = table.cell(this).index().column;

            $( table.cells().nodes() ).removeClass( 'highlight' );
            $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
        } );

	new $.fn.DataTable.Buttons( table, {
        buttons: [
            {
                text: 'Carico',
                action: function () {
					$("#dialog_carico").dialog("open");
                }
            },
            {
                text: 'Scarico',
                action: function () {
                    alert("Scarico");
                }
            },
			'pdf','excel',
            {
                text: 'Info',
                action: function () {
                    alert("Info");
                }
            }
        ]
	} );

	table.buttons().container().insertBefore( '#magazzino_filter' );
			
});

</script>

</head>

<body>

<div id="banner" onclick="location.href='<?php echo $_SERVER['PHP_SELF'] ?>';">
<h1><a class="firstletter">G</a>estione <a class="firstletter">M</a>agazzino <a class="firstletter">D</a>C<a class="firstletter">T</a>O</h1>
</div>

<div id="contents">
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

<div id="dialog_carico" title="Carico merce">aaaaaaaaaaaaaaa!</div>

<div id="footer">
<h3>&copy; Poste italiane 2016 - Specifiche tecniche</h3>
</div>

</body>

</html>