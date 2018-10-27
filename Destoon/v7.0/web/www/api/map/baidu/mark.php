<?php
require '../../../common.inc.php';
include DT_ROOT.'/api/map/baidu/config.inc.php';
$map = isset($map) ? $map : '';
preg_match("/^[0-9\.\,]{17,21}$/", $map) or $map = $map_mid;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DT_CHARSET;?>" />
<title>Baidu Map - 双击标注位置</title>
<style type="text/css">
html{height:100%}
body{height:100%;margin:0px;padding:0px;font-size:12px;}
td{font-size:12px;}
#container{height:100%}
</style>
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/config.js"></script>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?php echo $map_key;?>"></script>
</head>
<body>
<div id="container"></div>
<script type="text/javascript">
var map = new BMap.Map("container");
var point = new BMap.Point(<?php echo $map;?>);
map.centerAndZoom(point, 15);
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));
map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));
map.addControl(new BMap.CityListControl({anchor: BMAP_ANCHOR_TOP_RIGHT,offset: BMap.Size(50, 20)}));
map.addOverlay(new BMap.Marker(point));
map.addEventListener("dblclick", function(e){
	try {
		window.parent.document.getElementById('map').value = e.point.lng+','+e.point.lat;
		window.parent.cDialog();
	} catch(e) {}
});
<?php if($map == $map_mid) { ?>
// 自动定位
var localCity = new BMap.LocalCity();
localCity.get(function (r) {
	map.centerAndZoom(r.center, 16);
});
<?php } ?>
<?php if($map_key) { ?>
// 增加检索框
    function ZoomControl() {
        this.defaultAnchor = BMAP_ANCHOR_TOP_LEFT;
        this.defaultOffset = new BMap.Size(10, 10);
    } 
    ZoomControl.prototype = new BMap.Control();
    ZoomControl.prototype.initialize = function(map){
      var p = document.createElement("div");
      p.innerHTML = '<div id="r-result"><input type="text" id="suggestId" placeholder="检索地址" style="width:300px;" /></div><div id="searchResultPanel" style="border:1px solid #C0C0C0;width:300px;height:auto; display:none;"></div>';
      map.getContainer().appendChild(p);
      return p;
    }
    var myZoomCtrl = new ZoomControl();
    map.addControl(myZoomCtrl);
    var ac = new BMap.Autocomplete({"input" : "suggestId","location" : map}); 
    ac.addEventListener("onhighlight", function(e) {
		var str = "";
        var _value = e.fromitem.value;
        var value = "";
        if (e.fromitem.index > -1) {
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        }
        str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value; 
        value = "";
        if (e.toitem.index > -1) {
            _value = e.toitem.value;
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        }
        str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
        document.getElementById("searchResultPanel").innerHTML = str;
    }); 
    var myValue;
    ac.addEventListener("onconfirm", function(e) {
    var _value = e.item.value;
        myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        document.getElementById("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue; 
        setPlace();
    }); 
    function setPlace(){
        map.clearOverlays();
        function myFun(){
            var pp = local.getResults().getPoi(0).point;
            map.centerAndZoom(pp, 14);
            map.addOverlay(new BMap.Marker(pp));
			var label = new BMap.Label('<div onclick="window.parent.cDialog();window.parent.document.getElementById(\'map\').value=this.innerHTML;" style="background:#007AFF;color:#FFFFFF;border-radius:6px;padding:6px;cursor:pointer;" title="点击标注">'+pp.lng+','+pp.lat+'<\/div>', {position : pp,offset : new BMap.Size(-80, -55)});
			label.setStyle({borderColor : "#FFFFFF"});
			map.addOverlay(label); 
			try {window.parent.document.getElementById('map').value = pp.lng+','+pp.lat;} catch(e) {}
        }
        var local = new BMap.LocalSearch(map, {onSearchComplete: myFun});
        local.search(myValue);
    }
<?php } ?>
</script>
</body>
</html>