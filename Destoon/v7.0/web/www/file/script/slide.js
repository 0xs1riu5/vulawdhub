/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
function dslide(id, time) {
	var _this = this;
	this.w = $('#'+id).width();
	this.h = $('#'+id).height();
	this.c = 0;
	this.src = [];
	this.url = [];
	this.alt = [];
	this.tar = [];
	$('#'+id).find('a').each(function(i) {
		_this.src.push($(this).find('img')[0].src);
		_this.alt.push($(this).find('img')[0].alt);
		_this.url.push(this.href);
		_this.tar.push(this.target);
	});
	if(!this.src[0]) return;
	this.max = this.src.length;
	this.htm = '<a href="'+this.url[0]+'" target="'+this.tar[0]+'" id="'+id+'_url"><img src="'+this.src[0]+'" width="'+this.w+'" height="'+this.h+'" id="'+id+'_img"/></a>';
	if(this.alt[0]) this.htm += '<div style="width:'+this.w+'px;height:24px;line-height:24px;overflow:hidden;z-index:1;position:relative;margin-top:-24px;background:#384349;filter:Alpha(Opacity=80);opacity:0.8;">&nbsp;</div>';
	if(this.alt[0]) this.htm += '<div id="'+id+'_alt" style="width:'+(this.w-12)+'px;height:24px;line-height:24px;overflow:hidden;z-index:2;position:relative;margin-top:-24px;padding:0 6px 0 6px;font-weight:bold;color:#FFFFFF;">'+this.alt[0]+'</div>';
	this.htm += '<div style="width:'+(this.w-8)+'px;height:20px;overflow:hidden;z-index:3;position:relative;margin-top:-20px;padding:0 4px 0 4px;text-align:right;">';
	for(var i = 0; i < this.max; i++) {
		this.htm += '<span id="'+id+'_no_'+i+'" style="padding:1px 5px 1px 5px;margin-left:1px;cursor:pointer;font-size:12px;color:#FFFFFF;'+(i == this.c ? 'background:#FF7E00;' : 'background:#838B90;')+'">'+(i+1)+'</span>';
	}
	this.htm += '</div>';
	$('#'+id).html(this.htm);
	if(this.max == 1) return;
	this.t;
	this.p = 0;
	$('#'+id).mouseover(function() {_this.p=1;});
	$('#'+id).mouseout(function() {_this.p=0;});
	$('#'+id).find('span').each(function(i) {
		$(this).mouseover(function() {
			_this.slide(i);
		});
	});
	this.slide = function(o) {
		if(o == this.c) return;
		$('#'+id+'_img').fadeOut(50);
		$('#'+id+'_img').attr("src", this.src[o]);
		$('#'+id+'_img').fadeIn(200);
		$('#'+id+'_url').attr("href", this.url[o] ? this.url[o] : '###');
		$('#'+id+'_url').attr("target", this.url[o] ? this.tar[o] : '_self');
		$('#'+id+'_no_'+this.c).css({background:'#828A8F'});
		$('#'+id+'_no_'+o).css({background:'#FF7E00'});
		if(this.alt[0]) $('#'+id+'_alt').html(this.alt[o]);
		this.c = o;
	}
	this.start = function() {
		if(this.p) return;
		if(this.c == this.max - 1) {
			this.slide(0);
		} else {
			this.slide(this.c+1);
		}
	}
	if(!time) time = 5000;
	this.t = setInterval(function() {_this.start();}, time);
}
//e.g. new dslide(id, time);