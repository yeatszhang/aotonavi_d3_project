<?php
require_once 'GPSconvertor.php';
require_once 'MysqlConnection.php';
require_once 'Tools.php';
include_once 'params.php';

class Point{
	var $x;
	var $y;
	var $extra;
	function Point($dit, $sinr,$longitude,$latitude){
		$this->x = $dit;
		$this->y = $sinr;
		$this->extra = array($longitude,$latitude);
	}
}
class Band{
	var $from;
	var $to;
	var $pci;
	function Band($dit1,$dit2,$p){
		$this->from = $dit1;
		$this->to = $dit2;
		$this->pci = $p;
	}
}

function distance($lon1, $lat1, $lon2, $lat2){ 
return (2*ATAN2(SQRT(SIN(($lat1-$lat2)*PI()/180/2)*SIN(($lat1-$lat2)*PI()/180/2)+COS($lat2*PI()/180)*COS($lat1*PI()/180)*SIN(($lon1-$lon2)*PI()/180/2)*SIN(($lon1-$lon2)*PI()/180/2)),SQRT(1-SIN(($lat1-$lat2)*PI()/180/2)*SIN(($lat1-$lat2)*PI()/180/2)+COS($lat2*PI()/180)*COS($lat1*PI()/180)*SIN(($lon1-$lon2)*PI()/180/2)*SIN(($lon1-$lon2)*PI()/180/2))))*6371004;
}

$data =  json_decode($_POST["data"],true);
$points_sinr_ave = array();
$points_sinr_var = array();
$points_sinr_scatter = array();
$points_rsrp_ave = array();
$points_rsrp_var = array();
$points_rsrp_scatter = array();
$points_tput_ave = array();
$points_tput_var = array();
$points_tput_scatter = array();
$points_band = array();
$dit1 = $dit2 = $pci = 0;
foreach($data as $d){
	$gps = GPS2Grid($d['lng'],$d['lat']);
	$lng = $gps['lng'];
	$lat = $gps['lat'];
	$result = mysql_query("SELECT * FROM baidubasedinfo") or die("Invalid query of selecting * from baidubased: " . mysql_error());
	while($row = mysql_fetch_array($result)){
		if(distance($lng,$lat,$row['Longitude'],$row['Latitude'])<=$_config['params']['ACCURACY_DEFAULT']){
			//Add Average values and Variance values to arrays
			array_push($points_sinr_ave,new Point($d['dit'],floatval($row['PCC_RANK1_SINR_AVERAGE']),$row['Longitude'],$row['Latitude']));
			array_push($points_sinr_var,new Point($d['dit'],floatval($row['PCC_RANK1_SINR_VARIANCE']),$row['Longitude'],$row['Latitude']));
			array_push($points_rsrp_ave,new Point($d['dit'],floatval($row['SERVING_CELL_RSRP_AVERAGE']),$row['Longitude'],$row['Latitude']));
			array_push($points_rsrp_var,new Point($d['dit'],floatval($row['SERVING_CELL_RSRP_VARIANCE']),$row['Longitude'],$row['Latitude']));
			array_push($points_tput_ave,new Point($d['dit'],round(floatval($row['PDCP_Throughput_DL_AVERAGE'])/1024,1),$row['Longitude'],$row['Latitude']));
			array_push($points_tput_var,new Point($d['dit'],round(floatval($row['PDCP_Throughput_DL_VARIANCE'])/1024/1024,1),$row['Longitude'],$row['Latitude']));
			
			//Add Scatter dots to arrays
			$gid = $row['Gridid'];
			$processed = mysql_fetch_array(mysql_query("SELECT * FROM processedinfo WHERE id = '$gid'"));
			$numarr = String2Int($processed['originalid']);
			foreach($numarr as $num){
				$s = mysql_fetch_array(mysql_query("SELECT * FROM originalinfo WHERE id = '$num'"));
				array_push($points_sinr_scatter,new Point($d['dit'],floatval($s['PCC_RANK1_SINR']),$row['Longitude'],$row['Latitude']));
				array_push($points_rsrp_scatter,new Point($d['dit'],floatval($s['Serving_Cell_RSRP']),$row['Longitude'],$row['Latitude']));
				array_push($points_tput_scatter,new Point($d['dit'],round(floatval($s['PDCP_Throughput_DL'])/1024,1),$row['Longitude'],$row['Latitude']));
			}
			
			//Add Bands to arrays
			if($row['PCI']!=$pci){ //new band 
				array_push($points_band,new Band($dit1,$dit2,$pci));
				$dit1 = $dit2 = $d['dit'];
				//echo $pci . "   ";
				$pci = $row['PCI'];
				//echo $pci . "   ";
			}else{ //old band
				$dit2 = $d['dit'];
				//echo $pci . "   ";
			}
			
			break;
		}
	}
}
array_push($points_band,new Band($dit1,$dit2,$pci));
array_shift($points_band);
echo json_encode(array($points_sinr_ave,$points_sinr_var,$points_sinr_scatter,$points_rsrp_ave,$points_rsrp_var,$points_rsrp_scatter,$points_tput_ave,$points_tput_var,$points_tput_scatter,$points_band));
?>