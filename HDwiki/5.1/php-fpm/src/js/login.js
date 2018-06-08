document.write('<script type="text/javascript" src="js/function.js"></script>');
function loadScript(url, callback) {
	var f = arguments.callee;
	if (!("queue" in f))
		f.queue = {};
	var queue =  f.queue;
	if (url in queue) { // script is already in the document
		if (callback) {
			if (queue[url]) // still loading
				queue[url].push(callback);
			else // loaded
				callback();
		}
		return;
	}
	queue[url] = callback ? [callback] : [];
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.onload = script.onreadystatechange = function() {
		if (script.readyState && script.readyState != "loaded" && script.readyState != "complete")
			return;
		script.onreadystatechange = script.onload = null;
		while (queue[url].length)
			queue[url].shift()();
		queue[url] = null;
	};
	script.src = url;
	document.getElementsByTagName("head")[0].appendChild(script);
}

function check_username(){
	
	if(indexlogin==1){
		var name_tip = 'logintip';
	}else{
		var name_tip = 'checkusername';
	}
	var result=false;
	var username=$("#username").val();
	var length=bytes(username);
	if(username==""){
		$('#'+name_tip).html(loginTip1);
		divDance(name_tip);
	}else{
		$.ajax({
			url: "index.php?user-checkusername",
			data: {username:username},
			dataType: "xml",
			type: "POST",
			success: function(xml){
				var message=$.trim(xml.lastChild.firstChild.nodeValue);
				if(message!='OK'){
					$('#'+name_tip).html(message);
					divDance(name_tip);
				}else{
					$('#'+name_tip).html("<font color='green'>"+message+"</font>");
					result=true;
				}
			}
		});
	}
	return result;
}

function check_passwd(){
	if(indexlogin==1){
		var passwd_tip = 'logintip';
	}else{
		var passwd_tip = 'checkpassword';
	}
	var result = false;
	var passwd = $("#password").val();
	var length=bytes(passwd);
	if( length <1|| length>32){
		$('#'+passwd_tip).html(editPassTip1);
		divDance(passwd_tip);
	}else{
		$('#'+passwd_tip).html("<font color='green'>OK</font>");
		result=true;
	}
	return result;
}

function check_code(){
	if(indexlogin==1){
		var code_tip = 'logintip';
	}else{
		var code_tip = 'checkcode';
	}
	var result=false;
	$.ajax({
		url: "index.php?user-checkcode",
		data: {code:$("#code").val()},
		dataType: "xml",
		type: "POST",
		//async: false,
		success: function(xml){
			var message=xml.lastChild.firstChild.nodeValue;
			message=$.trim(message);
			if(message=='OK'){
				$('#'+code_tip).html("<font color='green'>OK</font>");
				result=true;
			}else{
				$('#'+code_tip).html(indexlogin==1?logincodewrong:loginTip4);
				divDance(code_tip);
			}
		}
	});
	return result;
}

function docheck(){
	var results = false;
	/*
	if(check_passwd()){//check_username()&&
		results = true;
		if(checkcode!=3){
			if(check_code()){
				results = true;
			}else{
				results = false;
			}
		}
	}*/
	if(indexlogin){
		$.ajax({
			url: "index.php?user-login",
			data: {submit:'ajax',username:$("#username").val(),password:$("#password").val(),code:$("#code").val(),indexlogin:indexlogin},
			dataType: "data",
			type: "POST",
			//async: false,
			success: function(data){
				if(data.substr(0,5) != '<?xml'){
					if (data.substr(0,7) == '<script'){
						//window.onerror = function(){return false};
						var app_num_1=0, app_num_2=0, jsUrl = data.match(/http:[^ '"]*/ig);
						if (jsUrl != null){
							$("form[name=box-login]").find('input[type=submit]').val(Lang.TipUcenterLogin);
							for(i in jsUrl){
								if (isNaN(parseInt(i))) continue;
								app_num_1++;
								loadScript(jsUrl[i], function(){
									app_num_2++;
									if(app_num_1 == app_num_2){
										window.location.reload();
									}
								});
							}
						}
					}else if(data==''){
						window.location.reload();
					}else{
						alert(data);
					}
					setTimeout(function(){window.location.reload()}, 5000);
				}else{
					var xml = create_doc(data);
					var message=xml.lastChild.firstChild.nodeValue;
					if(message.indexOf(':')==-1){
						$('#logintip').html(message);
						divDance('logintip');
					}else{
						eval("var message="+message);
						if(parseInt(message.news)){
							var userpms='<span class="h_msg">（'+message.news+'）</span>';
						}else{
							var userpms='<img alt="HDWiki" src="style/default/noshine.gif"/>';
						}
						if(parseInt(message.pubpms)){
							var url='index.php?pms-box-inbox-system';
						}else{
							var url='index.php?pms';
						}
						var data= message.channel+'<li class="bor_no pad10">欢迎你，<a href="index.php?user-space-'+message.uid+'">'+message.username+'</a></li>\n'
								+'<li><a href="'+url+'" id="header-pms">'+userpms+'</a></li>\n'
								+'<li><a  href="index.php?user-profile">个人管理</a></li>\n';
						if(message.adminlogin==1)data+='<li><a href="index.php?admin_main">系统设置</a></li>\n';
						data+='<li class="bor_no"><a href="index.php?user-logout" >退出</a></li>\n'
								+'<li class="bor_no help"><a href="index.php?doc-innerlink-帮助">帮助</a></li>';
						$('#login').html(data);
						var data2='<h2 class="col-h2">用户登录</h2><dl id="islogin" class="col-dl twhp" >'
								+'<dd class="block"><a href="index.php?user-space-'+message.uid+'" class="a-img1"><img width="36" alt="点击进入用户中心" src="'+message.image+'"/></a></dd>'
								+'<dt><a href="index.php?user-space-'+message.uid+'" class="m-r8 bold black">'+message.username+'</a><img title="您现在拥有'+message.credit1+'金币 " src="style/default/jb.gif" class="sign"/></dt>'
								+'<dd class="m-b8"><span>头衔：'+message.grouptitle+'</span></dd><dd><span>经验：'+message.credit2+'</span></dd>'
								+'<dd><span>创建词条：'+message.creates+'</span><span>人气指数：'+message.views+'</span></dd>'
								+'<dd class="twhp_dd"><span>编辑词条：'+message.edits+'</span><a href="index.php?user-space-'+message.uid+'" class="red">我的百科</a></dd>'
								+'</dl>'
								+'<p class="novice"><a href="index.php?doc-innerlink-初来乍到，了解一下" target="_blank">初来乍到，了解一下</a><a href="index.php?doc-innerlink-我是新手，怎样编写词条" target="_blank">我是新手，怎样编写词条</a><a href="index.php?doc-innerlink-我要成为词条达人" target="_blank">我要成为词条达人</a></p>';
						$('#login-static').html(data2)
					}
				}
				
			}
		});
		results=false;
	}else{
		results=true;
	}
	return results;
}


if (typeof g_uname_minlength == 'undefined'){
	var g_uname_minlength = 3;
	var g_uname_maxlength = 15;
}else{
	g_uname_minlength = g_uname_minlength ||3;
	g_uname_maxlength = g_uname_maxlength ||15;
}

function updateverifycode() {
	var img = "index.php?user-code-"+Math.random();
	$('#verifycode').attr("src",img);
}

function changeverifycode(){
	$('#verifycode2').attr('src', getHDUrl("user-code-"+Math.random()));
}

function create_doc(text){
	var xmlDoc = null;
	try //Internet Explorer
	{
		xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
		xmlDoc.async="false";
		xmlDoc.loadXML(text);
	}
	catch(e)
	{
		try //Firefox, Mozilla, Opera, etc.
		 {
			 parser=new DOMParser();
			 xmlDoc=parser.parseFromString(text,"text/xml");
		 }
		catch(e) {}  
	}
	return xmlDoc;  
}


function doLogin(E){
	var auth;
	if (E){
		g_forward = E.href;
	} else {
		g_forward = '';
	}
	
	if (g_regulars && g_forward){
		auth = getAction(g_forward);
		if (g_regulars.indexOf(auth) != -1){
			return true;
		}
	}
	
	if (g_isLogin === false){
		if(typeof g_api_url != 'undefined' && g_api_url){
			window.location.href=api_url;
		}
		if ($.dialog.exist("login")){
			changeverifycode();
			location.href='index.php?user-login';
		//	$.dialog.box('login', Lang.Login, 'url:'+ getHDUrl('user-boxlogin'));
		}else{
			location.href='index.php?user-login';
		//	changeverifycode();
		}
		return false;
	}else {
		return true;
	}
}

function getHDUrl(url){
	return g_seo_prefix + url + g_seo_suffix;
}

var Message = {
	sendto: '',
	box : function(username){
		this.sendto = username;
		if (doLogin()){
			var html = '<table border="0" class="send_massage"><tr><td width="60" >'+Lang.Subject+'</td>'
			+'<td><input id="messageSubject" type="text" style="width:300px" maxlength="35"/></td></tr><tr><td>'+Lang.Content+'</td>'
			+'<td><textarea id="messageContent" cols="47" rows="6" style="width:300px"></textarea><br />'+Lang.TipMessageLength+'</td></tr>'
			+'<tr><td></td><td height="40"><input id="messageSubmit" onclick="Message.send()" type="submit" value="'+Lang.Submit+'" />'
			+'&nbsp;&nbsp;<span id="messageTip"></span></td></tr></table>';
			
			$.dialog.box('login', Lang.sendMessage + Lang.To + ' ' +username, html);
			
			$("#messageSubject").val('');
			$("#messageContent").val('');
			$("#messageSubject").focus();
			$("#messageSubmit").attr('disabled', false).val(Lang.Submit);
		}
		return false;
	},
	
	send: function(){
		var params = {'submit':'ajax', 'checkbox':0, 'sendto':this.sendto};
		params.subject = $("#messageSubject").val();
		params.content = $("#messageContent").val();
		
		params.subject = $.trim(params.subject);
		params.content = $.trim(params.content);		
		
		params.content = params.content.substr(0,300);
		
		if (params.subject == ''){
			$("#messageSubject").focus();
			$("#messageTip").css('color','red').html(Lang.TipMessageSubjectIsNull);
			return false;
		}
		
		if (params.content == ''){
			$("#messageContent").focus();
			$("#messageTip").css('color','red').html(Lang.TipMessageContentIsNull);
			return false;
		}
		$("#messageSubmit").attr('disabled', true).val(Lang.Submiting);
		$.post(getHDUrl("pms-sendmessage"), params, function(data, status){
			$("#messageSubmit").attr('disabled', false).val(Lang.Submit);
			if (status == 'success'){
				if (data == 'OK'){
					//send success
					alert(Lang.TipMessageSendOk);
					$("#messageTip").html('');
					$.dialog.close('login');
				} else {
					//send false
					alert(Lang.TipMessageSendError);
				}
			} else {
				alert(Lang.TipMessageSendError);
			}
		});
	}
}