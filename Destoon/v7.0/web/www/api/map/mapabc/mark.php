<?php
require '../../../common.inc.php';
include DT_ROOT.'/api/map/mapabc/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,]{17,21}$/", $map) or $map = $map_mid;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DT_CHARSET;?>" />
<title>MapABC - 点击标注位置</title>
<style type="text/css">
html{height:100%}
body{height:100%;margin:0px;padding:0px}
#map{height:100%}
</style>
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/config.js"></script>
<script type="text/javascript" src="http://app.mapabc.com/apis?&t=flashmap&v=2.4&key=<?php echo $map_key;?>"></script>
<script type="text/javascript">
var mapObj=null;
function mapInit() {
	var mapoption = new MMapOptions();
	mapoption.toolbar = MConstants.ROUND; //设置地图初始化工具条，ROUND:新版圆工具条
	mapoption.overviewMap = MConstants.SHOW; //设置鹰眼地图的状态，SHOW:显示，HIDE:隐藏（默认）
	mapoption.scale = MConstants.SHOW; //设置地图初始化比例尺状态，SHOW:显示（默认），HIDE:隐藏。
	mapoption.zoom = 13;//要加载的地图的缩放级别
	mapoption.center = new MLngLat(<?php echo $map;?>);//要加载的地图的中心点经纬度坐标
	mapoption.language = MConstants.MAP_CN;//设置地图类型，MAP_CN:中文地图（默认），MAP_EN:英文地图
	mapoption.fullScreenButton = MConstants.SHOW;//设置是否显示全屏按钮，SHOW:显示（默认），HIDE:隐藏
	mapoption.centerCross = MConstants.SHOW;//设置是否在地图上显示中心十字,SHOW:显示（默认），HIDE:隐藏
	mapoption.toolbarPos=new MPoint(20,20); //设置工具条在地图上的显示位置
	mapObj = new MMap("map", mapoption); //地图初始化
	mapObj.addEventListener(mapObj,MConstants.MOUSE_CLICK,MclickMouse);//鼠标点击事件
}
function MclickMouse(param){	
	window.parent.document.getElementById('map').value = param.eventX+','+param.eventY;
	window.parent.cDialog();
}
</script>
</head>
<body onload="mapInit();">
<div id="map"></div>
</body>
</html>