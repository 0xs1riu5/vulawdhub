/*!
 * jQuery.dialog  0.88
 *
 * Copyright 2010, Baike.com
 * Dual licensed under the MIT and GPL licenses.
 * author: panxuepeng
 * blog: http://dushii.blog.163.com
 * $Date: 2008-08-31 $
 * $Last: 2010-08-26 $
 */
 
 /*
 run:
 Firefox 2+, IE6+, Chrome2+, Safari3+
 
 usage:
$.dialog({
	id:'demo',
	align:'left',
	position:'center',
	width:400,
	title:'Hi! This is a demo of the $.dialog',
	content:"$.dialog({<br>&nbsp;id:'demo',<br>&nbsp;align:'left',<br>&nbsp;width:400,<br>&nbsp;title:'Hi! This is a demo of the $.dialog',<br>&nbsp;content:'......'<br>});"
});
 */

(function($){

var userAgent = navigator.userAgent;
$.IE = $.browser.msie;
$.Firefox = /Firefox/i.test(userAgent);

$.IE6 = (function(){
	if (/MSIE [7891]\d?/.test(userAgent)) return false;
	if (/MSIE [56]/.test(userAgent)) return true;
	return false;
})();

//计算一些常用数字
function init_size(){
	var self = $._dialog;if(!self) return;
	self.scrollTop   =$(document).scrollTop();
	self.scrollLeft  =$(document).scrollLeft();
	self.windowHeight=$(window).height();
	self.windowWidth =$(window).width();
	
	self.bodyPosition=$(document).find('body').css('position');
	self.bodyWidth   =$(document).find('body').width();
	self.bodyLeft    =$(document).find('body').position().left;
	if(!self.bodyLeft && self.windowWidth > self.bodyWidth){
		self.bodyLeft=(self.windowWidth - self.bodyWidth)/2;
	}
}

function init_dialog(){
	var self=$._dialog, clockScroll=0, clockResize=0;
	self.init();
	init_size();
	
	$(window).bind('resize', function(){
		var self=$._dialog;
		if(clockResize){clearTimeout(clockResize)}
		
		clockResize=setTimeout(function(){
			init_size();
			var self=$._dialog, options;
			for(id in self.list){
				if (self.isShow(id)){
					options=self.options[id];
					self.show(id);
					if (options.overlay){self.setOverlay(id)}
				}
			}
		}, 200);
		return false;
	});
	
	$(window).bind('scroll',function(){
		var self=$._dialog;
		if(clockScroll){clearTimeout(clockScroll)}
		
		clockScroll=setTimeout(function(){
			//窗口滚动停止后执行如下代码
			self.scrollTop = $(document).scrollTop();//重新计算 self.scrollTop
			
			for(id in self.options){
				var options=self.options[id], pos =self.getPosition(options.id), dialog=self.list[id];
				
				dialog.stop();
				if(options.minScrollTop){
					if(options.minScrollTop >self.scrollTop){
						dialog.css({visibility:'hidden'});
					}else{
						dialog.show().css({left:pos.left, top:pos.top, visibility:'visible'});
					}
					
					//if(options.autoClose){fn_clockAutoClose(options.id)}
					return;
				}
				
				if($.IE6 && options.fixed && self.isShow(id) ){
					//在IE6下，使用animate方法操作将display属性会报js错误，
					//dialog.animate({left:pos.left, top:pos.top, display:'block'}, 200);
					if(typeof options.resetTime == "number" && options.resetTime>0){
						dialog.show().animate({left:pos.left, top:pos.top}, options.resetTime);
					}else{
						dialog.css({left:pos.left, top:pos.top}).show();
					}
					//if(options.autoClose){fn_clockAutoClose(options.id)}
				}
			}
		}, 200);
		return false;
	});
}

$(document).ready(function(){
	init_dialog();
});

//$._dialog 对象通过 $.dialog() 来调用
$._dialog = {
	version:0.88,
	list : {}, // dialog object
	isOpen:[], //是否处于打开状态
	isClose:[], //是否处于关闭状态
	options : {}, // dialog options
	parts : {}, //窗口的组成部分，标题，关闭按钮，内容区域，底部按钮区域
	fn_clockScroll:[],
	skins:{},
	keys:{},
	
	config : {
		lastid:'',
		id:'default',
		skin:'default',
		offsetClick:{},
		focusStart:false,
		zIndex:2010,
		effect_time_up:500,
		effect_time_down:500,
		effect_time_fade:200,
		base:'.', //图片资源路径
		htmlImgLoading:'',
		imgLoading:'indicator.gif',
		imgClose:'close.jpg',
		imgTitleBg:'bg_box_hand.gif'
	},
	defaults: {
		id:'default',
		key:'', //唯一码
		skin:'default',
		move:true, //是否可以拖动
		overlay:true, //是否有遮罩层
		model:'default',//default  mini  together  alone
		zIndex:2010,
		title:'', //窗口标题
		content:'', //窗口内容
		type:'',//img url iframe selector，注意 type 的优先级大于 content
		url:'', //内容的url地址，当 type=img||url 时有效
		callback:null, //回调函数
		callbackTimeout:500,
		position:'middle', //窗口位置
		offsetX:0,//相对应屏幕的水平偏移量
		offsetY:0,//相对应屏幕的垂直偏移量
		fixed:false, //是否固定于屏幕上
		effects:'',// fade  up down
		autoClose:0, //是否定时自动关闭，单位毫秒
		forceClose:1, //当鼠标在窗口上时，取消自动关闭，离开时再设置关闭
		width:350, //窗口宽
		height:200, //窗口高
		fixedWidth:0,//锁定窗口宽度
		minScrollTop:0,
		onOk:null,//确定按钮触发的函数
		onCancel:null,//取消按钮触发的方法
		onClose:null,//关闭窗口时触发的方法
		textOk:'确定',
		textCancel:'取消',
		closeImg:true,
		resetTime:100,//重新定位窗口的移动时间
		resizable:1, //是否允许根据内容自动调整窗口大小
		styleDialog:{},
		styleTitle:{},
		styleContent:{},
		styleBtn:{},
		styleOk:{},
		styleCancel:{},
		styleOverlay:{opacity:0.3},
		valign:''//垂直居中
	},
	
	/*
	* 初始化，在 document.ready 时执行一次，且仅执行一次！
	*/
	init : function(){
		var self=this;
		self.config.htmlImgLoading='<img src="'+self.config.base+'/'+self.config.imgLoading+'"/>';
		//默认样式，可以通过$.dialog.addSkin(strStyle) 来添加其他 css 皮肤
		var style='div.hudong_dialog {border:1px solid #8C8C8C;background-color:#FFFFFF;overflow:hidden;}\
			\n div.hudong_dialog h2.title{margin:0;height:22px;background:url('+self.config.base+'/'+self.config.imgTitleBg+');\
				border-bottom:1px solid #E0E0E0;padding:3px 5px;font-weight:bold;font-size:14px;overflow:hidden;-moz-user-select:none;line-height:22px;}\
			\n div.hudong_dialog .close{display:block;float:right;cursor:pointer;width:16px;height:16px;margin:0;\
				top:5px;right:5px;position:absolute;background:url('+self.config.base+'/'+self.config.imgClose+');}\
			\n div.hudong_dialog div.content{padding:5px;text-align:center;background-color:#FFFFFF;position:relative;}\
			\n div.hudong_dialog div.button{position:absolute;width:100%;left:0;bottom:0;text-align:center;height:25px;padding:4px 10px 10px;}\
			\n div.hudong_dialog div.button input{text-align:center;margin-right:10px;}\
			\n div.hudong_overlay{z-index:2001;cursor:default;background-color:#ffffff;\
				width:100%;height:100%;top:0px;left:0px;position:absolute;margin:0 auto;}';
			
		style+='div.bluebox {border:5px solid #666666;}\
			\n div.bluebox h2.title{background:none;background-color:#009DF0;color:#FFFFFF;font-size:14px;}\
			\n div.bluebox .close{top:5px;right:5px;}\
			\n div.bluebox div.button{background-color:#E8E8E8;padding:5px 10px 2px;}';
			
		style+='div.noborder {border:0;background-color:transparent;}\n div.noborder .close{top:10px;right:10px;}';
		
		if($("style#hudong_dialog_style").size()==0){
			$('head').append('<style id="hudong_dialog_style">'+ style +'</style>');
		}
	},
	
	/*
	* 打开一个窗口
	*/
	open :function(options){
		var self=this;
		if(document.getElementsByTagName('body').length == 0){
			$(document).ready(function(){
				self.open(options);
			});
			return false;
		}
		
		if (typeof options != 'object'){
			alert('arguments must be a object, like {id:"id", title:"title"}.');
			return false;
		}
		
		var id =self.config.id =  options.id;
		if (typeof id != 'string' && typeof id != 'number'){
			alert('the type of id must be string or number.');
			return false;
		}
		
		if(! options["skin"]){
			options["skin"] = self.config["skin"];
		}
		
		
		//对于未定义的选项使用默认值 self.defaults[i]
		function extend(defaults, options){
			var type;
			for (var i in defaults){
				type=typeof options[i];
				if (type =='undefined'){//使用默认值
					options[i] = defaults[i];
				}else if(type == 'object'){
					extend(defaults[i], options[i])
				}
			}
		}
		
		extend(self.defaults, options);
		
		/*
		//使用上面的递归方式代替下面的方式
		var type;
		for (var i in self.defaults){
			type=typeof options[i];
			if (type =='undefined'){//使用默认值
				options[i] = self.defaults[i];
			}else if(type == 'object'){
				for(var j in self.defaults[i]){
					if (typeof options[i][j] =='undefined'){//使用默认值
						options[i][j] = self.defaults[i][j];
					}
				}
			}
		}
		*/
		
		function isInt(x, defaultValue){
			return (typeof x == 'number') ? x : defaultValue;
		}
		options.autoClose = isInt(options.autoClose, 0);
		options.width = isInt(options.width, 350);
		options.height = isInt(options.height, 200);
		if(options.autoClose > 0 && options.autoClose < 100){
			options.autoClose *= 1000;
		}
		
		if (typeof options.position=="object"||options.height >self.windowHeight){options.fixed=false}
		if (typeof options.fixed == "string"){options.fixed=options.fixed.toLowerCase()}
		options.effects= /up|down|fade/i.test(options.effects)?options.effects.toLowerCase():'';
		
		if(!options.type && options.content){
			if(/^[\w\/:&?,=-]+\.(jpg|gif|png)$/i.test(options.content)){
				//内容是个图片地址，则以图片形式打开
				options.type='img';
				options.url=options.content;
			}else if(/^[\w\/:&?,=-]+\.html?$/i.test(options.content)){
				//内容是个网页地址，则以网页形式打开
				options.type='url';
				options.url=options.content;
			}
			
		}
		
		self.options[id] =options;
		if (options.overlay){self.setOverlay(id)}
		
		if(options.model.indexOf('alone')>-1){
			for(i in self.list){
				if(i != id && self.options[i] && self.options[i].model.indexOf('together')<0 ){
					self.close(i);
				}
			}
		}
		
		self.build(options);
		
		options['isLoad']=1;
		return self;
	},
	
	/*
	* 设置窗口内容
	*/
	setContent : function(id, content){
		var self=this, dialog = self.list[id];
		if (!dialog){alert('dialog '+id+ ' is not exist.')}
		self.parts[id].content.html(content);
		return self;
	},
	
	reset: function(id, ms){
		var self=this;
		ms = ms||100;
		setTimeout(function(){
			//先根据窗口内容调整窗口的大小
			self.resize(id);
			
			//再重新定位窗口的位置
			var dialog = self.list[id], pos = self.getPosition(id), options=self.options[id];
			if(options.resetTime > 0){
				//注意此处不能使用 dialog.stop()，否则将导致一些使用特效的窗口在完全显示之前被终止
				dialog.animate({top:pos.top, left:pos.left}, options.resetTime);
			}else if(options.resetTime == 0){
				dialog.css({top:pos.top, left:pos.left});
			}
		}, ms);
	},
	
	/*
	* 配置窗口
	*/
	setOptions : function(options){
		var id = options.id, content='', url='';
		var self=this, dialog = self.list[id], part = self.parts[id];
		
		/*
		//已经修改为 $._dialog.reset()
		
		//延迟重新定位
		function _reset(id, ms){
			ms = ms||100;
			setTimeout(function(){
				self.resize(id);
				var pos = self.getPosition(id);
				if(options.resetTime>0){
					//注意此处不能使用 dialog.stop()，否则将导致一些使用特效的窗口在完全显示之前被终止
					dialog.animate({top:pos.top, left:pos.left}, options.resetTime);
				}else if(options.resetTime==0){
					dialog.css({top:pos.top, left:pos.left});
				}
			}, ms);
		}
		*/
		
		if (options.type == 'url'){
			url = options.url;
			if (self.config.url == url){
				self.show(id);
				return self;
			}
			self.config.url = url;
			self.setContent(id, self.config.htmlImgLoading);
			$.get(url, function(data, state){
				if (self.isClose[id]){return}
				if (state == 'success'){
					self.setContent(id, data);
					self.reset(id);
					if($.IE) self.reset(id, 200);
					if ($.isFunction(options.callback)){options.callback(dialog)}
				}else {
					self.setContent(id, "Loading failure!");
				}
			});
		}else if (options.type == 'img'){
			url = options.url;
			if (self.config.url == url){
				self.show(id);
				return self;
			}
			self.config.url = url;
			self.setContent(id, self.config.htmlImgLoading);
			var pos = self.getPosition(id);
			dialog.css({top:pos.top,left:pos.left,opacity:''});
					
			var img = new Image();
			img.onload = function(){
				if (self.isClose[id]){return}
				var width = img.width >950 ?950 : img.width;
				self.setContent(id, '<img src="'+url+'" width="'+width+'" />');
				self.reset(id);
				if($.IE) self.reset(id, 200);
				if ($.isFunction(options.callback)){options.callback(dialog)}
			}
			img.onerror = function(){self.setContent(id, options.error)}
			img.src= url;
			
			self.parts[id].content.dblclick(function(){$.dialog.close(id)});

		}else if(options.type == 'iframe'){
			content = "<iframe id='"+id+"_iframe' name='"+id+"_iframe' border='0' width='"+(options.width-20)+"' height='"+(options.height-50)+"' frameborder='no' "
				+ " marginwidth='0' marginheight='0' scrolling='no' allowtransparency='yes'></iframe>";
			self.setContent(id, content);
			self.reset(id);
			var iframe=$("#"+id+"_iframe");
			iframe.load(function(){
				if ($.isFunction(options.callback)){
					setTimeout(function(){options.callback(dialog)}, options.callbackTimeout);
				}

			}).attr('src', options.url);
		}else if(options.type == 'selector'){
			content = $(options.url||options.content).html();
			if(content){
				self.setContent(id, content);
				self.reset(id);
			}
			if ($.isFunction(options.callback)){
				setTimeout(function(){options.callback(dialog)}, options.callbackTimeout);
			}
		}else{				
			if(options.type != 'customize'){
				if(options.valign && /center|top|middle|baseline|bottom/.test(options.valign)){
					if("center" == options.valign){ options.valign = "middle"; }
					var _height=options.height-40;
					if(typeof options.onOk == "function" || typeof options.onCancel == "function"){
						_height -= 25;
					}
					self.setContent(id, '<table width="100%" height="'+ _height +'"><tr><td valign="'+ options.valign +'">'+ options.content +'</td></tr></table>');
				}else{
					self.setContent(id, options.content);
				}
			}
			
			self.reset(id);
			if (!options.type && $.isFunction(options.callback)){
				setTimeout(function(){options.callback(dialog)}, options.callbackTimeout);
			}
		}
		
		var part = self.parts[id];
		options.title?part.title.show(): part.title.hide();
		
		if (options.move){part.title.css('cursor', 'move')}
		
		//设置窗口的初始高度和宽度
		if(options.type != 'customize'){
			if (options.width){dialog.width(options.width)}
			
			if (options.height){dialog.height(options.height)}
		}
		
		if(options.closeImg){
			part.close.show().unbind('click').click(function(){
				var id=$(this).parent().attr('id').replace('dialog_', '');
				$.dialog.close(id);
			});
		}else{
			part.close.hide();
		}
		
		
		if($.isFunction(options.onOk)|| $.isFunction(options.onCancel)){
			part.button.show();
			$.isFunction(options.onOk)? part.ok.show(): part.ok.hide();
			$.isFunction(options.onCancel)? part.cancel.show(): part.cancel.hide();
			
			if($.isFunction(options.onOk)) part.ok.unbind('click').click(function(){options.onOk(dialog)});
			if($.isFunction(options.onCancel)) part.cancel.unbind('click').click(function(){options.onCancel(dialog)});
		}else{
			part.button.hide();
		}
		
		//设置自定义样式，当设置不合法的css属性时，则IE下会报js错误
		function _css(o, s){
			if (typeof options[s] == 'object'){
				try{
					o.css(options[s]);
				}catch(e){
					alert("id = [" + options.id + "]的窗口，自定义css样式存在错误，请检查css的 "+s+" 部分。");
				}
			}
		}
		_css(part.content, 'styleContent');
		_css(part.title, 'styleTitle');
		_css(part.button, 'styleBtn');
		_css(part.ok, 'styleOk');
		_css(part.cancel, 'styleCancel');
		_css(dialog, 'styleDialog');

		self.show(id);
		return self;
	},
	
	
	/*
	* 根据内容重新设置窗口大小
	* 被 reset 方法调用
	*/
	resize: function(id){
		var self =this, dialog=self.list[id], part=self.parts[id], options=self.options[id];
		if(options.type == 'customize' || !options.resizable){//如果是自定义的窗口，则不再resize()
			return;
		}
		var W, H, otherHeight=0, child=part.content.children('div,img,table,iframe').eq(0),
			//内容区域的上下padding之和
			paddingTB=parseInt(part.content.css('paddingTop')) + parseInt(part.content.css('paddingBottom')) +5,
			paddingLR=parseInt(part.content.css('paddingLeft')) + parseInt(part.content.css('paddingRight'));
		
		dialog.css({height:''});
		if(!$.IE6) part.content.css({height:''});//在IE6下，如果没有指定options.height，可能导致内容不显示
		
		if(options.onOk || options.onCancel) otherHeight +=part.button.outerHeight();
		if(options.title) otherHeight +=part.title.outerHeight();
		
		if(child.size()){
			W =child.outerWidth() +paddingLR;
		}else{
			W =part.content.outerWidth();
		}
		
		H=part.content.outerHeight();
		
		//调整内容区域的高度
		if(options.height && H < options.height -otherHeight-paddingTB){
			//当内容高度小于窗口高度时，为了确保 确定 取消 按钮 在窗口的底部，
			//需要给内容区域设置高度
			part.content.height(options.height -otherHeight -paddingTB);
		}
		
		
		//在不锁定宽度时才调整窗口宽度
		if(!options.fixedWidth){
			
			//调整内容区域的宽度
			if((options.width && options.width > W) || !child.css('width')){
				//当内容宽度小于指定的宽度时，
				//需要给内容区域设置宽度，以调整窗口的宽度
				W = options.width;
				part.content.width(options.width - parseInt(part.title.css('paddingTop'))*2 );
			}
			
			//当有 fixed 属性时必须给dialog指定宽度，否则会出现问题
			dialog.css({width: W+'px'});
			/*
			if(options.fixed){
				dialog.css({width: W+'px'});
			}else{
				if(!$.IE) dialog.css({width: ''});//清除width样式，在IE下会导致标题栏不再100%
			}
			*/
		}
				
		//如果dialog的高度大于当前窗口的高度，则将dialog的顶部和窗口顶部对齐
		if(H > self.windowHeight){dialog.css('top', self.scrollTop)}
	},
	
	/*
	* 显示窗口
	*/
	show : function(id){
		if (!id) id = this.config.id;
		var self = this, dialog = self.list[id], options =self.options[id], pos;
		var removeOpacity =function(){
				dialog.css({opacity:''});
			};	
		pos = self.getPosition(id);
		
		self.setPosition(id, pos);
		if(options.minScrollTop){
			if(options.minScrollTop > self.scrollTop){
				//scrollTop 不足 minScrollTop 则隐藏
				//也不能使用hide()方法，否则可能导致显示位置不正确
				dialog.css({visibility:'hidden'});
			}
			
		}else{
			if(options.effects && typeof options['isLoad'] == 'undefined'){
				//指定了打开特效的窗口，窗口首次加载时
				var top=pos.top, height=dialog.height();
				dialog.stop().show();
				switch(options.effects){
					case 'down':
						dialog.css({left:pos.left, top:-1000, opacity:0.1})
							.animate({top:top,opacity:1},self.config.effect_time_down, removeOpacity );
						setTimeout(function(){removeOpacity()}, self.config.effect_time_down);
						//IE下面在某种情况下貌似不能执行上面的 animate 方法，所以需要使用setTimeout，下同
					break;case 'up':
						dialog.css({left:pos.left, top:top+height, opacity:0.1})
							.animate({top:top,opacity:1},self.config.effect_time_up, removeOpacity );
						setTimeout(function(){removeOpacity()}, self.config.effect_time_up);
					break;case 'fade':
						//var duration = (typeof options.position == "object")?200:300;
						dialog.css({left:pos.left, top:pos.top, opacity:0.1});
						dialog.animate({opacity:1}, self.config.effect_time_fade, removeOpacity);
						setTimeout(function(){removeOpacity()}, self.config.effect_time_fade);
					break;
				}
				
			}else if(options.effects){
				//指定了打开特效的窗口，再次实现 show()
				var dialog = self.list[id];
				if(/^(?:up|down)$/i.test(options.effects) && options.resetTime>0){
					dialog.show().animate({top:pos.top, left:pos.left, opacity:1}, options.resetTime, removeOpacity);
				}else{
					dialog.show().css({top:pos.top, left:pos.left, opacity:''});
				}
			}else{
				//未指定打开特效的窗口
				dialog.css({top:pos.top, left:pos.left, opacity:''}).show();
			}
			//self.config.zIndex+=1;
			dialog.css({zIndex: ++self.config.zIndex});
		}
		
		setTimeout(function(){
			var inputTexts = dialog.find(":text").filter(":visible");
			if (!inputTexts.length) {
				inputTexts = dialog.find("textarea:visible");
			}
			if (inputTexts.length) {
				inputTexts.eq(0).focus();
				//执行focus()后，如input有值，FF默认是将光标定位在值的后面，而IE是默认定位在最前面
				//统一修正为默认定位在文本框的值后面，可以通过参数 self.config.focusStart 配置
				var el=inputTexts.eq(0)[0];
				if(el.createTextRange){
					var re = el.createTextRange();
					re.select();
					re.collapse(self.config.focusStart);
					re.select();
				}else if(el.setSelectionRange){//作用于 FF Chrome 等
					if(self.config.focusStart){
						el.setSelectionRange(0, 0);
					}else{
						el.setSelectionRange(el.value.length, el.value.length);
					}
				}
			}
		}, 200);
		self.isOpen[id]=1;
		self.isClose[id]=0;
		return self;
	},
	
	autoClose: function(id){
		var self=this, options =self.options[id];
		if(options['clockAutoClose']){clearTimeout(options['clockAutoClose'])}
		options['clockAutoClose']=setTimeout(function(){
			$.dialog.close(id);
		}, options.autoClose);
		return false;
	},
	
	/*
	* 关闭窗口
	*/
	close : function(id){
		if (!id) id = this.list.length -1;
		if(typeof this.list[id] == 'undefined') return;
		var self=this, dialog = self.list[id], options =self.options[id], height=dialog.height(),pos=self.getPosition(id);

		function fn_dialogClose(){
			dialog.stop().css({top:-1999, opacity:'', display:'none'});
			self.isOpen[id]=0;//此句必须在 self.hideOverlay() 之前，否则不能关闭遮罩层
			self.isClose[id]=1;
			self.hideOverlay();
		}
		
		if (dialog && self.isShow(id)){
			if(self.fn_clockScroll[options.id]) $(window).unbind('scroll', self.fn_clockScroll[options.id]);
			if(options['clockAutoClose']) clearTimeout(options['clockAutoClose']);
			dialog.unbind('mouseleave').stop();
			
			var o_dialogClose={duration:200, complete:fn_dialogClose};
			switch(options.effects){
				case 'down':
					dialog.animate({top:-1999, opacity:0.1}, o_dialogClose);
				break;case 'up':
					dialog.animate({top:pos.top+height+100, opacity:0.1}, o_dialogClose);
				break;case 'fade':
					dialog.animate({opacity:0.1}, o_dialogClose);
				break;default:
					fn_dialogClose();
			}
			
		}else if(dialog){
			//为防止一些意外，点击关闭按钮一定会触发关闭操作
			fn_dialogClose();
		}
		
		if(dialog){
			if(options.effects || $.IE6) setTimeout(function(){fn_dialogClose()}, 500);
		}
	},
	
	//判断窗口是否显示
	isShow : function(id){
		if(this.isClose[id]) return false;
		if(this.isOpen[id]) return true;
	},
	
	/*
	* 构建窗口
	*/
	build : function(options){
		var self =this, id =options.id, dialog=self.list[id], part, width=options.width, _id="dialog_"+id, isExist=0;
		self.config.zIndex++;
		if (!dialog){
			//此ID的窗口首次打开
			isExist=0;
			dialog = $('#'+_id);
			var position=(options.fixed && !$.IE6)?'fixed':'absolute';
			
			if(dialog.size()){//此 ID 的 dialog 已经存在，直接使用，适合自定义 dialog 窗口
				dialog.css({position:position}).attr('customize', '1');
				if(options.skin) dialog.addClass(options.skin);
				options['type']='customize';
			}else{
				//构建一个dialog
				var html='<div class="hudong_dialog '+options.skin+'" id="'+_id+'" style="position:'+position+';">';
				html +='<h2 class="title">'+options.title+'</h2>';
				html +='<div class="content"></div>';
				html +='<div class="button">';
				html +='<input type="button" class="ok" name="ok" value="'+options.textOk+'"/>';
				html +='<input type="button" class="cancel" name="cancel" value="'+options.textCancel+'"/>';
				html +='</div><img class="close"></img></div>';
				$('body').append(html);
				dialog = $('#'+_id);
			}
			
			self.list[id] = dialog;
			
			part=self.parts[id] ={
				title:dialog.children("h2.title"),
				close:dialog.children("img.close"),
				content:dialog.children("div.content"),
				button:dialog.children("div.button"),
				ok:dialog.find("input.ok"),
				cancel:dialog.find("input.cancel")
			};
			
			if(!part.title){
				part.title=dialog.find("[name='dialog_title']");
			}
			if(!part.close){
				part.close=dialog.find("[name='dialog_close']");
			}
		}else{
			isExist=1;
			part=self.parts[id];
			part.title.html(options.title);
		}
		
		dialog.stop().css({'zIndex':self.config.zIndex++, 'opacity':''});
		
		if(options.key && self.keys[id]==options.key){
			//通过判断 key 来避免当连续重复打开同一个内容时，进行重复构建
			if (self.isShow(id)){
				//当前窗口已经处于显示状态，无需重复执行显示操作
				$.dialog.close(id);
			}else{
				self.show(id);
				
				//再次执行 callback()
				if ($.isFunction(options.callback)){
					setTimeout(function(){options.callback(dialog)}, options.callbackTimeout);
				}
			}
			//========================
			//不需要再次绑定事件，终止
			//========================
			return;
		}else{
			//此ID的窗口未指定options.key，推荐指定options.key，但是对于每次打开都需要初始化内容的情况则不要指定options.key
			//或者首次打开
			
			self.setOptions(options);
			//至此已经显示窗口
			self.keys[id]=options.key ?options.key :'';
		}
		
		//设置自动关闭事件,每次打开窗口都需要设置
		if(options.autoClose){
			options.autoClose = parseInt(options.autoClose);
			options.autoClose = isNaN(options.autoClose)?2000:options.autoClose;
			
			/*
			//将此函数改为了 $._dialog.autoClose()
			function fn_clockAutoClose(id){
				var options =self.options[id];
				if(options['clockAutoClose']){clearTimeout(options['clockAutoClose'])}
				options['clockAutoClose']=setTimeout(function(){
					$.dialog.close(id);
				}, options.autoClose);
				return false;
			}
			*/
			self.autoClose(options.id);
			
			if(options.title && !options.forceClose){
				//如果存在标题栏，则当鼠标在dialog上时取消自动关闭
				//鼠标离开dialog时再次绑定自动关闭
				dialog.unbind('mouseover').mouseover(function(){
					clearTimeout(options['clockAutoClose']);
				}).unbind('mouseleave').mouseleave(function(e){
					var id=$(this).attr('id').replace('dialog_', '');
					self.autoClose(id);
				});
			}
		}
		
		if(isExist){//此 ID 的窗口已经存在
			return;
			//============================================
			// 不再需要重复绑定事件，终止
			//============================================
		}
		
		dialog.unbind('click').click(function(){
			//使用局部变量代替全局变量，更加稳定
			var id, dialog;
			id=$(this).attr("id").replace("dialog_", "");
			dialog=$.dialog.get(id);

			if (self.config.id != id){
				//如果点击的窗口不是当前操作的窗口
				self.config.lastid = self.config.id;
				self.config.id = id;
				self.config.zIndex +=1;
				dialog.css({'opacity':'', 'zIndex':self.config.zIndex});
			}
		});
		
		//绑定事件
		if(part.title.length){
			//mousedown move
			if (options.move){
				part.title.unbind('mousedown').mousedown(function(e){
					//使用局部变量代替全局变量，更加稳定
					var id, dialog;
					id=$(this).parents(".hudong_dialog").attr("id").replace("dialog_", "");
					dialog=$.dialog.get(id);
					self.config.zIndex +=1;
					dialog.css({'zIndex':self.config.zIndex});
					var offset = dialog.offset();
					self.config.offsetClick = {
						width: e.pageX - offset.left,
						height:e.pageY - offset.top,
						left:dialog.css('left'),//left:offset.left,
						top:dialog.css('top') //top:offset.top
					};
					
					var fn_mousemove=function(e){self.move(e, id);return false;};
					var fn_selectstart=function(){return false};
					$(document).mousemove(fn_mousemove).bind("selectstart", fn_selectstart);
					
					$(document).one('mouseup',function(e){
						var o=self.config.offsetClick, pos={left:o.left, top:o.top};
						if (e.clientY < 0 || e.clientX < 2
							|| e.clientX > $(document).width()
							|| e.clientY > $(window).height()
						){
							dialog.css(pos);
						}
						$(document).unbind("mousemove", fn_mousemove).unbind("selectstart", fn_selectstart);
						return false;
					});
					return false;
				});
			}
			
			if(options.title && options.model.indexOf('mini')>-1){
				part.title.unbind('dblclick').bind("dblclick", function(){
					//使用局部变量代替全局变量，更加稳定
					var id=$(this).parents(".hudong_dialog").attr("id").replace("dialog_", "");
					var part = self.parts[id];
					part.content.toggle();
					return false;
				});
			}
			
			part.title.unbind('selectstart').bind("selectstart", function(){return false});
		}
		
		/*
		var fn_clockScroll = self.fn_clockScroll[options.id] = function(e){
			if(options['clockScroll']){clearTimeout(options['clockScroll'])}
			
			options['clockScroll']=setTimeout(function(){
				//窗口滚动停止后执行如下代码
				self.scrollTop = $(document).scrollTop();//重新计算 self.scrollTop
				var pos = self.getPosition(options.id);
				dialog.stop();
				if(options.minScrollTop){
					if(options.minScrollTop >self.scrollTop){
						dialog.css({visibility:'hidden'});
					}else{
						dialog.css({left:pos.left, top:pos.top, visibility:'visible'});
					}
					
					if(options.autoClose){fn_clockAutoClose(options.id)}
					return;
				}
				
				if($.IE6 && options.fixed){
					dialog.animate({left:pos.left, top:pos.top}, 200);
					if(options.autoClose){fn_clockAutoClose(options.id)}
				}
			}, 200);
			return false;
		}
		
		$(window).unbind('scroll', fn_clockScroll).bind('scroll',fn_clockScroll);
		*/
		return this;
	},
	
	//移动窗口
	move : function(e, id){
		var self=this, dialog=self.list[id], options=self.options[id], offset=self.config.offsetClick,
			left=e.pageX-offset.width, top=e.pageY-offset.height;
		self.bodyLeft=self.bodyLeft||$('body').position().left;
		
		if (options.fixed){
			if(!$.IE6){top -=self.scrollTop} //$(document).scrollTop()
		}else{
			left = parseInt(left-self.bodyLeft);
		}
		
		dialog.css({left:left, top:top});
		return false;
	},
	
	//设置窗口位置
	setPosition :function(id, pos){
		var dialog = this.list[id], options = this.options[id];
		pos=pos||this.getPosition(id);
		if (typeof pos == 'object'){
			if(!/up|down/i.test(options.effects)){
				dialog.css(pos);
			}
		}
	},
	
	/*
	* 根据option参数返回窗口的合适坐标
	*/
	getPosition : function(id){
		var self=this, left=0, top=0;
		var dialog =self.list[id], options = self.options[id];
		//var winW =$(window).width(), winH =$(window).height(), sL=$(document).scrollLeft(), sT =$(document).scrollTop();
		var winW =self.windowWidth, winH =self.windowHeight, sT=self.scrollTop, sL=self.scrollLeft;
		
		var dH = dialog.outerHeight(), dW = dialog.outerWidth();
		options.position = options.position || 'middle';
		switch(options.position){
			case 'center':
			case 'middle':
			case 'c':
			case 'm':
				left = (winW - dW)/2;
				top  = (winH - dH)/2;
				break;
			case 'rb':
			case 'br':
			case 'rightBottom':
			case 'bottomRight':
				left = winW - dW -4;
				top  = winH - dH -3;
				break;
			case 'rt':
			case 'tr':
			case 'rightTop':
			case 'topRight':
				left = winW - dW -4;
				top  = 1;
				break;
			case 'lt':
			case 'tl':
			case 'leftTop':
			case 'topLeft':
				left = 0;
				top  = 0;
				break;
			case 'ct':
			case 'tc':
			case 'centerTop':
			case 'topCenter':
				left = (winW - dW)/2;
				top  = 0;
				break;
			case 'cb':
			case 'bc':
			case 'centerBottom':
			case 'bottomCenter':
				left = (winW - dW)/2;
				top  = winH - dH;
				break;
			case 'lb':
			case 'bl':
			case 'leftBottom':
			case 'bottomLeft':
				left = 0;
				top  = winH - dH -3;
				break;
			default:
				if (typeof options.position != "object") break;
				var E = $(options.position);
				
				//非IE浏览器可能不能正确获取外部元素的高度，顾需要转为内部的图片
				if (!$.IE && E.find('img').length > 0){
					E = E.find('img');
				}
				
				var offset = E.offset(), eH = E.outerHeight(), eW = E.outerWidth();
				
				var w1, w2, h1, h2;
				//主要思路，根据定位的参考对象，将窗口分为4个区域，左上、左下、右上、右下，判断哪个区域比较适合显示窗口
				w1 = offset.left - sL + eW; //窗口左侧不包括滚动部分，距参考对象右侧的距离，下面类推
				w2 = winW + sL - offset.left; //窗口右侧
				h1 = offset.top - sT + eH; //上，从下面的边算起
				h2 = winH + sT - offset.top; //下，从上面的边算
				
				if (w2 > dW && w1 > dW){//左右都可以
					left = (w2>w1) //选择大的那一边
						? offset.left -3
						: offset.left + eW - dW +3;
				}else if (w2 > dW){//选择右侧
					left = offset.left -3;
				}else if (w1 > dW){//选择左侧
					left = offset.left + eW - dW +3;
				}else {//左右居中
					left = sL + (winW - dW)/2;
				}
				
				//left = (dW < 800) ? left : sL + (winW - dW)/2;
				
				if (h2 > dH){//选择下面
					top = (eH < 50) ? offset.top + eH : offset.top;
				}else if (h1 > dH){//选择上面
					top = (eH < 50) ? offset.top - dH : offset.top -dH + eH;
				}else {//上下居中
					top  = sT + (winH - dH)/2;
				}
				top = top > sT ? top : sT;//当顶部可能被隐藏时，将顶部设置为和scrollTop相等
				top = (winH + sT > offset.top + eH)//判断当前点击的对象是否超出了当前浏览器窗口的最下端
					? top //当前点击的对象在当前浏览器窗口范围之内，顶部位置保持不变
					: winH + sT - dH; //当前点击的对象超出了当前浏览器窗口的最下端，dialog和浏览器窗口的最下端对齐，测试如果图片过高则顶部可能被隐藏
				
		}
		
		if (typeof options.position != "object" && (!options.fixed || $.IE6 )){
			left += sL;
			top  += sT;
		}

		if(!self.bodyLeft){init_size()}
		
		if (!options.fixed && self.bodyPosition == 'relative'){
			left = left - self.bodyLeft;
		}else if(options.fixed && $.IE6){
			left = left - self.bodyLeft;
		}
		
		left +=options.offsetX;
		top +=options.offsetY;
		
		top = top > 0 ? top : 0;
		left = left > 0 ? left : 0;
		return {"left":left, "top":top};
	},
	
	/*
	* 隐藏遮罩层
	*/
	hideOverlay : function(){
		var self=this;
		for(id in self.options){
			var options = self.options[id];
			if (options.overlay && self.isShow(id)){return false}
		}
		$("div.hudong_overlay").hide();
	},
	
	/*
	* 设置遮罩层
	*/
	setOverlay : function(did){
		var self=this, id = 'hudong_overlay'+did, height = $(document).height(), width="100%",left=0;
		var options=self.options[did], overlay = $('div#'+id);
		
		//当body.style.position == 'relative' ，在window.resize()时需要重新setOverlay()
		//if ($(".hudong_overlay:visible").length > 0){return self}
		if (overlay.length > 0){
			overlay.show();
		}else{
			$('body').append('<div class="hudong_overlay" id="'+id+'"></div>');
			overlay = $('div#'+id);
		}
		
		self.windowWidth =$(window).width();
		self.bodyLeft    =$(document).find('body').position().left;
		if(!self.bodyLeft && self.windowWidth > self.bodyWidth){
			self.bodyLeft=(self.windowWidth - self.bodyWidth)/2;
		}
	
		if(!self.bodyLeft){init_size()}
		if (self.bodyPosition == 'relative'){
			width = self.windowWidth;
			left=-self.bodyLeft;
		}
		
		if(options['styleOverlay']){overlay.css(options['styleOverlay'])}
		overlay.css({left:left, width:width, height:height, zIndex:self.config.zIndex});
		return self;
	}
}


/*
 * $.dialog
 * 
 */
$.dialog = function(options){$._dialog.open(options)}
$.extend($.dialog, {
	box:function(id, title, content, position, callback){
		var options={valign:'center', styleOverlay:{backgroundColor:'#FFFFFF'}}
		this.open(id, title, content, position, callback, options);
	},
	get: function(id){
		var dialog=$._dialog.list[id];
		if(dialog){return dialog}else{alert("提示：id为"+id+"的dialog不存在。")}
		
	},
	options:function(id){
		return $._dialog.options[id];
	},
	open:function(id, title, content, position, callback, config){
		var options = {id:id, title:title, position:position, callback:callback, width:350, height:200};
		if(config && typeof config =='object'){
			for(var key in config){
				options[key]=config[key];
			}
		}
		if (content.substr(0,4) == 'url:'){
			options.type = 'url';
			options.url = content.substr(4);
		}else if (content.substr(0,4) == 'img:'){
			options.type = 'img';
			options.url = content.substr(4);
		}else if (content.substr(0,7) == 'iframe:'){
			options.type = 'iframe';
			options.url = content.substr(7);
		}else if (content.substr(0,9) == 'selector:'){
			options.type = 'selector';
			options.content = content.substr(9);
		}else {
			options.content = content;
		}
		$._dialog.open(options);
	},
	tip:function(content, title, autoClose, skin, onOk, id){
		title = title || '提示';
		autoClose = autoClose || 30000;
		skin = skin || $._dialog["config"]["skin"];
		id = id||'jquery_dialog_tip';
		
		height = typeof onOk == "function" ? 180 : 200;
		
		var options = {id:id, title:title, position:'c', skin:skin,
			content:content,
			valign:'center',
			width:320,
			height:height,
			autoClose:autoClose,
			onOk:onOk,
			styleContent:{'verticalAlign':'middle'},
		//	styleBtn:{},
			offsetY:-80,
			resetTime:0,
			resizable:0
		};
		$._dialog.open(options);
	},
	alert:function(content, title, autoClose, skin, id){
		id = id || 'jquery_dialog_alert';
		this.tip(content, title, autoClose, skin, function(){$._dialog.close(id)}, id);
	},
	addSkin: function(strStyle){
		strStyle = strStyle.replace(/{base}/i, $._dialog.config.base);
		$('head').append('<style>'+ strStyle +'</style>');
	},
	close:function(id){
		var options=$._dialog.options[id];
		if(typeof options == "undefined"){
			alert('提示：id='+id+' 的dialog不存在！');
			return;
		}
		if($.isFunction(options.onClose)){options.onClose()}
		$._dialog.close(id);
	},
	resize: function(id){$._dialog.resize(id)},
	show: function(id){$._dialog.show(id)},
	ok:function(id){$._dialog.close(id)},
	exist:function(id){return $._dialog.list[id]},
	setConfig:function(key, value, config){
		if (!key) return this;
		config = config || 'config';
		if (typeof key == 'string' && value !== null) {
			$._dialog[config][key] = value;
		}else if (typeof key == 'object') {
			$.extend($._dialog[config], key);
		}
		return this;
	},
	setDefaults: function(key, value){
		return this.setConfig(key, value, 'defaults');
	},
	content : function(id, content){
		if(content){
			$._dialog.setContent(id, content);
			$._dialog.resize(id);
		}else{
			var dialog=this.get(id);
			if(dialog) return dialog.find('div.content').html();
			else return "";
		}
	},
	remove: function(id){
		var dialog=this.get(id);
		dialog.remove();
		$._dialog.list[id]=null;
		$._dialog.options[id]=null;
	},

	initSize: function(){
		init_size();
	}
});

if (/^http:\/\/(\w+\.){1,2}hudong\.com/i.test(location.href)){
	$.dialog.setConfig({'base':'http://www.huimg.cn/lib/dialog', skin:'bluebox'});
}

})(jQuery);
