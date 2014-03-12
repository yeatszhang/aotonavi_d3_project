<?php
require_once 'GPSconvertor.php';
require_once 'MysqlConnection.php';
// set_time_limit(0);

// $url = "http://map.yanue.net/gpsApi.php?lat=" . $pro_lat . "&lng=" . $pro_lng;
// $baidu_lat = GPS2Grid($ret['baidu']['lat'],"lat");
// $baidu_lng = GPS2Grid($ret['baidu']['lng'],"lng");	

function ToBaidu($pro_lng,$pro_lat){
	if($pro_lng==0||$pro_lat==0)
		return array("lng"=>0, "lat"=>0);
	return array("lng"=>$pro_lng+0.01225,"lat"=>$pro_lat+0.00745);
	// $urlx = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=" . $pro_lng . "&y=" . $pro_lat;
	// $ret_json = file_get_contents($urlx);
	// $ret = json_decode($ret_json,true);
	// if($ret['error']==0){
		// return array("lng"=>base64_decode($ret['x']), "lat"=>base64_decode($ret['y']));
	// }else{
		// return array("lng"=>0, "lat"=>0);
	// }
}
?>