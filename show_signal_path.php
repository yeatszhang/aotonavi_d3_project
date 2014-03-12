<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
body,html {
	width: 100%;
	height: 100%;
	overflow: hidden;
	margin: 0;
}

#l-map {
	margin-right: 300px;
	width:100%;
	height: 100%;
	overflow: hidden;
}

#result {
	border-left: 1px dotted #999;
	height: 100%;
	width: 0;
	position: absolute;
	top: 0px;
	right: 0px;
	font-size: 12px;
}

dl,dt,dd,ul,li {
	margin: 0;
	padding: 0;
	list-style: none;
}

dt {
	font-size: 14px;
	font-family: "微软雅黑";
	font-weight: bold;
	border-bottom: 1px dotted #000;
	padding: 5px 0 5px 5px;
	margin: 5px 0;
}

dd {
	padding: 5px 0 0 5px;
}

li {
	line-height: 28px;
}

.cityList {
	height: 320px;
	width: 372px;
	overflow-y: auto;
}

.sel_container {
	z-index: 9999;
	font-size: 12px;
	position: absolute;
	right: 300px;
	top: 0px;
	width: 140px;
	background: rgba(255, 255, 255, 0.8);
	height: 30px;
	line-height: 30px;
	padding: 5px;
}

.map_popup {
	position: absolute;
	z-index: 200000;
	width: 382px;
	height: 344px;
	right: 320px;
	top: 40px;
}

.map_popup .popup_main {
	background: #fff;
	border: 1px solid #8BA4D8;
	height: 100%;
	overflow: hidden;
	position: absolute;
	width: 100%;
	z-index: 2;
}

.map_popup .title {
	background: url("http://map.baidu.com/img/popup_title.gif") repeat
		scroll 0 0 transparent;
	color: #6688CC;
	font-size: 12px;
	font-weight: bold;
	height: 24px;
	line-height: 25px;
	padding-left: 7px;
}

.map_popup button {
	background: url("http://map.baidu.com/img/popup_close.gif") no-repeat
		scroll 0 0 transparent;
	border: 0 none;
	cursor: pointer;
	height: 12px;
	position: absolute;
	right: 4px;
	top: 6px;
	width: 12px;
}
</style>
<script type="text/javascript"
	src="http://api.map.baidu.com/api?v=2.0&ak=A4749739227af1618f7b0d1b588c0e85"></script>
<!-- ���ذٶȵ�ͼ��ʽ��Ϣ���� -->
<script type="text/javascript"
	src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
<link rel="stylesheet"
	href="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css" />
<!-- ���س����б� -->
<script type="text/javascript"
	src="http://api.map.baidu.com/library/CityList/1.2/src/CityList_min.js"></script>

		<!-- Add jQuery library -->
	<script type="text/javascript" src="fancybox/lib/jquery-1.10.1.min.js"></script>

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<title>叠加麻点图Demo</title>
</head>
<body>
	<div id="l-map"></div>
	<div id="result">
		<dl>
			<dt>control</dt>
			<dd>
				<ul>
					<li><button id="open">open</button>
						<button id="close">close</button>
				
				</ul>
			</dd>
		</dl>
		<dl>
			<dd>
				<div id="information">123123123</div>
			</dd>
		</dl>

	</div>

<a id="autostart" style="display:none" href="" > </a> 
</body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>
<script type="text/javascript">
//百度地图API功能
var map = new BMap.Map("l-map");         // 创建地图实例
var point = new BMap.Point(116.633604,40.312968); // 创建点坐标
map.centerAndZoom(point, 40);                 // 初始化地图，设置中心点坐标和地图级别
map.enableScrollWheelZoom();
map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
var myPoligons = [];
//在地图上显示点信息
$(document).ready(function(){
	$.getJSON("data_output.php?type=path&&file=<?php echo $_GET['file']?>",function(data){
		for(var i=0;i<data.pathdata.length;i++){
			var sinr = data.pathdata[i][8];
		 	var longi = data.pathdata[i][1];
		 	var lati = data.pathdata[i][2];
		 	if(sinr!='NULL' && longi !='NULL' && lati !='NULL'){
				var circle_color = getColor(sinr);
				var centerpoint = new BMap.Point(parseFloat(longi)+0.01225,parseFloat(lati)+0.00745);
				//var centerpoint = new BMap.Point(longi,lati);
				var circle = new BMap.Circle(centerpoint,2,{strokeColor:circle_color, strokeWeight:3, strokeOpacity: 1, fillColor:circle_color, fillOpacity:1});
				myPoligons.push(circle);
				map.addOverlay(circle);	
		  	} 
		 }
		 for(var i=0;i<data.pathdata.length;i++){
			(function(){
				var info=data.pathdata[i];
				myPoligons[i].addEventListener('mouseover', function(){
					var centerpoint = this.getCenter();
                        var hotSpot = new BMap.Hotspot(centerpoint, {text: "PCI: "+info[10]+
																	"<br />SINR平均值: "+info[8]+
																	"<br />RSRP平均值: "+info[11]+
																	"<br />RSRQ平均值: "+info[12]+
																	"<br />RSSI平均值: "+info[13]+
																	"<br />Throughput_UL平均值: "+info[14]});
                        map.addHotspot(hotSpot);
	    		}); 
				myPoligons[i].addEventListener('mouseout',function(){});
			})();
		 }
 });
});
//  for($i=1;$i<count($data_array);$i++){
// 	$info = $data_array[$i][8];
// 	$longi = $data_array[$i][1];
// 	$lati = $data_array[$i][2];
// 	if($info!='NULL' && $longi !='NULL' && $lati !='NULL'){
//   		echo "
//  		setTimeout(\"show_point($info,$longi,$lati)\",200);";
//  	} 
//  }

function callback(e)//单击热点图层
{
  var customPoi = e.customPoi,
		  str = [];
		str.push("address = " + customPoi.address);
		str.push("phoneNumber = " + customPoi.phoneNumber);
        var content = '<p style="width:280px;margin:0;line-height:20px;">地址：' + customPoi.address + '</p>';
        var searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
            title: customPoi.title, //标题
            width: 290, //宽度
            height: 40, //高度
            panel : "panel", //检索结果面板
            enableAutoPan : true, //自动平移
            enableSendToPhone: true, //是否显示发送到手机按钮
            searchTypes :[
                BMAPLIB_TAB_SEARCH,   //周边检索
                BMAPLIB_TAB_TO_HERE,  //到这里去
                BMAPLIB_TAB_FROM_HERE //从这里出发
            ]
        });


        var point = new BMap.Point(customPoi.point.lng, customPoi.point.lat);
        searchInfoWindow.open(point);
}




//获得颜色
function getColor(number){
	if(number <= -5 )
		return 'blue';
	else if(number >-5&& number<=0 )
		return '#4169E1';
	else if(number >0&& number<=5 )
        return '#00FFFF';	
	else if(number >5&& number<=10 )
        return '#00FF00';	
	else if(number >10&& number<=15 )
        return '#FFFF00';
	else if(number >15&& number<=20 )
        return '#FA8072';	
	else if(number >20&& number<=25 )
        return '#FF4500';	
	else if(number >25&& number<=30 )
        return 'red';
	else
		return '#DC143C';
}

</script>
</html>