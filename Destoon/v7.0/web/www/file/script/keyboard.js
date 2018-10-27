/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
var kb_iid; var kb_kid;
var kb_chars = ['', '`1234567890-=', '~!@#$%^&*()_+', 'qwertyuiop[]\\', 'QWERTYUIOP{}|', 'asdfghjkl;\'', 'ASDFGHJKL:"', 'zxcvbnm,./', 'ZXCVBNM<>?'];
document.write('<style>#kb{position:absolute;z-index:999;border:#AAB2BD 1px solid;background:#D0D5DB;padding:10px;border-radius:5px;}#kb input{padding:10px 6px;cursor:pointer;font-family:Verdana;font-size:16px;color:#000000;width:25px;border:none;border-bottom:#444444 1px solid;background:#FFFFFF;border-radius:3px;}#kb input:hover{background:#1AAD19;color:#FFFFFF;}</style>');
function kb_h() {$('#'+kb_kid).fadeOut(300);}
function kb_d() {var p= Dd(kb_iid).value;var l = p.length;if(l == 0){kb_h();return;} else if(l == 1) {Dd(kb_iid).value = '';} else {Dd(kb_iid).value = p.substring(0, l - 1);}}
function kb_u(v) {for(var i = 1; i < 9; i++) {Dd('table_'+i).style.display = (i%2 == v ? 0 : 1) ? '' : 'none';}}
function kb_i(v) {if(v == '&quot;') {v = '"';} Dd(kb_iid).value += v;}
function kb_s(i, k) {
	kb_iid = i; kb_kid = k;
	var htm = '';
	for(var i = 1; i < 9; i++) {
		var l = kb_chars[i].length; var r = Math.floor(Math.random()*l); var s = i%2 == 0 ? 'none' : '';
		htm += '<table id="table_'+i+'" style="display:'+s+';"><tr>';
		for(var j = r; j >= 0; j--) {
			var v = kb_chars[i].charAt([j]);
			if(v == '"') v = '&quot;';
			htm += '<td title=" '+v+' "><input type="button" value="'+v+'" onclick="kb_i(this.value)"/></td>';
		}
		for(var j = r+1; j < l; j++) {
			var v = kb_chars[i].charAt([j]);
			if(v == '"') v = '&quot;';
			htm += '<td title=" '+v+' "><input type="button" value="'+v+'" onclick="kb_i(this.value)"/></td>';
		}
		if(i == 5) htm += '<td title="Enter"><input type="button" value="Enter" onclick="kb_h();" style="width:54px;background:#007AFF;color:#FFFFFF;"/></td>';
		if(i == 6) htm += '<td title="Enter"><input type="button" value="Enter" onclick="kb_h();" style="width:54px;background:#007AFF;color:#FFFFFF;"/></td>';
		if(i == 7) htm += '<td title="Shift"><input type="button" value="Shift" onclick="kb_u(1);" style="width:54px;"/><td title="Backspace"><input type="button" value="x" onclick="kb_d();" style="background:#AAB2BE;color:#000000;"/></td>';
		if(i == 8) htm += '<td title="Shift"><input type="button" value="Shift" onclick="kb_u(0);" style="width:54px;"/><td title="Backspace"><input type="button" value="x" onclick="kb_d();" style="background:#AAB2BE;color:#000000;"/></td>';
		htm += '</tr></table>';
	}
	$('#'+kb_kid).css({'left':$('#'+kb_iid).offset().left+'px','top':($('#'+kb_iid).offset().top+$('#'+kb_iid).height()+10)+'px'});
	$('#'+kb_kid).html(htm);
	$('#'+kb_kid).fadeIn(300);
}
