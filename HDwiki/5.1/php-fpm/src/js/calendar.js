var controlid = null;
var currdate = null;
var startdate = null;
var enddate  = null;
var yy = null;
var mm = null;
var hh = null;
var ii = null;
var currday = null;
var addtime = false;
var today = new Date();
var lastcheckedyear = false;
var lastcheckedmonth = false;
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);


function doane(event) {
	e = event ? event : window.event;
	if(is_ie) {
		e.returnValue = false;
		e.cancelBubble = true;
	} else if(e) {
		e.stopPropagation();
		e.preventDefault();
	}
}

function getposition(obj) {
	var r = new Array(), offset=$(obj).offset();
	r['x'] = offset.left;
	r['y'] = offset.top;
	
	if ($('body').css('position') == 'relative'){
		var bodyW=$('body').width(), winW=$(window).width();
		if (winW > bodyW) {
			r['x'] = r['x'] - (winW - bodyW)/2;
		}
	}
	return r;
}

function loadcalendar() {
	s = '';
	s += '<div id="calendar" style="display:none; position:absolute; z-index:9;" onclick="doane(event)">';
	s += '<div style="width: 210px; border: 1px solid #FFF;"><table cellspacing="0" cellpadding="0" width="100%" style="text-align: center;">';
	s += '<tr align="center" id="calendar_week"><td><a href="###" onclick="refreshcalendar(yy, mm-1)" title="&#19978;&#19968;&#26376;"><<</a></td><td colspan="5" style="text-align: center"><a href="###" onclick="showdiv(\'year\');doane(event)" class="dropmenu" title="&#28857;&#20987;&#36873;&#36873;&#24180;&#20221;" id="year"></a>&nbsp; - &nbsp;<a id="month" class="dropmenu" title="&#28857;&#20987;&#36873;&#36873;&#26376;&#20221;" href="###" onclick="showdiv(\'month\');doane(event)"></a></td><td><A href="###" onclick="refreshcalendar(yy, mm+1)" title="&#19979;&#19968;&#26376;">>></A></td></tr>';
	s += '<tr id="calendar_header"><td>&#26085;</td><td>&#19968;</td><td>&#19968;</td><td>&#19977;</td><td>&#22235;</td><td>&#20116;</td><td>&#20845;</td></tr>';
	for(var i = 0; i < 6; i++) {
		s += '<tr>';
		for(var j = 1; j <= 7; j++)
			s += "<td id=d" + (i * 7 + j) + " height=\"19\">0</td>";
		s += "</tr>";
	}
	s += '<tr id="hourminute"><td colspan="7" align="center"><input type="text" size="2" value="" id="hour" onKeyUp=\'this.value=this.value > 23 ? 23 : zerofill(this.value);controlid.value=controlid.value.replace(/\\d+(\:\\d+)/ig, this.value+"$1")\'> &#28857; <input type="text" size="2" value="" id="minute" onKeyUp=\'this.value=this.value > 59 ? 59 : zerofill(this.value);controlid.value=controlid.value.replace(/(\\d+\:)\\d+/ig, "$1"+this.value)\'> &#20998;</td></tr>';
	s += '</table></div></div>';
	s += '<div id="calendar_year" onclick="doane(event)" style="display: none; width: 400px;"><div class="col">';
	for(var k = 1930; k <= 2019; k++) {
		s += k != 1930 && k % 10 == 0 ? '</div><div class="col">' : '';
		s += '<a href="###" onclick="refreshcalendar(' + k + ', mm);$(\'#calendar_year\').css(\'display\',\'none\')"><span' + (today.getFullYear() == k ? ' class="calendar_today"' : '') + ' id="calendar_year_' + k + '">' + k + '</span></a><br />';
	}
	s += '</div></div>';
	s += '<div id="calendar_month" onclick="doane(event)" style="display: none">';
	for(var k = 1; k <= 12; k++) {
		s += '<a href="###" onclick="refreshcalendar(yy, ' + (k - 1) + ');$(\'#calendar_month\').css(\'display\',\'none\')"><span' + (today.getMonth()+1 == k ? ' class="calendar_today"' : '') + ' id="calendar_month_' + k + '">' + k + ( k < 10 ? '&nbsp;' : '') + ' &#26376;</span></a><br />';
	}
	s += '</div>';
	if(is_ie && is_ie < 7) {
		s += '<iframe id="calendariframe" frameborder="0" style="display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)"></iframe>';
		s += '<iframe id="calendariframe_year" frameborder="0" style="display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)"></iframe>';
		s += '<iframe id="calendariframe_month" frameborder="0" style="display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)"></iframe>';
	}

	var div = document.createElement('div');
	div.innerHTML = s;
	$('#append_parent').append(div);
	document.onclick = function(event) {
		$('#calendar').css('display','none');
		$('#calendar_year').css('display','none');
		$('#calendar_month').css('display','none');
		if(is_ie && is_ie < 7) {
			$('#calendariframe').css('display','none');
			$('#calendariframe_year').css('display','none');
			$('#calendariframe_month').css('display','none');
		}
	}
	$('#calendar').onclick = function(event) {
		doane(event);
		$('#calendar_year').css('display','none');
		$('#calendar_month').css('display','none');
		if(is_ie && is_ie < 7) {
			$('#calendariframe_year').css('display','none');
			$('#calendariframe_month').css('display','none');
		}
	}

}

function parsedate(s) {
	/(\d+)\-(\d+)\-(\d+)\s*(\d*):?(\d*)/.exec(s);
	var m1 = (RegExp.$1 && RegExp.$1 > 1899 && RegExp.$1 < 2101) ? parseFloat(RegExp.$1) : today.getFullYear();
	var m2 = (RegExp.$2 && (RegExp.$2 > 0 && RegExp.$2 < 13)) ? parseFloat(RegExp.$2) : today.getMonth() + 1;
	var m3 = (RegExp.$3 && (RegExp.$3 > 0 && RegExp.$3 < 32)) ? parseFloat(RegExp.$3) : today.getDate();
	var m4 = (RegExp.$4 && (RegExp.$4 > -1 && RegExp.$4 < 24)) ? parseFloat(RegExp.$4) : 0;
	var m5 = (RegExp.$5 && (RegExp.$5 > -1 && RegExp.$5 < 60)) ? parseFloat(RegExp.$5) : 0;
	/(\d+)\-(\d+)\-(\d+)\s*(\d*):?(\d*)/.exec("0000-00-00 00\:00");
	return new Date(m1, m2 - 1, m3, m4, m5);
}

function settime(d) {
	$('#calendar').css('display','none');
	$('#calendar_month').css('display','none');
	if(is_ie && is_ie < 7) {
		$('#calendariframe').css('display','none');
	}
	controlid.value = yy + "-" + zerofill(mm + 1) + "-" + zerofill(d) + (addtime ? ' ' + zerofill($('#hour')[0].value) + ':' + zerofill($('#minute')[0].value) : '');
}

function showcalendar(event, controlid1, addtime1, startdate1, enddate1) {
	controlid = controlid1;
	addtime = addtime1;
	startdate = startdate1 ? parsedate(startdate1) : false;
	enddate = enddate1 ? parsedate(enddate1) : false;
	currday = controlid.value ? parsedate(controlid.value) : today;
	hh = currday.getHours();
	ii = currday.getMinutes();
	var p = getposition(controlid);
	$('#calendar').css('display','block');
	$('#calendar').css({"left":p['x']+'px',"top":(p['y'] + 20)+'px'});
	doane(event);
	refreshcalendar(currday.getFullYear(), currday.getMonth());
	if(lastcheckedyear != false) {
		$('#calendar_year_' + lastcheckedyear)[0].className = 'calendar_default';
		$('#calendar_year_' + today.getFullYear())[0].className = 'calendar_today';
	}
	if(lastcheckedmonth != false) {
		$('#calendar_month_' + lastcheckedmonth)[0].className = 'calendar_default';
		$('#calendar_month_' + (today.getMonth() + 1))[0].className = 'calendar_today';
	}
	$('#calendar_year_' + currday.getFullYear())[0].className = 'calendar_checked';
	$('#calendar_month_' + (currday.getMonth() + 1))[0].className = 'calendar_checked';
	$('#hourminute')[0].style.display = addtime ? '' : 'none';
	lastcheckedyear = currday.getFullYear();
	lastcheckedmonth = currday.getMonth() + 1;
	if(is_ie && is_ie < 7) {
		$('#calendariframe').css('top',$('#calendar')[0].style.top);
		$('#calendariframe').css('left',$('#calendar')[0].style.left);
		$('#calendariframe').css('width',$('#calendar')[0].offsetWidth);
		$('#calendariframe').css('height',$('#calendar')[0].offsetHeight);
		$('#calendariframe').css('display','block');
	}
}

function refreshcalendar(y, m) {
	var x = new Date(y, m, 1);
	var mv = x.getDay();
	var d = x.getDate();
	var dd = null;
	yy = x.getFullYear();
	mm = x.getMonth();
	$("#year").html(yy);
	$("#month").html(mm + 1 > 9  ? (mm + 1) : '0' + (mm + 1));

	for(var i = 1; i <= mv; i++) {
		dd = $("#d" + i)[0];
		dd.innerHTML = "&nbsp;";
		dd.className = "";
	}

	while(x.getMonth() == mm) {
		dd = $("#d" + (d + mv))[0];
		dd.innerHTML = '<a href="###" onclick="settime(' + d + ');return false">' + d + '</a>';
		if(x.getTime() < today.getTime() || (enddate && x.getTime() > enddate.getTime()) || (startdate && x.getTime() < startdate.getTime())) {
			dd.className = 'calendar_expire';
		} else {
			dd.className = 'calendar_default';
		}
		if(x.getFullYear() == today.getFullYear() && x.getMonth() == today.getMonth() && x.getDate() == today.getDate()) {
			dd.className = 'calendar_today';
			dd.firstChild.title = '&#20170;&#22825;';
		}
		if(x.getFullYear() == currday.getFullYear() && x.getMonth() == currday.getMonth() && x.getDate() == currday.getDate()) {
			dd.className = 'calendar_checked';
		}
		x.setDate(++d);
	}

	while(d + mv <= 42) {
		dd = $("#d" + (d + mv))[0];
		dd.innerHTML = "&nbsp;";
		d++;
	}

	if(addtime) {
		$('#hour')[0].value = zerofill(hh);
		$('#minute')[0].value = zerofill(ii);
	}
}

function showdiv(id) {
	var p = getposition($("#"+id)[0]);
	$('#calendar_' + id).css('left',p['x']+'px');
	$('#calendar_' + id).css('top',(p['y'] + 16)+'px');
	$('#calendar_' + id).css('display','block');
	if(is_ie && is_ie < 7) {
		$('#calendariframe_' + id).css('top',$('#calendar_' + id)[0].style.top);
		$('#calendariframe_' + id).css('left',$('#calendar_' + id)[0].style.left);
		$('#calendariframe_' + id).css('width',$('#calendar_' + id)[0].offsetWidth);
		$('#calendariframe_' + id ).css('height',$('#calendar_' + id)[0].offsetHeight);
		$('#calendariframe_' + id).css('display','block');
	}
}

function zerofill(s) {
	var s = parseFloat(s.toString().replace(/(^[\s0]+)|(\s+$)/g, ''));
	s = isNaN(s) ? 0 : s;
	return (s < 10 ? '0' : '') + s.toString();
}

loadcalendar();
