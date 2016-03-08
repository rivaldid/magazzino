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

	$("#fornitore").autocomplete({
		source: "lib/json/contatti.php",
		minLength: 2
	});
	$("#tipi_doc").autocomplete({
		source: "lib/json/tipi_doc.php",
		minLength: 2
	});
	$("#num_doc").autocomplete({
		source: "lib/json/num_doc.php",
		minLength: 2
	});

	$( ".datepicker" ).datepicker();

	$("input:text, input:password, input[type=email]").button().addClass("my-textfield");

	$("#dialog_carico").dialog({
		autoOpen: false,
		show: { effect: "blind", duration: 500 },
		hide: {	effect: "clip", duration: 500 },
		dialogClass: "no-close",
		buttons: {
			"Submit": {
				text: "Invia",
				click: function() {
					$(this).dialog("close");
				}
			}
		}
	});

	var table = $("#magazzino").DataTable({
		"iDisplayLength": 25,
        fixedHeader: { header: true, footer: true },
		columnDefs: [ {
            orderable: true,
            className: "select-checkbox",
            targets: 0
        } ],
		select: { style: "multi", selector: "td:first-child" }
	});

    $("#magazzino tbody")
        .on( "mouseenter", "td", function () {
            var colIdx = table.cell(this).index().column;
            $( table.cells().nodes() ).removeClass("highlight");
            $( table.column( colIdx ).nodes() ).addClass("highlight");
        } );

	new $.fn.DataTable.Buttons( table, {
        buttons: [
            {
                text: "Carico",
                action: function () {
					$("#dialog_carico").dialog("open");
                }
            },
            {
                text: "Scarico",
                action: function () {
                    alert("Scarico");
                }
            },
			"pdf","excel",
            {
                text: "Info",
                action: function () {
                    alert("Info");
                }
            }
        ]
	} );

	table.buttons().container().insertBefore("#magazzino_filter");

});

</script>

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

<div id="dialog_carico" title="Carico merce">
	<div>
		<span><label>Fornitore</label><input id="fornitore" name="fornitore" type="text" autofocus/></span>
		<span><label>Tipo documento</label><input id="tipi_doc" name="tipo_doc" type="text" /></span>
		<span><label>Numero documento</label><input id="num_doc" name="num_doc" type="text" /></span>
	</div>
	<div>
		<span><label>Data documento</label><input name="data_doc" class="datepicker" type="text" /></span>
		<span><label>Scansione</label><input name="scansione" type="text" /></span>
	</div>
	<div>
		<span><label>Merce</label><input name="merce" type="text" /></span>
		<span><label>Quantita'</label><input name="quantita" type="text" /></span>
		<span><label>Posizione</label><input name="posizione" type="text" /></span>
	</div>
	<div>
		<span><label>Data carico</label><input name="data_carico" class="datepicker" type="text" /></span>
		<span><label>Note'</label><input name="note" type="text" /></span>
		<span><label>ODA</label><input name="oda" type="text" /></span>
	</div>
</div>

<div id="footer">
<h3>&copy; Poste italiane 2016 - Specifiche tecniche</h3>
</div>

</body>

</html>