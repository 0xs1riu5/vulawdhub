/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
var property_Interval = setInterval('ppt_attach_catid()', 500);
function ppt_attach_catid() {
	if(Dd('catid_1').value != property_catid) {
		property_catid = Dd('catid_1').value
		if(property_catid > 0) load_property();
	}
}
function load_property() {
	$.post(AJPath, 'action=property&itemid='+property_itemid+'&catid='+property_catid+'&admin='+property_admin, function(data) {
		if(data) {
			$('#load_property').html(data);
			$('#load_property').show();
		} else {
			$('#load_property').html('<tr><td></td><td></td></tr>');
			$('#load_property').hide();
		}
	});
}
$(function(){
	if(property_catid) load_property();
});