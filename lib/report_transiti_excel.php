<?php

require 'class_config.php';
require '../config.php';
require 'vars.php';
require 'functions.php';
require 'class_pdo.php';

$db = myquery::start();
if (!(isset($_SERVER['HTTP_REFERER']))) $_SERVER['HTTP_REFERER'] = null;

myquery::logger($db);

$filename = "report_mensile_transiti__".date('m', strtotime("last month"));
$result = myquery::report_transiti_mensile($db);
$file_ending = "xls";

//header info for browser
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache");
header("Expires: 0");

/*******Start of Formatting for Excel*******/
$sep = "\t"; //tabbed character

echo "Data".$sep;
echo "Account".$sep;
echo "Direzione".$sep;
echo "Posizione".$sep;
echo "Merce".$sep;
echo "Quantita'".$sep;
echo "Documento".$sep;
echo "Note".$sep;
echo "Ordine".$sep;
print("\n");

foreach ($result as $row) {
	$schema_insert = "";
        
    $schema_insert .= $row['data'].$sep;
    $schema_insert .= $row['rete'].$sep;
    $schema_insert .= $row['status'].$sep;
    $schema_insert .= $row['posizione'].$sep;
    $schema_insert .= $row['tags'].$sep;
    $schema_insert .= $row['quantita'].$sep;
    $schema_insert .= $row['riferimento'].$sep;
    $schema_insert .= $row['note'].$sep;
    $schema_insert .= $row['ordine'].$sep;
        
	$schema_insert = str_replace($sep."$", "", $schema_insert);
	$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
	$schema_insert .= "\t";
	print(trim($schema_insert));
	print "\n";
}
?>
