/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
function EditorAPI(i, k, v) {
	var i = i ? i : 'content';
	var k = k ? k : 'len';
	var v = v ? v : '';
	switch(k) {
		case 'get':
			return FCKeditorAPI.GetInstance(i).GetXHTML(true);
		break;
		case 'set':
			FCKeditorAPI.GetInstance(i).SetData(v);
		break;
		case 'ins':
			var o = FCKeditorAPI.GetInstance(i);
			if(o.EditMode == FCK_EDITMODE_WYSIWYG) {o.InsertHtml(v);} else {alert(L['wysiwyg_mode']);}
		break;
		case 'len':
			var o = FCKeditorAPI.GetInstance(i);
			var d = o.EditorDocument;
			var l ;
			if(document.all) {
				return d.body.innerText.length;
			} else {
				var r = d.createRange(); 
				r.selectNodeContents(d.body);
				return r.toString().length;
			}
		break;
		default:
		break;
	}
}