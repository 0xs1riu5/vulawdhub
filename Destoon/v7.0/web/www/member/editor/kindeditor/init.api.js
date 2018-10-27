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
			return editor.html();
		break;
		case 'set':
			editor.html(v);
		break;
		case 'ins':
			if(editor.designMode) {editor.insertHtml(v);} else {alert(L['wysiwyg_mode']);}
		break;
		case 'len':
			return editor.count('text');
		break;
		default:
		break;
	}
}