/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
var mid_div = Dd('mid_div');
var mid_pic = Dd('mid_pic');
var big_div = Dd('big_div');
var big_pic = Dd('big_pic');
var zoomer = Dd('zoomer');
var AL = Dd('mid_pos').offsetLeft + 5;
var AT = Dd('mid_pos').offsetTop + 5;
var ZW = zoomer.clientWidth;
var ZH = zoomer.clientHeight;
var PW = mid_pic.clientWidth;
var PH = mid_pic.clientHeight;
function MAlbum(e) {
    e = e || window.event;
	var l,t,ll,tt;
	eX = e.clientX;
    var pl = (big_pic.clientWidth - big_div.clientWidth)/(PW - ZW);
	if(eX <= AL + ZW/2) {
		l = AL;
		ll = 0;
	} else if(eX >= AL + (PW - ZW/2)) {
		l = AL + PW - ZW;
		ll = big_div.clientWidth - big_pic.clientWidth;
	} else {
		l = eX - ZW/2;
		ll = parseInt((AL - eX + ZW/2) * pl);
	}
	if(big_pic.clientWidth < big_div.clientWidth) ll = 0;
	eY = e.clientY + $(document).scrollTop();
    var pt = (big_pic.clientHeight - big_div.clientHeight)/(PH - ZH);
	if(eY <= AT + ZH/2) {
		t = AT;
		tt = 0;
	} else if(eY >= AT + (PH - ZH/2)) {
		t = AT + PH - ZH;
		tt = big_div.clientHeight - big_pic.clientHeight;
	} else {
		t = eY - ZH/2;
		tt =  parseInt((AT - eY + ZH/2) * pt);
	}
	if(big_pic.clientHeight < big_div.clientHeight) tt = 0;
    zoomer.style.left = l + 'px';
    zoomer.style.top = t + 'px';
	big_pic.style.left = ll + 'px';
	big_pic.style.top = tt + 'px';
}
function Album(id, s) {
	for(var i=0; i<3; i++) {Dd('t_'+i).className = i==id ? 'ab_on' : 'ab_im';}
	Dd('mid_pic').src = s;
}
function SAlbum() {
	s = Dd('mid_pic').src;
	if(s.indexOf('nopic320.gif') != -1) return;
	if(s.indexOf('.middle.') != -1) s = s.substring(0, s.length-8-ext(s).length);
	Dd('big_pic').src = s;
	Ds('big_div');
	Ds('zoomer');
}
function HAlbum() {Dh('zoomer');Dh('big_div');}
function PAlbum(o) {if(o.src.indexOf('nopic320.gif')==-1) View(o.src);}
Dh('zoomer');
mid_div.onmousemove = MAlbum;