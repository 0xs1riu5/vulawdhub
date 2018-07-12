/**
 * 小名片JS模型
 */
core.facecard ={
		//给工厂调用的接口
		_init:function(attrs){
			this.init();
		},
		init:function(){
			if("undefined" != typeof(this.face_box)){
				return false;
			}
			var html = '<div id="face_card" class="wrap-layer" style="position:absolute;left:10%;background-color:#fff;display:none;z-index:99999">'+
	            	   '<div class="content-layer" style="font-size:12px;">'+
	            	   '<div class="layer-content" >'+
	            	   '<div class="name-card clearfix" id="name-card">'+
		               '<div class="loading"><img src="'+THEME_URL+'/image/load.gif"></div></div></div>'+
		               '<div class="arrow arrow-l" ></div></div></div>';
			this.face_box = $(html);
			$('body').append(this.face_box);
			this.user_info = new Array();
		},
		show:function(obj,uid){ 
			this.obj = obj;
			$(obj).attr('show','yes');
			var _this = this;
			var _show = function(){
				//设置默认框的位置
				if($(obj).attr('show') != 'yes'){
					return false;
				}
				_this.setCss(obj);
                $('#face_card').addClass('show_box');
				if("undefined" != typeof(_this.user_info[uid]) || _this.user_info[uid] == ''){
					_this.face_box.find('.name-card').html(_this.user_info[uid]);
					_this.setCss(obj); //重设高宽
				}else{
					$.post(U('public/Index/showFaceCard'),{uid:uid},function(msg){
						_this.face_box.find('.name-card').html(msg);
						_this.setCss(obj); //重设高宽
						_this.user_info[uid] = msg;
					});
				}
			};
			setTimeout(_show,600);

			$(obj).mouseover(function(){
				$(this).attr('show','yes');
			});
			$(obj).mouseout(function(){
				$(this).attr('show','no');
			});
		},
		deleteUser:function(uid){
			if("undefined" != this.user_info[uid]){
				this.user_info[uid] = '';
				delete this.user_info[uid];
			}
		},
		setCss:function(obj){	//计算位置
			
			var p =$(obj).offset();
			var bh = $('body').height();
			var ww = $(window).width();
			var scrollHeight = $(window).scrollTop();
			var fw = this.face_box.width(); //可以设定 小名片的宽度
			var fh = this.face_box.height(); //可以设定 小名片的高度
			
//			var left = p.left+$(obj).width()+5; //默认当前的left
//			var top = p.top - 20;
//			var className = 'arrow-l';	
			var top = p.top - fh - 8;
			var className = 'arrow-b';
			var left = p.left - 9;
		
			
			if(ww-p.left < fw ){
				left = p.left -fw - 5;
				className = 'arrow-r';
				top = p.top - 5;
			}
			if(p.top - scrollHeight < 40+fh){
				//向下
				//重设left
				top = p.top + $(obj).height() + 5;
				left = p.left - 15;
				className = 'arrow-t';
			}
			if(bh-p.top < fh ||  ( $(window).height() +  scrollHeight - p.top) < fh ){
				//向上
				top = p.top - fh - 5;
				className = 'arrow-b';
				left = p.left - 18;
			}
			
			var arrow = this.face_box.find('.arrow'); 
			arrow.removeClass('arrow-r');
			arrow.removeClass('arrow-l');
			arrow.removeClass('arrow-b');
			arrow.removeClass('arrow-t');
			arrow.addClass(className);
			
			this.face_box.css({'left':left+'px','top':top+'px'})	
			this.face_box.show();
			var _this = this;
			this.face_box.mouseover(function(){
				_this.boxOn = true;
			});
			this.face_box.mouseout(function(){
				_this.boxOn = false;
				_this.hide();
			});
		},
		hide:function(){
			//隐藏弹窗，清空人信息
			var _this = this;
			var hidden = function(){
				if(_this.boxOn || $(_this.obj).attr('show') == 'yes'){
					return false;
				}
				_this.face_box.hide();
				//_this.face_box.find('.name-card').html('');
				$(_this.obj).attr('show','no');
			};
            $('#face_card').removeClass('show_box');
			setTimeout(hidden,500);
		},
		dohide:function(){//强制隐藏
			var _this = this;
			_this.face_box.hide();
			$(_this.obj).attr('show','no');
		}
};		