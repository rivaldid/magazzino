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
<link rel="stylesheet" href="css/tabella.css" type="text/css" />
<link rel="stylesheet" href="/lib/bower_components/dynatable/jquery.dynatable.css" type="text/css" />
<link rel="stylesheet" href="/lib/bower_components/font-awesome-bower/css/font-awesome.css" type="text/css" />
<script type="text/javascript" src="/lib/bower_components/jquery/dist/jquery.js"></script>
<script type="text/javascript" src="/lib/bower_components/dynatable/jquery.dynatable.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('#magazzino').dynatable({
	  features: {
		paginate: true,
		sort: true,
		pushState: true,
		search: true,
		recordCount: true,
		perPageSelect: true
	  },
	  table: {
		defaultColumnIdStyle: 'camelCase',
		columns: null,
		headRowSelector: 'thead tr', // or e.g. tr:first-child
		bodyRowSelector: 'tbody tr',
		headRowClass: null
	  },
	  inputs: {
		queries: null,
		sorts: null,
		multisort: ['ctrlKey', 'shiftKey', 'metaKey'],
		page: null,
		queryEvent: 'blur change',
		recordCountTarget: null,
		recordCountPlacement: 'after',
		paginationLinkTarget: null,
		paginationLinkPlacement: 'after',
		paginationPrev: 'Previous',
		paginationNext: 'Next',
		paginationGap: [1,2,2,1],
		searchTarget: null,
		searchPlacement: 'before',
		perPageTarget: null,
		perPagePlacement: 'before',
		perPageText: 'Show: ',
		recordCountText: 'Showing ',
		processingText: 'Processing...'
	  },
	  dataset: {
		ajax: false,
		ajaxUrl: null,
		ajaxCache: null,
		ajaxOnLoad: false,
		ajaxMethod: 'GET',
		ajaxDataType: 'json',
		totalRecordCount: null,
		queries: null,
		queryRecordCount: null,
		page: null,
		perPageDefault: 10,
		perPageOptions: [10,20,50,100],
		sorts: null,
		sortsKeys: null,
		sortTypes: {},
		records: null
	  },
	  // Built-in writer functions,
	  // can be overwritten, any additional functions
	  // provided in writers will be merged with
	  // this default object.
	  writers: {
		_rowWriter: defaultRowWriter,
		_cellWriter: defaultCellWriter,
		_attributeWriter: defaultAttributeWriter
	  },
	  // Built-in reader functions,
	  // can be overwritten, any additional functions
	  // provided in readers will be merged with
	  // this default object.
	  readers: {
		_rowReader: null,
		_attributeReader: defaultAttributeReader
	  },
	  params: {
		dynatable: 'dynatable',
		queries: 'queries',
		sorts: 'sorts',
		page: 'page',
		perPage: 'perPage',
		offset: 'offset',
		records: 'records',
		record: null,
		queryRecordCount: 'queryRecordCount',
		totalRecordCount: 'totalRecordCount'
	  }
	})
});
</script>

</head>

<body>
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

</body>

</html>