/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
function check_kw() {if(Dd('kw').value == L['keyword_value'] || Dd('kw').value.length<2) {alert(L['keyword_message']); Dd('kw').focus(); return false;}}
function Df(url, etc) {document.write('<iframe src="'+url+'" scrolling="no" frameborder="0" '+etc+'></iframe>');}
function show_date() {
	var dt_day = dt_month = dt_weekday = '';
	var dt_week = [L['Sunday'], L['Monday'], L['Tuesday'], L['Wednesday'], L['Thursday'], L['Friday'], L['Saturday']];
	dt_today = new Date();
	dt_weekday = dt_today.getDay();
	dt_month = dt_today.getMonth()+1;
	dt_day = dt_today.getDate();
	document.write(lang(L['show_date'], [dt_month, dt_day, dt_week[dt_weekday]]));
}