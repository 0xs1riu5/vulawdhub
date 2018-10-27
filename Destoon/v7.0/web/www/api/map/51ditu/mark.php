<?php
require '../../../common.inc.php';
include DT_ROOT.'/api/map/51ditu/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,]{13,17}$/", $map) or $map = $map_mid;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DT_CHARSET;?>" />
<title>灵图 - 双击标注位置</title>
<style type="text/css">
html{height:100%}
body{height:100%;margin:0px;padding:0px}
#container{height:100%}
</style>
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/config.js"></script>
<script type="text/javascript" src="http://api.51ditu.com/js/maps.js"></script>
<?php if($map == $map_mid) { ?>
<script type="text/javascript" src="http://api.51ditu.com/js/ipposition.js"></script>
<?php } ?>
<script type="text/javascript">
var map;
var dblclickListener=null;
function onLoad() {
<?php if($map == $map_mid) { ?>
	//自动定位(注:误差较大)
    var ip= new LTIpPosition();  
    ip.getIpPosition();
	eval("var ip_json="+ip.error);
	var center = new LTPoint(ip_json.lo, ip_json.la);
<?php } else { ?>
	var center = new LTPoint(<?php echo $map;?>);
<?php } ?>
	map=new LTMaps(document.getElementById("container")); 
	map.centerAndZoom(center,2); 
	map.addControl(new LTStandMapControl()); 
	map.handleMouseScroll(true);
<?php if($map != $map_mid) { ?>
	var option = new LTMarkerOptions(); 
	option.point = center;
	var marker= new LTMarkerOverlay(option);
	map.overlayManager.addOverLay(marker);
<?php } ?>
	addDblclickEvent();
}    
function addDblclickEvent() {
	if(dblclickListener==null) dblclickListener=LTEvent.addListener(map,"dblclick",dbclickCallBack); 
} 
function dbclickCallBack(point) {
	var xy = point.getLongitude().toString()+','+point.getLatitude().toString();
	window.parent.document.getElementById('map').value = xy;
	window.parent.cDialog();
}
window.onload = onLoad;
</script> 
</head>
<body>
<div id="container"></div>
</body>
</html>