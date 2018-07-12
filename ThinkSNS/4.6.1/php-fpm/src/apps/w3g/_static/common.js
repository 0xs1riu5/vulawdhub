// 加入微吧
function joingroup(gid) {
	if(!confirm("确认加入")) return false;
	
	$.ajax({
		type: "POST",
		url: U('w3g/Group/joinGroup'),
		data:   "gid="+gid,
		success: function(msg){
			var msg = eval('('+msg+')');
			if(msg.status!=1){
				$.ui.showMask(msg.info, true);
			}else{
				$.ui.showMask(msg.info, true);				
				setTimeout("refreshurl()",1500);				
			}
		}
	});
}
function refreshurl(){
	location.href = location.href
}
// 删除微吧
function delgroup(gid) {
    ui.box.load(U('group/Group/delGroupDialog')+'&gid='+gid,'解散微吧');
}
// 退出微吧
function quitgroup(gid) {
	if(!confirm("确认退出")) return false;
	
	$.ajax({
		type: "POST",
		url: U('w3g/Group/quitGroup'),
		data:   "gid="+gid,
		success: function(msg){
			if(msg==0){
				$.ui.showMask("退出失败", true);
			}else{
				$.ui.showMask("退出成功", true);				
				setTimeout("refreshurl()",1500);				
			}
		} 
	});
}
// 过滤html，字串检测长度
function checkPostContent(content)
{
	content = content.replace(/&nbsp;/g, "");
	content = content.replace(/<br>/g, "");
	content = content.replace(/<p>/g, "");
	content = content.replace(/<\/p>/g, "");
	return getLength(content);
}