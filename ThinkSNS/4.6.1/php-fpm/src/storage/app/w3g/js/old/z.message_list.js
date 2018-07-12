$(function(){
	//替换私信列表页面连接
	$(document).on('tap','.c_content a',function(e){
		var r=new RegExp('^'+SITE_URL,'ig');
		var url=$(this).attr("href");
		url = url.replace(r,'');
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
				}else if(/^\/index\.php\?app\=public\&mod\=Message\&act\=index$/ig.test(url)){
					location.href=U('w3g/Message/index');
				}
				console.log(url);
			}
		}
		e.preventDefault();
	});
	$(document).on('click','.c_content a',function(e){e.preventDefault();});
});