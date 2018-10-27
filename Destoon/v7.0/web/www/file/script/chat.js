/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
Dd('word').focus();var chat_time=chat_new_msg=0;var chat_word='';var chat_interval;
function unixtime(){return Math.round(new Date().getTime()/1000);}
function ec_set(i){if(i==1){Dd('ec1').className='ec';Dd('ec2').className='';Dd('chat_s').title=chat_lang.ec1;}else{Dd('ec1').className='';Dd('ec2').className='ec';Dd('chat_s').title=chat_lang.ec2;}chat_ec=i;set_local('chat_ec', i);Dh('ec');}
var chat_ec=get_local('chat_ec');chat_ec=chat_ec==1?1:2;ec_set(chat_ec);
function chat_send(msg){
	var d = msg ? msg : Dd('word').value;
	var l=d.length;
	if(d == chat_lang.tip || l<1){chat_tip('发送内容不能为空，请输入');Dd('word').focus();return;}
	if(l>chat_maxlen){chat_tip('最多输入'+chat_maxlen+'字，当前已输入'+l+'字');Dd('word').focus();return;}
	if(chat_mintime&&(unixtime()-chat_time<chat_mintime)){chat_tip('您的发言过快，请稍后再发');return;}
	face_hide();
	chat_time=unixtime();
	Dd('font_s_id').value=Dd('font_s').value;
	Dd('font_c_id').value=Dd('font_c').value;
	Dd('font_b_id').value=Dd('tool_font_b').className=='tool_a' ? 0 : 1;
	Dd('font_i_id').value=Dd('tool_font_i').className=='tool_a' ? 0 : 1;
	Dd('font_u_id').value=Dd('tool_font_u').className=='tool_a' ? 0 : 1;
	Dd('word_id').value=d;	
	$.post('chat.php', $('#chat_send').serialize(), function(data) {
		if(data == 'ok') {
			$('#word').val('');
			//chat_load();
		} else if(data == 'max') {
			chat_tip('发送内容过长');
		} else {
			chat_tip('发送失败，请重试');
		}
	});
}
function chat_load(d){
	$.post('chat.php', 'action=load&chatlast='+chat_last+'&chatid='+chat_id+'&first='+(d ? 1 : 0), function(data) {
		if(data) {
			eval("var chat_json="+data);
			chat_last=chat_json.chat_last;
			chat_msg=chat_json.chat_msg;
			msglen=chat_msg.length;
			if(msglen && d) {$('#chat').append('<div class="chat-more"><a href="im.php?action=view&chatid='+chat_id+'" target="_blank"><span>更多记录</span></a></div>');}
			for(var i=0;i<msglen;i++){
				msghtm = '';
				if(chat_msg[i].date) msghtm += '<div class="chat-date"><span>'+chat_msg[i].date+'</span></div>';
				msghtm += '<table cellpadding="0" cellspacing="0" width="100%">';
				msghtm += '<tr>';
				if(chat_msg[i].self == 1) {
					msghtm += '<td width="70"></td>';
					msghtm += '<td valign="top"><div class="chat_msg1">'+chat_msg[i].word+'</div></td>';
					msghtm += '<td class="chat_arr1"></td>';
					msghtm += '<td width="60" valign="top" align="center"><a href="'+chat_home1+'" target="_blank"><img src="'+chat_head1+'" width="40" height="40"/></a></td>';
					msghtm += '<td width="10"></td>';
				} else {
					msghtm += '<td width="10"></td>';
					msghtm += '<td width="60" valign="top" align="center"><a href="'+chat_home0+'" target="_blank"><img src="'+chat_head0+'" width="40" height="40"/></a></td>';
					msghtm += '<td class="chat_arr0"></td>';
					msghtm += '<td valign="top"><div class="chat_msg0">'+chat_msg[i].word+'</div></td>';
					msghtm += '<td width="70"></td>';
				}
				msghtm += '</tr>';
				msghtm += '</table>';
				$('#chat').append(msghtm);
			}			
			if(msglen) $('#chat').animate({scrollTop:$('#chat')[0].scrollHeight+1000}, 500);
			//if(msglen) Dd('chat').scrollTop=Dd('chat').scrollHeight;
		}
	});
}
function chat_log(){
	Dd('chat').innerHTML='';
	chat_last=0;
	chat_load(1);
}
function chat_save(){
	Dd('down_data').value=Dd('chat').innerHTML;
	Dd('chat_down').submit();
}
function chat_off(){
	if(confirm('确定要中断聊天吗？')){
		window.close();
	}
}
function chat_key(e){
	if(!e){e=window.event;}
	if(e.keyCode==13){
		if(chat_ec==1){
			if(e.ctrlKey){
				Dd('word').value=Dd('word').value+"\n";
				if(isIE){
					var r =Dd('word').createTextRange();
					r.moveStart('character', Dd('word').value.length);
					r.moveEnd("character", 0);
					r.collapse(true);
					r.select();
				}
			}else{
				chat_send();
				return false;
			}
		}else{
			if(e.ctrlKey) chat_send();
		}
	}
}
function chat_tip(msg){
	Ds('tip');
	Dd('tip').innerHTML=msg;
	Dd('sd').innerHTML=sound('chat_tip');
	window.setTimeout("Dh('tip');",5000);
}
var chat_title_i=0;
var title_interval;
function chat_new(num){
	if(num>0){
		Dd('sd').innerHTML=sound('chat_msg');
		chat_new_msg=num;
		if(chat_title_i==0){
			title_interval=setInterval('new_tip()',1000);
		}
	}
}
function new_tip(){
	chat_title_i++;
	if(chat_title_i>5){
		new_tip_stop();
		return;
	}
	if(chat_title_i%2==0){
		document.title='【新消息('+chat_new_msg+')】'+chat_title;
	}else{
		document.title=chat_title;
	}
}
function new_tip_stop(){
	try{
		clearInterval(title_interval);
		chat_title_i=0;
		document.title=chat_title;
	}catch(e){}
}
function font_show(){
	if(Dd('font').style.display!='none'){
		font_hide();
		return;
	}
	Ds('font');
	Dd('tool_font').className='tool_b';
}
function font_hide(){
	Dh('font');
	Dd('tool_font').className='tool_a';
}
function font_init(){
	if(Dd('word').value==chat_lang.tip){$('#word').val('');$('#word').attr('class', '');}
	var s='';
	if(Dd('font_s').value){s+=' s'+Dd('font_s').value;}
	if(Dd('font_c').value){s+=' c'+Dd('font_c').value;}
	if(Dd('tool_font_b').className=='tool_b'){s+=' fb';}
	if(Dd('tool_font_i').className=='tool_b'){s+=' fi';}
	if(Dd('tool_font_u').className=='tool_b'){s+=' fu';}
	if(s){Dd('word').className=s.substring(1);}
}
function face_show(){
	if(Dd('face').style.display!='none'){
		face_hide();
		return;
	}
	Ds('face');
	Dd('tool_face').className='tool_b';
}
function face_hide(){
	Dh('face');
	Dd('tool_face').className='tool_a';
}
function face_into(s){
	if(Dd('word').value==chat_lang.tip){$('#word').val('');$('#word').attr('class', '');}
	Dd('word').value+=':'+s+')';
}
$(function(){
	Dd('word').value=chat_lang.tip;
	chat_interval=setInterval('chat_load()',chat_poll);
	chat_log();
	$('#word').bind('input click',function(){
		if($('#word').val().indexOf(chat_lang.tip)!=-1) {
			$('#word').val($('#word').val().replace(chat_lang.tip,''));
			$('#word').attr('class', '');
		}
	});
});