/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
var area_id;
function load_area(areaid, id) {
	area_id = id; area_areaid[id] = areaid;
	$.post(AJPath, 'action=area&area_title='+area_title[id]+'&area_extend='+area_extend[id]+'&area_id='+area_id+'&areaid='+areaid, function(data) {
		$('#areaid_'+area_id).val(area_areaid[area_id]);
		if(data) $('#load_area_'+area_id).html(data);
	});
}