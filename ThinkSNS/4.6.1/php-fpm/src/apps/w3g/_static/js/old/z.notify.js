$(function(){
//替换系统通知页面连接
	$(document).on('tap','.c_notify a',function(e){
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
				}else if(/^\/index\.php\?app\=public\&mod\=Topic\&act\=index\&k\=[\w\%]+/ig.test(url)){
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
				// console.log(url);
			}
		}
		e.preventDefault();
	});
	$(document).on('click','.c_notify a',function(e){e.preventDefault();});
	//替换宽度
	$('table,td').css("width","auto");
});