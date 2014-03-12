<?php
include_once 'params.php';
error_reporting(0);
$con = mysql_connect($_config['db']['dbhost'],$_config['db']['dbuser'],$_config['db']['dbpw']) or die('Could not connect' . mysql_error());
mysql_select_db($_config['db']['dbname'],$con);
?>