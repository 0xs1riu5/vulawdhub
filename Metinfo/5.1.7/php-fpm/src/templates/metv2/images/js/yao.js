var YAO = function(){
	var D = document, OA = '[object Array]', FC = "[object Function]", OP = Object.prototype, nt = "nodeType", listeners = [], webkitKeymap = {
		63232: 38, // up
		63233: 40, // down
		63234: 37, // left
		63235: 39, // right
		63276: 33, // page up
		63277: 34, // page down
		25: 9 // SHIFT-TAB (Safari provides a different key code in
	}, patterns = {
		HYPHEN: /(-[a-z])/i,
		ROOT_TAG: /body|html/i
	}, lastError = null;
	
	return {
		isArray: function(obj){
			return OP.toString.apply(obj) === OA;
		},
		isString: function(s){
			return typeof s === 'string';
		},
		isBoolean: function(b){
			return typeof b === 'boolean';
		},
		isFunction: function(func){
			return OP.toString.apply(func) === FC;
		},
		isNull: function(obj){
			return obj === null;
		},
		isNumber: function(num){
			return typeof num === 'number' && isFinite(num);
		},
		isObject: function(str){
			return (str && (typeof str === "object" || this.isFunction(str))) || false;
		},
		isUndefined: function(obj){
			return typeof obj === 'undefined';
		},
		hasOwnProperty: function(obj, prper){
			if (OP.hasOwnProperty) {
				return obj.hasOwnProperty(prper);
			}
			return !this.isUndefined(obj[prper]) && obj.constructor.prototype[prper] !== obj[prper];
		},
		isMobile: function(mobile){
			return /^(13|15|18)\d{9}$/.test(YAO.trim(mobile));
		},
		isName: function(name){
			return /^[\w\u4e00-\u9fa5]{1}[\w\u4e00-\u9fa5 \.]{0,19}$/.test(YAO.trim(name));
		},
		
        keys: function(obj){
            var b = [];
            for (var p in obj) {
                b.push(p);
            }
            return b;
        },
        values: function(obj){
            var a = [];
            for (var p in obj) {
                a.push(obj[p]);
            }
            return a;
        },
        isXMLDoc: function(obj){
            return obj.documentElement && !obj.body || obj.tagName && obj.ownerDocument && !obj.ownerDocument.body;
        },
        formatNumber: function(b, e){
            e = e || '';
            b += '';
            var d = b.split('.');
            var a = d[0];
            var c = d.length > 1 ? '.' + d[1] : '';
            var f = /(\d+)(\d{3})/;
            while (f.test(a)) {
                a = a.replace(f, '$1,$2');
            }
            return e + a + c;
        },
        unformatNumber: function(a){
            return a.replace(/([^0-9\.\-])/g, '') * 1;
        },
        stringBuffer: function(){
            var a = [];
            for (var i = 0; i < arguments.length; ++i) {
                a.push(arguments[i]);
            }
            return a.join('');
        },
        trim: function(str){
            try {
                return str.replace(/^\s+|\s+$/g, '');
            } 
            catch (a) {
                return str;
            }
        },
        stripTags: function(str){
            return str.replace(/<\/?[^>]+>/gi, '');
        },
        stripScripts: function(str){
            return str.replace(/<script[^>]*>([\\S\\s]*?)<\/script>/g, '');
        },
        isJSON: function(obj){
            obj = obj.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"/g, '');
            return (/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(obj);
        },
        encodeHTML: function(str){
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        },
        decodeHTML: function(str){
            return str.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
        },
		toCamel: function(property){
			if (!patterns.HYPHEN.test(property)) {
				return property;
			}
			if (propertyCache[property]) {
				return propertyCache[property];
			}
			var converted = property;
			while (patterns.HYPHEN.exec(converted)) {
				converted = converted.replace(RegExp.$1, RegExp.$1.substr(1).toUpperCase());
			}
			propertyCache[property] = converted;
			return converted;
		},
		 
        Cookie: {
            set: function(g, c, f, b){
                var e = new Date();
                var a = new Date();
                if (f == null || f == 0) {
                    f = 1;
                }
                a.setTime(e.getTime() + 3600000 * 24 * f);
                D.cookie = g + '=' + encodeURI(c) + ';expires=' + a.toGMTString() + ';domain=' + b + '; path=/';
            },
            get: function(e){
                var b = D.cookie;
                var d = e + '=';
                var c = b.indexOf('; ' + d);
                if (c == -1) {
                    c = b.indexOf(d);
                    if (c != 0) {
                        return null;
                    }
                }
                else {
                    c += 2;
                }
                var a = D.cookie.indexOf(';', c);
                if (a == -1) {
                    a = b.length;
                }
                return decodeURI(b.substring(c + d.length, a));
            },
            clear: function(b, a){
                if (this.get(b)) {
                    D.cookie = b + '=' + ((domain) ? '; domain=' + a : '') + '; expires=Thu, 01-Jan-70 00:00:01 GMT';
                }
            }
        },
		
		ua: function(){
			var C = {
				ie: 0,
				opera: 0,
				gecko: 0,
				webkit: 0,
				mobile: null,
				air: 0,
				caja: 0
			}, B = navigator.userAgent, A;
			if ((/KHTML/).test(B)) {
				C.webkit = 1;
			}
			A = B.match(/AppleWebKit\/([^\s]*)/);
			if (A && A[1]) {
				C.webkit = parseFloat(A[1]);
				if (/ Mobile\//.test(B)) {
					C.mobile = 'Apple';
				}
				else {
					A = B.match(/NokiaN[^\/]*/);
					if (A) {
						C.mobile = A[0];
					}
				}
				A = B.match(/AdobeAIR\/([^\s]*)/);
				if (A) {
					C.air = A[0];
				}
			}
			if (!C.webkit) {
				A = B.match(/Opera[\s\/]([^\s]*)/);
				if (A && A[1]) {
					C.opera = parseFloat(A[1]);
					A = B.match(/Opera Mini[^;]*/);
					if (A) {
						C.mobile = A[0];
					}
				}
				else {
					A = B.match(/MSIE\s([^;]*)/);
					if (A && A[1]) {
						C.ie = parseFloat(A[1]);
					}
					else {
						A = B.match(/Gecko\/([^\s]*)/);
						if (A) {
							C.gecko = 1;
							A = B.match(/rv:([^\s\)]*)/);
							if (A && A[1]) {
								C.gecko = parseFloat(A[1]);
							}
						}
					}
				}
			}
			A = B.match(/Caja\/([^\s]*)/);
			if (A && A[1]) {
				C.caja = parseFloat(A[1]);
			}
			return C;
		}(),
		
        extend: function(subClass, superClass, override){
            if (!superClass || !subClass) {
                throw new Error('extend failed, please check that all dependencies are included.');
            }
            var F = function(){};
            F.prototype = superClass.prototype;
            subClass.prototype = new F();
            subClass.prototype.constructor = subClass;
            subClass.superclass = superClass.prototype;
            if (superClass.prototype.constructor == Object.prototype.constructor) {
                superClass.prototype.constructor = superClass;
            }
            if (override) {
                for (var p in override) {
                    subClass.prototype[p] = override[p];
                }
            }
        },
        augmentProto: function(sub, sup){
            if (!sub || !sup) {
                throw new Error('augment failed, please check that all dependencies are included.');
            }
            var d = sub.prototype, g = sup.prototype, b = arguments, c, h;
            if (b[2]) {
                for (c = 2; c < b.length; c += 1) {
                    d[b[c]] = g[b[c]];
                }
            }
            else {
                for (h in g) {
                    if (!d[h]) {
                        d[h] = g[h];
                    }
                }
            }
        },
        augmentObject: function(e, d){
            if (!d || !e) {
                throw new Error('augment failed, please check that all dependencies are included.');
            }
            var b = arguments, c, f;
            if (b[2]) {
                if (YAO.isString(b[2])) {
                    e[b[2]] = d[b[2]];
                }
                else {
                    for (c = 0; c < b[2].length; c += 1) {
                        e[b[2][c]] = d[b[2][c]];
                    }
                }
            }
            else {
                for (f in d) {
                    e[f] = d[f];
                }
            }
            return e;
        },
        clone: function(d, f){
            var e = function(){
            }, b, c = arguments;
            e.prototype = d;
            b = new e;
            if (f) {
                for (p in f) {
                    b[p] = f[p];
                }
            }
            return b;
        },
		
		addListener: function(el, sType, fn, obj, overrideContext, bCapture){
			var oEl = null, context = null, wrappedFn = null;
			if(YAO.isString(el)){
				oEl = YAO.getEl(el);
				el = oEl;
			}
			if(!el || !fn || !fn.call){
				return false;
			}
			context = el;
			if (overrideContext) {
				if (overrideContext === true) {
					context = obj;
				}
				else {
					context = overrideContext;
				}
			}
			wrappedFn = function(e){
				return fn.call(context, YAO.getEvent(e, el), obj);
			};
			try {
				try {
					el.addEventListener(sType, wrappedFn, bCapture);
				} 
				catch (e) {
					try {
						el.attachEvent('on' + sType, wrappedFn);
					} 
					catch (e) {
						el['on' + sType] = wrappedFn;
					}
				}
			} 
			catch (e) {
				lastError = e;
				this.removeListener(el, sType, wrappedFn, bCapture);
				return false;
			}
			if ('unload' != sType) {
				// cache the listener so we can try to automatically unload
				listeners[listeners.length] = [el, sType, fn, wrappedFn, bCapture];
			}
			return true;
		},
        removeListener: function(el, sType, fn, bCapture){
			try {
				if (window.removeEventListener) {
					return function(el, sType, fn, bCapture){
						el.removeEventListener(sType, fn, (bCapture));
					};
				}
				else {
					if (window.detachEvent) {
						return function(el, sType, fn){
							el.detachEvent("on" + sType, fn);
						};
					}
					else {
						return function(){
						};
					}
				}
			} 
			catch (e) {
				lastError = e;
				return false;
			}
			
			return true;
		},
		on: function(el, sType, fn, obj, overrideContext){
			var oEl = obj || el, scope = overrideContext || this;
			return YAO.addListener(el, sType, fn, oEl, scope, false);
		},
		stopEvent: function(evt){
			this.stopPropagation(evt);
			this.preventDefault(evt);
		},
		stopPropagation: function(evt){
			if (evt.stopPropagation) {
				evt.stopPropagation();
			}
			else {
				evt.cancelBubble = true;
			}
		},
		preventDefault: function(evt){
			if (evt.preventDefault) {
				evt.preventDefault();
			}
			else {
				evt.returnValue = false;
			}
		},
		getEvent: function(e, boundEl){
			var ev = e || window.event;
			
			if (!ev) {
				var c = this.getEvent.caller;
				while (c) {
					ev = c.arguments[0];
					if (ev && Event == ev.constructor) {
						break;
					}
					c = c.caller;
				}
			}
			
			return ev;
		},
		getCharCode: function(ev){
			var code = ev.keyCode || ev.charCode || 0;
			
			// webkit key normalization
			if (YAO.ua.webkit && (code in webkitKeymap)) {
				code = webkitKeymap[code];
			}
			return code;
		},
		_unload: function(e){
			var j, l;
			if (listeners) {
				for (j = listeners.length - 1; j > -1; j--) {
					l = listeners[j];
					if (l) {
						YAO.removeListener(l[0], l[1], l[3], l[4]);
					}
				}
				l = null;
			}
			
			YAO.removeListener(window, "unload", YAO._unload);
		},
		
		getEl: function(elem){
			var elemID, E, m, i, k, length, len;
			if (elem) {
				if (elem[nt] || elem.item) {
					return elem;
				}
				if (YAO.isString(elem)) {
					elemID = elem;
					elem = D.getElementById(elem);
					if (elem && elem.id === elemID) {
						return elem;
					}
					else {
						if (elem && elem.all) {
							elem = null;
							E = D.all[elemID];
							for (i = 0, len = E.length; i < len; i += 1) {
								if (E[i].id === elemID) {
									return E[i];
								}
							}
						}
					}
					return elem;
				}
				else {
					if (elem.DOM_EVENTS) {
						elem = elem.get("element");
					}
					else {
						if (YAO.isArray(elem)) {
							m = [];
							for (k = 0, length = elem.length; k < length; k += 1) {
								m[m.length] = YAO.getEl(elem[k]);
							}
							return m;
						}
					}
				}
			}
			return null;
		},
		hasClass: function(elem, className){
			var has = new RegExp("(?:^|\\s+)" + className + "(?:\\s+|$)");
			return has.test(elem.className);
		},
		addClass: function(elem, className){
			if (YAO.hasClass(elem, className)) {
				return;
			}
			elem.className = [elem.className, className].join(" ");
		},
		removeClass: function(elem, className){
			var replace = new RegExp("(?:^|\\s+)" + className + "(?:\\s+|$)", "g");
			if (!YAO.hasClass(elem, className)) {
				return;
			}
			var o = elem.className;
			elem.className = o.replace(replace, " ");
			if (YAO.hasClass(elem, className)) {
				YAO.removeClass(elem, className);
			}
		},
		replaceClass: function(elem, newClass, oldClass){
			if (newClass === oldClass) {
				return false;
			}
			var has = new RegExp("(?:^|\\s+)" + newClass + "(?:\\s+|$)", "g");
			if (!YAO.hasClass(elem, newClass)) {
				YAO.addClass(elem, oldClass);
				return;
			}
			elem.className = elem.className.replace(has, " " + oldClass + " ");
			if (YAO.hasClass(elem, newClass)) {
				YAO.replaceClass(elem, newClass, oldClass);
			}
		},
		getElByClassName: function(className, tag, rootTag){
			var elems = [], i, tempCnt = YAO.getEl(rootTag).getElementsByTagName(tag), len = tempCnt.length;
			for (i = 0; i < len; ++i) {
				if (YAO.hasClass(tempCnt[i], className)) {
					elems.push(tempCnt[i]);
				}
			}
			if (elems.length < 1) {
				return false;
			}
			else {
				return elems;
			}
		},
		getStyle: function(el, property){
			if (document.defaultView && document.defaultView.getComputedStyle) {
				var value = null;
				if (property == 'float') {
					property = 'cssFloat';
				}
				var computed = document.defaultView.getComputedStyle(el, '');
				if (computed) {
					value = computed[YAO.toCamel(property)];
				}
				return el.style[property] || value;
			}
			else {
				if (document.documentElement.currentStyle && YAO.ua.ie) {
					switch (YAO.toCamel(property)) {
						case 'opacity':
							var val = 100;
							try {
								val = el.filters['DXImageTransform.Microsoft.Alpha'].opacity;
							} 
							catch (e) {
								try {
									val = el.filters('alpha').opacity;
								} 
								catch (e) {
								}
							}
							return val / 100;
							break;
						case 'float':
							property = 'styleFloat';
						default:
							var value = el.currentStyle ? el.currentStyle[property] : null;
							return (el.style[property] || value);
					}
				}
				else {
					return el.style[property];
				}
			}
		},
		setStyle: function(el, property, val){
			if (YAO.ua.ie) {
				switch (property) {
					case 'opacity':
						if (YAO.isString(el.style.filter)) {
							el.style.filter = 'alpha(opacity=' + val * 100 + ')';
							if (!el.currentStyle || !el.currentStyle.hasLayout) {
								el.style.zoom = 1;
							}
						}
						break;
					case 'float':
						property = 'styleFloat';
					default:
						el.style[property] = val;
				}
			}
			else {
				if (property == 'float') {
					property = 'cssFloat';
				}
				el.style[property] = val;
			}
		},
		setStyles: function(el, propertys){
			for(var p in propertys){
				YAO.setStyle(el,p,propertys[p]);
			}
			return el;
		},
        getElementsBy: function(method, tag, root){
            tag = tag || "*";
            var m = [];
            if (root) {
                root = YAO.getEl(root);
                if (!root) {
                    return m;
                }
            }
            else {
                root = document;
            }
            var oElem = root.getElementsByTagName(tag);
            if (!oElem.length && (tag === "*" && root.all)) {
                oElem = root.all;
            }
            for (var n = 0, j = oElem.length; n < j; ++n) {
                if (method(oElem[n])) {
                    m[m.length] = oElem[n];
                }
            }
            return m;
        },
        getDocumentWidth: function(){
            var k = YAO.getScrollWidth();
            var j = Math.max(k, YAO.getViewportWidth());
            return j;
        },
        getDocumentHeight: function(){
            var k = YAO.getScrollHeight();
            var j = Math.max(k, YAO.getViewportHeight());
            return j;
        },
        getScrollWidth: function(){
            var j = (D.compatMode == "CSS1Compat") ? D.body.scrollWidth : D.Element.scrollWidth;
            return j;
        },
        getScrollHeight: function(){
            var j = (D.compatMode == "CSS1Compat") ? D.body.scrollHeight : D.documentElement.scrollHeight;
            return j;
        },
        getXScroll: function(){
            var j = self.pageXOffset || D.documentElement.scrollLeft || D.body.scrollLeft;
            return j;
        },
        getYScroll: function(){
            var j = self.pageYOffset || D.documentElement.scrollTop || D.body.scrollTop;
            return j;
        },
        getViewportWidth: function(){
            var j = self.innerWidth;
            var k = D.compatMode;
            if (k || c) {
                j = (k == "CSS1Compat") ? D.documentElement.clientWidth : D.body.clientWidth;
            }
            return j;
        },
        getViewportHeight: function(){
            var j = self.innerHeight;
            var k = D.compatMode;
            if ((k || c) && !a) {
                j = (k == "CSS1Compat") ? D.documentElement.clientHeight : D.body.clientHeight;
            }
            return j;
        },
        removeChildren: function(j){
            if (!(prent = YAO.getEl(j))) {
                return false;
            }
            while (j.firstChild) {
                j.firstChild.parentNode.removeChild(j.firstChild);
            }
            return j;
        },
        prependChild: function(k, j){
            if (!(k = YAO.getEl(k)) || !(j = YAO.getEl(j))) {
                return false;
            }
            if (k.firstChild) {
                k.insertBefore(j, k.firstChild);
            }
            else {
                k.appendChild(j);
            }
            return k;
        },
        insertAfter: function(l, j){
            var k = j.parentNode;
            if (k.lastChild == j) {
                k.appendChild(l);
            }
            else {
                k.insertBefore(l, j.nextSibling);
            }
        },
		setOpacity: function(el, val){
			YAO.setStyle(el, 'opacity', val);
		},
		Builder: {
			nidx: 0,
			NODEMAP: {
				AREA: 'map',
				CAPTION: 'table',
				COL: 'table',
				COLGROUP: 'table',
				LEGEND: 'fieldset',
				OPTGROUP: 'select',
				OPTION: 'select',
				PARAM: 'object',
				TBODY: 'table',
				TD: 'table',
				TFOOT: 'table',
				TH: 'table',
				THEAD: 'table',
				TR: 'table'
			},
			ATTR_MAP: {
				'className': 'class',
				'htmlFor': 'for',
				'readOnly': 'readonly',
				'maxLength': 'maxlength',
				'cellSpacing': 'cellspacing'
			},
			EMPTY_TAG: /^(?:BR|FRAME|HR|IMG|INPUT|LINK|META|RANGE|SPACER|WBR|AREA|PARAM|COL)$/i,
			// 追加Link节点（添加CSS样式表）
			linkNode: function(url, cssId, charset){
				var c = charset || 'utf-8', link = null;
				var head = D.getElementsByTagName('head')[0];
				link = this.Node('link', {
					'id': cssId || ('link-' + (YAO.Builder.nidx++)),
					'type': 'text/css',
					'charset': c,
					'rel': 'stylesheet',
					'href': url
				});
				head.appendChild(link);
				return link;
			},
			// 追加Script节点
			scriptNode: function(url, scriptId, win, charset){
				var d = win || document.body;
				var c = charset || 'utf-8';
				return d.appendChild(this.Node('script', {
					'id': scriptId || ('script-' + (YAO.Builder.nidx++)),
					'type': 'text/javascript',
					'charset': c,
					'src': url
				}));
			},
			// 创建元素节点
			Node: function(tag, attr, children){
				tag = tag.toUpperCase();
				// try innerHTML approach
				var parentTag = YAO.Builder.NODEMAP[tag] || 'div';
				var parentElement = D.createElement(parentTag);
				var elem = null;
				try { // prevent IE "feature": http://dev.rubyonrails.org/ticket/2707
				    if (this.EMPTY_TAG.test(tag)) {
						//alert(tag);
					}
					else {
						parentElement.innerHTML = "<" + tag + "></" + tag + ">";
					}
				} 
				catch (e) {
				}
				elem = parentElement.firstChild;
				
				// see if browser added wrapping tags
				if (elem && (elem.tagName.toUpperCase() != tag)) {
					elem = elem.getElementsByTagName(tag)[0];
				}
				// fallback to createElement approach
				if (!elem) {
					if (YAO.isString(tag)) {
						elem = D.createElement(tag);
					}
				}
				// abort if nothing could be created
				if (!elem) {
					return;
				}
				else {
					if (attr) {
						this.Attributes(elem, attr);
					}
					if (children) {
						this.Child(elem, children);
					}
					return elem;
				}
			},
			// 给节点添加属性
			Attributes: function(elem, attr){
				var attrName = '', i;
				for (i in attr) {
					if (attr[i] && YAO.hasOwnProperty(attr, i)) {
						attrName = i in YAO.Builder.ATTR_MAP ? YAO.Builder.ATTR_MAP[i] : i;
						if (attrName === 'class') {
							elem.className = attr[i];
						}
						else {
							elem.setAttribute(attrName, attr[i]);
						}
					}
				}
				return elem;
			},
			// 追加子节点
			Child: function(parent, child){
				if (child.tagName) {
					parent.appendChild(child);
					return false;
				}
				if (YAO.isArray(child)) {
					var i, length = child.length;
					for (i = 0; i < length; i += 1) {
						if (child[i].tagName) {
							parent.appendChild(child[i]);
						}
						else {
							if (YAO.isString(child[i])) {
								parent.appendChild(D.createTextNode(child[i]));
							}
						}
					}
				}
				else {
					if (YAO.isString(child)) {
						parent.appendChild(D.createTextNode(child));
					}
				}
			}
		},
		
		batch: function(el, method, o, override){
			var id = el;
			el = YAO.getEl(el);
			var scope = (override) ? o : window;
			if (!el || el.tagName || !el.length) {
				if (!el) {
					return false;
				}
				return method.call(scope, el, o);
			}
			var collection = [];
			for (var i = 0, len = el.length; i < len; ++i) {
				if (!el[i]) {
					id = el[i];
				}
				collection[collection.length] = method.call(scope, el[i], o);
			}
			return collection;
		},

		fadeUp: function(elem){
			if (elem) {
				var level = 0, fade = function(){
					var timer = null;
					level += 0.05;
					if (timer) {
						clearTimeout(timer);
						timer = null;
					}
					if (level > 1) {
						YAO.setOpacity(elem, 1);
						return false;
					}
					else {
						YAO.setOpacity(elem, level);
					}
					timer = setTimeout(fade, 50);
				};
				fade();
			}
		},
		zebra: function(){
			var j, length = arguments.length;
			for (j = 0; j < length; ++j) {
				(function(config){
					var root = YAO.getEl(config.rootTag) || (config.root || null), rows = root.getElementsByTagName(config.rowTag) || (config.rows || null), i, len = rows.length, lastClass = [];
					if (root && rows && len > 1) {
						for (var i = 0; i < len; ++i) {
							rows[i].className = i % 2 === 0 ? 'even' : 'odd';
							lastClass[i] = rows[i].className;
							YAO.on(rows[i],'mouseover', function(index){
								return function(){
									YAO.replaceClass(this, lastClass[index], 'hover');
								}
							}(i),rows[i],true);
							YAO.on(rows[i], 'mouseout', function(index){
								return function(){
									YAO.replaceClass(this, 'hover', lastClass[index]);
								}
							}(i),rows[i],true);
						}
					}
					else {
						return false;
					}
				})(arguments[j]);
			}
		},
		moveElement: function(element, finalX, finalY, speed){
			var elem = YAO.isString(element) ? YAO.getEl(element) : element, style = null;
			if (elem) {
				if (elem.movement) {
					clearTimeout(elem.movement);
				}
				if (!elem.style.left) {
					elem.style.left = "0";
				}
				if (!elem.style.top) {
					elem.style.top = "0";
				}
				var xpos = parseInt(elem.style.left);
				var ypos = parseInt(elem.style.top);
				if (xpos == finalX && ypos == finalY) {
					return true;
				}
				if (xpos < finalX) {
					var dist = Math.ceil((finalX - xpos) / 10);
					xpos = xpos + dist;
				}
				if (xpos > finalX) {
					var dist = Math.ceil((xpos - finalX) / 10);
					xpos = xpos - dist;
				}
				if (ypos < finalY) {
					var dist = Math.ceil((finalY - ypos) / 10);
					ypos = ypos + dist;
				}
				if (ypos > finalY) {
					var dist = Math.ceil((ypos - finalY) / 10);
					ypos = ypos - dist;
				}
				elem.style.left = xpos + "px";
				elem.style.top = ypos + "px";
				elem.movement = setTimeout(function(){
					YAO.moveElement(element, finalX, finalY, speed);
				}, speed);
			}
		},
		
		ajax: function(config){
			var oXhr, method = config.method ? config.method.toUpperCase() : 'GET', url = config.url || '', fn = config.fn || null, postData = config.data || null, elem = config.id ? YAO.getEl(config.id) : (config.element || null), load = config.loadFn ? config.loadFn : (config.loading || '正在获取数据，请稍后...');
			if (!url) {
				return;
			}
			if (window.XMLHttpRequest) {
				oXhr = new XMLHttpRequest();
			}
			else {
				if (window.ActiveXObject) {
					oXhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
			}
			if (oXhr) {
				try {
					oXhr.open(method, url, true);
					oXhr.onreadystatechange = function(){
						if (oXhr.readyState !== 4) {
							return false
						}
						if (oXhr.readyState == 4) {
							if (oXhr.status == 200 || location.href.indexOf('http') === -1) {
								if (fn) {
									fn.success(oXhr);
								}
								else {
									elem.innerHTML = oXhr.responseText;
								}
							}
							else {
								if (fn) {
									fn.failure(oXhr.status);
								}
								else {
									if (YAO.isFunction(load)) {
										load();
									}
									else {
										elem.innerHTML = load;
									}
								}
							}
						}
					};
					oXhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
					if (postData) {
						oXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
					}
					oXhr.send(postData);
				} 
				catch (e) {
					throw new Error(e);
					return false;
				}
			}
			else{
				throw new Error("Your browser does not support XMLHTTP.");
				return false;
			}
		},
		JSON: function(){
			function f(n){
				return n < 10 ? '0' + n : n;
			}
			
			Date.prototype.toJSON = function(){
				return this.getUTCFullYear() + '-' + f(this.getUTCMonth() + 1) + '-' + f(this.getUTCDate()) + 'T' + f(this.getUTCHours()) + ':' + f(this.getUTCMinutes()) + ':' + f(this.getUTCSeconds()) + 'Z';
			};
			
			var m = {
				'\b': '\\b',
				'\t': '\\t',
				'\n': '\\n',
				'\f': '\\f',
				'\r': '\\r',
				'"': '\\"',
				'\\': '\\\\'
			};
			
			function stringify(value, whitelist){
				var a, i, k, l, r = /["\\\x00-\x1f\x7f-\x9f]/g, v;
				switch (typeof value) {
					case 'string':
						return r.test(value) ? '"' +
						value.replace(r, function(a){
							var c = m[a];
							if (c) {
								return c;
							}
							c = a.charCodeAt();
							return '\\u00' + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
						}) +
						'"' : '"' + value + '"';
					case 'number':
						return isFinite(value) ? String(value) : 'null';
					case 'boolean':
					case 'null':
						return String(value);
					case 'object':
						if (!value) {
							return 'null';
						}
						
						if (typeof value.toJSON === 'function') {
							return stringify(value.toJSON());
						}
						a = [];
						if (typeof value.length === 'number' && !(value.propertyIsEnumerable('length'))) {
						
							l = value.length;
							for (i = 0; i < l; i += 1) {
								a.push(stringify(value[i], whitelist) || 'null');
							}
							
							return '[' + a.join(',') + ']';
						}
						if (whitelist) {
							l = whitelist.length;
							for (i = 0; i < l; i += 1) {
								k = whitelist[i];
								if (typeof k === 'string') {
									v = stringify(value[k], whitelist);
									if (v) {
										a.push(stringify(k) + ':' + v);
									}
								}
							}
						}
						else {
							for (k in value) {
								if (typeof k === 'string') {
									v = stringify(value[k], whitelist);
									if (v) {
										a.push(stringify(k) + ':' + v);
									}
								}
							}
						}
						return '{' + a.join(',') + '}';
				}
			}
			
			return {
				stringify: stringify,
				parse: function(text, filter){
					var j;
					
					function walk(k, v){
						var i, n;
						if (v && typeof v === 'object') {
							for (i in v) {
								if (OP.hasOwnProperty.apply(v, [i])) {
									n = walk(i, v[i]);
									if (n !== undefined) {
										v[i] = n;
									}
									else {
										delete v[i];
									}
								}
							}
						}
						return filter(k, v);
					}
					
					if (/^[\],:{}\s]*$/.test(text.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
						j = eval('(' + text + ')');
						
						return typeof filter === 'function' ? walk('', j) : j;
					}
					
					throw new SyntaxError('parseJSON');
				}
			};
		}(),
		
		YTabs: function(){
			var j, len = arguments.length, Tabs = [];
			for (j = 0; j < len; ++j) {
				Tabs[j] = new YAO.singleTab(arguments[j]);
			}
			return Tabs;
		},
		scrollNews: function(S, SI, RT, CT){
            var SN = new YAO.scrollVertical(S, SI, RT, CT);
            SN.speed = 4000;
            SN.isPause = true;
            var TM = setTimeout(function(){
                if (TM) {
                    clearTimeout(TM);
                }
                SN.isPause = false;
            }, 2000);
            YAO.on(SN.scrollArea, 'mouseover', function(){
                SN.isPause = true;
            });
            YAO.on(SN.scrollArea, 'mouseout', function(){
                SN.isPause = false;
            });
        }
	};
	
	YAO.on(window, "unload", YAO._unload);
}();

YAO.singleTab = function(oConfigs){
	this.tabCnt = (oConfigs.tabId) ? YAO.getEl(oConfigs.tabId) : (oConfigs.tabRoot || null);
	this.tabs = (oConfigs.tTag) ? this.tabCnt.getElementsByTagName(oConfigs.tTag) : (oConfigs.tabs || null);
	this.contents = (oConfigs.cTag) ? this.tabCnt.getElementsByTagName(oConfigs.cTag) : (oConfigs.contents || null);
	this.length = this.tabs.length || 0;
	this.defaultIndex = oConfigs.defaultIndex || 0;
	this.lastIndex = this.defaultIndex;
	this.lastTab = this.tabs[this.lastIndex] || null;
	this.lastContent = this.contents[this.lastIndex] || null;
	this.evtName = oConfigs.evt || 'mouseover';
	this.defaultClass = oConfigs.defaultClass || 'current';
	this.previousClass = oConfigs.previousClass || '';
	this.hideAll = oConfigs.hideAll || false;
	this.auto = oConfigs.auto || false;
	this.autoSpeed = oConfigs.autoSpeed || 6000;
	this.fadeUp = oConfigs.fadeUp || false;
	this.scroll = oConfigs.scroll || false;
	this.scrollId = oConfigs.scrollId || null;
	this.scrollSpeed = oConfigs.scrollSpeed || 5;
    this.direction = oConfigs.direction || 'V';
	this.activeTag = oConfigs.activeTag || 'IMG';
	this.stepHeight = oConfigs.stepHeight || 0;
	this.stepWidth = oConfigs.stepWidth || 0;
	this.ajax = oConfigs.ajax || false;
	this.ajaxDefaultInfo = this.contents.innerHTML;
	this.aPath = oConfigs.aPath || '';

	this.init();
};
YAO.singleTab.prototype.timer = null;
YAO.singleTab.prototype.isPause = false;
YAO.singleTab.prototype = {
	init: function(){
		var i, that = this, scrollWidth, scrollHeight, activeObj = null, itemHeight = 0, itemWidth = 0;
		if (this.tabs && this.contents) {
			if (this.auto) {
				this.timer = setTimeout(function(){
					that.autoChange();
				}, that.autoSpeed);
			}
			if (!this.hideAll) {
				YAO.addClass(this.lastTab, this.defaultClass);
				if (!this.ajax && !this.scroll) {
					if (this.lastContent) {
						this.lastContent.style.display = 'block';
					}
				}
				if (this.ajax) {
					this.ajaxTab(this.lastTab);
				}
				if (this.scroll) {
					this.scrollCnt((this.lastContent || this.contents), this.defaultIndex);
				}
			}
			else {
				YAO.removeClass(this.lastTab, this.defaultClass);
			}
			for (i = 0; i < this.length; ++i) {
				if (i !== this.defaultIndex) {
					YAO.removeClass(this.tabs[i], 'current');
					if (!this.ajax && !this.scroll) {
						this.contents[i].style.display = 'none';
					}
				}
				YAO.on(this.tabs[i], this.evtName, function(index){
					return function(event){
						var evt = null, curClass = (this.tabs[index] === this.tabs[this.defaultIndex]) ? this.defaultClass : 'current';
						if (!YAO.hasClass(this.tabs[index], curClass)) {
							var currentContent = (this.ajax || (this.scroll && (this.stepHeight || this.stepWidth))) ? this.contents : (this.contents[index] || null);
							
							this.setCurrent(currentContent, index);
							this.lastIndex = index;
						}
						if (this.auto) {
							this.isPause = true;
						}
						evt = event || window.event;
						YAO.stopEvent(evt);
					}
				}(i), this.tabs[i], that);
				YAO.on(this.tabs[i], 'mouseout', function(index){
					return function(){
						var curTab = this.tabs[index];
						if (this.hideAll && this.evtName === 'mouseover') {
							if (this.lastTab === curTab) {
								YAO.removeClass(curTab, (YAO.hasClass(curTab, that.defaultClass) ? this.defaultClass : 'current'));
							}
							if (this.previousClassTab) {
								YAO.removeClass(this.previousClassTab, this.previousClass);
							}
							if (!this.scroll && !this.ajax) {
								this.contents[index].style.display = 'none';
							}
						}
						else {
							if (this.auto) {
								this.isPause = false;
							}
						}
					}
				}(i), this.tabs[i], that);
			}
		}
	},
	autoChange: function(){
		var that = this;
		if (!this.isPause) {
			var currentContent = null, currentTab = null;
			if (this.timer) {
				clearTimeout(this.timer);
				this.timer = null;
			}
			this.lastIndex = this.lastIndex + 1;
			if (this.lastIndex === this.length) {
				this.lastIndex = 0;
			}
			currentContent = this.ajax ? this.contents : (this.contents[this.lastIndex] || null);
			this.setCurrent(currentContent, this.lastIndex);
			this.timer = setTimeout(function(){
				that.autoChange();
			}, this.autoSpeed);
		}
		else {
			this.timer = setTimeout(function(){
				that.autoChange()
			}, this.autoSpeed);
			return false;
		}
	},
	setCurrent: function(curCnt, index){
		var activeObj = null;
		curTab = this.tabs[index];
		YAO.removeClass(this.lastTab, (YAO.hasClass(this.lastTab, this.defaultClass) ? this.defaultClass : 'current'));
		if (curTab === this.tabs[this.defaultIndex]) {
			YAO.addClass(curTab, this.defaultClass);
		}
		else {
			YAO.addClass(curTab, 'current');
		}
		if (this.previousClass) {
			if (this.previousClassTab) {
				YAO.removeClass(this.previousClassTab, this.previousClass);
			}
			if (index !== 0) {
				YAO.addClass(this.tabs[index - 1], this.previousClass);
				if ((index - 1) === this.defaultIndex) {
					YAO.removeClass(this.tabs[index - 1], this.defaultClass);
				}
				this.previousClassTab = (this.tabs[index - 1]);
			}
		}
		if (!this.scroll && !this.ajax) {
			if (this.lastContent) {
				this.lastContent.style.display = "none";
			}
			if (curCnt) {
				curCnt.style.display = "block";
			}
		}
		
		if (this.fadeUp) {
			 activeObj = (curCnt.tagName.toUpperCase() === 'IMG') ? curCnt : curCnt.getElementsByTagName('img')[0];
			if (this.lastContent !== curCnt) {
				YAO.fadeUp(activeObj);
			}
		}
		else {
			if (this.scroll) {
				this.scrollCnt(curCnt, index);
			}
		}
		if (!this.ajax) {
			this.lastContent = curCnt;
		}
		else {
			if (this.ajax) {
				this.ajaxTab(curTab);
			}
		}
		this.lastTab = curTab;
	},
	scrollCnt: function(curCnt,index){
		var activeObj = null, itemHeight = 0, itemWidth = 0, scrollWidth = 0, scrollHeight = 0;
		if (this.activeTag) {
			activeObj = (curCnt.tagName.toUpperCase() === this.activeTag) ? curCnt : curCnt.getElementsByTagName(this.activeTag)[0];
		}
		if (this.direction === 'V') {
			itemHeight = activeObj ? activeObj.offsetHeight : this.stepHeight;
			scrollHeight = -(index * itemHeight);
		}
		else {
			itemWidth = activeObj ? activeObj.offsetWidth : this.stepWidth;
			scrollWidth = -(index * itemWidth);
		}
		YAO.moveElement(this.scrollId, scrollWidth, scrollHeight, this.scrollSpeed);
	},
	ajaxTab: function(curTab){
		var url = '', ajaxLink = null, cnt = this.contents, uriData = this.aPath.split('/');
		ajaxLink = (curTab.tagName.toUpperCase() === 'A') ? curTab : curTab.getElementsByTagName('a')[0];
		url = uriData[0] + '/' + ajaxLink.rel + uriData[1] + uriData[2] + ajaxLink.rel;
		
		if (curTab === this.tabs[this.defaultIndex]) {
		    cnt.innerHTML = this.ajaxDefaultInfo;
		}
		else {
			YAO.ajax({
				url: url,
				element: cnt,
				load: cnt.innerHTML
			});
		}
	}
};	

YAO.scrollVertical = function(disp, msg, tg, stg){
	var D = document;
	if (YAO.isString(disp)) {
		this.scrollArea = D.getElementById(disp);
	}
	else {
		this.scrollArea == disp;
	}
	if (YAO.isString(msg)) {
		this.scrollMsg = D.getElementById(msg);
	}
	else {
		this.scrollMsg = msg;
	}
	var s_msg = this.scrollMsg;
	var s_area = this.scrollArea;
	if (!tg) {
		var tg = 'li';
	}
	this.unitHeight = s_msg.getElementsByTagName(tg)[0].offsetHeight;
	this.msgHeight = this.unitHeight * s_msg.getElementsByTagName(tg).length;
	s_msg.style.position = "absolute";
	s_msg.style.top = "0";
	s_msg.style.left = "0";
	var copydiv = D.createElement(stg || 'div');
	copydiv.id = s_area.id + "_copymsgid";
	copydiv.innerHTML = s_msg.innerHTML;
	copydiv.style.height = this.msgHeight + "px";
	s_area.appendChild(copydiv);
	copydiv.style.position = "absolute";
	copydiv.style.left = "0";
	copydiv.style.top = this.msgHeight + "px";
	this.copyMsg = copydiv;
	this.play(this);
};
YAO.scrollVertical.prototype.scrollArea = null;
YAO.scrollVertical.prototype.scrollMsg = null;
YAO.scrollVertical.prototype.unitHeight = 0;
YAO.scrollVertical.prototype.msgHeight = 0;
YAO.scrollVertical.prototype.copyMsg = null;
YAO.scrollVertical.prototype.scrollValue = 0;
YAO.scrollVertical.prototype.scrollHeight = 0;
YAO.scrollVertical.prototype.isStop = true;
YAO.scrollVertical.prototype.isPause = false;
YAO.scrollVertical.prototype.scrollTimer = null;
YAO.scrollVertical.prototype.speed = 2000;
YAO.scrollVertical.prototype.play = function(o){
	var s_msg = o.scrollMsg, c_msg = o.copyMsg, s_area = o.scrollArea, msg_h = o.msgHeight, isMoz = function(){
		if (navigator.userAgent.toLowerCase().match(/mozilla/)) {
			return 1;
		}
	}, anim = function(){
		if (o.scrollTimer) {
			clearTimeout(o.scrollTimer);
		}
		if (o.isPause) {
			o.scrollTimer = setTimeout(anim, 50);
			return;
		}
		if (msg_h - o.scrollValue <= 0) {
			o.scrollValue = 0;
		}
		else {
			o.scrollValue += 1;
			o.scrollHeight += 1;
		}
		if (isMoz) {
			s_area.scrollTop = o.scrollValue;
		}
		else {
			s_msg.style.top = -1 * o.scrollValue + "px";
			c_msg.style.top = (msg_h - o.scrollValue) + "px";
		}
		if (o.scrollHeight % s_area.offsetHeight == 0) {
			o.scrollTimer = setTimeout(anim, o.speed);
		}
		else {
			o.scrollTimer = setTimeout(anim, 50);
		}
	};
	anim();
};

YAO.chkAll = function(config){
	this.chkAllItem = config.chkAllItem ? config.chkAllItem : (config.chkAllItemId ? YAO.getEl(config.chkAllItemId) : null);
	this.list = config.list ? config.list : (config.listId ? YAO.getEl(config.listId) : null);
	this.items = config.items ? config.items : (config.itemTag ? this.list.getElementsByTagName(config.itemTag) : null);
	this.length = this.items.length;
	this.itemsNumPerPage = config.itemsNumPerPage || this.length;
    this.pages = Math.ceil(this.length/this.itemsNumPerPage); 
	this.curPage = (this.itemsNumPerPage !== this.length && config.curPage) ? config.curPage : 0;
	this.init();
};
YAO.chkAll.prototype.chkNum = 0;
YAO.chkAll.prototype.init = function(){
	if (this.chkAllItem && this.items) {
		var i, oSelf = this;
		for (i = 0; i < this.length; ++i) {
			YAO.on(this.items[i], 'click', function(){
				oSelf.chgItemBg.call(oSelf,this);
			},this.items[i],true);
		}
		YAO.on(this.chkAllItem, 'click', function(){
			oSelf.all.call(oSelf);
		},this.chkAllItem,true);
	}
};	
YAO.chkAll.prototype.all = function(){
	var i, startNum = this.curPage * this.itemsNumPerPage, len = (this.length < startNum + this.itemsNumPerPage) ? this.length : (startNum + this.itemsNumPerPage);
	for (i = startNum; i < len; ++i) {
		this.items[i].checked = this.chkAllItem.checked;
		this.chgItemBg(this.items[i]);
	}
};
YAO.chkAll.prototype.chgItemBg = function(item){
	var i, row = item.parentNode, curAllItemLength = (this.length < ((this.curPage+1) * this.itemsNumPerPage)) ? (this.length - (this.curPage * this.itemsNumPerPage)) : this.itemsNumPerPage;
	
	if (item.checked) {
		YAO.addClass(row, 'chked');
		this.chkNum += 1;
		if (this.chkNum >=  curAllItemLength) {
			this.chkNum =  curAllItemLength;
			this.chkAllItem.checked = true;
		}
	}
	else {
		YAO.removeClass(row, 'chked');
		if(this.chkAllItem.checked){
			this.chkAllItem.checked = false;
		}
		this.chkNum -= 1;
		if (this.chkNum < 0) {
			this.chkNum = 0;
		}
	}
};

YAO.Carousel = function(oConfig){
	this.btnPrevious = oConfig.btnPrevious;
	this.lnkBtnPrevious = this.btnPrevious.getElementsByTagName('a')[0];
	this.Container = oConfig.ContainerID ? YAO.getEl(oConfig.ContainerID) : (oConfig.Container || null);
	this.Scroller = oConfig.scrollId ? YAO.getEl(oConfig.scrollId) : (oConfig.Scroller || null);
	this.btnNext = oConfig.btnNext;
	this.lnkBtnNext = this.btnNext.getElementsByTagName('a')[0];
	this.items = oConfig.itemTag ? this.Container.getElementsByTagName(oConfig.itemTag) : (oConfig.items || null);
	this.length = this.items.length;
	this.itemWidth = this.items[0].offsetWidth;
	this.itemHeight = this.items[0].offsetHeight;
	this.scrollerWidth = this.itemWidth * this.length;
	this.scrollHeight = this.itemHeight * this.length;
	this.derection = oConfig.derection || 'H';
	this.stepHeight = oConfig.stepHeight || this.itemHeight;
	this.stepWidth = oConfig.stepWidth || this.itemWidth;
	this.groups = this.derection === 'H' ? Math.ceil(this.scrollerWidth / this.stepWidth) : Math.ceil(this.scrollHeight / this.stepHeight);
	this.maxMovedNum = this.derection === 'H' ? (this.groups - (this.Container.offsetWidth / this.stepWidth)) : (this.groups - (this.Container.offsetHeight / this.stepHeight));
	this.scrollSpeed = oConfig.speed || 50;
	
	this.init();
};
YAO.Carousel.prototype.movedNum = 0;
YAO.Carousel.prototype.init = function(){
	var oSelf = this;
	if (this.derection === 'H') {
		this.Scroller.style.width = this.scrollerWidth + 'px';
	}
	else {
		this.Scroller.style.height = this.scrollerHeight + 'px';
	}
	this.Container.style.overflow = 'hidden';
	if (this.lnkBtnNext && this.movedNum === this.maxMovedNum) {
		YAO.addClass(this.lnkBtnNext, 'dis');
	}
	if (this.lnkBtnPrevious && this.movedNum === 0) {
		YAO.addClass(this.lnkBtnPrevious, 'dis');
	}
	YAO.on(this.btnPrevious, 'click', this.scrollPrevious, this.btnPrevious, oSelf);
	YAO.on(this.btnNext, 'click', this.scrollNext, this.btnNext, oSelf);
};
YAO.Carousel.prototype.scrollPrevious = function(event){
	var evt = event || window.event;
	if (this.movedNum > 0) {
		this.movedNum -= 1;
		if (this.lnkBtnNext && YAO.hasClass(this.lnkBtnNext, 'dis')) {
			YAO.removeClass(this.lnkBtnNext, 'dis');
		}
		if (this.movedNum <= 0) {
			this.movedNum = 0;
			if (this.lnkBtnPrevious) {
				YAO.addClass(this.lnkBtnPrevious, 'dis');
			}
		}
		this.scroll(this.movedNum);
	}
	YAO.stopEvent(evt);
};
YAO.Carousel.prototype.scrollNext = function(event){
	var evt = event || window.event;
	if (this.movedNum < this.maxMovedNum) {
		this.movedNum += 1;
		if (this.lnkBtnPrevious && YAO.hasClass(this.lnkBtnPrevious, 'dis')) {
			YAO.removeClass(this.lnkBtnPrevious, 'dis');
		}
		if (this.movedNum >= this.maxMovedNum) {
			this.movedNum = this.maxMovedNum;
			if (this.lnkBtnNext) {
				YAO.addClass(this.lnkBtnNext, 'dis');
			}
		}
		this.scroll(this.movedNum);
	}
	YAO.stopEvent(evt);
}; 
YAO.Carousel.prototype.scroll = function(steps){
	var scrollWidth = 0, scrollHeight = 0;
	if (this.derection === 'H') {
		if (this.stepWidth) {
			scrollWidth = -(this.stepWidth * steps);
		}
		else {
			scrollWidth = -(this.itemWidth * steps);
		}
	}
	else {
		if (this.stepHeight) {
			scrollHeight = -(this.stepHeight * steps);
		}
		else {
			scrollHeight = -(this.itemHeight * steps);
		}
	}
	YAO.moveElement(this.Scroller, scrollWidth, scrollHeight, this.scrollSpeed);
};

YAO.YAlbum = function(){
	var oSelf = this;
	this.oCarousel = new YAO.Carousel({
		btnPrevious: oSelf.CARSOUEL_BTN_PREVIOUS,
		Container: oSelf.CARSOUEL_CONTAINER,
		Scroller: oSelf.CARSOUEL_SCROLLER,
		btnNext: oSelf.CARSOUEL_BTN_NEXT,
		itemTag: oSelf.CARSOUEL_ITEM_TAG,
		stepWidth: oSelf.CARSOUEL_STEP_WIDTH
	}) || null;
	this.oSamples = this.oCarousel.Scroller.getElementsByTagName('a') || null;
	this.length = this.oSamples.length || 0;
	this.lastSample = this.oSamples[0] || null;
	this.photoContainer = YAO.getEl(this.PHOTO_CONTAINER_ID) || null;
	this.photo = YAO.getEl(this.PHOTO_ID) || null;
	this.photoIntro = YAO.getEl(this.PHOTO_INTRO_ID) || null;
	this.sIntro = this.photo.alt || '';
	
	this.init();
};

YAO.YAlbum.prototype.lastIndex = 0;
YAO.YAlbum.prototype.isLoading = false;
YAO.YAlbum.prototype.lastPhotoHeight = 0;
YAO.YAlbum.prototype.loadShardow = null;
YAO.YAlbum.prototype.loadImg = null;

YAO.YAlbum.prototype.CARSOUEL_BTN_PREVIOUS = YAO.getEl('carousel_btn_lastgroup');
YAO.YAlbum.prototype.CARSOUEL_CONTAINER = YAO.getEl('carousel_container');
YAO.YAlbum.prototype.CARSOUEL_SCROLLER = YAO.getEl('samples_list');
YAO.YAlbum.prototype.CARSOUEL_BTN_NEXT = YAO.getEl('carousel_btn_nextgroup');
YAO.YAlbum.prototype.CARSOUEL_ITEM_TAG = 'li';
YAO.YAlbum.prototype.CARSOUEL_STEP_WIDTH = 672;
YAO.YAlbum.prototype.PHOTO_MAX_WIDTH = 800;
YAO.YAlbum.prototype.PHOTO_CONTAINER_ID = 'carousel_photo_container';
YAO.YAlbum.prototype.PHOTO_ID = 'carousel_photo';
YAO.YAlbum.prototype.PHOTO_INTRO_ID = 'carousel_photo_intro';
YAO.YAlbum.prototype.BTN_NEXT_ID = 'carousel_next_photo';
YAO.YAlbum.prototype.BTN_NEXT_CLASS = 'next';
YAO.YAlbum.prototype.BTN_PREVIOUS_ID = 'carousel_previous_photo';
YAO.YAlbum.prototype.BTN_PREVIOUS_CLASS = 'previous';
YAO.YAlbum.prototype.BTN_DISABLED_CLASS = 'dis';
YAO.YAlbum.prototype.IMG_BTN_PREVIOUS = 'url(img/last-photo.gif)';
YAO.YAlbum.prototype.IMG_BTN_NEXT = 'url(img/next-photo.gif)';
YAO.YAlbum.prototype.SHARDOW_ID = 'carousel_photo_shardow';
YAO.YAlbum.prototype.LOAD_IMG_PATH = 'img/loading.gif';
YAO.YAlbum.prototype.LOAD_IMG_ID = 'carousel_photo_loading';

YAO.YAlbum.prototype.init = function(){
	var oSelf = this, i;
	
	YAO.addClass(this.lastSample, 'current');
	this.btnPrevious = YAO.Builder.Node('a', {
		href: oSelf.oSamples[oSelf.lastIndex].href,
		id: oSelf.BTN_PREVIOUS_ID,
		className: oSelf.BTN_PREVIOUS_CLASS,
		title: '上一张'
	}, '上一张');
	this.photoContainer.appendChild(this.btnPrevious);
	this.btnNext = YAO.Builder.Node('a', {
		href: oSelf.oSamples[oSelf.lastIndex + 1].href,
		id: oSelf.BTN_NEXT_ID,
		className: oSelf.BTN_NEXT_CLASS,
		title: '下一张'
	}, '下一张');
	this.photoContainer.appendChild(this.btnNext);
	this.load(this.photo.src);
	
	YAO.on(this.btnPrevious, 'click', function(event){
		var evt = event || window.event;
		this.Previous();
		YAO.stopEvent(evt);
	}, this.btnPrevious, oSelf);
	YAO.on(this.btnNext, 'click', function(event){
		var evt = event || window.event;
		this.Next();
		YAO.stopEvent(evt);
	}, this.btnNext, oSelf);
	
	for (i = 0; i < this.length; ++i) {
		YAO.on(this.oSamples[i], 'click', function(index){
			return function(event){
				var evt = event || window.event, curSample = this.oSamples[index];
				if (this.lastSample !== curSample && !this.isLoading) {
					this.lastIndex = index;
					this.btnsEnabled();
					this.chgPhoto();
				}
				YAO.stopEvent(evt);
			}
		}(i), this.oSamples[i], oSelf);
	}
};
YAO.YAlbum.prototype.btnsEnabled = function(){
	if (this.lastIndex !== 0 && YAO.hasClass(this.btnPrevious, this.BTN_DISABLED_CLASS)) {
		YAO.removeClass(this.btnPrevious, this.BTN_DISABLED_CLASS);
		if (YAO.ua.ie) {
			this.btnPrevious.style.backgroundImage = this.IMG_BTN_PREVIOUS;
		}
		this.btnPrevious.href = this.oSamples[this.lastIndex - 1];
	}
	else {
		if (this.lastIndex === 0) {
			YAO.addClass(this.btnPrevious, this.BTN_DISABLED_CLASS);
			if (YAO.ua.ie) {
				this.btnPrevious.style.backgroundImage = 'none';
			}
			this.btnPrevious.href = this.oSamples[this.lastIndex];
		}
	}
	if (this.lastIndex !== (this.length - 1) && YAO.hasClass(this.btnNext, this.BTN_DISABLED_CLASS)) {
		YAO.removeClass(this.btnNext, this.BTN_DISABLED_CLASS);
		if (YAO.ua.ie) {
			this.btnNext.style.backgroundImage = this.IMG_BTN_NEXT;
		}
		this.btnNext.href = this.oSamples[this.lastIndex + 1];
	}
	else {
		if (this.lastIndex === (this.length - 1)) {
			YAO.addClass(this.btnNext, this.BTN_DISABLED_CLASS);
			if (YAO.ua.ie) {
				this.btnNext.style.backgroundImage = 'none';
			}
			this.btnNext.href = this.oSamples[this.lastIndex];
		}
	}
};					
YAO.YAlbum.prototype.load = function(path){
	var oImage = new Image(), oDf = document.createDocumentFragment();
	oImage.src = path;
	
	if (oImage.complete) {
		this.resize(oImage);
	}
	else {
		this.isLoading = true;
		this.loadShardow = YAO.Builder.Node('div', {
			id: this.SHARDOW_ID
		});
		this.loadImg = YAO.Builder.Node('img', {
			src: this.LOAD_IMG_PATH,
			id: this.LOAD_IMG_ID
		});
		oDf.appendChild(this.loadShardow);
		if (YAO.ua.ie) {
			this.loadShardow.style.height = this.lastPhotoHeight ? this.lastPhotoHeight + 'px' : this.photoContainer.offsetHeight + 'px';
		}
		oDf.appendChild(this.loadImg);
		this.photoContainer.appendChild(oDf);
		YAO.on(oImage, 'load', function(){
			this.resize(oImage);
		}, oImage, this);
	}
};
YAO.YAlbum.prototype.resize = function(oImage){
	var oSelf = this;
	var width = oImage.width;
	var height = oImage.height;
	var percent = width / height;
	if (width > this.PHOTO_MAX_WIDTH) {
		width = this.PHOTO_MAX_WIDTH;
		height = width / percent;
	}
	if (YAO.ua.ie) {
		this.lastPhotoHeight = height;
		YAO.setStyles(this.btnPrevious, {
			height: height + 'px',
			backgroundImage: oSelf.IMG_BTN_PREVIOUS
		});
		YAO.setStyles(this.btnNext, {
			height: height + 'px',
			backgroundImage: oSelf.IMG_BTN_NEXT
		});
	}
	if (this.lastIndex === 0) {
		YAO.addClass(this.btnPrevious, this.BTN_DISABLED_CLASS);
		if (YAO.ua.ie) {
			this.btnPrevious.style.backgroundImage = 'none';
		}
	}
	if (this.lastIndex === (this.length - 1)) {
		YAO.addClass(this.btnNext, this.BTN_DISABLED_CLASS);
		if (YAO.ua.ie) {
			this.btnNext.style.backgroundImage = 'none';
		}
	}
	this.photoIntro.innerHTML = this.sIntro;
	YAO.setStyle(this.photoContainer, 'width', (width + 'px'));
	YAO.setStyles(this.photo, {
		width: width + 'px',
		height: height + 'px'
	});
	if (this.loadImg && this.loadShardow) {
		this.isLoading = false;
		this.photoContainer.removeChild(this.loadImg);
		this.loadImg = null;
		this.photoContainer.removeChild(this.loadShardow);
		this.loadShardow = null;
	}
};
YAO.YAlbum.prototype.Previous = function(){
	if (this.lastIndex !== 0) {
		this.lastIndex -= 1;
		if (YAO.hasClass(this.btnNext, this.BTN_DISABLED_CLASS)) {
			YAO.removeClass(this.btnNext, this.BTN_DISABLED_CLASS);
		}
		if (this.lastIndex >= 1) {
			this.btnPrevious.href = this.oSamples[this.lastIndex - 1].href;
		}
		if (this.lastIndex < 0) {
			this.lastIndex = 0;
			YAO.addClass(this.btnPrevious, this.BTN_DISABLED_CLASS);
		    this.btnPrevious.href = this.oSamples[this.lastIndex].href;
		}
		this.btnNext.href = this.oSamples[this.lastIndex+1].href;
		this.chgPhoto();
	}
};
YAO.YAlbum.prototype.Next = function(){
	if (this.lastIndex < (this.length - 1)) {
		this.lastIndex += 1;
		if (YAO.hasClass(this.btnPrevious, this.BTN_DISABLED_CLASS)) {
			YAO.removeClass(this.btnPrevious, this.BTN_DISABLED_CLASS);
		}
		if (this.lastIndex <= (this.length - 2)) {
			this.btnNext.href = this.oSamples[this.lastIndex + 1].href;
		}
		if (this.lastIndex > (this.length - 1)) {
			this.lastIndex = (this.length - 1);
			YAO.addClass(this.btnNext, this.BTN_DISABLED_CLASS);
			this.btnNext.href = this.oSamples[this.lastIndex].href;
		}
		this.btnPrevious.href = this.oSamples[this.lastIndex-1].href;
		this.chgPhoto();
	}
};
YAO.YAlbum.prototype.chgPhoto = function(){
	var path = '';
	this.sIntro = this.oSamples[this.lastIndex].title;
	path = this.oSamples[this.lastIndex].href;
	YAO.removeClass(this.lastSample, 'current');
	YAO.addClass(this.oSamples[this.lastIndex], 'current');
	this.lastSample = this.oSamples[this.lastIndex];
	this.photo.src = path;
	this.load(path);
	this.scroll();
};
YAO.YAlbum.prototype.scroll = function(){
	var curScreen = Math.ceil(((this.lastIndex + 1) * this.oCarousel.itemWidth) / this.oCarousel.stepWidth) - 1;
	if (curScreen != this.oCarousel.movedNum) {
		this.oCarousel.scroll(curScreen);
		this.oCarousel.movedNum = curScreen;
		if (this.oCarousel.movedNum !== 0 && YAO.hasClass(this.oCarousel.lnkBtnPrevious, this.BTN_DISABLED_CLASS)) {
			YAO.removeClass(this.oCarousel.lnkBtnPrevious, this.BTN_DISABLED_CLASS);
		}
		else {
			if (this.oCarousel.movedNum === 0) {
				YAO.addClass(this.oCarousel.lnkBtnPrevious, this.BTN_DISABLED_CLASS);
			}
		}
		if (this.oCarousel.movedNum !== this.oCarousel.maxMovedNum && YAO.hasClass(this.oCarousel.lnkBtnNext, this.BTN_DISABLED_CLASS)) {
			YAO.removeClass(this.oCarousel.lnkBtnNext, this.BTN_DISABLED_CLASS);
		}
		else {
			if (this.oCarousel.movedNum === this.oCarousel.maxMovedNum) {
				YAO.addClass(this.oCarousel.lnkBtnNext, this.BTN_DISABLED_CLASS);
			}
		}
	}
};