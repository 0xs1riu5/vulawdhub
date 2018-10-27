<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<?php load('swfobject.js');?>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/amcharts/amcharts.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/amcharts/amfallback.js"></script>
<?php foreach($lists as $k=>$v) {?>
<div class="tt"><?php echo $v['title'];?></div>
<a name="q<?php echo $k;?>"></a>
<table cellspacing="0" class="tb">
<tr>
<td style="padding:10px;">
<div id="chartdiv<?php echo $k;?>" style="width:600px;height:400px;background:#FFFFFF;"></div>
<script type="text/javascript">
var params = 
{
	bgcolor:"#FFFFFF"
};	
var flashVars = 
{
	path: "<?php echo DT_PATH;?>api/amcharts/flash/",		
	chart_data: "<?php echo $v['chart_data'];?>",
	chart_settings: "<settings><data_type>csv</data_type><balloon><show><![CDATA[{title} {value} 票 ({percents}％)]]></show><alpha>80</alpha><max_width>300</max_width><corner_radius>5</corner_radius><border_width>3</border_width><border_color>000000</border_color><border_alpha>50</border_alpha></balloon><legend><enabled>0</enabled></legend><pie><inner_radius>40</inner_radius><hover_brightness>-10</hover_brightness><gradient>radial</gradient><gradient_ratio>0,0,0,-50,0,0,0,-50</gradient_ratio></pie><animation><start_time>2</start_time><start_effect>strong</start_effect><pull_out_time>1.5</pull_out_time></animation><data_labels><show>{title}: {percents}％</show></data_labels><labels><label lid='0'><text><![CDATA[<b><?php echo $v['title'];?></b>]]></text><y>10</y><text_size>12</text_size><align>center</align></label></labels></settings>"
};	
if(swfobject.hasFlashPlayerVersion("8")) {
	swfobject.embedSWF("<?php echo DT_PATH;?>api/amcharts/flash/ampie.swf", "chartdiv<?php echo $k;?>", "600", "400", "8.0.0", "<?php echo DT_PATH;?>api/amcharts/flash/expressInstall.swf", flashVars, params);
} else {
	var amFallback = new AmCharts.AmFallback();
	amFallback.chartSettings = flashVars.chart_settings;
	amFallback.pathToImages = "<?php echo DT_PATH;?>api/amcharts/images/";
	amFallback.chartData = flashVars.chart_data;
	amFallback.type = "pie";
	amFallback.write("chartdiv<?php echo $k;?>");
}
</script>
</td>
</tr>
</table>
<?php } ?>
<script type="text/javascript">Menuon(4);</script>
<?php include tpl('footer');?>