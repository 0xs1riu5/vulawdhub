<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
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
<input type="submit" value="生成报表" class="btn-g"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
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
	chart_settings: "<settings><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>55</top><bottom>30</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>8</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><graph gid='0'><title><?php echo $DT['money_name'];?>(<?php echo $T1.$DT['money_unit'];?>)</title><color>0D8ECF</color><color_hover>F08F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph></graphs><labels><label lid='0'><text><![CDATA[<b><?php echo $title;?></b>]]></text><y>15</y><text_size>13</text_size><align>center</align></label></labels></settings>"
};	
if (swfobject.hasFlashPlayerVersion("8")) {
	swfobject.embedSWF("<?php echo DT_PATH;?>api/amcharts/flash/amline.swf", "chartdiv", "700", "400", "8.0.0", "<?php echo DT_PATH;?>api/amcharts/flash/expressInstall.swf", flashVars, params);
} else {
	var amFallback = new AmCharts.AmFallback();
	amFallback.chartSettings = flashVars.chart_settings;
	amFallback.pathToImages = "<?php echo DT_PATH;?>api/amcharts/images/";
	amFallback.chartData = flashVars.chart_data;
	amFallback.type = "line";
	amFallback.write("chartdiv");
}
</script>
</td>
</tr>
</table>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>