/*
 * 互动摸摸  hdmomo v2.1
 * Copyright (c) 2008 互动在线（北京）科技有限公司
 * @Website: http://www.hudong.com
 * @Date: 2009-09-17
 * @Author: wolf, panxuepeng
 */
(function(){
var momo = window.momo = {};
if(!document.body)return alert('请将互动摸摸的js代码放到body区域。');
momo.config = {
	wikiurl: (typeof momourl == 'undefined' || !momourl) ? "http://kaiyuan.hudong.com" : momourl,
	areaid: (typeof momoid == 'undefined' || !momoid) ? '' :momoid,
	keylength: (typeof momolength == 'undefined' || !momolength) ? 16 :momolength,
	selecttype: (typeof momotype == 'undefined' || !momotype) ? "1" :momotype,
	
	setarea: function(){
		momo.config.area = (this.areaid && document.getElementById(this.areaid)) || document;
	},
	body_left:(document.documentElement.clientWidth - document.body.clientWidth)/2,
	
	divstr: '<p style="margin: 0pt; padding: 0pt 0pt 5px; width: 315px; float: left;">' +
	'<span style="float: left; font-size: 12px; margin-left: 5px;">' +
	'<img align="absmiddle" src="http://www.hudong.com/hdmomo/img/logo.png"/>互动摸摸' +
	'</span>' +
	'<span style="float: right; margin-right: 5px">' +
	'<span style="cursor:pointer" onclick="momo.setOff()" title="禁用操作仅当前页面有效">禁用</span> '+
	'<img style="cursor:pointer" border="0" src="http://www.hudong.com/images/momo/guanbi_normal.gif" onclick = "momo.div.undisplay();"/>' +
	'</span>' +
	'</p>' +
	'<div style="padding: 5px; margin: 0pt; padding: 5px; float: left; width: 305px; background-color: #FFFFFF;">' +
	'<iframe width="100%" scrolling="no" height="145" frameborder="0" src="" name="momoFrame" id="momoFrame"/>' +
	'</div><div align="center" style="margin: 0pt; padding: 0pt 0pt 5px; width: 290px; float: left;">' +
	'<span style=" font-size: 12px;">©2003-2008<a target="_blank" href="http://www.hudong.com/"><b><font color="#2ea8ed">hudong.com</font></b></a>互动百科</span>' +
	'</div>',
	
	//是否可以使用
	is_active: 1,
	
	//所有获取过数据的缓存信息，避免重复链接
	cache_list:[],
	
	//显示延迟标识，用于记录setTimeout的返回值
	defer_show:0,
	
	//隐藏延迟标识，用于记录setTimeout的返回值
	defer_hide:0,
	
	//触摸延迟，延迟此时间再触发显示事件
	timeout:500
};

momo.setOn = function(){
	this.config.is_active = 1;
	var el = document.getElementById('disabled_tip');
	if (el){
		el.style.display="none";
	}
}

momo.setOff = function(){
	this.config.is_active = 0;
	this.div.hide();
}

momo.common = {
	selection: function(){
		if (window.getSelection) {
			return window.getSelection().toString();
		}else if (document.getSelection) {
			return document.getSelection();
		}else if (document.selection) {
			return document.selection.createRange().text;
		}else{ 
			return '';
		}
	},
	write: function($str){
		var el = document.createElement('div');
		el.id = 'momo_div';
		el.style.padding = '5px';
		el.style.overflow = 'hidden';
		el.style.width = '315px';
		el.style.zIndex = 8306;
		el.style.backgroundColor = '#C8DAF3';
		el.style.position = 'absolute';
		el.style.fontSize = '12px';
		el.style.display = 'none';
		
		el.innerHTML = $str;
		document.body.appendChild(el);
	}
};

momo.tag = {
	flag : 1,
	init: function(){
		var self=momo, p, $class = "innerlink", tags = document.getElementsByTagName('a');
		for (var i = 0; i < tags.length; i++){
			if (tags[i].className.toLowerCase() == $class){
				tags[i].onmouseover = function(){
					if (!self.config.is_active){return self.div.disabled_tip()}
					var a = this;
					clearTimeout(self.config.defer_show);
					clearTimeout(self.config.defer_hide);
					self.div.hide();
					momo.config.defer_show = setTimeout(function(){
					 	if (momo.tag.flag){
							p = momo.tag.position(a);
							momo.div.display(a.innerHTML);
							momo.div.position('tag', p.left, p.top);
						}
					}, self.config.timeout);
				}
				
				tags[i].onmouseout = function(){
					if (!self.config.is_active){return}
					clearTimeout(self.config.defer_show);
					self.config.defer_hide = setTimeout(function(){
						self.div.hide();
					}, 2000);
				}
			}else{
				tags[i].onmouseover = function(){
					clearTimeout(self.config.defer_show);
					clearTimeout(self.config.defer_hide);
					self.div.hide();
				}

			}
		}
	},
	position: function($tag){
		var vleft = 0, vtop = -3, element = $tag;
		while (element != null && element != document.body.offsetParent) {
			vleft += element.offsetLeft;
			vtop += element.offsetTop;
			if (document.all) {
				parseInt(element.currentStyle.borderLeftWidth) > 0 ? vleft += parseInt(element.currentStyle.borderLeftWidth) : "";
				parseInt(element.currentStyle.borderTopWidth) > 0 ? vtop += parseInt(element.currentStyle.borderTopWidth) : "";
			}
			element = element.offsetParent;
		}
		if (document.all) vleft -= momo.config.body_left;
		return {top:vtop, left:vleft}
	}
}
momo.mouse = {
	flag : 1,
	pos_down:null,
	down: function(ev){
		ev = ev||event;
		momo.tag.flag = 0;
		momo.mouse.pos_down = momo.mouse.position(ev);
	},
	up: function(ev){
		var self = momo;
		if(momo.mouse.flag){
			var pos_up = momo.mouse.position(ev);
			if (Math.abs(pos_up.y - momo.mouse.pos_down.y) > 25){
				return;
			}
			clearTimeout(momo.config.defer_hide);
			var word = momo.common.selection();
			word = word.replace(/^\s*|\s*$/g, "");
			if (!momo.config.is_active){
				if (typeof word == 'string' && word && word.length < momo.config.keylength){
					momo.div.disabled_tip();
				}
				return;
			}
			if (typeof word == 'string' && word && word.length < momo.config.keylength) {
				momo.div.display(word);
				momo.div.position('mouse', ev);
			}else{
				momo.div.hide();
			}
			momo.tag.flag = 1;
		}
	},
	position: function(ev){
		var x, y;
		if (ev.pageX){
			x= ev.pageX;
			y= ev.pageY;
		}else{
			x= ev.clientX + document.body.scrollLeft - document.body.clientLeft,
			y= ev.clientY + document.documentElement.scrollTop - document.body.clientTop
		}
		return {x:x - momo.config.body_left, y:y}
	}
};
momo.div = {
	momodiv : document.getElementById('momo_div'),
	position: function($type, ev){
		var momodiv = document.getElementById('momo_div');
		switch ($type) {
			case 'mouse':
				var p = momo.mouse.position(ev);
				var left = p.x + 5;
				var top = p.y - 10;
				break;
			case 'tag':
				var left = arguments[1];
				var top = arguments[2];
				break;
		}
		var retop = top;
		var releft = left;
		var button_y = momodiv.offsetHeight + top;
		var button_x = momodiv.offsetWidth + left;
		var screenheight = document.documentElement.clientHeight;
		var screenwidth = document.documentElement.clientWidth;
		var scrollHeight = document.documentElement.scrollTop;
		if (button_y >= screenheight + scrollHeight){
			retop = top - momodiv.offsetHeight;
			if (retop < 0){
				retop = top;
			}
		}else{
			retop += 18; 
		}
		
		if (button_x >= screenwidth){
			releft = left - momodiv.offsetWidth;
			if (releft < 0){
				releft = left;
			}
		}
		momodiv.style.left = releft + "px";
		momodiv.style.top = retop + "px";
	},
	display: function($word){
		var momodiv = document.getElementById('momo_div');
		momodiv.style.display = 'block';
		momodiv.onmousedown = momo.div.mousedown;
		
		momodiv.onmouseover = function(){
			clearTimeout(momo.config.defer_hide);
			momo.event.remove(momo.config.area, "mousedown", momo.div.undisplay);
			momo.mouse.flag = 0;
		};
		momodiv.onmouseout = function(){
			momo.config.defer_hide = setTimeout(function(){
				momo.div.hide();
			}, 2000);
			momo.event.add(momo.config.area, "mousedown", momo.div.undisplay);
			momo.mouse.flag = 1;
		};
		
		document.getElementById("momoFrame").src = 'about:blank';
		setTimeout(function(){
			document.getElementById("momoFrame").src = momo.config.wikiurl + '/index.php?plugin-momo-momo-default-' + encodeURI($word);
		}, 200);
	},
	undisplay: function(){
		var div = document.getElementById('momo_div');
		if (div.style.display == "block") {
			div.style.display = "none";
		}
	},
	hide: function(){
		momo.div.undisplay();
	},
	mousedown: function(ev){
		momo.event.stop(ev);
		var element = this;
		e = ev || event;
		var deltaX = e.clientX - parseInt(element.style.left);
		var deltaY = e.clientY - parseInt(element.style.top);
		function startDrag(e){
			e = e || event;
			element.style.left = e.clientX - deltaX + "px";
			element.style.top = e.clientY - deltaY + "px";
		};
		function stopDrag(){
			if (document.removeEventListener) {
				document.removeEventListener("mousemove", startDrag, true);
				document.removeEventListener("mouseup", stopDrag, true);
			}else {
				document.onmousemove = "";
				document.onmouseup = "";
			}
		}
		if (document.addEventListener) {
			document.addEventListener("mousemove", startDrag, true);
			document.addEventListener("mouseup", stopDrag, true);
		}else {
			document.onmousemove = startDrag;
			document.onmouseup = stopDrag;
		}
		
	},
	disabled_tip: function(){
		var el = document.getElementById('disabled_tip');
		if (!el){
			el = document.createElement('div');
			el.id = 'disabled_tip';
			el.style.padding = '5px';
			el.style.top = '5px';
			el.style.left = '5px';
			el.style.height = '20px';
			el.style.lineHeight = '20px';
			el.style.width = '150px';
			el.style.textAlign = 'center';
			el.style.zIndex = 8306;
			el.style.backgroundColor = '#C8DAF3';
			el.style.position = 'absolute';
			el.style.fontSize = '12px';			
			el.innerHTML = '互动默默已关闭，<a href="javascript:;" onclick="momo.setOn()"><b>开启</b></a>';
			document.body.appendChild(el);
		}
		el.style.top = (10+document.documentElement.scrollTop)+'px';
		el.style.display = 'block';
		clearTimeout(momo.config.defer_hide);
		momo.config.defer_hide=setTimeout(function(){
			el.style.display = 'none';
		},2000);
	}
}
momo.event = {
	init: function(){
		this.add(momo.config.area, "mouseup", momo.mouse.up);
		this.add(momo.config.area, "mousedown", momo.mouse.down);
	},
	add: function($el, $ev, $func){
		if (document.all) {
			$el.attachEvent("on" + $ev, $func);
		}else {
			$el.addEventListener($ev, $func, true);
		}
	},
	remove: function($el, $ev, $func){
		if (document.all) {
			$el.detachEvent("on" + $ev, $func);
		}else {
			$el.removeEventListener($ev, $func, true);
		}
	},
	stop: function(ev){
		var evt = (document.all) ? window.event : ev;
		if (document.all) {
			evt.cancelBubble = true;
			evt.returnValue = false;
		}else {
			evt.preventDefault();
			evt.stopPropagation();
		}
	}
};

window.onload = function(){
	momo.common.write(momo.config.divstr);
	momo.config.setarea();
	switch(momo.config.selecttype){
		case '0':
			momo.event.init();
			momo.tag.init();
			break;
		case '1':
			momo.tag.init();
			break;
		case '2':
			momo.event.init();
			break;
	}
}
})();