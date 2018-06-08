	//分享到显示
	var partakehover = null;
	$("#share_link").hover(function(){
		clearTimeout(partakehover);
		$("#share_btn").show();
	}, function(){
		clearTimeout(partakehover);
		partakehover = setTimeout(function(){
			$("#share_btn").fadeOut("normal");
		}, 500);
	});
	$("#share_btn").hover(function(){
		clearTimeout(partakehover);
		$("#share_btn").show();
	}, function(){
		partakehover = setTimeout(function(){
			$("#share_btn").fadeOut("normal");
		}, 500);
	});
	//点击转帖到Kaixin001
		$url = "http://www.kaixin001.com/repaste/share.php?rtitle="+encodeURI($.trim($("#doctitle").html()))+"&rurl="+encodeURI(document.location.href)+"&rcontent="+encodeURI($("meta[name='description']").attr("content"));
		$('.kaixin001').attr('href',$url).attr('target','_blank');

	//点击人人分享
	$(".renren").bind("click", function(){
		$("body").append("<div id=\"renren_repaste_div\" style=\"display:none;\"><form name=\"renren_repaste\" id=\"renren_repaste\" action=\"http://share.renren.com/share/buttonshare.do\" method=\"get\" target=\"_blank\"><input type=\"hidden\" name=\"link\" value=\"" + location.href + "\"></form></div>");
		$("#renren_repaste").submit();
		$("#renren_repaste").remove();	
		return false;
	});
	
	//新浪微博
	/*
	新浪微博分享后默认显示来自【新浪网内容分享】，如想修改来自 *** 信息，
	则修改下面代码当中 <input type="hidden" name="appkey" value=""> 的 value 值即可。
	
	方法1：新浪微博 【工具】 -> 【分享按钮】获取appkey，分享后显示来自【分享按钮】，
	方法2：http://open.t.sina.com.cn/ 申请appkey，需新浪认证，在认证之前显示来自【微博开放平台接口】。
	方法3：保持appkey的value值为空，那么将显示来自【新浪网内容分享】
	*/
	$(".sina_blog").bind("click", function(){
		var sinaminblogurl = location.href;
		var maxlength = 140;
		var ablelength = maxlength - sinaminblogurl.length / 2 - $("#doctitle").html().length - 7;
		var summary = $.trim($("meta[name='description']").attr("content"));
		if (ablelength > 50) {
			ablelength = 50;
		}
		if (summary.length > ablelength) {
			summary = summary.substr(0, ablelength - 2) + "……";
		}
		var content = $("#doctitle").val() + "  " + summary;
		var html = '<form name="sinaminblogform" id="sinaminblogform" action="http://v.t.sina.com.cn/share/share.php" method="get" target="_blank">' +'<input type="hidden" name="title" value="' +content +'"><input type="hidden" name="url" value="' +sinaminblogurl +'"><input type="hidden" name="content" value="utf-8"><input type="hidden" name="appkey" value=""></form>';
		$("body").append(html);
		$("#sinaminblogform").submit();
		$("#sinaminblogform").remove();
		return false;
	});