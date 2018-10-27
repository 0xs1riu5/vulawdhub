<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
if(!$itemid) show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<select name="mid">
<?php
	foreach($MODULE as $m) {
	if(!in_array($m['moduleid'], array(1,3,4)) && !$m['islink']) {
?>
<option value="<?php echo $m['moduleid'];?>"<?php echo $mid == $m['moduleid'] ? ' selected' : ''?>><?php echo $m['name'];?></option>
<?php } } ?>
</select>&nbsp;
<select name="year">
<option value="0">选择年</option>
<?php for($i = date("Y", $DT_TIME); $i >= 2000; $i--) { ?>
<option value="<?php echo $i;?>"<?php echo $i == $year ? ' selected' : ''?>><?php echo $i;?>年</option>
<?php } ?>
</select>&nbsp;
<select name="month">
<option value="0">选择月</option>
<?php for($i = 1; $i < 13; $i++) { ?>
<option value="<?php echo $i;?>"<?php echo $i == $month ? ' selected' : ''?>><?php echo $i;?>月</option>
<?php } ?>
</select>&nbsp;
<input type="submit" value="生成报表" class="btn-g"/>&nbsp;&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?file=<?php echo $file;?>&action=<?php echo $action;?>&mid=<?php echo $mid;?>&itemid=<?php echo $itemid;?>');"/>
</form>
</div>
<?php
	if($year && $month && $mid) {
	$tb = get_table($mid);
	$fd = 'addtime';
	$ym = $year.'-'.$month;
	if($mid == 2) $fd = 'regtime';
	$d = date('t', strtotime($ym.'-1'));
	$chart_data = '';
	for($i = 1; $i <= $d; $i++) {
		$f = strtotime($ym.'-'.$i.' 00:00:00');
		$t = strtotime($ym.'-'.$i.' 23:59:59');
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$tb} WHERE `$fd`>=$f AND `$fd`<=$t");
		if($i > 1) $chart_data .= '\n';
		$chart_data .= $i.';'.($r['num'] ? $r['num'] : 0);
	}
?>
<div class="tt"><?php echo $MODULE[$mid]['name'];?> <?php echo $year;?>年<?php echo $month;?>月统计报表</div>
<table cellspacing="0" class="tb">
<tr>
<td style="padding:10px;">
<?php load('swfobject.js');?>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/amcharts/amcharts.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/amcharts/amfallback.js"></script>
<div id="chartdiv" style="width:700px;height:400px;background:#FFFFFF;"></div>        
<script type="text/javascript">
var params = 
{
	bgcolor:"#FFFFFF"
};	
var flashVars = 
{
	path: "<?php echo DT_PATH;?>api/amcharts/flash/",		
	chart_data: "<?php echo $chart_data;?>",
	chart_settings: "<settings><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>50</top><bottom>50</bottom></margins></plot_area><grid><category><dashed>1</dashed><dash_length>4</dash_length></category><value><dashed>1</dashed><dash_length>4</dash_length></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><legend><enabled>0</enabled></legend><angle>0</angle><column><width>85</width><balloon_text>{title}:{value}</balloon_text><grow_time>3</grow_time><sequenced_grow>1</sequenced_grow></column><graphs><graph gid='0'><title>总数</title><color>7F8DA9</color></graph></graphs><labels><label lid='0'><text><![CDATA[<b><?php echo $MODULE[$mid]['name'];?><?php echo $year;?>年<?php echo $month;?>月统计报表</b>]]></text><y>18</y><text_color>000000</text_color><text_size>13</text_size><align>center</align></label></labels></settings>"
};
if (swfobject.hasFlashPlayerVersion("8")) {
	swfobject.embedSWF("<?php echo DT_PATH;?>api/amcharts/flash/amcolumn.swf", "chartdiv", "700", "400", "8.0.0", "<?php echo DT_PATH;?>api/amcharts/flash/expressInstall.swf", flashVars, params);
} else {
	var amFallback = new AmCharts.AmFallback();
	amFallback.chartSettings = flashVars.chart_settings;
	amFallback.pathToImages = "<?php echo DT_PATH;?>api/amcharts/images/";
	amFallback.chartData = flashVars.chart_data;
	amFallback.type = "column";
	amFallback.write("chartdiv");
}
</script>
</td>
</tr>
</table>
<?php
	} else if($year && $mid) {
	$tb = get_table($mid);
	$fd = 'addtime';
	$ym = $year;
	if($mid == 2) $fd = 'regtime';
	$chart_data = '';
	for($i = 1; $i < 13; $i++) {		
		$f = strtotime($ym.'-'.$i.'-1 00:00:00');
		$d = date('t', $f);
		$t = strtotime($ym.'-'.$i.'-'.$d.' 23:59:59');
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$tb} WHERE `$fd`>=$f AND `$fd`<=$t");
		if($i > 1) $chart_data .= '\n';
		$chart_data .= $i.';'.($r['num'] ? $r['num'] : 0);
	}
?>
<div class="tt"><?php echo $MODULE[$mid]['name'];?> <?php echo $year;?>年统计报表</div>
<table cellspacing="0" class="tb">
<tr>
<td style="padding:10px;">
<?php load('swfobject.js');?>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/amcharts/amcharts.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/amcharts/amfallback.js"></script>
<div id="chartdiv" style="width:700px;height:400px;background:#FFFFFF;"></div>        
<script type="text/javascript">
var params = 
{
	bgcolor:"#FFFFFF"
};	
var flashVars = 
{
	path: "<?php echo DT_PATH;?>api/amcharts/flash/",		
	chart_data: "<?php echo $chart_data;?>",
	chart_settings: "<settings><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>50</top><bottom>50</bottom></margins></plot_area><grid><category><dashed>1</dashed><dash_length>4</dash_length></category><value><dashed>1</dashed><dash_length>4</dash_length></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><legend><enabled>0</enabled></legend><angle>0</angle><column><width>85</width><balloon_text>{title}:{value}</balloon_text><grow_time>3</grow_time><sequenced_grow>1</sequenced_grow></column><graphs><graph gid='0'><title>总数</title><color>7F8DA9</color></graph></graphs><labels><label lid='0'><text><![CDATA[<b><?php echo $MODULE[$mid]['name'];?><?php echo $year;?>年统计报表</b>]]></text><y>18</y><text_color>000000</text_color><text_size>13</text_size><align>center</align></label></labels></settings>"
};
if (swfobject.hasFlashPlayerVersion("8")) {
	swfobject.embedSWF("<?php echo DT_PATH;?>api/amcharts/flash/amcolumn.swf", "chartdiv", "700", "400", "8.0.0", "<?php echo DT_PATH;?>api/amcharts/flash/expressInstall.swf", flashVars, params);
} else {
	var amFallback = new AmCharts.AmFallback();
	amFallback.chartSettings = flashVars.chart_settings;
	amFallback.pathToImages = "<?php echo DT_PATH;?>api/amcharts/images/";
	amFallback.chartData = flashVars.chart_data;
	amFallback.type = "column";
	amFallback.write("chartdiv");
}
</script>
</td>
</tr>
</table>
<?php } ?>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>