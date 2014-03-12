<?php
require_once 'Tools.php';
include_once 'params.php';
/**
 * 连接数据库
 * @throws Exception
 * @return mysqli
 */
function db_connect() {
	global $_config;
	$result = new mysqli ( $_config ['db'] ['dbhost'], $_config ['db'] ['dbuser'], $_config ['db'] ['dbpw'], $_config ['db'] ['dbname'] );
	if (! $result) {
		throw new Exception ( 'Could not connect to database server' );
	} else {
		return $result;
	}
}
/**
 * function to query the sql string and return the result
 * @param unknown $sqlString
 */
function sql_query($sqlString) {
	$conn = db_connect ();
	$result = $conn->query ( $sqlString );
	return $result;
}

/**
 * 清空数据库
 */
function database_clear() {
	$conn = db_connect ();
	$conn->query ( "truncate baidubasedinfo" );
	$conn->query ( "truncate originalinfo" );
	$conn->query ( "truncate processedinfo" );
}

/**
 * 上传原始数据
 * @param unknown $upfile
 * @param unknown $people
 */
function originalinfo_upload($upfile, $people) {
	$conn = db_connect ();
	$handle = fopen ( $upfile, "r" );
	if ($head = fgetcsv ( $handle )) {
		$col = array ();
		for($c = 0; $c < count ( $head ); $c ++) {
			$col [$head [$c]] = $c;
		}
		while ( $data = fgetcsv ( $handle ) ) {
			foreach ( $data as &$column ) {
				// if (empty ( $column ))
				// $column = 'NULL';
				$column = trim ( $column );
			}
			$datetime = Empty2Zero ( $data [$col ['Date & Time']] );
			$longitude = Empty2Zero ( $data [$col ['Longitude']] );
			$latitude = Empty2Zero ( $data [$col ['Latitude']] );
			if (! ($longitude && $latitude))
				continue;
			$gpshight = Empty2Zero ( $data [$col ['GPS Hight']] );
			$gpsspeed = Empty2Zero ( $data [$col ['GPS Speed']] );
			$gpssatellites = Empty2Zero ( $data [$col ['GPS Satellites']] );
			$gpsheading = Empty2Zero ( $data [$col ['GPS Heading']] );
			$pccaveragesinr = Empty2Zero ( $data [$col ['PCC Average SINR(Normal/csi-MeasSubframeSet1)(dB)']] );
			$pccrank1sinr = Empty2Zero ( $data [$col ['PCC RANK1 SINR(Normal/csi-MeasSubframeSet1)(dB)']] );
			$pccrank2sinr1 = Empty2Zero ( $data [$col ['PCC RANK2 SINR1(Normal/csi-MeasSubframeSet1)(dB)']] );
			$servingcellpci = Empty2Zero ( $data [$col ['Serving Cell PCI']] );
			$servingcellrsrp = Empty2Zero ( $data [$col ['Serving Cell RSRP(dBm)']] );
			$servingcellrsrq = Empty2Zero ( $data [$col ['Serving Cell RSRQ(dB)']] );
			$servingcellrssi = Empty2Zero ( $data [$col ['Serving Cell RSSI(dBm)']] );
			$throughputul = Empty2Zero ( $data [$col ['PDCP Throughput UL(kbit/s)']] );
			$throughputdl = Empty2Zero ( $data [$col ['PDCP Throughput DL(kbit/s)']] ) * $people;
			
			$sqlstr1 = "INSERT into originalinfo values (
			NULL,
			'$datetime',
			'$longitude',
			'$latitude',
			'$gpshight',
			'$gpsspeed',
			'$gpssatellites',
			'$gpsheading',
			'$pccaveragesinr',
			'$pccrank1sinr',
			'$pccrank2sinr1',
			'$servingcellpci',
			'$servingcellrsrp',
			'$servingcellrsrq',
			'$servingcellrssi',
			'$throughputul',
			'$throughputdl')";
			$conn->query ( $sqlstr1 ) or die ( $conn->error );
		}
		echo 'upload complete1';
	} else
		echo "upload error";
	fclose ( $handle );
}

/**
 * 返回全部原始数据
 * 
 * @throws Exception
 * @return multitype:unknown
 */
function originalinfo_get() {
	$conn = db_connect ();
	$result = $conn->query ( "select * from baidubasedinfo" );
	if (! $result) {
		throw new Exception ( 'ԭʼ��ݶ�ȡʧ�ܡ�' );
	}
	if ($result->num_rows <= 0) {
		throw new Exception ( 'ԭʼ���Ϊ�ա�' );
	}
	$infos = array ();
	for($i = 0; $row = $result->fetch_assoc (); $i ++) {
		$infos [$i] = $row;
	}
	return $infos;
	// $json_string = json_encode($infos);
	// echo " var originaldata = $json_string;";
}

/**
 * 学习D3测试函数
 * Enter description here ...
 * @throws Exception
 */
function d3_originalinfo_get($num) {
	$conn = db_connect ();
	if($num == 1)
	{$result = $conn->query ( "select DateTime dt,PCC_Average_SINR pas from originalinfo limit 100 " );}
	else if($num == 2){
		$result = $conn->query ( "select Serving_Cell_PCI pci, count(*) num from originalinfo group by  Serving_Cell_PCI" );
	}
	if (! $result) {
		throw new Exception ( 'ԭʼ��ݶ�ȡʧ�ܡ�' );
	}
	if ($result->num_rows <= 0) {
		throw new Exception ( 'ԭʼ���Ϊ�ա�' );
	}
	$infos = array ();
	for($i = 0; $row = $result->fetch_assoc (); $i ++) {
		$infos [$i] = $row;
	}
	return $infos;
	// $json_string = json_encode($infos);
	// echo " var originaldata = $json_string;";
}

function get_detial_data($longi, $lati) {
	$conn = db_connect ();
	$result = $conn->query ( "SELECT originalid FROM processedinfo WHERE id=(select gridid from baidubasedinfo where Longitude=$longi and Latitude=$lati)" );
	if (! $result) {
		throw new Exception ( 'ԭʼ��ݶ�ȡʧ�ܡ�' );
	}
	if ($result->num_rows <= 0) {
		throw new Exception ( 'ԭʼ���Ϊ�ա�' );
	}
	$data = $result->fetch_assoc ();
	$infos = $data ['originalid'];
	$originalid_arr = String2Int ( $infos );
	$query_string = 'select * from originalinf where id in (';
	$id_string = implode ( ',', $originalid_arr );
	$data_arr = $conn->query ( "select * from originalinfo where id in ($id_string)" );
	$infos = array ();
	for($i = 0; $row = $data_arr->fetch_assoc (); $i ++) {
		$infos [$i] = $row;
	}
	return $infos;
}

/**
 * 获得pci凸包数组，如果传入0返回所有PCI凸包数组，否则返回传入pci凸包数组
 * @param int $num 传入的pci
 * @throws Exception
 * @return multitype:unknown
 */
function get_region_data() {
	$k = 0;
	$conn = db_connect ();
	$PCI_list = get_PCI_list(); 
	$arrayPro = array();
	for($j = 0; $j < count ( $PCI_list ); $j ++) {
		$PCI = $PCI_list [$j];
		$infos = array();

		$point = array();
		$result = $conn->query ( "SELECT Latitude as y,Longitude as x FROM baidubasedinfo where PCI=$PCI" );
		if (! $result) {
			throw new Exception ( '获取数据失败' );
		}
		if ($result->num_rows <= 0) {
			continue;
		}
		
		for($i = 0; $row = $result->fetch_assoc (); $i ++) {
			$infos [$i] = $row;
		}
		
		// 根据pci将正确的基站坐标加入到凸包的数组中
		$station = $conn->query ( "SELECT * FROM `basestation` WHERE PCI1 = $PCI or PCI2 = $PCI or PCI3 = $PCI" );
		if($row = $station->fetch_assoc ()) {
			$point ['x'] = $row['Longitude'];
			$point ['y'] = $row['Latitude'];
			array_push ( $infos, $point );
		}
		// end
		$arrayPro[$k]['pci'] = $PCI;
		$arrayPro[$k]['pointList'] = GetRegion($infos);
		$arrayPro[$k]['color'] = '#'.rcolor().rcolor().rcolor();
		$k ++;
	}
	return $arrayPro;
}

function rcolor() {
	$rand = rand(0,255);//����ȡ0--255������
	return sprintf("%02X","$rand");//���ʮ����Ƶ�������д��ĸ
}

/**
 * ����pci���
 * 
 * @throws Exception
 * @return unknown
 */
function get_PCI_list() {
	$conn = db_connect ();
	$result = $conn->query ( "SELECT distinct PCI FROM baidubasedinfo where PCI!=0" );
	if (! $result) {
		throw new Exception ( '原始数据读取失败。' );
	}
	if ($result->num_rows <= 0) {
		throw new Exception ( '原始数据为空。' );
	}
	
	for($i = 0; $row = $result->fetch_assoc (); $i ++) {
		$infos [$i] = $row ['PCI'];
	}
	return $infos;
}

/**
 * return the base station data list
 * 
 * @throws Exception
 * @return unknown
 */
function getBasestation() {
	$conn = db_connect ();
	$result = $conn->query ( "SELECT * FROM basestation " );
	if (! $result) {
		throw new Exception ( '获取基站数据失败' );
	}
	if ($result->num_rows <= 0) {
		throw new Exception ( '基站数据为空' );
	}
	for($i = 0; $row = $result->fetch_assoc (); $i ++) { // import the data from database to array infos
		$infos [$i] = $row;
	}
	return $infos;
}

 
/**
 * function to insert the basestation to the database
 * @param string $lng
 * @param string $lat
 * @param string $r
 * @param string $d1
 * @param string $d2
 * @param string $d3
 * @param string $d4
 * @param string $d5
 * @param string $d6
 */
function insertBasestation($lng, $lat, $r, $n,$pci1, $d1, $d2,$pci2, $d3, $d4,$pci3, $d5, $d6) {
	$queryString = "INSERT INTO basestation (Longitude, Latitude,Radius,Name,PCI1,Degree1,Degree2,PCI2,Degree3,Degree4,PCI3,Degree5,Degree6) values ('$lng', '$lat', '$r', '$n','$pci1', '$d1', '$d2','$pci2', '$d3', '$d4','$pci3', '$d5', '$d6')";
	echo $queryString;
	$result = sql_query($queryString);
	if (! $result) {
		throw new Exception ( '插入数据失败' );
	}
}

/**
 * function to upadate the basestation to the database
 * @param string $lng
 * @param string $lat
 * @param string $r
 * @param string $d1
 * @param string $d2
 * @param string $d3
 * @param string $d4
 * @param string $d5
 * @param string $d6
 */
function updateBasestation($stationID, $lng, $lat, $r, $n,$pci1, $d1, $d2,$pci2, $d3, $d4,$pci3, $d5, $d6) {
	$queryString = "UPDATE basestation SET Longitude = '" . $lng .
			"', Latitude ='" . $lat .
			"', Radius = '" . $r .
			"', Name = '" . $n .
			"', PCI1 = '" . $pci1 .
			"', Degree1 = '" . $d1 .
			"', Degree2 = '" . $d2 .
			"', PCI2 = '" . $pci2 .
			"', Degree3 = '" . $d3 .
			"', Degree4 = '" . $d4 .
			"', PCI3 = '" . $pci3 .
			"', Degree5 = '" . $d5 .
			"', Degree6 = '" . $d6 .
			"' WHERE Id =" . $stationID;
	//echo $queryString;
	$result = sql_query($queryString);
	if (! $result) {
		throw new Exception ( '更新数据失败' );
	}
}

/**
 * get data of the station which id = $stationID
 * @param unknown $stationID
 * @throws Exception
 */
function get_wish_by_wish_id($stationID) {
	$result = sql_query("SELECT Id,Longitude, Latitude,Radius,Name,PCI1,Degree1,Degree2,PCI2,Degree3,Degree4,PCI3,Degree5,Degree6 FROM basestation WHERE Id = " . $stationID);
	if (! $result) {
		throw new Exception ( '获取数据失败' );
	}
	if ($result->num_rows <= 0) {
		throw new Exception ( '数据为空' );
	}
	$row = $result->fetch_assoc ();
	return $row;
}

//delete the station which id = $stationID
function delete_basestation($stationID) {
	$result = sql_query("DELETE FROM basestation WHERE Id = " . $stationID);
	if (! $result) {
		throw new Exception ( '删除基站失败' );
	}
}

?>
