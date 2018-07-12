core.dropnotify = {
	_init:function(attrs){
			if(attrs.length == 1){
				return false; // 意思是执行插件 只是为了加载此文件
			} 
			this.init(attrs[1],attrs[2]);
	},
	init:function(dropclass,parentObjId){

		this.dropclass = dropclass;
		this.parentObjId = parentObjId;

		this.close = false;
		var _this = this;

		this.count();
		return false;
	},
	//显示父对象
	dispayParentObj:function(){
		if(this.close == false){
			 $('#'+this.parentObjId).show();
			 $('.'+this.dropclass).show();
		}
	},
	//隐藏
	hideParentObj:function(){
		if("undefined" != typeof(this.parentObjId)){
			$('#'+this.parentObjId).hide();
		}else{
			if($('#'+this.parentObjId).length > 0){
				$('#'+this.parentObjId).hide();	
			}
		}
	},
	//关闭 不在循环显示
	closeParentObj:function(){
		this.close = true;
		this.hideParentObj();
	},
	count:function(){
		this.titleTips();
		var _this = this;
		var noticeTipsText = {
                unread_notify:  L('PUBLIC_SYSTEM_MAIL'),
                unread_atme:    L('PUBLIC_SYSTEM_TAME'),
                unread_comment: L('PUBLIC_SYSTEM_CONCENT'),
                unread_message: L('PUBLIC_SYSTEM_PRIVATE_MAIL'),
                new_folower_count: L('PUBLIC_SYSTEM_FOLLOWING'),
                unread_group_atme: '条群聊@提到我',
                unread_group_comment: '条群组评论'
        };
        var loopCount = '';
		var getCount = function() {
 			
/*			$.get( U( "widget/UserCount/getUnreadCount" ), function( msg ) {	
				if("undefined" == typeof(msg.data) || msg.status != 1){
					return false;
				}else{
					
					var txt =msg.data;
					if(txt.unread_total <= 0){
						_this.hideParentObj();
						return false;
					}else{
						_this.dispayParentObj();
					}
					$('.'+_this.dropclass).each(function(){
						$(this).find('li').each(function(){
							var name = $(this).attr('rel');
							num  =  txt[name] ;
							if(num > 0){
								$(this).find('span').html(num +noticeTipsText[name]);
								$(this).show();
							}else{
								$(this).hide();
							}
						});
					});
				}
			},'json');*/

			$.get(U('widget/UserCount/getUnreadCount'), function(msg) {	
				if ('undefined' == typeof msg.data || msg.status != 1) {
					return false;
				} else {
					var txt = msg.data;

					var unread_notify = parseInt(txt.unread_notify);
					var unread_atme = parseInt(txt.unread_atme);
					var unread_digg = parseInt(txt.unread_digg);
                    var unread_digg_weibapost = parseInt(txt.unread_digg_weibapost);
                    var unread_digg_weibareply = parseInt(txt.unread_digg_weibareply);
                    var unread_digg_total = parseInt(txt.unread_digg_total);
					var unread_comment = parseInt(txt.unread_comment);
					var unread_message = parseInt(txt.unread_message);
					var new_folower_count = parseInt(txt.new_folower_count);
					
					var unread_total = unread_notify+unread_atme+unread_digg_total+unread_comment+unread_message;
					
					document.cookie=_CP+'unread_message='+parseInt(unread_total);

					core.message.setMessageNumber('pl', unread_comment);
                    core.message.setMessageNumber('zan', unread_digg_total);
                    core.message.setMessageNumber('tz', unread_notify);

                    // 新关注数量
					if(new_folower_count > 0){
						$('.new_folower_count').text('+'+new_folower_count);
					}else{
						$('.new_folower_count').text('');
					}

				}
			},'json');
	
		};
		loopCount = setInterval( getCount, 30000 );
		
		getCount();

       
	},
	
	titleTips:function(){
		try{
			var oldTitle,toggle = 1;
			if(typeof oldTitle == 'undefined'){
				oldTitle = document.title;
			}else{
				return;
			}
			setInterval(function(){
				var $unread_total = $('li[model-node="notice"]');
				$unread_total.find('a.num').remove('a.num');
				var re  = new RegExp(_CP+'unread_message=(\\d+)');
				var num = document.cookie.match(re);
				if(num && parseInt(num[1]) > 0){
					if(toggle > 0 && toggle < 8) {
						toggle = toggle+1;
						$('title:first').html('【新消息】'+oldTitle);
					}else{
						toggle = 1;
						$('title:first').html('【　　　】'+oldTitle);
					}
				}else if(document.title != oldTitle){
					$('title:first').html(oldTitle);
				}
			}, 1000);
		}catch(e){}
	}
};