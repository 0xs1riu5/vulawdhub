<?php
defined('IN_DESTOON') or exit('Access Denied');
preg_match("/^[0-9\.\,\-]{20,50}$/", $map) or $map = '';
?>
<tr>
<td class="tl">公司地图标注</td>
<td class="tr">
<input type="text" name="setting[map]" id="map" value="<?php echo $map;?>" readonly size="50" onclick="MapMark();"/>&nbsp;&nbsp;
<a href="javascript:MapMark();" class="t">标注</a>&nbsp;|&nbsp;<a href="javascript:DelMark();" class="t">清空</a>
<script type="text/javascript">
function MapMark() {
	Dwidget(DTPath+'api/map/google/mark.php?map='+Dd('map').value, '谷歌地图 - 在地图上双击鼠标完成标注');
}
function DelMark() {
	Dd('map').value='';
}
</script>
</td>
</tr>