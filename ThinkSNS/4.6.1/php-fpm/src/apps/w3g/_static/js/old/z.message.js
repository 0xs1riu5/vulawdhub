$(function(){
	since_id=defalut_since;
	var LoadMSG = function (x) {//加载私信对话详情列表
		var url = U('public/Message/loadMessage');
		var p = {since_id: defalut_since, list_id: list_id, type: 1, max_id: 0};
		$.ajax({
			type:"POST",
			url :url,
			data:p,
			dataType:"json",
			timeout:10000,
			context:x,
			beforeSend:function(){
				// x.html('ajax load start');
				// alert('before!');
			},
			success:function(r){
				since_id=r.since_id;
				msgdata=r.data;
				msgdata=msgdata.replace(/\<script type\=\"text\/javascript\"\>[.\s\(\)\w]*(.*[\n\s]*)*/ig,'');
				$('body').append('<div id="loadmsg-savebox">'+msgdata+'</div>');
				$("#loadmsg-savebox>script,#loadmsg-savebox>style").remove();
				var h=$('#loadmsg-savebox>dl');
				var hl=h.length-1;
				$.each(h,function(index,item){$("#msg_listbox").append(h[hl-index])});
				$('#loadmsg-savebox').remove();
			},
			error:function(xhr,type){
				alert('error!');
				alert('xhr: '+xhr);
				alert('type: '+type);
			}
		});
	}
	LoadMSG($("#msg_listbox"));//页面加载完成时加载私信对话
	// 调试
	var doR = function(x,y){//x->私信消息列表容器，y->输入框
		var url = U('public/Message/doReply');
		var reply_content = y.val();
		var p = {id:list_id, reply_content:reply_content, to:to, attach_ids:''};
		$.get(U('w3g/Public/ava')+"&uid="+uid,function(data){//获取用户头像地址
			var ava_i=data;
			$.ajax({
				type:"POST",
				url :url,
				data:p,
				dataType:"json",
				timeout:10000,
				context:x,
				before:function(){

				},
				success:function(r){
					if(r.status=='1'){
						loadMoreMSG();
						y.val("").focus();
					}
				},
				error:function(xhr,type){
				}
			});
		});
	}
	$(document).on('tap','#doR_submit',function(){//点击发表私信
		doR($("#msg_listbox"),$("#doR_input"));
	});
	$(document).on('tap','#doPostMsg_submit',function(){
		var url = U('widget/SearchUser/search');
		var username = $("#doPostMsg_uname").val();
		var p = {key:username, follow:0, noself:1};
		var message_to = 0;
		//首先检查用户名是否存在，若存在，则发送私信
		$.ajax({
			type:"POST",
			url :url,
			data:p,
			dataType:"json",
			timeout:10000,
			// context:x,
			before:function(){

			},
			success:function(r){
				if(r.data != null){
					var r_username = r.data[0].uname;
					console.log("r_username = " + r_username);//调试信息
					if(r_username == username){
						message_to = Number(r.data[0].uid);
						// console.log("message_to = " + message_to);//调试信息
					}
				}
				if(message_to > 0){
					console.log("message_to = " + message_to);//调试信息
					url = U('public/Message/doPost');//若用户存在，重定义post地址
					var message_content = $("#doPostMsg_area").val();
					var p = {to:message_to, content:message_content, attach_ids:''};
					$.ajax({
						type:"POST",
						url :url,
						data:p,
						dataType:"json",
						timeout:10000,
						// context:x,
						before:function(){

						},
						success:function(r){
							if(r.status == 1 && r.data == "发送成功"){
								console.log("发送成功");
					        } else {
								console.log(r.data);
					        }
						},
						error:function(xhr,type){
						}
					});
				}else{
					console.log("用户名错误");
					console.log("message_to = " + message_to);//调试信息
				}
			},
			error:function(xhr,type){
			}
		});
	});
	//读取更多消息
	function loadMoreMSG(){
		var url = U('public/Message/loadMessage');
		var p = {since_id: since_id, list_id: list_id, type: 1};
		$.ajax({
			type:"POST",
			url :url,
			data:p,
			dataType:"json",
			timeout:2000,
			context:$("#msg_listbox"),
			success:function(r){
				if(r.count!=0 && r.data!=''){
					since_id=r.since_id;
					msgdata=r.data;
					msgdata=msgdata.replace(/\<script type\=\"text\/javascript\"\>[.\s\(\)\w]*(.*[\n\s]*)*/ig,'');
					var nm = $("#newmsg");
					nm.append(msgdata);
					$("#newmsg>script,#newmsg>style").remove();
					var h=nm.children('dl');
					var hl=nm.length-1;
					nm.html('');
					$.each(h,function(index,item){$("#msg_listbox").append(h[hl-index])});
					window.scrollTo(0,$(document).height()+50);
				}
			},
			error:function(xhr,type){
			}
		});
	}
	setInterval(loadMoreMSG,2000);
	//替换私信列表页面连接
	$(document).on('tap','.reply-list a',function(e){
		// var r=new RegExp('^'+SITE_URL,'ig');
		var local_r=new RegExp('^http\:\/\/localhost\/svn','ig');//本地测试使用
		var url=$(this).attr("href");
		// url = url.replace(r,'');
		url = url.replace(SITE_URL,'');
		url = url.replace('http://localhost/svn','');
		// url = url.replace(local_r,'');//本地测试使用
		if(url =="/message"){
			location.href=U('w3g/Message/index');
		}else{
			if(/^\/[\w]+\/[\w\?\=\&\%]*/ig.test(url)){
				if(/^\/space\/[0-9]+/ig.test(url)){
					var maid = url.match(/[0-9]+$/ig);
					if(maid!=null){
						maid=maid[0];
						location.href=U('w3g/Index/weibo',new Array('uid='+maid));
					}
				}else if(/^\/weibo\/[0-9]+\?uid\=[0-9]+$/ig.test(url)){
					var tid = url.match(/[0-9]+/ig);
					if(tid!=null){
						tid=tid[tid.length-2];
						location.href=U('w3g/Index/detail',new Array('weibo_id='+tid));
					}
				}else if(/^\/weibo\/[0-9]+\?uid\=[0-9]+\&digg\=1$/ig.test(url)){
					var tid = url.match(/[0-9]+/ig);
					if(tid!=null){
						tid=tid[tid.length-3];
						location.href=U('w3g/Index/detail',new Array('weibo_id='+tid));
					}
				}else if(/\/topic\?k\=[\w\%]+/ig.test(url)){
					var key=url.match(/[\w\%]+$/ig);
					if(key!=null){
						key=key[0];
						console.log(key);
						location.href=U('w3g/Index/doSearch',new Array('key='+key));
					}
				}
				console.log(url);
			}else{
				if(/^\/index\.php\?app\=public\&mod\=Profile\&act\=index\&uid\=[0-9]+$/ig.test(url)){
					var maid = url.match(/[0-9]+$/ig);
					if(maid!=null){
						maid=maid[0];
						location.href=U('w3g/Index/weibo',new Array('uid='+maid));
					}
				}else if(/^\/index\.php\?app\=public\&mod\=Index\&act\=detail\&weibo_id\=[0-9]+\&uid\=[0-9]+$/ig.test(url)){
					var tid = url.match(/[0-9]+/ig);
					if(tid!=null){
						tid=tid[tid.length-2];
						location.href=U('w3g/Index/detail',new Array('weibo_id='+tid));
					}
				}else if(/\/index\.php\?app\=public\&mod\=Profile\&act\=feed\&feed_id\=[0-9]+\&uid\=[0-9]+/ig.test(url)){
					var tid = url.match(/[0-9]+/ig);
					if(tid!=null){
						tid=tid[tid.length-2];
						location.href=U('w3g/Index/detail',new Array('weibo_id='+tid));
					}
				}else if(/^\/index\.php\?app\=public\&mod\=Message\&act\=index$/ig.test(url)){
					location.href=U('w3g/Message/index');
				}else if(/^\/index\.php\?app\=public\&mod\=Topic\&act\=index\&k\=[\w\%]/ig.test(url)){
					var key=url.match(/[\w\%]+$/ig);
					if(key!=null){
						key=key[0];
						location.href=U('w3g/Index/doSearch',new Array('key='+key));
					}
				}else if(/\/topic\?k\=[\w\%]+/ig.test(url)){
					var key=url.match(/[\w\%]+$/ig);
					if(key!=null){
						key=key[0];
						console.log(key);
						location.href=U('w3g/Index/doSearch',new Array('key='+key));
					}
				}
				console.log(url);
			}
		}
		e.preventDefault();
	});
	$(document).on('click','.reply-list a',function(e){e.preventDefault();});
	$('#footer_p').html('&nbsp;');
});