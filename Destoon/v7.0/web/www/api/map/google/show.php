<?php
require '../../../common.inc.php';
include DT_ROOT.'/api/map/google/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,\-]{20,50}$/", $map) or $map = $map_mid;
$company = isset($company) ? trim(strip_tags($company)) : '';
$address = isset($address) ? trim(strip_tags($address)) : '';
($company && $address) or exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-Type" content="text/html;charset=<?php echo DT_CHARSET;?>"/>
<title>Google Map</title>
<style type="text/css">
body {margin:0;}
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
			map.addOverlay(new GMarker(new GLatLng(<?php echo $map;?>)));//
			var point = new GLatLng(<?php echo $map;?>);
			var marker = createMarker(point,"<?php echo $company;?>",[new GInfoWindowTab("Details", "<div id=\"gmapmarker\" style=\"font-size:13px;\"><strong><?php echo $company;?><\/strong><br \/><?php echo $address;?><\/div>")], 0, "");
			map.addOverlay(marker);
			GEvent.trigger(marker,"click");
		}
	} else {
		alert("The map could not be displayed on your browser.");
	}
}
function createMarker(point, title, html, n, tooltip) {
	if(n >=0) { n = -1; }
	var marker = new GMarker(point,{'title': title});
	if(isArray(html)) {GEvent.addListener(marker, "click", function() { marker.openInfoWindowTabsHtml(html); }); }
	else { GEvent.addListener(marker, "click", function() { marker.openInfoWindowHtml(html); }); }
	return marker;
}
function isArray(a) {return isObject(a) && a.constructor == Array;}
function isObject(a) {return (a && typeof a == 'object') || isFunction(a);}
function isFunction(a) {return typeof a == 'function';}
window.onload=mapOnLoad;
//]]>
</script>
</head>
<body>
<script type="text/javascript">
//<![CDATA[
if (GBrowserIsCompatible()) {
document.write('<div id="map" class="map" style="width:100%;height:300px;"></div>');
} else {
document.write('The map could not be displayed on your browser.');
}
//]]>
</script>
<noscript>The map requires javascript to be enabled.</noscript>
</body>
</html>