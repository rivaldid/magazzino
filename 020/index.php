<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
<head>
	<title>GMDCTO</title>
	<link rel="stylesheet" href="css/menu.css" type="text/css" />
	<link rel="stylesheet" href="css/footer.css" type="text/css" />
	<link rel="stylesheet" href="css/main.css" type="text/css" />
	<link rel="stylesheet" href="css/tabella.css" type="text/css" />
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" />
	<script type="text/javascript" src="lib/jquery.min.js"></script>
	<script type="text/javascript" src="lib/jquery-ui.js"></script>
	<script type="text/javascript" src="lib/jquery.filtertable.js"></script>
	<script type="text/javascript" src="lib/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="lib/jquery.ui.datepicker-it.js"></script>
	<script>
$(document).ready(function () {
    var str = "";
    for (i = 0; i < 25; i++) {
        a = i + 1;
        str += "<tr><td><input type='checkbox' id='checkall' name='mydata' value=" + a + "></td><td>a" + a + "</td><td>name" + a + "</td></tr>";
    }
    $("#flow-table > tbody").append(str);
    oTableStaticFlow = $('#flow-table').dataTable({
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
    });

    $("#flowcheckall").click(function () {
        $('#flow-table tbody input[type="checkbox"]').prop('checked', this.checked);
    });
});
</script>   
</head>
<body>

<?php
if ($_SERVER["QUERY_STRING"] != NULL) {
	if (!empty($_GET["page"])) $page = sprintf("page/%s.php",$_GET["page"]);
	if (!file_exists($page)) $page = sprintf("page/404.php");
} else $page = sprintf("page/home.php");

require_once 'lib/vars.php';
require_once 'lib/functions.php';
include 'lib/menu.php';
?>

<div id="contents">
<?php include $page; ?>
</div>

<?php include 'lib/footer.html'; ?>

</body>
</html>
