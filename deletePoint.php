<?php
require_once 'MysqlConnection.php';
require_once 'Tools.php';
$grid = intval($_GET['grid']);
mysql_query("DELETE FROM baidubasedinfo WHERE Gridid=$grid");
$row = mysql_fetch_array(mysql_query("SELECT * FROM processedinfo WHERE id=$grid"));
echo $row['originalid'];
$id_arr = String2Int($row['originalid']);
foreach($id_arr as $id){
	mysql_query("DELETE FROM originalinfo WHERE id=$id");
}
mysql_query("DELETE FROM processedinfo WHERE id=$grid");
?>