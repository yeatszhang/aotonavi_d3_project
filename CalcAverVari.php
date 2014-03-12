<?php
require_once 'GPSconvertor.php';
require_once 'MysqlConnection.php';
require_once 'ToBaidu.php';
require_once 'Tools.php';
include_once 'params.php';

set_time_limit(0);


$result = mysql_query("SELECT 
			id,
			Longitude, 
			Latitude, 
			Serving_Cell_PCI,
			PCC_RANK1_SINR, 
			PCC_RANK2_SINR1, 
			Serving_Cell_RSRP, 
			Serving_Cell_RSRQ, 
			Serving_Cell_RSSI,
			PDCP_Throughput_UL,
			PDCP_Throughput_DL
			FROM originalinfo") or die("Invalid query of selecting all data from original: " . mysql_error());

while($row = mysql_fetch_array($result))
{
	$gps = GPS2Grid($row['Longitude'],$row['Latitude']);
	$pro_lng = $gps['lng'];
	$pro_lat = $gps['lat'];
	
	$_exit = mysql_query("SELECT * FROM processedinfo WHERE  
			Longitude = '$pro_lng' 
			AND Latitude = '$pro_lat'") or die("Invalid query of selecting exact entry of grid: " . mysql_error());
	if(mysql_num_rows($_exit)==0) //no grid exits, will insert
	{
		// insert into processedinfo table(id, lng, lat, count, originalid)
		// $originalid = strval($row['id']);
		$originalid = $row['id'];
		
		//get baidubased point
		$baidu_arr = ToBaidu($pro_lng,$pro_lat);
		$baidu_lng = Empty2Zero($baidu_arr['lng']);
		$baidu_lat = Empty2Zero($baidu_arr['lat']);
		if($baidu_lng==0||$baidu_lat==0||Empty2Zero($row['Serving_Cell_PCI'])==0)
			continue;
		
		mysql_query("INSERT INTO processedinfo VALUES (
		NULL ,
		'$pro_lng' ,
		'$pro_lat' ,
		'1' , 
		'$originalid')") or die("Invalid query of inserting into processed: " . mysql_error());
		
		$affected_rows = mysql_affected_rows();
		echo "INSERT " . $affected_rows . " rows into processed.<br />";
		
		//insert into baidubasedinfo table(lng, lat, gridid, count, sinr_ave, sinr1_ave, rsrp_ave, rsrq_ave, rssi_ave, sinr_var, sinr1_var, rsrp_var, rsrq_var, rssi_var)
		
		$gridid = mysql_insert_id(); //last inserted id
		$pci = Empty2Zero($row['Serving_Cell_PCI']);
		$sinr = Empty2Zero($row['PCC_RANK1_SINR']);
		$sinr1 = Empty2Zero($row['PCC_RANK2_SINR1']);
		$rsrp = Empty2Zero($row['Serving_Cell_RSRP']);
		$rsrq = Empty2Zero($row['Serving_Cell_RSRQ']);
		$rssi = Empty2Zero($row['Serving_Cell_RSSI']);
		$tputul = Empty2Zero($row['PDCP_Throughput_UL']);
		$tputdl = Empty2Zero($row['PDCP_Throughput_DL']);
		
		mysql_query("INSERT INTO baidubasedinfo VALUES (
		'$baidu_lng' ,
		'$baidu_lat' ,
		'$gridid' ,
		'1' ,
		'$pci' ,
		'$sinr' , 
		'$sinr1' , 
		'$rsrp' , 
		'$rsrq' ,
		'$rssi' ,
		'$tputul' ,
		'$tputdl' ,
		'0' ,
		'0' ,
		'0' ,
		'0' ,
		'0' ,
		'0' ,
		'0')") or  defined("DEPLOY")? die($_config['params']['ERROR_MESSAGE']) : die("Invalid query of inserting into baidubased: " . mysql_error());
		
		$affected_rows = mysql_affected_rows();
		echo "INSERT " . $affected_rows . "rows into baidubased.<br />";
	}
	else // one grid exits, will update
	{	
		//update processedinfo table set(count, originalid)
		$pro = mysql_fetch_array($_exit);
		$pro_id = $pro['id'];
		$count = $pro['COUNT'];
		if(in_array($row['id'],String2Int($pro['originalid'])))
			continue;
		$originalid = $pro['originalid'] . "#" . $row['id'];
		mysql_query("UPDATE processedinfo SET
		COUNT = COUNT+1 ,
		originalid = '$originalid'
		WHERE Longitude = '$pro_lng' AND Latitude = '$pro_lat'") or die("Invalid query of updating processed: " . mysql_error());
		$affected_rows = mysql_affected_rows();
		echo "UPDATE " . $affected_rows . " rows of processed.<br />";
		
		//id of original items
		$id_arr = String2Int($originalid);
		foreach($id_arr as $id){
			echo $id . " ";
		}
		echo "<br />";
		
		//baidu items
		$_baidu = mysql_query("SELECT * FROM baidubasedinfo WHERE  Gridid='$pro_id'") or  defined("DEPLOY")? die($_config['params']['ERROR_MESSAGE']) : die("Invalid query of selecting exact entry of baidu: " . mysql_error());
		$baidu = mysql_fetch_array($_baidu);
		
		
		//update baidubasedinfo table set(count, sinr_ave, sinr1_ave, rsrp_ave, rsrq_ave, rssi_ave, sinr_var, sinr1_var, rsrp_var, rsrq_var, rssi_var)
		$sinr_ave = Empty2Zero(($baidu['PCC_RANK1_SINR_AVERAGE']*$count+$row['PCC_RANK1_SINR'])/($count+1));
		$sinr1_ave = Empty2Zero(($baidu['PCC_RANK2_SINR1_AVERAGE']*$count+$row['PCC_RANK2_SINR1'])/($count+1));
		$rsrp_ave = Empty2Zero(($baidu['SERVING_CELL_RSRP_AVERAGE']*$count+$row['Serving_Cell_RSRP'])/($count+1));
		$rsrq_ave = Empty2Zero(($baidu['SERVING_CELL_RSRQ_AVERAGE']*$count+$row['Serving_Cell_RSRQ'])/($count+1));
		$rssi_ave = Empty2Zero(($baidu['SERVING_CELL_RSSI_AVERAGE']*$count+$row['Serving_Cell_RSSI'])/($count+1));
		$tputul_ave = Empty2Zero(($baidu['PDCP_Throughput_UL_AVERAGE']*$count+$row['PDCP_Throughput_UL'])/($count+1));
		$tputdl_ave = Empty2Zero(($baidu['PDCP_Throughput_DL_AVERAGE']*$count+$row['PDCP_Throughput_DL'])/($count+1));
		
		$sinr_var = CalcVariance($id_arr,$count+1,$sinr_ave,0); //"PCC_RANK1_SINR"
		$sinr1_var = CalcVariance($id_arr,$count+1,$sinr1_ave,1); //"PCC_RANK2_SINR1"
		$rsrp_var = CalcVariance($id_arr,$count+1,$rsrp_ave,2); //"Serving_Cell_RSRP"
		$rsrq_var = CalcVariance($id_arr,$count+1,$rsrq_ave,3); //"Serving_Cell_RSRQ"
		$rssi_var = CalcVariance($id_arr,$count+1,$rssi_ave,4); //"Serving_Cell_RSSI"
		$tputul_var = CalcVariance($id_arr,$count+1,$tputul_ave,5); //"PDCP_Throughput_UL"
		$tputdl_var = CalcVariance($id_arr,$count+1,$tputdl_ave,6); //"PDCP_Throughput_DL"
		
		
		mysql_query("UPDATE baidubasedinfo SET 
		COUNT = COUNT+1 , 
		PCC_RANK1_SINR_AVERAGE = $sinr_ave , 
		PCC_RANK2_SINR1_AVERAGE = $sinr1_ave ,
		SERVING_CELL_RSRP_AVERAGE = $rsrp_ave ,
		SERVING_CELL_RSRQ_AVERAGE = $rsrq_ave ,
		SERVING_CELL_RSSI_AVERAGE = $rssi_ave ,
		PDCP_Throughput_UL_AVERAGE = $tputul_ave ,
		PDCP_Throughput_DL_AVERAGE = $tputdl_ave ,
		PCC_RANK1_SINR_VARIANCE = $sinr_var ,
		PCC_RANK2_SINR1_VARIANCE = $sinr1_var ,
		SERVING_CELL_RSRP_VARIANCE = $rsrp_var ,
		SERVING_CELL_RSRQ_VARIANCE = $rsrq_var ,
		SERVING_CELL_RSSI_VARIANCE = $rssi_var ,
		PDCP_Throughput_UL_VARIANCE = $tputul_var ,
		PDCP_Throughput_DL_VARIANCE = $tputdl_var
		WHERE Gridid='$pro_id'") or  defined("DEPLOY")? die($_config['params']['ERROR_MESSAGE']) : die("Invalid query of updating baidubased: " . mysql_error());
		$affected_rows = mysql_affected_rows();
		echo "UPDATE " . $affected_rows . " rows of baidubased.<br />";
	}
}
?>
