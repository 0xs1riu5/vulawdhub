function EditorAPI(i, k, v) {
	var i = i ? i : 'content';
	var k = k ? k : 'len';
	var v = v ? v : '';
	switch(k) {
		case 'get':
			return ue.getContent();
		break;
		case 'set':
			ue.setContent(v);
		break;
		case 'ins':
			ue.execCommand('inserthtml', v);
		break;
		case 'len':
			return ue.getContentTxt().length;
		break;
		default:
		break;
	}
}