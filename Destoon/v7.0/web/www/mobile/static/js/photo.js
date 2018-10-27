/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
function photo_show(obj, id) {
	var cur = parseInt($('#cur_'+id).html());
	if(obj != cur) {
		$('#photo_'+id).attr('src', $('#image_'+id+'_'+(obj-1)).html());
		$('#photo_page_'+id).html(obj);
		$('#photo_intro').html($('#intro_'+id+'_'+(obj-1)).html());
		cur = obj;
		$('#cur_'+id).html(cur);
	}
}
function photo_next(id) {
	var cur = parseInt($('#cur_'+id).html());
	var max = parseInt($('#max_'+id).html());
	if(cur >= max) {
		photo_show(1, id);
	} else {
		photo_show(cur + 1, id);
	}
}
function photo_prev(id) {
	var cur = parseInt($('#cur_'+id).html());
	var max = parseInt($('#max_'+id).html());
	if(cur <= 1) {
		photo_show(max, id);
	} else {
		photo_show(cur - 1, id);
	}
}