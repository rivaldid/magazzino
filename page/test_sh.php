<?php

myquery::mysession_open($db);

echo session_id();

$_SESSION['puzza'] = 'lacacca';
$_SESSION['pazza'] = 'lafoca';
$_SESSION['pizza'] = 'palomba';

print_r($_SESSION);

myquery::mysession_close($db);

?>