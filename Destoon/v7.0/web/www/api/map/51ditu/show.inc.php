<?php
defined('IN_DESTOON') or exit('Access Denied');
preg_match("/^[0-9\.\,]{13,17}$/", $map) or $map = $map_mid;
?>
<div style="height:<?php echo $map_height;?>px;margin:auto;overflow:hidden;" id="myMap"></div>
<script type="text/javascript" src="http://api.51ditu.com/js/maps.js"></script>
<script type="text/javascript">
window.onload = function() {
	var map=new LTMaps("myMap");
	map.addControl(new LTSmallMapControl());
	var point=new LTPoint(<?php echo $map;?>);
	map.centerAndZoom(point, 3);
	var marker = new LTMarker(point,new LTIcon('<?php echo DT_PATH;?>file/image/map_point.gif',[20,20],[12,12]));
	map.addOverLay(marker);
	var text = new LTMapText(marker);text.setLabel("<div style=\"padding:3px;line-height:20px;\"><strong><?php echo $COM['company'];?></strong><br/><?php echo $COM['address'];?></div>");
	map.addOverLay(text);
}
</script>