/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
var _p0 = _p1 = 0;
function AutoTab0() {
	Dd('ian').onmouseover = function() {_p0 = 1;} 
	Dd('ian').onmouseout = function() {_p0 = 0;}
	if(_p0) return;
	var c;
	for(var i = 1; i < 4; i++) { if(Dd('ian-h-'+i).className == 'on') {c = i;} }
	c++; 
	if(c > 3) c = 1;
	Tb(Dd('ian-h-'+c));
}
function AutoTab1() {
	Dd('itrade').onmouseover = function() {_p1 = 1;} 
	Dd('itrade').onmouseout = function() {_p1 = 0;}
	if(_p1) return;
	var c;
	var a = new Array;
	var i = 0;
	$('#trade-h').children().each(function() {
		if($(this).attr('class') == 'on') c = i;
		a[i++] = $(this).attr('id');
	});
	a[i++] = a[0];
	Tb(Dd(a[c+1]));
}
$(function(){
	if(Dd('brands') != null) new dmarquee(220, 10, 3000, 'brands');
	if(Dd('ian') != null) window.setInterval('AutoTab0()', 5000);
	if(Dd('itrade') != null) window.setInterval('AutoTab1()', 8000);
});