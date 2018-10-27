<?php
require '../../../common.inc.php';
include DT_ROOT.'/api/map/google/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,\-]{20,50}$/", $map) or $map = $map_mid;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-Type" content="text/html;charset=<?php echo DT_CHARSET;?>"/>
<title>Google Map - 点击标注位置</title>
<style type="text/css">
html{height:100%}
body{height:100%;margin:0px;padding:0px}
#map{height:100%}
</style>
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/config.js"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $map_key;?>" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
var points = [];
var markers = [];
var counter = 0;
var map = null;
function mapOnLoad() {
	if (GBrowserIsCompatible()) {
	var mapObj = document.getElementById("map");
	if (mapObj != "undefined" && mapObj != null) {
		map = new GMap2(document.getElementById("map"));
		map.setCenter(new GLatLng(<?php echo $map;?>), 13, G_NORMAL_MAP);
		map.addControl(new GLargeMapControl3D());
		map.addControl(new GMenuMapTypeControl());
		map.addControl(new GScaleControl());
		map.addOverlay(new GMarker(new GLatLng(<?php echo $map;?>)));
			GEvent.addListener(map, 'dblclick', function(overlay,point) {
				if(overlay) {
				} else if(point) {
					map.clearOverlays();
					map.addOverlay(new GMarker(point));
					try {
						//for(var x in point) alert(x+':'+point[x]);
						//var xy = point.toString();
						window.parent.document.getElementById('map').value = point.lat()+','+point.lng();
						window.parent.cDialog();
					} catch(e) {}
				}
			});
		}
	} else {
		alert("The map could not be displayed on your browser.");
	}
}
function createMarker(point, title, html, n, tooltip) {
	if(n >=0) { n = -1; }
	var marker = new GMarker(point,{'title': title});
	return marker;
}
function isArray(a) {return isObject(a) && a.constructor == Array;}
function isObject(a) {return (a && typeof a == 'object') || isFunction(a);}
function isFunction(a) {return typeof a == 'function';}
window.onload=mapOnLoad;
</script>
</head>
<body>
<script type="text/javascript">
if (GBrowserIsCompatible()) {
document.write('<div id="map" class="map" style="width:100%;height:100%;"></div>');
} else {
document.write('The map could not be displayed on your browser.');
}
</script>
</body>
</html>