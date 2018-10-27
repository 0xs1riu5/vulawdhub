<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<style type="text/css">
#chat{width:auto;height:246px;overflow:auto;}
#chat div {margin:5px 5px 5px 10px;line-height:20px;}
#chat img {cursor:pointer;}
#chat a:link,#chat a:visited,#chat a:active {color:#0072C1;text-decoration:underline;}
#chat a:hover {color:#FF0000;}
.dt {display:block;height:20px;line-height:20px;margin:0;}
.dt span{color:#FF6600;}
.u1 {color:#008040;}
.u0 {color:#0000FF;}
.t1 {color:#666666;padding-left:8px;font-size:11px;}
.t0 {color:#666666;padding-left:8px;font-size:11px;}
.w1 {padding:0 10px 0 20px;margin:0;color:green;}
.w0 {padding:0 10px 0 20px;margin:0;color:blue;}
.s10 {font-size:10px;}
.s11 {font-size:11px;}
.s12 {font-size:12px;}
.s13 {font-size:13px;}
.s14 {font-size:14px;}
.s16 {font-size:16px;}
.s18 {font-size:18px;}
.s20 {font-size:20px;}
.s24 {font-size:24px;}
.c1 {color:#000000;}
.c2 {color:#FF0000;}
.c3 {color:#0000FF;}
.c4 {color:#008040;}
.c5 {color:#FF6600;}
.c6 {color:#FF00FF;}
.fb {font-weight:bold;}
.fi {font-style:italic;}
.fu {text-decoration:underline;}
#word {width:98%;height:40px;border:none;}
</style>
<iframe src="" name="send" id="send" style="display:none;"></iframe>
<div id="sd"></div>
<form action="?" target="send" method="post" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="send"/>
<input type="hidden" name="openid" value="<?php echo $openid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td bgcolor="#DEEAF2">
<a href="<?php echo $U['headimgurl'];?>" target="_blank"><img src="<?php echo $U['headimgurl'];?>" width="46" style="margin:5px 10px 5px 5px;float:left;"/></a>
<div style="padding-top:5px;"><strong style="font-size:14px;"><?php echo $U['nickname'];?></strong><br/><?php echo $U['sex'] == 1 ? '男，' : ($U['sex'] == 2 ? '女，' : '');?>来自<?php echo $U['country'].$U['province'].$U['city'];?></div>
</td>
</tr>
<tr>
<td><div id="chat"></div></td>
</tr>
<tr>
<td><textarea id="word" name="word" onkeydown="return chat_key(event);" title="按Ctrl+Enter发送" placeholder="请输入聊天内容"></textarea></td>
</tr>
</table>
<div style="padding:16px 10px;background:#F1F2F3;">
<span class="f_r"><input type="submit" value="发 送" class="btn-g" id="btn"/></span>
<img src="api/weixin/image/media_upload.gif" onclick="Dfile(<?php echo $moduleid;?>, '', 'chat', 'jpg|amr|mp3|mp4');" class="c_p" title="上传多媒体文件文件(有效期3天)&#10;- 图片，支持jpg格式，最大128K&#10;- 语音，支持amr、mp3格式，最大256K&#10;- 视频，支持mp4格式，最大1M"/>
</div>
</form>
<script type="text/javascript">
var chat_last = 0;
var chat_link = 0;
function chat_load(){
	if(chat_link) return;
	chat_link=1;
	$.get('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&openid=<?php echo $openid;?>&action=load&chatlast='+chat_last, function(data) {
		eval("var chat_json="+data);
		chat_last=chat_json.chat_last;
		chat_msg=chat_json.chat_msg;
		msglen=chat_msg.length;
		for(var i=0;i<msglen;i++){
			chat_into((chat_msg[i].date ? '<p class="dt"><span>'+chat_msg[i].date+'</span></p>' : '')+'<span class="u'+chat_msg[i].self+'">'+chat_msg[i].name+'</span><span class="t'+chat_msg[i].self+'">'+chat_msg[i].time+'</span><br/><p class="w'+chat_msg[i].self+'">'+chat_msg[i].word+'</p>');
		}
		if(chat_json.chat_new > 0) {
			Dd('sd').innerHTML=sound('chat_msg');
			if(Dd('word').disabled == true) chat_show(1);
		}
		chat_link=0;
	});
}
function chat_into(msg){
	var o=document.createElement("div");
	o.innerHTML=msg;Dd('chat').appendChild(o);
	Dd('chat').scrollTop=Dd('chat').scrollHeight;
}
chat_interval=setInterval('chat_load()', 3);
function chat_key(e){
	if(!e){e=window.event;}
	if(e.keyCode==13){
		if(e.ctrlKey && check()) chat_send();
	}
}
function chat_send() {
	if(check()) Dd('dform').submit();
}
function chat_show(i) {
	Dd('btn').value = '发 送';
	Dd('btn').disabled = false;
	if(i == 2) Dd('word').value = '';
	if(i == 1) Dd('word').disabled = false;
}
function chat_hide(i) {
	Dd('btn').value = i == 1 ? '无法发送' : '发送中.';
	Dd('btn').disabled = true;
	if(i == 1) {
		Dd('word').value = '';
		Dd('word').disabled = true;
	}
}
function check() {
	var l;
	var f;
	f = 'word';
	l = Dd(f).value.length;
	if(l < 2) {
		alert('内容最少2字，当前已输入'+l+'字');
		Dd(f).focus();
		return false;
	}
	chat_hide();
	return true;
}
Menuon(2);
</script>
<?php include tpl('footer');?>