/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
document.write('<style type="text/css">');
document.write('.color_div_t {width:16px;height:16px;padding:6px;background:#FFFFFF;}');
document.write('.color_div_o,.color_div_t:hover {width:16px;height:16px;padding:6px;background:#DDDDDD;cursor:crosshair;}');
document.write('.color_div {width:16px;height:16px;line-height:16px;font-size:1px;}');
document.write('</style>');
var color_id = 1; var color_bk = color_htm = '';
color_htm += '<table cellpadding="0" cellspacing="0" bgcolor="#666666" width="100%">';
color_htm += '<tr><td width="10" height="32"> </td>';
color_htm += '<td><input type="text" style="width:60px;height:12px;border:#A0A0A0 1px solid;" value="" maxlength="7" id="color_viewview" onblur="color_select(this.value);" onkeyup="color_view(this.value);" ondblclick="this.value=\'\';"/></td>';
color_htm += '<td>&nbsp;&nbsp;&nbsp;</td>';
color_htm += '<td style="cursor:pointer;font-size:20px;width:32px;text-align:center;color:#FFFFFF;"  onmouseover="this.style.backgroundColor=\'#CE3C39\';" onmouseout="this.style.backgroundColor=\'#666666\';" onclick="color_close();">&#215;</td>';
color_htm += '</tr>';
color_htm += '</table>';
color_htm += '<div id="destoon_color_show"></div>';
function color_show(id) {
	Eh();
	if(Dd('destoon_color') == null) {
		var destoon_color_div = document.createElement("div");
		destoon_color_div.id = 'destoon_color';
		document.body.appendChild(destoon_color_div);		
		$('#destoon_color').css({'zIndex':'9999','position':'absolute','display':'none','width':'226px','background':'#FFFFFF'});
	}
	$('#destoon_color').css({'left':$('#color_img_'+color_id).offset().left+'px','top':($('#color_img_'+color_id).offset().top+$('#color_img_'+color_id).height())+'px'});
	$('#destoon_color').html(color_htm);
	color_id = id;
	var color = Dd('color_input_'+id).value;
	color_bk = color;
	$('#destoon_color').fadeIn(300);
	if(color) color_view(color);
	color_setup(color);
}
function color_hide() {$('#destoon_color').fadeOut(100);Es();}
function color_close() {color_hide();Dd('color_img_'+color_id).style.backgroundColor = color_bk;}
function color_select(color) {color=color.toUpperCase();if(color.length>0&&!color.match(/^#[A-F0-9]{6}$/)){return;}color_hide();Dd('color_input_'+color_id).value = color; Dd('color_img_'+color_id).style.backgroundColor = color;}
function color_setup(color) {
	var colors = [
	'#000000', '#993300', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
	'#800000', '#FF6600', '#808000', '#008000', '#008080', '#0000FF', '#000000', '#808080', 
	'#FF0000', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#999999', 
	'#FF00FF', '#FFCC00', '#FFFF00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0', 
	'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#99CCFF', '#CC99FF', ''];
	var colors_select = '';
	colors_select += '<table cellpadding="0" cellspacing="0" style="border:#E0E0E0 1px solid;">'
	for(i = 0; i < colors.length; i++) {
		if(i%8 == 0) colors_select += '<tr>';
		colors_select += '<td width="28" height="28">';
		if(color == colors[i]) {
			colors_select += '<div class="color_div_o" onmouseover="color_view(\''+colors[i]+'\');" onclick="color_select(\''+colors[i]+'\');">';
		} else {
			colors_select += '<div class="color_div_t" onmouseover="color_view(\''+colors[i]+'\');" onclick="color_select(\''+colors[i]+'\');">';
		}
		colors_select += '<div class="color_div" style="background:'+(colors[i] ? colors[i] : '#FFFFFF;border:#DDDDDD 1px dotted;')+'" onmouseover="color_view(\'\');" onclick="color_select(\'\');">&nbsp;</div></div></td>';
		if(i%8 == 7) colors_select += '</tr>';
	}
	colors_select += '</table>';
	$('#destoon_color_show').html(colors_select);
}
function color_view(color){color=color.toUpperCase();if(color.length>0&&!color.match(/^#[A-F0-9]{6}$/)){return;}try {Dd('color_viewview').value = color; Dd('color_viewview').style.color = color; Dd('color_img_'+color_id).style.backgroundColor = color;} catch(e) {}}