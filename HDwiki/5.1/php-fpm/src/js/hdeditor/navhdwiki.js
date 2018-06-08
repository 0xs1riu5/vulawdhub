var g_content_id="content";
if (typeof g_filterExternal == 'undefined') var g_filterExternal = 0;
HD.show({
	id : g_content_id,
	minHeight:400,
	skinType : 'editor',
	cssPath : 'js/hdeditor/skins/content.css',
	id_container:'hd_container',
	id_toolbar:'hd_container',
	position_toolbar:'position_toolbar',
	position_content:'hd_content',
	filterExternal:g_filterExternal,
	items : ['undo','redo','cut','copy','paste','bold','fontstyle','justifyleft','justifymore', 'title1', 'title2', 'innerlink', 'image', 'media','table','specialchar','clean','source']
});
UnloadConfirm.clear();