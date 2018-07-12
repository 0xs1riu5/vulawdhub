core.bdshare = {
	status : false,
	//给工厂调用的接口
	_init : function(){
		core.bdshare.init(attrs);
		return true;
	},
	init:function(callback){
		//加载百度分享代码
		if(!core.bdshare.status) {
			$.ajax({type:'GET',url:'http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5),dataType: 'script',ifModified:true,cache:true,success:function(){core.bdshare.status=true;callback&&callback();}});
		}else{
			callback && callback();
			window._bd_share_main.init();
		}
	},

	addConfig: function(itemName, config){
		if(!core.bdshare.status) {
			core.bdshare.init(function(){
				core.bdshare._addConfig(itemName, config);
			});
		}else{
			core.bdshare._addConfig(itemName, config);
		}
	},

	_addConfig : function(itemName, config){
		if (!window._bd_share_config) {
			window._bd_share_config = new Array();
		}
		if (!itemName || itemName == 'common') {
			window._bd_share_config["common"] = config;
		} else {
			if(!window._bd_share_config[itemName]) {
				window._bd_share_config[itemName] = [];
			}
			var i = window._bd_share_config[itemName].length;
			window._bd_share_config[itemName][i] = config;
		}
		if(core.bdshare.status) {
			window._bd_share_main.init();
		}
	},

	feedlistConfig: false,
	feedlist: function (obj){
		if(!core.bdshare.feedlistConfig){
			core.bdshare.addConfig('share', {
				"tag" : "share_feedlist",
				"onBeforeClick":function(cmd, config){
					if(window.event.target){
						var target = window.event.target;
					}else{
						var target = window.event.srcElement;
					}
					var dd = $(target).parents('dd');
					var content = dd.find('.contents:first p').clone();
					content.find('dl').remove();
					content = $.trim(content.text());
					var url = dd.find('p.info a.date').attr('href');
					config["bdUrl"]  = url;
					config["bdText"] = content;
					config["bdDesc"] = content;
					config["bdPic"]  = '';
					return config;
				}
			});
			core.bdshare.feedlistConfig = true;
		}
		var dd   = $(obj).parents('dd');
		var line = dd.find('.infopen:first');
		var box  = dd.find('.forward_box:first');
		var cmt  = $(obj.parentModel.childModels['comment_detail'][0]);
		if(cmt.size()) cmt.hide();
		if(box.is(':hidden')){
			line.show().find('.trigon').css('left',
			  $(obj).position().left+ ($(obj).width()/2));
			box.stop().slideDown(200);//.show();
			/*var s = box.find('.share_feedlist');
			s.css('top', (-s.outerHeight(true))+'px');
			s.stop().animate({top:0}, 200);*/
		}else{
			box.stop().slideUp(200, function(){
				line.hide();
			});
		}
	}
};
