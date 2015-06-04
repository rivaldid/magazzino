<?php

// PERCORSI


define("registro","dati/registro/");
define("registro_mds","dati/registro_mds/");
define("splog","dati/log/sp.log");
//define("accesslog",$_SERVER['DOCUMENT_ROOT']."/GMDCTO/log/login.log");
define("lib_mpdf57","lib/MPDF57/mpdf.php");
define("ricerche","dati/ricerche/");

// DB
/*define("host","localhost");
define("user","magazzino");
define("pass","magauser");
define("db","magazzino");*/

// db
Config::write('db.host', '127.0.0.1');
Config::write('db.port', '5432');
Config::write('db.basename', 'magazzino');
Config::write('db.user', 'magazzino');
Config::write('db.password', 'magauser');

// home
CONFIG::write('home','dati');

?>
