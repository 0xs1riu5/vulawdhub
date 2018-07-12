/*
瑞意日期选择框 rcalendar 2.0
create by rain, Nov 7, 2008
update by rain, Nov 14, 2008
copyright @ rainic.com
example:
<input type="text" onclick="rcalendar(this)">
<input type="text" onclick="rcalendar(this, 'full', alert)">
*/
var div_rcalendar;
var ryears;
var rmonths;
var rdates;
var rhours;
var rminutes;
var rseconds;
var ryear;
var rmonth;
var rhour;
var rminute;
var rsecond;
var robj_date; //根据文本域的值建立的Date对象
var rnow;
var rc_browser;
var rtext_date;
var rmode;
var rcalendar_function;
function rcalendar(text, mode, retfunction) { //文本域对象, 模式(dateonly,full), 选择日期后的事件函数(函数是新时间的Date对象)
	rc_browser = new function () {
		var matchs;
		if (matchs = navigator.userAgent.match(/MSIE (\d+(?:\.\d+){0,})/)) {
			this.name = "MSIE";
			this.version = matchs[1];
		}
		else if (matchs = navigator.userAgent.match(/Firefox\/(\d+(?:\.\d+){0,})/)) {
			this.name = "Firefox";
			this.version = matchs[1];
		}
		else if (matchs = navigator.userAgent.match(/Version\/(\d+(?:\.\d+){0,}) Safari/)) {
			this.name = "Safari";
			this.version = matchs[1];
		}
		else if (matchs = navigator.userAgent.match(/Opera\/(\d+(?:\.\d+){0,})/)) {
			this.name = "Opera";
			this.version = matchs[1];
		}
		else if (matchs = navigator.userAgent.match(/Chrome\/(\d+(?:\.\d+){0,})/)) {
			this.name = "Chrome";
			this.version = matchs[1];
		}
		else {
			this.name = "unknown";
			this.version = "unknown";
		}
		return this;
	};
	
	rnow = new Date();
	rtext_date = typeof(text)=='object' ? text :document.getElementById(text);
	rmode = mode;
	rcalendar_function = retfunction;
	
	try { //获取文本域中的日期
		var ymdhis = rtext_date.value.split(/[^\d]+/);
		ymdhis[0] = parseInt(ymdhis[0]);
		ymdhis[1] = parseInt(ymdhis[1].replace(/^0(\d)/, '$1'));
		ymdhis[2] = parseInt(ymdhis[2].replace(/^0(\d)/, '$1'));
		ymdhis[3] = (ymdhis[3] == null || ymdhis[3] == "") ? 0 : parseInt(ymdhis[3].replace(/^0(\d)/, '$1'));
		ymdhis[4] = (ymdhis[4] == null || ymdhis[4] == "") ? 0 : parseInt(ymdhis[4].replace(/^0(\d)/, '$1'));
		ymdhis[5] = (ymdhis[5] == null || ymdhis[5] == "") ? 0 : parseInt(ymdhis[5].replace(/^0(\d)/, '$1'));
		robj_date = new Date(ymdhis[0], ymdhis[1] - 1, ymdhis[2], ymdhis[3], ymdhis[4], ymdhis[5]);
		if (isNaN(robj_date.getTime())) {
			robj_date = new Date(rnow.getTime());
		}
	}
	catch (e) {
		robj_date = new Date(rnow.getTime());
	}
	
	if (!div_rcalendar) { //如果不存在，则初始化创建它
		//设置颜色选择框的样式 BEGIN
		var css = "";
		if (document.compatMode == "BackCompat" && navigator.userAgent.indexOf("MSIE") != -1) {
			css += "#rcalendar {width:200px; height:200px; background:#FFFFFF; font-size:12px; font-family:宋体; border:1px solid #CCCCCC; padding:4px; overflow:hidden;}";
			css += "#rcalendar_ym {width:216px; height:14px; overflow:hidden; margin-bottom:4px;}";
			css += "#rcalendar_y {width:50px; float:left; font-weight:bold; color:#777777; padding-left:2px;}";
			css += "#rcalendar_m {width:50px; float:left; font-weight:bold; color:#777777;}";
			css += "#rweeks {width:217px; height:20px; overflow:hidden;}";
			css += "#rdates {width:217px; overflow:hidden;}";
			css += ".rweek {width:31px; height:20px; padding:4px 0px 4px 0px; float:left; text-align:center; background:#DD4B39; color:#FFFFFF; overflow:hidden;border-top:# C54333 solid 1px}";
			css += ".rdate {width:31px; height:20px; padding:4px 0px 4px 0px; float:left; text-align:center; overflow:hidden; cursor:pointer;}";
			css += "#ryears {background:#fff; border:1px solid #ccc; border-top:0px; color:#FFFFFF; text-align:center;_width:50px;}";
			css += ".ryear {padding:4px 4px 4px 4px; height:20px; width:36px; overflow:hidden; cursor:pointer; font-weight:bold;display:block;}";
			css += "#ryear_add {padding:0px; height:12px; width:36px; overflow:hidden; cursor:pointer;}";
			css += "#rmonths {background:#fff; border:1px solid #ccc; border-top:0px; color:#FFFFFF; font-weight:bold; text-align:center; width:80px; height:81px; overflow:hidden;}";
			css += ".rmonth {padding:4px 4px 4px 4px; width:26px; height:20px; overflow:hidden; float:left; cursor:pointer;}";
			css += "#rtime {width:90px; height:13px; float:left; overflow:hidden;}";
			css += "#rhour {padding:0px 7px 0px 7px; background:#E9E9E9; cursor:pointer;}";
			css += "#rminute {padding:0px 7px 0px 7px; background:#E9E9E9; cursor:pointer;}";
			css += "#rsecond {padding:0px 7px 0px 7px; background:#E9E9E9; cursor:pointer;}";
			css += "#rbtns {width:90px; margin-left:10px; height:13px; float:right; text-align:right; overflow:hidden;}";
			css += "#rhours {width:104px; height:108px; overflow:hidden;}";
			css += ".rhour {padding:3px 7px 3px 7px; background:#fff; color:#2b2b2b; height:18px; overflow:hidden; float:left; cursor:pointer;}";
			css += "#rminutes {width:104px; height:54px; overflow:hidden;}";
			css += ".rminute {padding:3px 7px 3px 7px; background:#fff; color:#2b2b2b; height:18px; overflow:hidden; float:left; cursor:pointer;}";
			css += "#rseconds {width:104px; height:54px; overflow:hidden;}";
			css += ".rsecond {padding:3px 7px 3px 7px; background:#fff; color:#2b2b2b; height:18px; overflow:hidden; float:left; cursor:pointer;}";
		}
		else {//173  17  190
			css += "#rcalendar {width:216px; background:#FFFFFF; font-size:12px; font-family:宋体; border:1px solid #ccc; overflow:hidden;}";
			css += "#rcalendar_ym {width:217px; height:16px;line-height:16px;padding:5px 0; overflow:hidden;background:#F2F2F2}";
			css += "#rcalendar_y {float:left;  color:#535353;}";
			css += "#ryear {font-size:18px; padding:0 5px 0 10px;font-weight:700;}";
			css += "#rcalendar_m {width:50px; float:left; color:#535353;;padding:0 5px 0 10px}";
			css += "#rmonth {font-weight:700; font-size:18px;}";
			css += "#rweeks {width:217px; overflow:hidden;border-bottom:#C54333 solid 1px;border-top:#C54333 solid 1px}";
			css += "#rdates {width:217px;overflow:hidden;}";
			css += ".rweek {width:31px; height:16px;line-height:16px; padding:4px 0px 4px 0px; float:left; text-align:center; background:#DD4B39; color:#FFFFFF; overflow:hidden;border-top:# C54333 solid 1px}";
			css += ".rdate {width:30px; height:16px;line-height:16px;border-right:#E6E6E6 solid 1px;border-bottom:#E6E6E6 solid 1px; padding:4px 0px 4px 0px; float:left; text-align:center; overflow:hidden; cursor:pointer;}";
			css += "#ryears {background:#fff; border:1px solid #ccc; border-top:0px; color:#2b2b2b; text-align:center;_width:50px;}";
			css += ".ryear {padding:6px 0 6px 0; height:16px;line-height:16px; width:50px; overflow:hidden; cursor:pointer;border-top:#E6E6E6 solid 1px;display:block;}";
			css += "#ryear_add {padding:5px 0; height:16px; line-height:16px;  overflow:hidden; cursor:pointer;background:#f2f2f2}";
			css += "#rmonths {background:#fff; border:1px solid #ccc; color:#2b2b2b; text-align:center; width:96px;overflow:hidden;}";
			css += ".rmonth {padding:6px 0 6px 0; width:31px; height:16px;line-height:16px; overflow:hidden; float:left; cursor:pointer;border-bottom:#E6E6E6 solid 1px;border-right:#E6E6E6 solid 1px}";
			css += "#rtime {width:90px; height:13px; float:left; overflow:hidden;}";
			css += "#rhour {padding:0px 7px 0px 7px; background:#E9E9E9; cursor:pointer;}";
			css += "#rminute {padding:0px 7px 0px 7px; background:#E9E9E9; cursor:pointer;}";
			css += "#rsecond {padding:0px 7px 0px 7px; background:#E9E9E9; cursor:pointer;}";
			css += "#rbtns {width:90px; margin-left:10px; height:13px; float:right; text-align:right; overflow:hidden;}";
			css += "#rhours {width:104px; height:108px; overflow:hidden;}";
			css += ".rhour {padding:3px 7px 3px 7px; background:#DD4B39; color:#FFFFFF; height:12px; overflow:hidden; float:left; cursor:pointer;}";
			css += "#rminutes {width:104px; height:54px; overflow:hidden;}";
			css += ".rminute {padding:3px 7px 3px 7px; background:#DD4B39; color:#FFFFFF; height:12px; overflow:hidden; float:left; cursor:pointer;}";
			css += "#rseconds {width:104px; height:54px; overflow:hidden;}";
			css += ".rsecond {padding:3px 7px 3px 7px; background:#DD4B39; color:#FFFFFF; height:12px; overflow:hidden; float:left; cursor:pointer;}";
		}
		try { //IE下可行
			var style = document.createStyleSheet();
			style.cssText = css;
		}
		catch (e) { //Firefox,Opera,Safari,Chrome下可行
			var style = document.createElement("style"); 
			style.type = "text/css";
			style.textContent = css;
			document.getElementsByTagName("HEAD").item(0).appendChild(style);
		}
		//设置颜色选择框的样式 END		
		
		div_rcalendar = document.createElement("div");
		div_rcalendar.setAttribute("id", "rcalendar");
		div_rcalendar.style.position = "absolute";
		div_rcalendar.style.zIndex = 1000;
		div_rcalendar.style.background = "#FFFFFF";
		div_rcalendar.style.display = "none";
		
		var str = "";
		str += '<div id="rcalendar_ym">';
		str += '  <div id="rcalendar_y">';
		str += '    <span id="ryear" style="cursor:pointer" onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#ffffff\'" onmouseout="this.style.background=\'\';this.style.color=\'#2b2b2b\'" onclick="rselect_years(this)"></span>年';
		str += '  </div>';
		str += '  <div id="rcalendar_m">';
		str += '    <span id="rmonth" style="cursor:pointer" onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#ffffff\'" onmouseout="this.style.background=\'\';this.style.color=\'#2b2b2b\'" onclick="rselect_months(this)"></span>月';
		str += '  </div>';
		str += '  <div style="float:right; text-align:right;padding-right:10px">';
		str += '    <span style="cursor:pointer;" onclick="rcalendar_close()">×</span>';
		str += '  </div>';
		str += '</div>';
		str += '<div id="rweeks">';
		str += '  <div class="rweek">日</div>';
		str += '  <div class="rweek">一</div>';
		str += '  <div class="rweek">二</div>';
		str += '  <div class="rweek">三</div>';
		str += '  <div class="rweek">四</div>';
		str += '  <div class="rweek">五</div>';
		str += '  <div class="rweek">六</div>';
		str += '</div>';
		str += '<div id="rdates"></div>';
		str += '<div style="width:216px; height:13px; overflow:hidden;background:#F2F2F2;padding:5px 0 10px 0">';
		str += '  <div id="rtime">';
		str += '	<span id="rhour" onmouseover="this.style.background=\'#DD4B39\';" onmouseout="this.style.background=\'#E9E9E9\';" onclick="rselect_hours(this)"></span>:<span id="rminute" onmouseover="this.style.background=\'#DD4B39\';" onmouseout="this.style.background=\'#E9E9E9\';" onclick="rselect_minutes(this)"></span>:<span id="rsecond" onmouseover="this.style.background=\'#DD4B39\';" onmouseout="this.style.background=\'#E9E9E9\';" onclick="rselect_seconds(this)"></span>';
		str += '  </div>';
		str += '  <div id="rbtns">';
		str += '    <span style="padding:0px 6px 0px 6px; color:#36C; cursor:pointer;" onmouseover="this.style.background=\'\';" onmouseout="this.style.background=\'\';" onclick="rokclick()">确定</span><span style="padding:0px 6px 0px 6px; color:#36C; cursor:pointer;" onmouseover="this.style.background=\'\';" onmouseout="this.style.background=\'\';" onclick="rtext_date.value=\'\';rcalendar_close();">清空</span>';
		str += '  </div>';
		str += '</div>';
  		div_rcalendar.innerHTML = str;
		
		ryears = document.createElement("div");
		ryears.setAttribute("id", "ryears");
		ryears.style.position = "absolute";
		ryears.style.display = "none";
  		
		rmonths = document.createElement("div");
		rmonths.setAttribute("id", "rmonths");
		rmonths.style.position = "absolute";
		rmonths.style.display = "none";
  		rfill_rmonths();
		
		rhours = document.createElement("div");
		rhours.setAttribute("id", "rhours");
		rhours.style.position = "absolute";
		rhours.style.display = "none";
		rfill_rhours();
		
		rminutes = document.createElement("div");
		rminutes.setAttribute("id", "rminutes");
		rminutes.style.position = "absolute";
		rminutes.style.display = "none";
		rfill_rminutes();
		
		rseconds = document.createElement("div");
		rseconds.setAttribute("id", "rseconds");
		rseconds.style.position = "absolute";
		rseconds.style.display = "none";
		rfill_rseconds();
		
		document.body.appendChild(div_rcalendar);
		div_rcalendar.appendChild(ryears);
		div_rcalendar.appendChild(rmonths);
		rdates = document.getElementById("rdates");
		div_rcalendar.appendChild(rhours);
		div_rcalendar.appendChild(rminutes);
		div_rcalendar.appendChild(rseconds);
		ryear = document.getElementById("ryear");
		rmonth = document.getElementById("rmonth");
		rhour = document.getElementById("rhour");
		rminute = document.getElementById("rminute");
		rsecond = document.getElementById("rsecond");
	}
	
	if (div_rcalendar.style.display == "")
		rcalendar_close();
	
	if (rmode == "full")
		document.getElementById("rtime").style.visibility = "visible";
	else
		document.getElementById("rtime").style.visibility = "hidden";
	
	//填写年和月
	ryear.innerHTML = robj_date.getFullYear();
	rmonth.innerHTML = robj_date.getMonth() + 1 < 10 ? '0' + (robj_date.getMonth() + 1) : robj_date.getMonth() + 1;
	rhour.innerHTML = robj_date.getHours() < 10 ? '0' + robj_date.getHours() : robj_date.getHours();
	rminute.innerHTML = robj_date.getMinutes() < 10 ? '0' + robj_date.getMinutes() : robj_date.getMinutes();
	rsecond.innerHTML = robj_date.getSeconds() < 10 ? '0' + robj_date.getSeconds() : robj_date.getSeconds();
	
	rfill_ryears();
	rfill_rdates(); //输出日期表
	
	//定位并显示rcalendar
	var left_top = rget_offset_left_top(rtext_date);
	div_rcalendar.style.left = left_top[0] + "px";
	div_rcalendar.style.top = (left_top[1] + rtext_date.offsetHeight + 1) + "px";
	div_rcalendar.style.display = "";
}
function rfill_ryears(year) {
	year = year ? year : robj_date.getFullYear();
	str = '';
	for (var y = year - 2; y <= year + 2; y++) {
		str += '<div class="ryear" onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#fff\';" onmouseout="this.style.background=\'#fff\';this.style.color=\'#2b2b2b\';" onclick="rset_year(this.innerHTML)">' + y + '</div>';
	}
	str += '<div id="ryear_add">';
	str += '  <span onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#fff\';" onmouseout="this.style.background=\'#fff\';this.style.color=\'#2b2b2b\';" onclick="rfill_ryears(' + (year - 5) + ')">&nbsp;-&nbsp;</span>';
	str += '<span onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#fff\';" onmouseout="this.style.background=\'#fff\';this.style.color=\'#2b2b2b\';" onclick="rfill_ryears(' + (year + 5) + ')">&nbsp;+&nbsp;</span>';
	str += '</div>';
	ryears.innerHTML = str;
}
function rfill_rmonths() {
	str = '';
	for (var m = 1; m <= 12; m++) {
		str += '<div class="rmonth" onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#fff\';" onmouseout="this.style.background=\'#fff\';this.style.color=\'#2b2b2b\';" onclick="rset_month(this.innerHTML)">' + (m < 10 ? '0' + m : m) + '</div>';
	}
	rmonths.innerHTML = str;
}
function rfill_rdates() {
	var y = parseInt(ryear.innerHTML);
	var m = parseInt(rmonth.innerHTML.replace(/^0(\d)/, '$1'));
	var first_day_of_month = new Date(y, m - 1, 1); //当月第一天
	var date_b = new Date(y, m - 1, 1);
	var w = date_b.getDay();
	date_b.setDate(1 - w); //计算应该开始的日期
	
	var last_day_of_month = new Date(y, m, 0); //当月最后一天
	var date_e = new Date(y, m, 0);
	w = date_e.getDay();
	date_e.setDate(date_e.getDate() + 6 - w); //计算应该结束的日期
	
	str = "";
	for (var d = date_b; d.getTime() <= date_e.getTime(); d.setDate(d.getDate() + 1)) {
		var color, m_add;
		if (d.getTime() < first_day_of_month.getTime()) {
			color = '#999999';
			m_add = '-1';
		}
		else if (d.getTime() > last_day_of_month.getTime()) {
			color = '#999999';
			m_add = '1';
		}
		else {
			color = '#2b2b2b';
			m_add = '0';
		}
		if (d.getDate() == rnow.getDate() && d.getMonth() == rnow.getMonth() && d.getFullYear() == rnow.getFullYear()) {
			color = '#ffffff';
		}
		var font_weight = '';
		if (d.getDate() == robj_date.getDate() && m_add == '0') {
			font_weight = ' font-weight:bold;background-color:#dd4b39';
		}
		str += '<div class="rdate" style="color:' + color + ';' + font_weight + '" onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#ffffff\';" onmouseout="this.style.background=\'#FFFFFF\';this.style.color=\'#999\';" onclick="rset_date(this.innerHTML, ' + m_add + ')">' + d.getDate() + '</div>';
	}
	rdates.innerHTML = str;
}
function rfill_rhours() {
	str = '';
	for (var h = 0; h < 24; h++) {
		str += '<div class="rhour" onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#2b2b2b\';" onmouseout="this.style.background=\'#B0B0B0\';this.style.color=\'#DD4B39\';" onclick="rset_hour(this.innerHTML)">' + (h < 10 ? '0' + h : h) + '</div>';
	}
	rhours.innerHTML = str;
}
function rfill_rminutes() {
	str = '';
	for (var m = 0; m < 60; m += 5) {
		str += '<div class="rminute" onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#2b2b2b\';" onmouseout="this.style.background=\'#B0B0B0\';this.style.color=\'#ffffff\';" onclick="rset_minute(this.innerHTML)">' + (m < 10 ? '0' + m : m) + '</div>';
	}
	rminutes.innerHTML = str;
}
function rfill_rseconds() {
	str = '';
	for (var s = 0; s < 60; s += 5) {
		str += '<div class="rsecond" onmouseover="this.style.background=\'#DD4B39\';this.style.color=\'#2b2b2b\';" onmouseout="this.style.background=\'#fff\';this.style.color=\'#2b2b2b\';" onclick="rset_second(this.innerHTML)">' + (s < 10 ? '0' + s : s) + '</div>';
	}
	rseconds.innerHTML = str;
}
function rselect_years(span_year) {
	if (ryears.style.display == "none") {
		var left_top = rget_offset_left_top(span_year);
		ryears.style.left = (left_top[0] - parseInt(div_rcalendar.style.left) + 5) + "px";
		ryears.style.top = (left_top[1] - parseInt(div_rcalendar.style.top) + span_year.offsetHeight) + "px";
		if (rc_browser.name == "Opera") {
			ryears.style.left = (parseInt(ryears.style.left) - 1) + "px";
			ryears.style.top = (parseInt(ryears.style.top) - 1) + "px";
		}
		ryears.style.display = "";
		
		rhours.style.display = "none";
		rminutes.style.display = "none";
		rseconds.style.display = "none";
	}
	else {
		ryears.style.display = "none";
	}
}
function rselect_months(span_month) {
	if (rmonths.style.display == "none") {
		var left_top = rget_offset_left_top(span_month);
		rmonths.style.left = (left_top[0] - parseInt(div_rcalendar.style.left) - 6) + "px";
		rmonths.style.top = (left_top[1] - parseInt(div_rcalendar.style.top) + span_month.offsetHeight) + "px";
		if (rc_browser.name == "Opera") {
			rmonths.style.left = (parseInt(rmonths.style.left) - 1) + "px";
			rmonths.style.top = (parseInt(rmonths.style.top) - 1) + "px";
		}
		rmonths.style.display = "";
		
		rhours.style.display = "none";
		rminutes.style.display = "none";
		rseconds.style.display = "none";
	}
	else {
		rmonths.style.display = "none";
	}
}
function rselect_hours(span_hour) {
	if (rhours.style.display == "none") {
		var left_top = rget_offset_left_top(span_hour);
		rhours.style.left = (left_top[0] - parseInt(div_rcalendar.style.left)) + "px";
		rhours.style.top = (left_top[1] - parseInt(div_rcalendar.style.top) - 109) + "px";
		if (rc_browser.name == "Opera") {
			rhours.style.left = (parseInt(rhours.style.left) - 1) + "px";
			rhours.style.top = (parseInt(rhours.style.top) - 1) + "px";
		}
		rhours.style.display = "";
		
		ryears.style.display = "none";
		rmonths.style.display = "none";
		rminutes.style.display = "none";
		rseconds.style.display = "none";
	}
	else {
		rhours.style.display = "none";
	}
}
function rselect_minutes(span_minute) {
	if (rminutes.style.display == "none") {
		var left_top = rget_offset_left_top(span_minute);
		rminutes.style.left = (left_top[0] - parseInt(div_rcalendar.style.left)) + "px";
		rminutes.style.top = (left_top[1] - parseInt(div_rcalendar.style.top) - 55) + "px";
		if (rc_browser.name == "Opera") {
			rminutes.style.left = (parseInt(rminutes.style.left) - 1) + "px";
			rminutes.style.top = (parseInt(rminutes.style.top) - 1) + "px";
		}
		rminutes.style.display = "";
		
		ryears.style.display = "none";
		rmonths.style.display = "none";
		rhours.style.display = "none";
		rseconds.style.display = "none";
	}
	else {
		rminutes.style.display = "none";
	}
}
function rselect_seconds(span_second) {
	if (rseconds.style.display == "none") {
		var left_top = rget_offset_left_top(span_second);
		rseconds.style.left = (left_top[0] - parseInt(div_rcalendar.style.left)) + "px";
		rseconds.style.top = (left_top[1] - parseInt(div_rcalendar.style.top) - 55) + "px";
		if (rc_browser.name == "Opera") {
			rseconds.style.left = (parseInt(rseconds.style.left) - 1) + "px";
			rseconds.style.top = (parseInt(rseconds.style.top) - 1) + "px";
		}
		rseconds.style.display = "";
		
		ryears.style.display = "none";
		rmonths.style.display = "none";
		rhours.style.display = "none";
		rminutes.style.display = "none";
	}
	else {
		rseconds.style.display = "none";
	}
}
function rget_offset_left_top(obj) {
	var l = 0, t = 0;
	do {
		l += obj.offsetLeft;
		t += obj.offsetTop;
	} while (obj = obj.offsetParent);
	return new Array(l, t);
}
function rcalendar_close() {
	ryears.style.display = "none";
	rmonths.style.display = "none";
	rhours.style.display = "none";
	rminutes.style.display = "none";
	rseconds.style.display = "none";
	div_rcalendar.style.display = "none";
}
function rset_year(y) {
	ryear.innerHTML = y;
	rfill_rdates();
	ryears.style.display = "none";
}
function rset_month(m) {
	rmonth.innerHTML = m;
	rfill_rdates();
	rmonths.style.display = "none";
}
function rset_hour(h) {
	rhour.innerHTML = h;
	rhours.style.display = "none";
}
function rset_minute(m) {
	rminute.innerHTML = m;
	rminutes.style.display = "none";
}
function rset_second(s) {
	rsecond.innerHTML = s;
	rseconds.style.display = "none";
}
function rset_date(d, m_add) {
	rset_datetime(d, m_add);
}
function rokclick() {
	var d = 1;
	for (var k = 0; k < rdates.childNodes.length; k++) {
		if (rdates.childNodes[k].style.fontWeight == "bold" || rdates.childNodes[k].style.fontWeight == 700) {
			d = parseInt(rdates.childNodes[k].innerHTML.replace(/^0(\d)/, '$1'));
			break;
		}
	}
	rset_datetime(d, 0);
}
function rset_datetime(d, m_add) {
	
	var y = parseInt(ryear.innerHTML);
	var m = parseInt(rmonth.innerHTML.replace(/^0(\d)/, '$1')) - 1 + m_add;
	var h = parseInt(rhour.innerHTML.replace(/^0(\d)/, '$1'));
	var i = parseInt(rminute.innerHTML.replace(/^0(\d)/, '$1'));
	var s = parseInt(rsecond.innerHTML.replace(/^0(\d)/, '$1'));
	var date = new Date(y, m, d, h, i, s);
	m = date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
	d = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
	h = date.getHours() < 10 ? '0' + date.getHours() : date.getHours();
	i = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
	s = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds();
	if (rmode == "full")
		rtext_date.value = date.getFullYear() + "-" + m + "-" + d + " " + h + ":" + i + ":" + s;
	else
		rtext_date.value = date.getFullYear() + "-" + m + "-" + d;
	rcalendar_close();
	
	if (rcalendar_function != null && rcalendar_function != "") {
		rcalendar_function(date);
	}
}