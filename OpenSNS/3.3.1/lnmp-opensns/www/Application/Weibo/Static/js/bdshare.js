if(!bdshare){
	var bdshare = {
		status : false,
		//给工厂调用的接口
		_init : function(){
			bdshare.init(attrs);
			return true;
		},
		init:function(callback){
			//加载百度分享代码
			if(!bdshare.status) {
				$.ajax({type:'GET',url:'http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5),dataType: 'script',ifModified:true,cache:true,success:function(){bdshare.status=true;callback&&callback();}});
			}else{
				callback && callback();
				window._bd_share_main.init();
			}
		},

		addConfig: function(itemName, config){
			if(!bdshare.status) {
				bdshare.init(function(){
					bdshare._addConfig(itemName, config);
				});
			}else{
				bdshare._addConfig(itemName, config);
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
			if(bdshare.status) {
				window._bd_share_main.init();
			}
		},

		feedlistConfig: false,
		feedlist: function (obj){
			if(!bdshare.feedlistConfig){
				bdshare.addConfig('share', {
					"tag" : "share_feedlist",
					"onBeforeClick":function(cmd, config){
						if(window.event.target){
							var target = window.event.target;
						}else{
							var target = window.event.srcElement;
						}
						var $tag=$(target).parents('.share_block');
						var content = $tag.attr('data-text');
						var dec= $tag.attr('data-dec')
						var url = $tag.attr('data-url');
						config["bdUrl"]  = url;
						config["bdText"] = content;
						config["bdDesc"] = dec;
						config["bdPic"]  = '';
						return config;
					}
				});
				//console.log(window._bd_share_config['share']);
				bdshare.feedlistConfig = true;
			}
			var $share_block   = obj.parents('.share_button').siblings('.baidu-share').children('.share_block');
			if($share_block.is(':hidden')){
				$share_block.show();
				$share_block.siblings('.infopen').show();
			}else{
				$share_block.hide();
				$share_block.siblings('.infopen').hide();
			}
		}
	};
}
$(function(){
	$('[data-role="weibo_share_btn"]').unbind();
	$('[data-role="weibo_share_btn"]').click(function(){
		bdshare.feedlist($(this));
		var $tag=$(this).parents('.share_button').find('.share_block');
		if($tag.is(':visible')){
			$tag.hide();
		}else{
			$('.share_block').hide();
			$tag.show();
		}
	});
	/*$('.bds_count').each(function(){
		var $tag=$(this).parents('.share_button').find('.share_count');
		var html=$(this).html();
		if(html==''){
			$tag.html(0);
		}else{
			$tag.html(html);
		}
	});*/
});
//解决异步加载分享无效的问题
if(bdshare) bdshare.init();

