/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
function guest_log(id) {
	if($('#'+id)[0]) {
		v = get_local('guest_'+id);
		if(safe_input(v) && !$('#'+id).val()) $('#'+id).val(v);
		$('#'+id).blur(function(){
			v = $('#'+id).val();
			if(safe_input(v)) set_local('guest_'+id, v);
		});
	}
}
function safe_input(v) {
	if(v) {
		var a = ['%', '"', "'", '}', ']', '>', '#', ';', ','];
		for(var i in a) {
			if(v.indexOf(a[i]) != -1) return false;
		}	
		return true;
	}
	return false;
}
var guest_ids = ['company', 'truename', 'telephone', 'mobile', 'email', 'address', 'postcode', 'qq', 'wx', 'ali', 'skype', 'receive'];
for(var i in guest_ids) {
	guest_log(guest_ids[i]);
}
if($('#load_area_1')[0] && parseInt($('#areaid_1').val()) == 0) {
	v = get_local('guest_areaid');
	if(v) load_area(v, 1);
	$('#load_area_1').mouseout(function(){
		v = parseInt($('#areaid_1').val());
		if(v > 0) set_local('guest_areaid', v);
	});
}