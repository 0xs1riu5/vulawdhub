/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
var cat_id;
function load_category(catid, id) {
	cat_id = id; category_catid[id] = catid;
	$.post(AJPath, 'action=category&category_title='+category_title[id]+'&category_moduleid='+category_moduleid[id]+'&category_extend='+category_extend[id]+'&category_deep='+category_deep[id]+'&cat_id='+cat_id+'&catid='+catid, function(data) {
		$('#catid_'+cat_id).val(category_catid[cat_id]);
		if(data) $('#load_category_'+cat_id).html(data);
	});
}