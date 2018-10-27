/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware,use is subject to license.txt
*/
var d_date,c_year,c_month,t_year,t_month,t_day,v_year,v_month,v_day,v_hour,v_minute,v_second,ca_sep,ca_tm,ca_id,ca_interval,ca_timeout;var today=new Date();t_year=today.getYear();t_year=(t_year > 200)?t_year:1900+t_year;t_month=today.getMonth()+1;t_day=today.getDate();var ca_htm='';
ca_htm += '<table width="100%" cellpadding="0" cellspacing="0"><tr style="height:32px;background:#666666;text-align:center;color:#FFFFFF;font-weight:bold;font-family:Verdana;user-select:none;-moz-user-select:none;" onselectstart="return false"><td style="width:10px;"></td>';ca_htm += '<td onclick="ca_prev_year();" onmousedown="ca_setInterval(\'ca_prev_year\');" onmouseup="ca_clearInterval();" style="width:20px;cursor:pointer;font-size:18px;" valign="top" title="'+L['prev_year']+'"><div style="padding-top:2px;">&laquo;</div></td><td style="width:40px;"><input type="text" maxlength="4" style="width:36px;border:none;color:#FFFFFF;text-align:center;background:#666666;font-size:12px;" id="ca_year" onblur="ca_this_year();" onkeyup="ca_this_year(1);" ondblclick="this.value=\'\';"/></td><td onclick="ca_next_year();" onmousedown="ca_setInterval(\'ca_next_year\');" onmouseup="ca_clearInterval();" style="width:20px;cursor:pointer;font-size:18px;" valign="top" title="'+L['next_year']+'"><div style="padding-top:2px;">&raquo;</div></td><td style="width:10px;"></td><td onclick="ca_prev_month();" onmousedown="ca_setInterval(\'ca_prev_month\');" onmouseup="ca_clearInterval();" style="width:20px;cursor:pointer;font-size:18px;" valign="top" title="'+L['prev_month']+'"><div style="padding-top:2px;">&laquo;</div></td><td style="width:20px;"><input type="text" maxlength="2" style="width:16px;border:none;color:#FFFFFF;text-align:center;background:#666666;font-size:12px;" id="ca_month" onblur="ca_this_month();" onkeyup="ca_this_month(1);" ondblclick="this.value=\'\';"/></td><td onclick="ca_next_month();" onmousedown="ca_setInterval(\'ca_next_month\');" onmouseup="ca_clearInterval();" style="width:20px;cursor:pointer;font-size:18px;" valign="top" title="'+L['next_month']+'"><div style="padding-top:2px;">&raquo;</div></td><td></td><td style="cursor:pointer;font-size:20px;width:32px;"  onmouseover="this.style.backgroundColor=\'#CE3C39\';" onmouseout="this.style.backgroundColor=\'#666666\';" onclick="ca_close();">&#215;</td></tr></table><div id="d_ca_show" style="text-align:center;"></div>';
function get_days(year,month){
	d_date=new Date(year,month,1);
	d_date=new Date(d_date-(24*60*60*1000));
	return d_date.getDate();
}
function get_start(year,month){
	d_date=new Date(year,month-1,1);
	return d_date.getDay();
}
function ca_setInterval(func){
	ca_timeout=setTimeout(function(){ca_interval=setInterval(func+'()',200);},100);
}
function ca_clearInterval(){
	clearTimeout(ca_timeout);clearInterval(ca_interval);
}
function ca_this_year(u){
	if(u && Dd('ca_year').value.length<4){return;}
	if(Dd('ca_year').value.match(/^(\d{4})$/)){
		c_year=parseInt(Dd('ca_year').value);ca_setup(c_year,c_month);
	}else{
		Dd('ca_year').value=c_year;
	}
}
function ca_next_year(){c_year=parseInt(c_year)+1;ca_setup(c_year,c_month);}
function ca_prev_year(){c_year=parseInt(c_year)-1;ca_setup(c_year,c_month);}
function ca_this_month(u){
	if(u && Dd('ca_month').value.length<1){return;}
	if(Dd('ca_month').value.match(/^(\d{1,2})$/)){
		c_month=parseInt(Dd('ca_month').value);ca_setup(c_year,c_month);
	}else{
		Dd('ca_month').value=c_month;
	}
}
function ca_next_month(){
	if(c_month==12){
		c_year=parseInt(c_year)+1;c_month=1;
	}else{
		c_month=parseInt(c_month)+1;
	}
	ca_setup(c_year,c_month);
}
function ca_prev_month(){
	if(c_month==1){
		c_year=parseInt(c_year)-1;c_month=12;
	}else{
		c_month=parseInt(c_month)-1;
	}
	ca_setup(c_year,c_month);
}
function ca_setup(year,month){
	if(year > 9999){year=9999;}
	if(year<1970){year=1970;}
	if(month > 12){month=12;}
	if(month<1){month=1;}
	c_year=year;
	c_month=month;
	var days=get_days(year,month);
	var start=get_start(year,month);
	var end=7-(days+start)%7;
	if(end==7 ){end=0;}
	var calendar='';
	var weeks=[L['Sun'],L['Mon'],L['Tue'],L['Wed'],L['Thu'],L['Fri'],L['Sat']];
	var cells=new Array;
	var j=i=l=0;
	Dd('ca_year').value=year;
	Dd('ca_month').value=month;
	if(start){for(i=0;i<start;i++){cells[j++]=0;}}
	for(i=1;i<= days;i++){cells[j++]=i;}
	if(end){for(i=0;i<end;i++){cells[j++]=0;}}
	calendar += '<table cellpadding="0" cellspacing="0" width="100%" style="border:#E0E0E0 1px solid;"><tr style="user-select:none;-moz-user-select:none;">';
	for(i=0;i<7;i++){calendar += '<td style="width:32px;height:32px;background:#F1F1F1;font-weight:bold;font-size:12px;">'+(weeks[i])+'</td>';}
	calendar += '</tr>';
	l=cells.length
	for(i=0;i<l;i++){
		if(i%7==0){calendar += '<tr>';}
		if(cells[i]){
			calendar += '<td style="cursor:pointer;height:32px;font-size:12px;border-top:#E0E0E0 1px solid;'+(i%7==6?'':'border-right:#E0E0E0 1px solid;')+'';
			if(year+'-'+month+'-'+cells[i]==v_year+'-'+v_month+'-'+v_day){
				calendar += 'background:#FFFF00;"';
			}else if(year+'-'+month+'-'+cells[i]==t_year+'-'+t_month+'-'+t_day){
				calendar += 'font-weight:bold;color:#FF0000;"';
			}else{
				calendar += 'background:#FFFFFF;" onmouseover="this.style.backgroundColor=\'#DDDDDD\';" onmouseout="this.style.backgroundColor=\'#FFFFFF\';"';
			}
			calendar += 'title="'+year+'-'+ca_padzero(month)+'-'+ca_padzero(cells[i])+'" onclick="ca_select('+year+','+month+','+cells[i]+')"> '+cells[i]+' </td>';
		}else{
			calendar += '<td style="border-top:#E0E0E0 1px solid;'+(i%7==6?'':'border-right:#E0E0E0 1px solid;')+'">&nbsp;</td>';
		}
		if(i%7==6){calendar += '</tr>';}
	}
	if(ca_tm){
		calendar += '<tr style="height:48px;text-align:center;"><td colspan="7" style="border-top:#E0E0E0 1px solid;">';
		calendar += '<select id="ca_hour" onchange="ca_time();">';
		var j='';
		for(var i=0;i<24;i++){
			j=ca_padzero(i);
			calendar += '<option value="'+j+'"'+(j==v_hour?' selected':'')+'>'+j+'</option>';
		}
		calendar += '</select> : <select id="ca_minute" onchange="ca_time();">';
		for(var i=0;i<60;i++){
			j=ca_padzero(i);
			calendar += '<option value="'+j+'"'+(j==v_minute?' selected':'')+'>'+j+'</option>';
		}
		calendar += '</select> : <select id="ca_second" onchange="ca_time();">';
		for(var i=0;i<60;i++){
			j=ca_padzero(i);
			calendar += '<option value="'+j+'"'+(j==v_second?' selected':'')+'>'+j+'</option>';
		}
		calendar += '</select></td></tr>';
	}
	calendar += '</table>';
	Dd('d_ca_show').innerHTML=calendar;
}
function ca_show(id,sep,tm){
	Eh();
	ca_tm=tm?1:0;
	if(Dd('d_calendar')==null){
		var d_ca_div=document.createElement("div");
		d_ca_div.id='d_calendar';
		document.body.appendChild(d_ca_div);
		$('#d_calendar').css({'zIndex':'9999','position':'absolute','display':'none','width':'240px','background':'#FFFFFF'});
	}
	ca_sep=sep;
	ca_id=id;
	if(Dd(id).value){
		if(sep){
			var arr=Dd(id).value.substring(0,8+sep.length*2).split(sep);
			c_year=v_year=arr[0];
			c_month=v_month=ca_cutzero(arr[1]);
			v_day=ca_cutzero(arr[2]);
		}else{
			c_year=v_year=Dd(id).value.substring(0,4);
			c_month=v_month=ca_cutzero(Dd(id).value.substring(4,6));
			v_day=ca_cutzero(Dd(id).value.substring(6,8));
		}
		if(ca_tm){
			var arr=Dd(id).value.substring(9+sep.length*2).split(':');
			v_hour=arr[0];
			v_minute=arr[1];
			v_second=arr[2];
		}
	}else{
		c_year=t_year;
		c_month=t_month;
		if(ca_tm){
			v_hour=23;
			v_minute=59;
			v_second=59;
		}
	}
	$('#d_calendar').css({'left':$('#'+id).offset().left+'px','top':($('#'+id).offset().top+$('#'+id).height()+10)+'px'});
	$('#d_calendar').html(ca_htm);
	$('#d_calendar').fadeIn(300);
	ca_setup(c_year,c_month);

}
function ca_time() {
	if(Dd(ca_id).value) Dd(ca_id).value=Dd(ca_id).value.substring(0,8+ca_sep.length*2) +' '+Dd('ca_hour').value+':'+Dd('ca_minute').value+':'+Dd('ca_second').value;
}
function ca_select(year,month,day){
	month=ca_padzero(month);
	day=ca_padzero(day);
	Dd(ca_id).value=year+ca_sep+month+ca_sep+day+(ca_tm?' '+Dd('ca_hour').value+':'+Dd('ca_minute').value+':'+Dd('ca_second').value:'');
	ca_hide();
}
function ca_padzero(num){return (num<10)? '0'+num:num ;}
function ca_cutzero(num){return num.substring(0,1)=='0'?num.substring(1,num.length):num;}
function ca_hide(){$('#d_calendar').fadeOut(300);Es();}
function ca_close(){ca_hide();}