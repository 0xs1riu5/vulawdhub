M.addModelFns({
	announcement:{	//公告滚动JS
		mouseenter:function(){
			this.start = false;
			clearInterval(this.t);
		},
		mouseleave:function(){
			this.start = true;
			this.startli();
		},
		load:function(){
			var _this = this;
			this.start  = true;
			this.t = 0;
			var AutoScroll = function(){
				 $(_this).find("ul:first").animate({
                            marginTop:"-16px"
                 },800,function(){
                    $(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
                 });	
			};
			this.startli = function(){
				if(_this.start){
					_this.t = setInterval(AutoScroll,4000);
				} 
			};
			this.startli();
		}
	},
	myfollow:{
		click:function(){
			var x = $(this).offset(); 
			$('.layer-group-list').css({'left':x.left+'px','top':x.top+$(this).height()+'px'}).show();
			$(this).addClass('open');
			$('.layer-group-list').attr('_mouse','on');
		},
		mouseleave:function(){
			var _this = this;
			var hide = function(){
				if($('.layer-group-list').attr('_mouse') !='on'){
					$('.layer-group-list').hide();
					$(_this).removeClass('open');
				}else{
					$(_this).addClass('open');
				}
				
			}
			$('.layer-group-list').attr('_mouse','left');
			setTimeout(hide,200);
		}
	},
	layer_group_list:{
		mouseenter:function(){
			$(this).attr('_mouse','on');
		},
		mouseleave:function(){
			$(this).attr('_mouse','left');
			var hide = function(){
				if($('.layer-group-list').attr('_mouse') !='on'){
					$('.layer-group-list').hide();
					var myfollow = M.getModels('myfollow');
					$(myfollow[0]).removeClass('open');
				}
			}
			setTimeout(hide,200);
		}
	},
	// 我关注的频道
	mychannel: {
		click: function() {
			var x = $(this).offset();
			$('.layer-channel-group-list').css({'left':x.left+'px','top':x.top+$(this).height()+'px'}).show();
			$(this).addClass('open');
			$('.layer-channel-group-list').attr('_mouse','on');
		},
		mouseleave: function() {
			var _this = this;
			var hide = function() {
				if($('.layer-channel-group-list').attr('_mouse') != 'on') {
					$('.layer-channel-group-list').hide();
					$(_this).removeClass('open');
				} else {
					$(_this).addClass('open');
				}
			};
			$('.layer-channel-group-list').attr('_mouse', 'left');
			setTimeout(hide, 200);
		}
	},
	layer_channel_group_list: {
		mouseenter: function() {
			$(this).attr('_mouse','on');
		},
		mouseleave: function() {
			$(this).attr('_mouse','left');
			var hide = function(){
				if($('.layer-channel-group-list').attr('_mouse') !='on'){
					$('.layer-channel-group-list').hide();
					var myfollow = M.getModels('myfollow');
					$(myfollow[0]).removeClass('open');
				}
			}
			setTimeout(hide,200);		
		}
	}
}).addEventFns({
	close_announcement:{
		click:function(){
			//关闭公告
			$(this.parentModel).hide('fast');
		}
	}
});