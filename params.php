<?php
define("DEPLOY","");
$_config = array();
// ----------------------------  CONFIG DB  ----------------------------- //
$_config['db']['dbhost'] = 'localhost';
$_config['db']['dbuser'] = 'root';
$_config['db']['dbpw'] = '';
$_config['db']['dbname'] = 'signalinfo';
// --------------------------  CONFIG PARAMS  --------------------------- //
$_config['params']['GREAT_CIRCLE_PERIMETER'] = 40075.13;
$_config['params']['ACCURACY_DEFAULT'] = 30;
$_config['params']['ERROR_MESSAGE'] = 'Error occurs when connecting to http://api.map.baidu.com/ag/coord/convert   Please check Internet connection status or refer to network admin. ';

?>