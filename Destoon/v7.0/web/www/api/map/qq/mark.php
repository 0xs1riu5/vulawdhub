<?php
require '../../../common.inc.php';
include DT_ROOT.'/api/map/qq/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,]{17,37}$/", $map) or $map = $map_mid;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DT_CHARSET;?>" />
<title>腾讯地图 - 双击标注位置</title>
<style type="text/css">
html{height:100%}
body{height:100%;margin:0px;padding:0px}
#container{height:100%}
</style>
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/config.js"></script>
<script type="text/javascript" charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script type="text/javascript">
var init = function() {
	var center=new qq.maps.LatLng(<?php echo $map;?>);
    var map = new qq.maps.Map(document.getElementById("container"),{
        center:center,
        zoom:15
    });
<?php if($map == $map_mid) { ?>
	//自动定位
    citylocation = new qq.maps.CityService({
        complete : function(result){
            map.setCenter(result.detail.latLng);
        }
    });
    citylocation.searchLocalCity();
<?php } else { ?>
    setTimeout(function(){
        var marker=new qq.maps.Marker({
            position:center,
			animation:qq.maps.MarkerAnimation.DROP,
            map:map
        });
    },1000);
<?php } ?>
    qq.maps.event.addListener(map, 'dblclick', function(event) {
		var xy = event.latLng.getLat()+','+event.latLng.getLng();
		window.parent.document.getElementById('map').value = xy;
		window.parent.cDialog();
    });
}
window.onload = init;
</script>
</head>
<body>
<div id="container"></div>
</body>
</html>