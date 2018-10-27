/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
var cur = max = X1 = X2 = 0;
if(TB[1]) {max++;Ds('dot_0');Ds('dot_1');}
if(TB[2]) {max++;Ds('dot_2');}	
function album_show(obj) {
	if(max == 0) return;
	if(TB[obj] && obj != cur) {
		Dd('photo').src = TB[obj];
		Dd('dot_'+obj).className = 'album_c';
		Dd('dot_'+cur).className = 'album_o';
		cur = obj;
	}
}
function album_next() {
	if(cur == max) {
		album_show(0);
	} else {
		album_show(cur + 1);
	}
}
function album_prev() {
	if(cur == 0) {
		album_show(max);
	} else {
		album_show(cur - 1);
	}
}
$(document).on('pageinit', function(event) {
	$('#album').on('swipeleft click',function(){
		album_next();
	});
	$('#album').on('swiperight',function(){
		album_prev();
	});
});