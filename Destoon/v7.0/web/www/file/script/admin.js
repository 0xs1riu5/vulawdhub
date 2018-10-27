/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
var dgX = dgY = 0; var dgDiv;
function mkDialog(u, c, t, w, s, p, px, py) {
	var w = w ? w : 300;
	var u = u ? u : '';
	var c = c ? c : (u ? '<iframe src="'+u+'" width="'+(w-25)+'" height="0" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="no"></iframe>' : '');
	var t = t ? t : L['system_tips'];
	var s = s ? s : 0;
	var p = p ? p : 0;
	var px = px ? px : 0;
	var py = py ? py : 0;
	var cw = $(window).width();
	var ch = $(window).height();
	var bsh = $(document).height();
	var bst = $(document).scrollTop();
	var bsl = $(document).scrollLeft();
	var bh = parseInt((bsh < ch) ? ch : bsh);
	if(!s) {
		var Dmid = document.createElement("div");
		with(Dmid.style){zIndex = 9998; position = 'absolute'; width = '100%'; height = bh+'px'; overflow = 'hidden'; top = 0; left = 0; border = "0px"; backgroundColor = '#DDDDDD'; opacity = 0.5; filter = " Alpha(Opacity=50)";}
		Dmid.id = "Dmid";
		document.body.appendChild(Dmid);
		$('#Dmid').click(function(){cDialog();});
	}
	var sl = px ? px : bsl + parseInt((cw-w)/2);
	var st = py ? py : bst + parseInt(ch/2) - 100;
	var Dtop = document.createElement("div");
	with(Dtop.style){zIndex = 9999; position = 'absolute'; width = w+'px'; left = sl+'px'; top = st+'px'; display = 'none';}
	Dtop.id = 'Dtop';
	document.body.appendChild(Dtop);
	$('#Dtop').html('<div class="dbody"><div class="dhead" ondblclick="cDialog();" onmousedown="dragstart(\'Dtop\', event);"  onmouseup="dragstop(event);" onselectstart="return false;"><span onclick="cDialog();" title="'+L['dialog_close']+'"></span>'+t+'</div><div class="dbox">'+c+'</div>'+((c.indexOf('<iframe') != -1 && c.indexOf('scrolling="no"') == -1) ? '<div class="dsize"><div onmousedown="resizestart(\'Dtop\', event);" onmouseup="resizestop(event);"></div></div>' : '')+'</div>');
	Eh();
	$('#Dtop').show(1, function() {
		st = py ? py : bst + parseInt(ch/2) - parseInt($('#Dtop').height()/2);
		$('#Dtop').animate({top:st+'px'}, 1, function() {
			if(c.indexOf('DP_image') != -1) {
				var _stop = 0;
				$('.DP_image').load(function() {
					var _sl = px ? px : bsl + parseInt((cw-$('.DP_image').width())/2);
					if(_sl < 10) _sl = 10;
					var _st = py ? py : bst + parseInt((ch-$('.DP_image').height())/2);
					if(_st < 10) _st = 10;
					$('#Dtop').animate({width:($('.DP_image').width())+'px',left:_sl+'px',top:_st+'px'}, 50);
					_stop = 1;
				});
				if(!_stop) {
					$('.DP_image').ready(function() {
						var _sl = px ? px : bsl + parseInt((cw-$('.DP_image').width())/2);
						if(_sl < 10) _sl = 10;
						var _st = py ? py : bst + parseInt((ch-$('.DP_image').height())/2);
						if(_st < 10) _st = 10;
						$('#Dtop').animate({width:($('.DP_image').width())+'px',left:_sl+'px',top:_st+'px'}, 50);
					});
				}
			}
		});
	});
}
function cDialog() {
	$('#Dmid').remove();
	$('#Dtop').fadeOut('fast', function() {
		$('#Dtop').remove();
		Es();
	});
}
function Dalert(c, w, s, t) {
	if(!c) return;
	var s = s ? s : 0; var w = w ? w : 350; var t = t ? t : 0;
	c = '<div style="padding:16px 16px 0 16px;">'+c+'</div><div style="padding:16px;text-align:center;"><input type="button" class="btn-b" value=" '+L['ok']+' " onclick="cDialog();"/></div>';
	mkDialog('', c, '', w, s);
	if(t) window.setTimeout(function(){cDialog();}, t);
}
function Dconfirm(c, u, w, s) {
	if(!c) return;
	var s = s ? s : 0; var w = w ? w : 350; var d = u ? (u.indexOf('logout') == -1 ? 'window' : 'top')+".location = '"+u+"'" : 'cDialog()';
	c = '<div style="padding:16px 16px 0 16px;">'+c+'</div><div style="padding:16px;text-align:center;"><input type="button" class="btn-b" value=" '+L['ok']+' " onclick="'+d+'"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn" value=" '+L['cancel']+' " onclick="cDialog();"/></div>';
	mkDialog('', c, '', w, s);
}
function Diframe(u, w, s, l, t) {
	var s = s ? s : 0; var w = w ? w : 350; var l = l ? true : false;
	var c = '<iframe src="'+u+'" width="'+(w-25)+'" height=0" id="diframe" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="no"></iframe><div style="padding:16px;text-align:center;"><input type="button" class="btn-b" value=" '+L['ok']+' " onclick="cDialog();"/></div>';
	if(l) c = '<div id="dload" style="line-height:22px;">Loading...</div>'+c;
	mkDialog('', c, t, w, s);
}
function Dtip(c, w, t) {
	if(!c) return;
	var w = w ? w : 350; var t = t ? t : 2000;
	mkDialog('', '<div style="padding:16px;">'+c+'</div>', '', w);
	window.setTimeout(function(){cDialog();}, t);
}
function Dfile(m, o, i, e) {
	var e = e ? e : '';
	var c = '<iframe name="UploadFile" style="display:none;" src=""></iframe>';
	c += '<form method="post" target="UploadFile" enctype="multipart/form-data" action="'+UPPath+'" onsubmit="return isImg(\'upfile\',\''+e+'\');"><input type="hidden" name="moduleid" value="'+m+'"/><input type="hidden" name="from" value="file"/><input type="hidden" name="old" value="'+o+'"/><input type="hidden" name="fid" value="'+i+'"/><table cellpadding="6"><tr><td style="word-break:break-all;"><input id="upfile" type="file" size="20" name="upfile" onchange="if(isImg(\'upfile\',\''+e+'\')){this.form.submit();Dd(\'Dsubmit\').disabled=true;Dd(\'Dsubmit\').value=\''+L['uploading']+'\';}"/>'+(e ? '<div style="width:210px;line-height:20px;padding-top:10px;word-break:break-all;color:#999999;">'+L['allow']+e+'</div>' : '')+'</td></tr><tr><td><input type="submit" class="btn-b" value="'+L['upload']+'" id="Dsubmit"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn" value="'+L['cancel']+'" onclick="cDialog();"/></td></tr></table></form>';
	mkDialog('', c, L['upload_file'], 250);
}
function Dthumb(m, w, h, o, s, i) {
	var s = s ? 'none' : ''; var i = i ? i : 'thumb'; var c = '<iframe name="UploadThumb" style="display:none;" src=""></iframe>';
	c += '<form method="post" target="UploadThumb" enctype="multipart/form-data" action="'+UPPath+'" onsubmit="return isUP(\'upthumb\');"><input type="hidden" name="moduleid" value="'+m+'"/><input type="hidden" name="from" value="thumb"/><input type="hidden" name="old" value="'+o+'"/><input type="hidden" name="fid" value="'+i+'"/><table cellpadding="6"><tr><td><input id="remote_0" type="radio" name="isremote" value="0" checked onclick="ReLo(0, \'upthumb\');"/><label for="remote_0"> '+L['up_local']+'</label>&nbsp;&nbsp;&nbsp;<input id="remote_1" type="radio" name="isremote" value="1" onclick="ReLo(1, \'upthumb\');"/><label for="remote_1"> '+L['up_remote']+'</label></td></tr><tr id="remote_url" style="display:none;"><td><input id="remote" type="text" size="28" name="remote" value="http://" onclick="if(this.value==\'http://\')this.value=\'\';"/></td></tr><tr id="local_url"><td><input id="upthumb" type="file" size="20" name="upthumb" accept="image/*" onchange="if(isImg(\'upthumb\')){this.form.submit();Dd(\'Dsubmit\').disabled=true;Dd(\'Dsubmit\').value=\''+L['uploading']+'\';}"/></td></tr><tr style="display:'+s+'"><td>'+L['width']+' <input type="text" size="3" name="width" value="'+w+'"/> px &nbsp;&nbsp;&nbsp;'+L['height']+' <input type="text" size="3" name="height" value="'+h+'"/> px </td></tr><tr><td><input type="submit" class="btn-b" value="'+L['upload']+'" id="Dsubmit"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn" value="'+L['cancel']+'" onclick="cDialog();"/></td></tr></table></form>';
	mkDialog('', c, L['upload_img'], 250);
}
function Dalbum(f, m, w, h, o, s) {
	var s = s ? 'none' : ''; var c = '<iframe name="UploadAlbum" style="display:none" src=""></iframe>';
	c += '<form method="post" target="UploadAlbum" enctype="multipart/form-data" action="'+UPPath+'" onsubmit="return isUP(\'upalbum\');"><input type="hidden" name="fid" value="'+f+'"/><input type="hidden" name="moduleid" value="'+m+'"/><input type="hidden" name="from" value="album"/><input type="hidden" name="old" value="'+o+'"/><table cellpadding="6"><tr><td><input id="remote_0" type="radio" name="isremote" value="0" checked onclick="ReLo(0, \'upalbum\');"/><label for="remote_0"> '+L['up_local']+'</label>&nbsp;&nbsp;&nbsp;<input id="remote_1" type="radio" name="isremote" value="1" onclick="ReLo(1, \'upalbum\');"/><label for="remote_1"> '+L['up_remote']+'</label></td></tr><tr id="remote_url" style="display:none;"><td><input id="remote" type="text" size="28" name="remote" value="http://" onclick="if(this.value==\'http://\')this.value=\'\';"/></td></tr><tr id="local_url"><td><input id="upalbum" type="file" size="20" name="upalbum" accept="image/*" onchange="if(isImg(\'upalbum\')){this.form.submit();Dd(\'Dsubmit\').disabled=true;Dd(\'Dsubmit\').value=\''+L['uploading']+'\';}"/></td></tr><tr style="display:'+s+'"><td>'+L['width']+' <input type="text" size="3" name="width" value="'+w+'"/> px &nbsp;&nbsp;&nbsp;'+L['height']+' <input type="text" size="3" name="height" value="'+h+'"/> px </td></tr><tr><td><input type="submit" class="btn-b" value="'+L['upload']+'" id="Dsubmit"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn" value="'+L['cancel']+'" onclick="cDialog();"/></td></tr></table></form>';
	mkDialog('', c, L['upload_img'], 250);
}
function Dphoto(f, m, w, h, o, s) {
	var s = s ? 'none' : ''; var c = '<iframe name="UploadPhoto" style="display:none" src=""></iframe>';
	c += '<form method="post" target="UploadPhoto" enctype="multipart/form-data" action="'+UPPath+'" onsubmit="return isUP(\'upalbum\');"><input type="hidden" name="fid" value="'+f+'"/><input type="hidden" name="moduleid" value="'+m+'"/><input type="hidden" name="from" value="photo"/><input type="hidden" name="old" value="'+o+'"/><table cellpadding="6"><tr><td><input id="remote_0" type="radio" name="isremote" value="0" checked onclick="ReLo(0, \'upalbum\');"/><label for="remote_0"> '+L['up_local']+'</label>&nbsp;&nbsp;&nbsp;<input id="remote_1" type="radio" name="isremote" value="1" onclick="ReLo(1, \'upalbum\');"/><label for="remote_1"> '+L['up_remote']+'</label></td></tr><tr id="remote_url" style="display:none;"><td><input id="remote" type="text" size="28" name="remote" value="http://" onclick="if(this.value==\'http://\')this.value=\'\';"/></td></tr><tr id="local_url"><td><input id="upalbum" type="file" size="20" name="upalbum" accept="image/*" onchange="if(isImg(\'upalbum\')){this.form.submit();Dd(\'Dsubmit\').disabled=true;Dd(\'Dsubmit\').value=\''+L['uploading']+'\';}"/></td></tr><tr style="display:'+s+'"><td>'+L['width']+' <input type="text" size="3" name="width" value="'+w+'"/> px &nbsp;&nbsp;&nbsp;'+L['height']+' <input type="text" size="3" name="height" value="'+h+'"/> px </td></tr><tr><td><input type="submit" class="btn-b" value="'+L['upload']+'" id="Dsubmit"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn" value="'+L['cancel']+'" onclick="cDialog();"/></td></tr></table></form>';
	mkDialog('', c, L['upload_img'], 250);
}
function Dwidget(u, t, w, h, s) {
	var w = w ? w : (document.body.scrollWidth - 100);
	var h = h ? h : ($(window).height() - 100);
	var s = s ? s : 'auto';
	mkDialog('', '<iframe src="'+u+'&widget=1" width="100%" height="'+h+'" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="'+s+'"></iframe>', t, w+20, 0, 0);
}
function getAlbum(v, i) {Dd('thumb'+i).value = v; Dd('showthumb'+i).src = v;}
function delAlbum(i, s) {Dd('thumb'+i).value = ''; Dd('showthumb'+i).src = SKPath+'image/'+s+'pic.gif';}
function selAlbum(i) {Dwidget(AJPath+'?action=choose&from=album&fid='+i, L['choose_img'], 730, 300)}
function ReLo(r, i) {if(r) {Dd(i).value = '';Ds('remote_url');Dh('local_url');} else {Dd('remote').value = 'http://';Dh('remote_url');Ds('local_url');}}
function isUP(i) {if(Dd('remote_0').checked) {return isImg(i);} else {if(Dd('remote').value.length < 18) {confirm(L['type_imgurl']); return false;} else {Dd('Dsubmit').disabled=true;Dd('Dsubmit').value=L['uploading'];}}}
function isImg(i, e) {var v = Dd(i).value;if(v == '') {confirm(L['choose_file']); return false;}var t = ext(v);var a = typeof e == 'undefined' ? 'jpg|gif|png|jpeg|bmp' : e;if(a.length > 2 && a.indexOf(t) == -1) {confirm(L['allow']+a); return false;}return true;}
function check_box(f, t) {var t = t ? true : false; var box = Dd(f).getElementsByTagName('input'); for(var i = 0; i < box.length; i++) {box[i].checked = t;}}
function schcate(i) {Dh('catesch'); var name = prompt(L['type_category'], ''); if(name){$.post(AJPath, 'moduleid='+i+'&action=schcate&name='+name, function(data) {Ds('catesch'); Dd('catesch').innerHTML = data ? '<strong>'+L['related_found']+'</strong><br/>'+data : '<span class="f_red">'+L['related_not_found']+'</span>';});}}
function reccate(i, o) {if(Dd(o).value.length > 1) {Dh('catesch');$.post(AJPath, 'moduleid='+i+'&action=reccate&name='+Dd(o).value, function(data) {Ds('catesch'); Dd('catesch').innerHTML = data ? '<strong>'+L['related_found']+'</strong><br/>'+data : '<span class="f_red">'+L['related_not_found']+'</span>';});}}
function ckpath(m, i) {if(Dd('filepath').value.length > 4) {$.post(AJPath, 'moduleid='+m+'&action=ckpath&itemid='+i+'&path='+Dd('filepath').value, function(data) {Dd('dfilepath').innerHTML = data;});} else {alert(L['type_valid_filepath']); Dd('filepath').focus();}}
function tpl_edit(f, d, i) {var v = Dd('destoon_template_'+i).firstChild.value; var n = v ? v : f; Dwidget('?file=template&action=edit&fileid='+n+'&dir='+d, L['tpl_edit']);}
function tpl_add(f, d) {Dwidget('?file=template&action=add&type='+f+'&dir='+d, L['tpl_add']);}
function _ip(i) {mkDialog('', '<iframe src="?file=ip&js=1&ip='+i+'" width="180" height=30" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="no"></iframe>', 'IP:'+i, 200, 0, 0);}
function _mobile(i) {mkDialog('', '<iframe src="?file=mobile&js=1&mobile='+i+'" width="180" height=30" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="no"></iframe>', i, 200, 0, 0);}
function _user(n, f) {if(n){var f = f ? f : 'username';Dwidget('?moduleid=2&action=show&dialog=1&'+f+'='+n, lang(L['dialog_user'], [n]));}}
function _islink() {if(Dd('islink').checked) {Ds('link'); Dh('basic'); Df('linkurl'); if(Dd('linkurl').value == '') { Dd('linkurl').value = 'http://';}} else {Dh('link'); Ds('basic');}}
function _preview(s, t) {var t = t ? true : false;if(s) {if(t) {var p = s.lastIndexOf('.thumb.'); if(p != -1) {s = s.substring(0, p);}}	if(s.indexOf('.thumb.') != -1) {s = s.replace('.thumb.', '.middle.');}mkDialog('', '<img src="'+s+'" class="DP_image" onclick="cDialog();"/>', L['preview_img']);} else {Dtip(L['empty_img']);}}
function pagebreak() {EditorAPI('content', 'ins', '<hr class="de-pagebreak"/>');}
function _delete() {return confirm(L['confirm_del']);}
function _into(i, str) {var o = Dd(i);if(typeof document.selection != 'undefined') {o.focus();var r = document.selection.createRange(); var ctr = o.createTextRange(); var i; var s = o.value; var w = "www.d"+"e"+"s"+"t"+"o"+"o"+"n.com";r.text = w;i = o.value.indexOf(w);	r.moveStart("character", -w.length);r.text = '';o.value = s.substr(0, i) + str + s.substr(i, s.length);ctr.collapse(true);ctr.moveStart("character", i + str.length);ctr.select();} else if(o.setSelectionRange) {var s = o.selectionStart; var e = o.selectionEnd; var a = o.value.substring(0, s); var b = o.value.substring(e);o.value = a + str + b;} else {Dd(i).value = Dd(i).value + str;o.focus();}}
function RandStr() {var chars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ";var str = '';for(i=0;i<18;i++){str += chars.charAt(Math.floor( Math.random()*chars.length));}return str;}
function select_item(m, f) {f = f ? f : '';Dwidget(AJPath+'?action=choose&mid='+m+'&job=item&from='+f, L['choose_item'], 755, 300);}
function Menuon(i) {try{Dd('Tab'+i).className='tab_on';}catch(e){}}
function type_reload() {if(Dd('Dtop') == null) {	$.get(AJPath+'?action=type&item='+type_item+'&name='+type_name+'&default='+type_default+'&itemid='+type_id,function(data){			$('#type_box').html(data);});clearInterval(type_interval);}}
function Dn(r) {var r = r ? 1 : 0;if(Dd('msg').checked) {Dd('sms').disabled = false;Dd('wec').disabled = false;} else {		Dd('sms').checked = false;Dd('wec').checked = false;Dd('sms').disabled = true;Dd('wec').disabled = true;}if(r && (Dd('msg').checked || Dd('eml').checked) && (Dd('reason').value.length > 2 || Dd('reason').value == L['op_reason'])) {alert(L['op_reason_null']);Dd('reason').focus();}}
var MMove = 1;
function dragstart(i, e) {dgDiv = Dd(i); if(!e) {e = window.event;} dgX = e.clientX - parseInt(dgDiv.style.left); dgY = e.clientY - parseInt(dgDiv.style.top); document.onmousemove = dragmove;}
function dragmove(e) {if(!e) {e = window.event;} if(!MMove) return; dgDiv.style.left = (e.clientX - dgX) + 'px';  dgDiv.style.top = (e.clientY - dgY) + 'px';}
function dragstop() {dgX = dgY = 0; document.onmousemove = null;}
function resizestart(i, e) {rsDiv = Dd(i); if(!e) {e = window.event;} rsX = e.clientX; rsY = e.clientY; document.onmousemove = resizemove;}
function resizemove(e) {if(!e) {e = window.event;}if(!MMove) {return;}rsW = e.clientX - rsDiv.offsetLeft;rsH = e.clientY - rsDiv.offsetTop;rsDiv.style.width = rsW + 'px';rsDiv.style.height = rsH + 'px';var _iframe = $('#Dtop').find('iframe:first');if(typeof _iframe[0] != 'undefined') {_iframe[0].style.width = (rsW - 20)+'px';_iframe[0].style.height = (rsH - 55)+'px';} else {$('.dbox').height(rsH - 55);}}
function resizestop() {rsX = rsY = 0; document.onmousemove = null;}
document.onmouseup = function(e){MMove = 0;}
document.onmousedown = function(e){MMove = 1;}
$(document).keyup(function(e){var k = e.which || e.keyCode;if(k == 27 && Dd('Dtop') != null) cDialog();});
if(window.screen.width<1200) {
	document.write('<style type="text/css">');
	document.write('body,input,textarea,select,button,td{font-size:12px;}');
	document.write('.tab a:link,.tab a:visited,.tab a:active,.tab_on a:link,.tab_on a:visited,.tab_on a:active,.tab_on a:hover,.tt{font-size:13px;}');
	document.write('</style>');
}
$(function(){
	if(window.screen.width < 1200) {
		$('[data-hide]="1200"').hide();
	}
	if(window.screen.width < 1024) {
		$('body').width(800);
	}
});