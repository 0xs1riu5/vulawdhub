
(function($){
	$.cookie = $.cookie || {};
	$.cookie.set = function(name, value, options){
		options = options || {path:'/'};
		if (value === null) {
			value = '';
			options.expires = -1;
		}
		
		var expires = '';
		if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString())) {
			var date;
			if (typeof options.expires == 'number') {
				date = new Date();
				date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
			} else {
				date = options.expires;
			}
			expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
		}
		// CAUTION: Needed to parenthesize options.path and options.domain
		// in the following expressions, otherwise they evaluate to undefined
		// in the packed version for some reason...
		var path = options.path ? '; path=' + (options.path) : '';
		var domain = options.domain ? '; domain=' + (options.domain) : '';
		var secure = options.secure ? '; secure' : '';
		document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	};
	
	$.cookie.setServer = function(name, value, options){
		options = options || {};
		options.path = options.path || '/';
		$.cookie.set(name, value, options);
	};
	
	$.cookie.get = function(name){
		var cookieValue = null;
		if (document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++) {
				var cookie = $.trim(cookies[i]);
				// Does this cookie string begin with the name we want?
				if (cookie.substring(0, name.length + 1) == (name + '=')) {
					cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
					break;
				}
			}
		}
		return cookieValue;
	};

})(jQuery);

/*
* 从 http://kaiyuan.hudong.com 获取升级、补丁等重要提示信息。
* 每天仅对管理员提示一次，其他访问者不会看到，以帮助管理员在第一时间看到重要信息。
* http://kaiyuan.hudong.com/tips/tip.js
*/
function _kaiyuan_tip(){
	if(/login|logout|doc-edit/i.test(location.href)){
		return;
	}
	//var kaiyuan_tip_effect='up';
	
	var D=new Date(), d=(D.getMonth() + 1) +','+ D.getDate(),
		ispoped=$.cookie.get('kaiyuan_tip_date') == d;
	if(ispoped){return;}
	
	if(typeof kaiyuan_tip_title!='string'){
		kaiyuan_tip_title = '';
	}
	
	if(typeof kaiyuan_tip_autoClose!='number'){
		kaiyuan_tip_autoClose = 60000;
	}
	
	if(typeof kaiyuan_tip_effect!='string' || !/up|down/i.test(kaiyuan_tip_effect)){
		kaiyuan_tip_effect = '';
	}
	
	if(typeof hdwiki_tip_content=='string' && $.trim(hdwiki_tip_content)){
		$.dialog({
			id:'hdwiki_tip',
			position:'rb',
			align:'left',
			overlay:0,
			effects:kaiyuan_tip_effect,
			fixed:1,
			width:300,
			autoClose:kaiyuan_tip_autoClose,
			title:kaiyuan_tip_title,
			content:hdwiki_tip_content
		});
		
		
		$.cookie.set('kaiyuan_tip_date', d, {expires:1});
	}
}

function kaiyuan_pop(){
	if(typeof hdwiki_tip_content=='string'){
		_kaiyuan_tip();
	}else{
		loadScript('http://kaiyuan.hudong.com/tips/tip.js', function(){
			setTimeout(function(){_kaiyuan_tip()}, 2000);
		});
	}
}

kaiyuan_pop();
