function EditorAPI(i, k, v) {
	var i = i ? i : 'content';
	var k = k ? k : 'len';
	var v = v ? v : '';
	switch(k) {
		case 'get':
			return um.getContent();
		break;
		case 'set':
			um.setContent(v);
		break;
		case 'ins':
			um.execCommand('inserthtml', v);
		break;
		case 'len':
			return um.getContentTxt().length;
		break;
		default:
		break;
	}
}