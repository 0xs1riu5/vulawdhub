<?php
require '../../../common.inc.php';
include DT_ROOT.'/api/map/baidu/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,]{17,21}$/", $map) or $map = $map_mid;
$company = isset($company) ? trim(strip_tags($company)) : '';
$address = isset($address) ? trim(strip_tags($address)) : '';
($company && $address) or exit;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DT_CHARSET;?>" />
<title>Baidu Map</title>
<style type="text/css">
html{height:100%}
body{height:100%;margin:0px;padding:0px}
#container{height:100%}
</style>
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/config.js"></script>
<script src="http://api.map.baidu.com/api?v=1.2&services=false" type="text/javascript"></script>
</head>
<body>
<div id="container"></div>
<script type="text/javascript">
var map = new BMap.Map("container");
var point = new BMap.Point(<?php echo $map;?>);
map.centerAndZoom(point, 16);
map.addControl(new BMap.NavigationControl());
map.addControl(new BMap.ScaleControl());
map.addOverlay(new BMap.Marker(point));
var opts = {
  width : 250,
  height: 40,
  title : "<strong style=\"font-size:13px;\"><?php echo $company;?><\/strong>"
}
var infoWindow = new BMap.InfoWindow("<span style=\"font-size:13px;\"><?php echo $address;?><\/span>", opts);
map.openInfoWindow(infoWindow, map.getCenter());
</script>
</body>
</html>