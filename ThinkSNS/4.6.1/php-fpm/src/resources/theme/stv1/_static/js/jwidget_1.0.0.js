
/**
 * @fileoverview jWidget:a mini javascript widget library
 * @version 1.0
 * @author jessezhang <jianguang.qq@gmail.com>
 * Released under the MIT Licenses. 
 * More information: http://code.google.com/p/j-widget/
 */
;
(function(){
	jWidget = {
		version : '1.0.0',
		each : function(obj, fn){
			var i = 0, k, _fn = fn;	
			if (Object.prototype.toString.call(obj) === "[object Array]") {
				if (!!obj.forEach) {
					obj.forEach(fn);
				} else {
					var len = obj.length
					while (i < len) {
						_fn(obj[i], i, obj);
						++i;
					}
				}
			} else {
				for (k in obj) {
					_fn(obj[k], k, obj);
				}
			}
			return true;
		},
		extend : function(obj, ext) {
			if(obj && ext && typeof ext == 'object'){
				this.each(ext, function(v, k) {
					obj[k] = v;
				});
			}
		}
	};
	
	var _isW3cMode = document.defaultView && document.defaultView.getComputedStyle;
	
	jWidget.dom = {	
		get : function(e){
			if(typeof e == "string")
				return document.getElementById(e);
			return e;
		},
		
		/**
		 * 得到第一个子节点（element），firefox会得到到文本节点等。这里统一得到element。
		 * 
		 * @param {HTMLElement} node 对象
		 *            @example
		 *            var element=QZFL.dom.getFirstChild(QZFL.dom.get("el_id"));
		 * @return HTMLElement
		 */
		getFirstChild : function(el) {
			el = this.get(el);
			var child = !!el.firstChild && el.firstChild.nodeType == 1 ? el.firstChild : null;
			return child || this.getNextSibling(el.firstChild);
		},
		
		/**
		 * 得到下一个兄弟节点（element），firefox会得到到文本节点等。这里统一得到element。
		 * 
		 * @param {HTMLElement} el 对象
		 *            @example
		 *            QZFL.dom.getNextSibling(QZFL.dom.get("el_id"));
		 * @return HTMLElement
		 */
		getNextSibling : function(el) {
			el = this.get(el);
			while (el) {
				el = el.nextSibling;
				if (!!el && el.nodeType == 1) {
					return el;
				}
			}
			return null;
		},
		
	
		
		getChildren : function(el) {
			var _arr = [];
			var el = this.getFirstChild(el);
			while (el) {
				if (!!el && el.nodeType == 1) {
					_arr.push(el);
				}
				el = el.nextSibling;
			}	
			return _arr;
		},
		
		/**
		 * 获取对象尺寸
		 * 
		 * @param {HTMLElement} el
		 * @return Array [width,height]
		 * @type Array
		 *       @example
		 *       var size=QZFL.dom.getSize(QZFL.dom.get("div_id"));
		 * @return Array
		 */
		getSize : function(el) {
			var _fix = [0,0];
			if (el) {
				//修正 border 和 padding 对 getSize的影响
				jWidget.each(["Left", "Right", "Top", "Bottom"], function(v){ 
					_fix[v == "Left" || v == "Right" ? 0 : 1] += (parseInt(jWidget.dom.getStyle(el, "border" + v + "Width"), 10) || 0) + (parseInt(jWidget.dom.getStyle(el, "padding" + v), 10) || 0);
				});
				return [el.offsetWidth - _fix[0], el.offsetHeight - _fix[1]];
			}
			return [-1, -1];
		},
		
		/**
		 * 获取对象渲染后的样式规则
		 * 
		 * @param {String|HTMLElement} el 对象id或则dom
		 * @param {String} property 样式规则
		 *            @example
		 *            var width=QZFL.dom.getStyle("div_id","width");//width=163px;
		 * @return 样式值
		 */
		getStyle : function(el, property) {
			el = this.get(el);
	
			if (!el || el.nodeType == 9) {
				return null;
			}
			
			var computed = !_isW3cMode ? null : document.defaultView.getComputedStyle(el, '');
			var value = "";
			switch (property) {
				case "float" :
					property = _isW3cMode ? "cssFloat" : "styleFloat";
					break;
				case "opacity" :
					if (!_isW3cMode) { // IE Mode
						var val = 100;
						try {
							val = el.filters['DXImageTransform.Microsoft.Alpha'].opacity;
						} catch (e) {
							try {
								val = el.filters('alpha').opacity;
							} catch (e) {}
						}
						return val / 100;
					}else{
						return parseFloat((computed || el.style)[property]);
					}
					break;
			}
	
			if (_isW3cMode) {
				return (computed || el.style)[property];
			} else {
				return (el.currentStyle[property] || el.style[property]);
			}
		},
		
		/**
		 * 设置样式规则
		 * 
		 * @param {String|HTMLElement} el 对象id或则dom
		 * @param {String} property 样式规则
		 *            @example
		 *            QZFL.dom.setStyle("div_id","width","200px");
		 * @return 成功返回 true
		 */
		setStyle : function(el, property, value) {
			el = this.get(el);
			if (!el || el.nodeType == 9) return false;
			switch (property) {
				case "float" :
					property = _isW3cMode ? "cssFloat" : "styleFloat";
				case "opacity" :
					if (!_isW3cMode) { // for ie only
						if (value >= 1) {
							el.style.filter = "";
							return;
						}
						el.style.filter = 'alpha(opacity=' + (value * 100) + ')';
						return true;
					} else {
						el.style[property] = value;
						return true;
					}
					break;
				default :
					if (typeof el.style[property] == "undefined")return false;
					el.style[property] = value;
					return true;
			}
		},
		
		/**
		 * 是否有指定的样式类名称
		 * @param {Object} el 指定的HTML元素
		 * @param {String} cname 指定的类名称
		 * @example QZFL.css.hasClass($("div_id"),"cname");
		 * @return Boolean
		 */
		hasClass : function(el, cname) {
			return (el && cname) ? new RegExp('\\b' + cname + '\\b').test(el.className) : false;
		},
		
		/**
		 * 增加一个样式类名
		 * @param {Object} el 指定的HTML元素
		 * @param {Object} cname 指定的类名称
		 * @example QZFL.css.addClass($("ele"),"cname");
		 * @return Boolean
		 */
		addClass : function(el, cname) {
			if (el && cname) {
				if (el.className) {
					if (jWidget.dom.hasClass(el, cname)) {
						return false;
					} else {
						el.className += ' ' + cname;
					}
				} else {
					el.className = cname;
				}
				return true;
			}
			return false;
		},
	
		/**
		 * 除去一个样式类名
		 * @param {Object} el 指定的HTML元素
		 * @param {String} cname 指定的类名称
		 * @example QZFL.css.removeClass($("ele"),"cname");
		 * @return Boolean
		 */
		removeClass : function(el, cname) {
			if (el && cname && el.className) {
				var old = el.className;
				el.className = (el.className.replace(new RegExp('\\b' + cname + '\\b'), ''));
				return el.className != old;
			} else {
				return false;
			}
		}
	}	 
        
    jWidget.ui = jWidget.ui || {};
})();

/**
 * @fileoverview jWidget:a mini javascript widget library
 * @version 1.0
 * @author jessezhang <jianguang.qq@gmail.com>
 * Released under the MIT Licenses. 
 * More information: http://code.google.com/p/j-widget/
 */
;
(function(){	
	var $ = jWidget,
		$D = $.dom;	
	
	/**
	 * Slide轮播效果
	 * @param {json} 配置参数
	 *		@param {String|HTMLElement} container 包括id号，或则Html Element对象，Slider容
	 *		@param eventType         'mouseover' or 'click'，默认'mouseover'
	 *		@param autoPlay          是否自动播放,默认自动播放
	 *		@param autoPlayInterval  自动播放间隔时间，默认3秒
	 *		@param effect            播放效果 'none','scrollx', 'scrolly', 'fade'
	 *		@param panelWrapper     Slide内容item的容器，默认为Slider容器的firstChild
	 *		@param navWrapper        Slide导航的容器，默认为Slider容器的secondChild
	 *		@param navClassOn        navs鼠标移上后的样式，默认为'on'
	 *		@param slideTime         滑动时延
	 *		@param width             宽度（srcollx）,如样式中已有，会自动获取，一般无需填写
	 *		@param height            高度（scrolly）,如样式中已有，会自动获取，一般无需填写
	 */
	_Slide = function(conf) {
		conf = conf || {};	
		
		this.eventType = conf.eventType || 'mouseover' , 
		this.autoPlayInterval = conf.autoPlayInterval || 3 * 1000;
	
		this._play = true; 
		this._timer = null;	
		this._fadeTimer = null;
		this._container = $D.get(conf.container);
		this._panelWrapper = $D.get(conf.panelWrapper) || $D.getFirstChild(this._container);
		this._sliders = $D.getChildren(this._panelWrapper);
		this._navWrapper = $D.get(conf.navWrapper) || $D.getNextSibling(this._panelWrapper) || null;
		this._navs = (this._navWrapper && $D.getChildren(this._navWrapper)) || null;
		this._effect = conf.effect || 'scrollx';

		this._panelSize = (this._effect.indexOf("scrolly") == -1 ?  conf.width : conf.height) || $D.getSize($D.getFirstChild(this._panelWrapper))[this._effect.indexOf("scrolly") == -1 ? 0 : 1 ];

		
		//this._panelSize = (this._effect.indexOf("scrolly") == -1 ?  conf.width : conf.height) || $D.getSize(this._container)[this._effect.indexOf("scrolly") == -1 ? 0 : 1 ]; 
		this._count = conf.count || $D.getChildren(this._panelWrapper).length;
		this._navClassOn = conf.navClassOn || "on"; 	
		this._target = 0;	
		this._changeProperty  = this._effect.indexOf("scrolly") == -1 ? "left" : "top" ;	
		
		this.curIndex = 0;
		this.step = this._effect.indexOf("scroll") == -1 ? 1 : (conf.Step || 5);
		this.slideTime = conf.slideTime || 10;

		this.init();

		this.run(true);
	} 
	_Slide.prototype = {  
		init : function(){	
			$D.setStyle(this._container, "overflow", "hidden");
			$D.setStyle(this._container, "position", "relative");
			$D.setStyle(this._panelWrapper, "position", "relative");
			var _this = this;
			var tempData = [$D.getStyle(this._container,"width"),$D.getStyle(this._container,"height")];  //容器的宽度以及高度
			//alert($D.getStyle(this._container,"width"));
			if(this._effect.indexOf("scrolly") == -1){ 
				this._panelSize = this._container.offsetWidth;
				$D.setStyle(this._panelWrapper, "width", this._count * (this._panelSize+200) + "px");
				$.each(this._sliders,function(el){			
					el.style.styleFloat = el.style.cssFloat = "left";
					$D.getFirstChild($D.getFirstChild(el)).width = _this._panelSize;
				})
			}else{
				this._panelSize = this._container.offsetHeight;
				$.each(this._sliders,function(el){			
					$D.getFirstChild($D.getFirstChild(el)).width = _this._container.offsetWidth;
					$D.getFirstChild($D.getFirstChild(el)).height = _this._container.offsetHeight;
				})
			}
			
			
			if(this._navs){
    			if(_this.eventType == 'click'){  //onclick
    				$.each(this._navs, function(el, i){
    					el.onclick = (function(_this){return function(){
    						$D.addClass(el, _this._navClassOn);
    						_this._play = false;
    						_this.curIndex = i;
    						_this._play = true;
    						_this.run();
    					}})(_this)
    				})	
    			} else {  //onmouseover
    				$.each(this._navs, function(el, i){
    					el.onmouseover = (function(_this){return function(){
    						$D.addClass(el, _this._navClassOn);
    						_this._play = false;
    						_this.curIndex = i;
    						_this.run();
    					}})(_this)
    					el.onmouseout = (function(_this){return function(){
    						$D.removeClass(el, _this._navClassOn);
    						_this._play = true;
    						_this.run();
    					}})(_this)
    				})	
    			}	
			}
		},  
		
		run : function(isInit) {
			if(this.curIndex < 0){
				this.curIndex = this._count - 1;
			} else if (this.curIndex >= this._count){
				this.curIndex = 0; 
			}	
			var _this = this;
		
			this._target = -1 * this._panelSize * this.curIndex;


			if(this._navs){
    			$.each(this._navs, function(el, i){
    				_this.curIndex == (i) ? $D.addClass(el, _this._navClassOn) : $D.removeClass(el, _this._navClassOn);
    			})	
			}
			this.scroll();
			
			if(this._effect.indexOf("fade") >= 0){
				$D.setStyle(this._panelWrapper, "opacity", isInit ? 0.5 : 0.1);
				this.fade();
			}
		},
		
		scroll : function() {
			clearTimeout(this._timer);
			var _this = this, 
				_cur_property = parseInt(this._panelWrapper.style[this._changeProperty]) || 0, 
				_distance = (this._target - _cur_property) / this.step;
				//				alert(this._changeProperty);
				//alert(this._target);
				//alert(_cur_property);

			if (Math.abs(_distance) < 1 && _distance != 0) {
				_distance = _distance > 0 ? 1 : -1;
			}				
			if (_distance != 0) {
				this._panelWrapper.style[this._changeProperty] = (_cur_property + _distance) + "px";
				this._timer = setTimeout(function(){_this.scroll();}, this.slideTime);
			} else {
				this._panelWrapper.style[this._changeProperty] = this._target + "px";
				if (this._play) { 
					this._timer = setTimeout(function(){_this.curIndex++; _this.run();}, this.autoPlayInterval); 
				}
			}
		},
		
		fade : function() {
			var _opacity = $D.getStyle(this._panelWrapper, "opacity");
			var _this = this;
			if(_opacity < 1){
				$D.setStyle(this._panelWrapper, "opacity", parseFloat(_opacity) + 0.02);
				setTimeout(function(){_this.fade();}, 1);
			}
		}
	}
	
	jWidget.ui.SlideView = function(el, conf) {
		conf = conf || {};
		conf.container = el;
		return new _Slide(conf);	
	}
})();

/**
 * @fileoverview jWidget:a mini javascript widget library
 * @version 1.0
 * @author jessezhang <jianguang.qq@gmail.com>
 * Released under the MIT Licenses. 
 * More information: http://code.google.com/p/j-widget/
 */
;
(function(){	
	var $ = jWidget,
		$D = $.dom;	
	
	/**
	 * Tab切换效果
	 * @param {json} 配置参数
	 *		@param {String|HTMLElement} container 包括id号，或则Html Element对象，Slider容
	 *		@param eventType         'mouseover' or 'click'，默认'mouseover'
	 *		@param type           
	 *		@param panelWrapper      Slide内容item的容器，默认为Slider容器的firstChild
	 *		@param navWrapper        Slide导航的容器，默认为Slider容器的secondChild
	 *		@param navClassOn        navs鼠标移上后的样式，默认为'on'
	 */
	_Tab = function(conf) {		
		this.eventType = conf.eventType || 'mouseover', 
		this._container = conf.container;		 
        this._type = conf.type || "normal";         
        this._navClassOn = conf.navClassOn || "on"; 
        var _this = this;
        if(this._type == "list"){		
        	var cons = $D.getChildren(this._container);
        	this._panels = [];
            this._navs = [];    
            $.each(cons, function(el, i){
                if(i%2){
                    _this._panels.push(el);
                } else {
                    _this._navs.push(el);
                } 
            }) 
        }else{
            this._navWrapper = $D.get(conf.navWrapper) || $D.getFirstChild(this._container);
            this._navs = $D.getChildren(this._navWrapper);   
            this._panelWrapper = $D.get(conf.panelWrapper) || $D.getNextSibling(this._navWrapper);
            this._panels = $D.getChildren(this._panelWrapper);           
        }
                     
        this.curIndex = 0;  	
        $.each(this._panels, function(el, i){
            if(el.style.display != "none"){
                _this.curIndex = i;
            } 
        })

		this._panels[this.curIndex].style.display = '';
		this._panels[this.curIndex].style.display = '';
		$D.removeClass(this._navs[this.curIndex], this._navClassOn);
		$D.addClass(this._navs[this.curIndex], this._navClassOn);

		$.each(this._navs, function(el, i){
            el['on'+_this.eventType] = (function(_this){return function(){                  
                $D.removeClass(_this._navs[_this.curIndex], _this._navClassOn);
                _this._panels[_this.curIndex].style.display = 'none';
                _this.curIndex = i;                    
                $D.addClass(el, _this._navClassOn);
                _this._panels[_this.curIndex].style.display = '';
                try{QZFL.lazyLoad.loadHideImg(_this._panels[_this.curIndex])}catch(e){}//loadLoad隐藏的图片
            }})(_this)
        })
	}
	
	jWidget.ui.TabView = function(el, conf){
		conf = conf || {};
		conf.container = $D.get(el);
		return new _Tab(conf);
	}
})();