/*×
负责将图片由新页面打开转为在弹出的DIV窗口中打开
*/
function openremoveimage(E){
	var url = E.href, title, img=$(E).find('img');
	title = img.attr('title');
	if(!title||title == 'null') title = img.attr('alt') || Lang.BigImage;
	if (url.match(/(\w+_){4}/i) && url.match(/(\w+)\.html/i)){
		//百科联盟下载的内容包含互动百科的图片地址，将地址进行转换
		url = url.match(/(\w+)\.html/i);
		var a = url[1].split("_");
		url = "http://"+a[0]+".att.hudong.com/"+a[1]+"/"+a[2]+"/"+a[3]+"."+a[4];
	}else if(url.match(/\.(jpg|gif|png)$/i) == null){
		//如果链接不是一个图片地址
		return true;
	}
	
	//使用DIV窗口中打开
	$.dialog.box("image", title, 'img:'+url, E);
	return false;
}

$(document).ready(function(){
	var a, imgs = document.images, url;
	//遍历所有图片
	for(i=0; i<imgs.length; i++){
		url = $(imgs[i]).attr('src');//得到图片的 src 属性
		if(!url) continue;
		a = $(imgs[i]).parent("a");
		
		//如果是http开头并且包含符合 uploads/201002/****.jpg 的命名规则，则需要进行修复链接的href地址
		if (url.indexOf('http:') == 0 && /uploads\/(?:\d{6}|hdpic)\/\w+\.(?:jpg|gif|png)$/i.test(url)){
			url = 'uploads'+url.split('uploads')[1];
			
			//这种情况应该是图片使用完整路径保存，但是更换来域名
			//图片应该无法显示，对图片地址进行修复
			if (imgs[i].src != url) imgs[i].src = url;
			
			//如果图片有链接
			if (a.size() == 1){
				url = a.attr('href');
				if (url.indexOf('http:') == 0 && url.indexOf('uploads') > 0){
					url = 'uploads'+url.split('uploads')[1];
					a.attr('href', url);
				}
			}
		}
		
		if (a.size() == 1){
			a.click(function(){
				return openremoveimage(this);
			});
		}
	}
});