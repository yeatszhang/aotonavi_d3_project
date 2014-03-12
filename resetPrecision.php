<?php
require_once 'MysqlConnection.php';
include_once 'params.php';
set_time_limit(0);
if($_POST["precision"]==0){
	echo '精度不能为0';
}
else{
	mysql_query("truncate processedinfo");
	mysql_query("truncate baidubasedinfo");
	$str = file_get_contents("params.php");
	$str = str_replace('$_config[\'params\'][\'ACCURACY_DEFAULT\'] = ' . $_config['params']['ACCURACY_DEFAULT'] ,'$_config[\'params\'][\'ACCURACY_DEFAULT\'] = ' . $_POST["precision"],$str);
	file_put_contents("params.php",$str);
	file_get_contents("http://localhost/signalinfo/CalcAverVari.php");
	echo "重新生成数据库成功". "<a href=\"http://localhost/signalinfo/index.html\">回到主页</a>";
}
?>