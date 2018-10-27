<?php
require '../../../common.inc.php';
include DT_ROOT.'/api/map/qq/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,]{17,37}$/", $map) or $map = $map_mid;
$company = isset($company) ? trim(strip_tags($company)) : '';
$address = isset($address) ? trim(strip_tags($address)) : '';
($company && $address) or exit;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DT_CHARSET;?>" />
<title>Tencent Map</title>
<style type="text/css">
html{height:100%}
body{height:100%;margin:0px;padding:0px}
#container{height:100%}
</style>
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/config.js"></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script type="text/javascript">
var init = function() {
	var center=new qq.maps.LatLng(<?php echo $map;?>);
    var map = new qq.maps.Map(document.getElementById("container"),{
        center:center,
        zoom:16
    });
    var label = new qq.maps.Label({
        position: center,
        map: map,
        content:'<strong><?php echo $company;?></strong><br/><?php echo $address;?>'
    });
}
window.onload = init;
</script>
</head>
<body>
<div id="container"></div>
</body>
</html>