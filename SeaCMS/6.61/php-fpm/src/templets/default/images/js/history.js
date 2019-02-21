var $MH={
	limit: 10,
	width:960,
	height: 170,
	style: 'pic',
	setCookie: function(name, value) {
		var Days = 365;
		var exp = new Date;
		exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
		document.cookie = name + ("=" + (value) + ";expires=" + exp.toGMTString() + ";path=/;");
	},
	getCookie: function(name) {
		var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
		if (arr != null) {
			return (arr[2]);
		}
		return null;
	},
	getDc: function(){
		var x,y=document.getElementById('HISTORY');
		return y;
	},
	piclist: function (){
		var a = $MH.getCookie("HISTORY"), c = 1,img_li = "";
		a = (a !='' && ''+a != 'null') ? $MH.tryjosn(a) : {video:[]};
		for(var i=0;i<a.video.length;i++){
			if(c>$MH.limit){break;}
			if(a.video[i].link && a.video[i].pic && a.video[i].name){
			img_li += "<li class=\"pic\"><a href=\"" + a.video[i].link + "\" target=\"_self\"><img width=\"90\" height=\"120\" src=\"" + a.video[i].pic + "\" alt=\"" + a.video[i].name + "\" border=\"0\"/></a>\
						<p><a class=\"font-size-12\" href=\"" + a.video[i].link + "\" target=\"_self\">" + a.video[i].name + "</a></p></li>"
				c++;
			}
		}
		img_li = img_li != "" ? img_li : '<li>\u6CA1\u6709\u8BB0\u5F55</li>';
		return "<ul class=\"list\">" + img_li + "</ul>";
	},
	fontlist: function (){
		var a = $MH.getCookie("HISTORY"), c = 1,img_li = "";
		a = (a !='' && ''+a != 'null') ? $MH.tryjosn(a)  : {video:[]} ;
		for(var i=0;i<a.video.length;i++){
			if(c>$MH.limit){break;}
			if(a.video[i].link && a.video[i].pic && a.video[i].name){
			img_li += "<li><a href=\"" + a.video[i].link + "\" target=\"_self\"><span class=\"text-muted\">"+c+".</span> " + a.video[i].name + "</a></li>"
				c++;
			}
		}
		img_li = img_li != "" ? img_li : '<li>\u6CA1\u6709\u8BB0\u5F55</li>';
		return "<ul class=\"list\">" + img_li + "</ul>";
	},
	WriteHistoryBox: function(w,h,c){
		document.write('<div id="HISTORY" style="width:'+($MH.width=w)+'px;"></div>');
		$MH.height=h;$MH.style= c=='font' ? 'font' : 'pic';
		this.showHistory();
	},
	showHistory: function(ac) {
		var a = $MH.getCookie("HISTORY"),dc=$MH.getDc();
		var ishistory = $MH.getCookie("ishistory");
		if(!dc) return;
		if (ac == 1) {
			if (ishistory != 1) {
				$MH.setCookie("ishistory", 1);
				ishistory = 1;
			} else {
				$MH.setCookie("ishistory", 0);
				ishistory = 0;
			}
		}
		if (ac == 2) {
			ishistory = 0;
			$MH.setCookie("ishistory", 0);
			$MH.setCookie("HISTORY", 'null');
		}
		if(ishistory == 1){
			dc.innerHTML = $MH[$MH.style+'list']();
			dc.style.display = "";
		} else {
			dc.innerHTML = $MH[$MH.style+'list']();
			dc.style.display = "";
		}
	},
	recordHistory: function(video){
		if(video.link.indexOf('http://')==-1 || window.max_Player_File) return;
		var a = $MH.getCookie('HISTORY'), b = new Array(), c = 1;
		if(a !='' && a != null && a != 'null'){
			a = $MH.tryjosn(a);
			for(var i=0;i<a.video.length;i++){
				if(c>$MH.limit){break;}
				if(video.link != a.video[i].link && a.video[i].pic){b.push('{"name":"'+ $MH.u8(a.video[i].name) +'","link":"'+ $MH.u8(a.video[i].link) +'","pic":"'+ $MH.u8(a.video[i].pic) +'"}');c++;}
			}
		}
		b.unshift('{"name":"'+ $MH.u8(video.name) +'","link":"'+ $MH.u8(video.link) +'","pic":"'+ $MH.u8(video.pic) +'"}');
		$MH.setCookie("HISTORY",'{video:['+ b.join(",") +']}');
		b = null;
		a=null;
	},
	u8: function (s){
		return unescape(escape(s).replace(/%u/ig,"\\u")).replace(/;/ig,"\\u003b");
	},
	tryjosn: function (json){
		try{
			return eval('('+ json +')');
		}catch(ig){
			return {video:[]};
		}
	}
}