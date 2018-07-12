/**
 * 核心Js函数库文件，目前已经在core中自动加载
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */

// 字符串长度 - 中文和全角符号为1；英文、数字和半角为0.5
var getLength = function(str, shortUrl) {
	if (true == shortUrl) {
		// 一个URL当作十个字长度计算
		return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
							.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	} else {
		return Math.ceil(str.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	}
};

//xss前端过滤方法
var stripscript = function(s) 
{ 
	var pattern = new RegExp("[%--`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'\"。，、？]")//格式 RegExp("[在中间定义特殊过滤字符]")
	var rs = ""; 
	for (var i = 0; i < s.length; i++) { 
	 	rs = rs+s.substr(i, 1).replace(pattern, ''); 
	}
	return rs;
}

// 截取字符串
var subStr = function(str, len) {
    if(!str) {
    	return '';
    }
    len = len > 0 ? len * 2 : 280;
    var count = 0;			// 计数：中文2字节，英文1字节
	var temp = '';  		// 临时字符串
    for(var i = 0; i < str.length; i ++) {
    	if(str.charCodeAt(i) > 255) {
        	count += 2;
        } else {
        	count ++;
        }
        // 如果增加计数后长度大于限定长度，就直接返回临时字符串
        if(count > len) {
        	return temp;
        }
        // 将当前内容加到临时字符串
		temp += str.charAt(i);
    }

    return str;
};
// 异步请求页面
var async_page = function(url, target, callback) {
	if(!url) {
		return false;
	} else if(target) {
		var $target = $(target);
		//$target.html('<img src="'+_THEME_+'/images/icon_waiting.gif" width="20" style="margin:10px 50%;" />');
	}
	$.post(url, {}, function(txt) {
		txt = eval("(" + txt + ")");
		if(txt.status) {
			if(target) {
				$target.html(txt.data);
			}
			if(callback) {
				if(callback.match(/[(][^()]*[)]/)) {
					eval(callback);
				} else {
					eval(callback)(txt);
				}
			}
			if(txt.info) {
				ui.success(txt.info);
			}
		} else if(txt.info) {
			ui.error(txt.info);
			return false;
		}
	});

	return true;
};
// 异步加载翻页
var async_turn_page = function(page_number, target) {
	$(page_number).click(function(o) {
		var $a = $(o.target);
		var url = $a.attr("href");
		if(url) {
			async_page(url, target);
		}
		return false;
	});
};

//表单异步处理 
/* 生效条件：包含 jquery.form.js */
//TODO 优化jquery.form.js的加载机制
var async_form = function(form)
{
	var $form = form ? $(form) : $("form[ajax='ajax']");

	//监听 form 表单提交
	$form.bind('submit', function() {
		var callback = $(this).attr('callback');
		var options = {
		    success: function(txt) {
		    	txt = eval("("+txt+")");
				if(callback){
					if (callback.match(/[(][^()]*[)]/)) {
						eval(callback);
					} else {
						eval(callback)(txt);
					}
				}else{
					if(txt.status && txt.info){
						ui.success( txt.info );
					}else if (txt.info) {
						ui.error( txt.info );
					}						  	 
				}
		    }
		};		
    $(this).ajaxSubmit(options);
		return false;
});
};

// 复制剪贴板
var copy_clip = function (copy){
	if (window.clipboardData) {
		 window.clipboardData.setData("Text", copy);
	 } else if (window.netscape) {
		  try {
			   netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		  } catch (e) {
			   alert( L('PUBLIC_EXPLORER_ISCTRL') );
			   return false;
		  }
		  var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		  if (!clip) {
			  return false;
		  }
		  var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		  if (!trans) {
			  return false;
		  }
		  trans.addDataFlavor('text/unicode');
		  var str = new Object();
		  var len = new Object();
		  var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		  var copytext = copy;
		  str.data = copytext;
		  trans.setTransferData("text/unicode",str,copytext.length*2);
		  var clipid = Components.interfaces.nsIClipboard;
		  if (!clip) {
			  return false;
		  }
		  clip.setData(trans,null,clipid.kGlobalClipboard);
	 }
	 ui.success( L('PUBLIC_EXPLORER_CTRL') );
	 return true;	 
};
	//是否含有某个样式
	
function hasClass(ele,cls) {
	return $(ele).hasClass(cls);
	//return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
	//添加某个样式
function addClass(ele,cls) {
	$(ele).addClass(cls);
	//if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}
	//移除某个样式
function removeClass(ele,cls) {
	$(ele).removeClass(cls);
	//if (hasClass(ele,cls)) {
	//	var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		//ele.className=ele.className.replace(reg,' ');
	//}
}

var toElement = function(){
	var div = document.createElement('div');
	return function(html){
		div.innerHTML = html;
		var el = div.childNodes[0];
		div.removeChild(el);
		return el;
	}
}();

/**
 *	与php的implode方法用法一样
 *	@from php.js  
 */

var implode  = function (glue, pieces) {
    var i = '',
        retVal = '',        tGlue = '';
    if (arguments.length === 1) {
        pieces = glue;
        glue = '';
    }    if (typeof(pieces) === 'object') {
        if (Object.prototype.toString.call(pieces) === '[object Array]') {
            return pieces.join(glue);
        } 
        for (i in pieces) {            retVal += tGlue + pieces[i];
            tGlue = glue;
        }
        return retVal;
    }    return pieces;
};
/**
 * 与php的explode用法一致
 * @from php.js
 */
var explode = function(delimiter, string, limit){
	var emptyArray = {0:''};
 
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {        
    	return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;    
   }
 
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    } 
    if (delimiter === true) {
        delimiter = '1';
    }
     if (!limit) {
        return string.toString().split(delimiter.toString());
    }
    // support for limit argument
    var splitted = string.toString().split(delimiter.toString());    var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
};
/**
 *	与php的strlen方法用法一样
 *	@from php.js  
 */
var strlen = function (string) {
    var str = string + '';
    var i = 0,        chr = '',
        lgth = 0;
 
    if (!this.php_js || !this.php_js.ini || !this.php_js.ini['unicode.semantics'] || this.php_js.ini['unicode.semantics'].local_value.toLowerCase() !== 'on') {
        return string.length;    }
 
    var getWholeChar = function (str, i) {
        var code = str.charCodeAt(i);
        var next = '',            prev = '';
        if (0xD800 <= code && code <= 0xDBFF) { // High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
            if (str.length <= (i + 1)) {
                throw 'High surrogate without following low surrogate';
            }            next = str.charCodeAt(i + 1);
            if (0xDC00 > next || next > 0xDFFF) {
                throw 'High surrogate without following low surrogate';
            }
            return str.charAt(i) + str.charAt(i + 1);        } else if (0xDC00 <= code && code <= 0xDFFF) { // Low surrogate
            if (i === 0) {
                throw 'Low surrogate without preceding high surrogate';
            }
            prev = str.charCodeAt(i - 1);            if (0xD800 > prev || prev > 0xDBFF) { //(could change last hex to 0xDB7F to treat high private surrogates as single characters)
                throw 'Low surrogate without preceding high surrogate';
            }
            return false; // We can pass over low surrogates now as the second component in a pair which we have already processed
        }        return str.charAt(i);
    };
 
    for (i = 0, lgth = 0; i < str.length; i++) {
        if ((chr = getWholeChar(str, i)) === false) {            continue;
        } // Adapt this line at the top of any loop, passing in the whole string and the current iteration and returning a variable to represent the individual character; purpose is to treat the first part of a surrogate pair as the whole character and then ignore the second part
        lgth++;
    }
    return lgth;
};

/**
 * 与PHP的substr一样的用法、
 * @from php.js 
 */
var substr = function(str, start, len) {
    var i = 0,
        allBMP = true,
        es = 0,        el = 0,
        se = 0,
        ret = '';
    str += '';
    var end = str.length; 
    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    // END REDUNDANT    
    switch ((this.php_js.ini['unicode.semantics'] && this.php_js.ini['unicode.semantics'].local_value.toLowerCase())) {
    case 'on':
        // Full-blown Unicode including non-Basic-Multilingual-Plane characters
        // strlen()
        for (i = 0; i < str.length; i++) {            if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
                allBMP = false;
                break;
            }
        } 
        if (!allBMP) {
            if (start < 0) {
                for (i = end - 1, es = (start += end); i >= es; i--) {
                    if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {                        start--;
                        es--;
                    }
                }
            } else {                var surrogatePairs = /[\uD800-\uDBFF][\uDC00-\uDFFF]/g;
                while ((surrogatePairs.exec(str)) != null) {
                    var li = surrogatePairs.lastIndex;
                    if (li - 2 < start) {
                        start++;                    } else {
                        break;
                    }
                }
            } 
            if (start >= end || start < 0) {
                return false;
            }
            if (len < 0) {                for (i = end - 1, el = (end += len); i >= el; i--) {
                    if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
                        end--;
                        el--;
                    }                }
                if (start > end) {
                    return false;
                }
                return str.slice(start, end);            } else {
                se = start + len;
                for (i = start; i < se; i++) {
                    ret += str.charAt(i);
                    if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {                        se++; // Go one further, since one of the "characters" is part of a surrogate pair
                    }
                }
                return ret;
            }            break;
        }
        // Fall-through
    case 'off':
        // assumes there are no non-BMP characters;        //    if there may be such characters, then it is best to turn it on (critical in true XHTML/XML)
    default:
        if (start < 0) {
            start += end;
        }        end = typeof len === 'undefined' ? end : (len < 0 ? len + end : len + start);
    }
    return undefined; // Please Netbeans
};

var trim = function(str,charlist){
	  return str;
};
/**
 * 与php的rtrim函数用法一致
 * @from php.js
 */
var rtrim = function(str, charlist) {
return str;
};

/**
 * 与PHP的ltrim用法一致
 * @from php.js
 */
var ltrim = function(str, charlist) {
	return str;
};

/**
 * 闪动对象背景
 * @param obj
 * @returns
 * @author yangjs
 */
var flashTextarea = function(obj){
	var nums = 0;
	var flash = function(){
		if(nums > 3 ){
			return false;
		}
		if(hasClass(obj,'fb')){
			removeClass(obj,'fb');
		}else{
			addClass(obj,'fb')
		}
		setTimeout(flash, 300);
		nums ++;
	}
	flash();
	return false;
};
/**
 * 更新页面上监听的用户统计数目
 * @example
 * updateUserData('favorite_count', 1); 表示当前用户的收藏数+1
 * 页面结构例子:<strong event-node ="favorite_count" event-args ="uid={$uid}">{$_userData.favorite_count|default=0}</strong>
 * @param string key 监听的Key值
 * @param integer flag 改变的幅度值
 * @param integer uid 改变的用户ID
 * @return boolean false
 */
var updateUserData = function(key, flag, uid)
{
	// 获取所有Key监听的对象
	var countObj = M.nodes.events[key];
	// 判断数据类型
	if("undefined" === typeof countObj) {
		return false;
	}
	if("undefined" === typeof uid) {
		uid = UID;
	}
	// 修改数值
	for(var i in countObj) {
		var _wC = countObj[i];
		var args = M.getEventArgs(_wC);
		if(args.uid == uid) {
			_wC.innerHTML = parseInt(_wC.innerHTML, 10) + parseInt(flag, 10);
		}
	}

	return false;
};

/**
 * 滚动到顶端
 */
var scrolltotop={
	//startline: 鼠标向下滚动了100px后出现#topcontrol
	//scrollto: 它的值可以是整数，也可以是一个id标记。若为整数（假设为n），则滑动到距离top的n像素处；若为id标记，则滑动到该id标记所在的同等高处
	//scrollduration:滑动的速度
	//fadeduration:#topcontrol这个div的淡入淡出速度，第一个参数为淡入速度，第二个参数为淡出速度
	//controlHTML:控制向上滑动的html源码，默认为<img src="up.png" style="width:48px; height:48px" />，可以自行更改。该处的html代码会被包含在一个id标记为#topcontrol的div中。
	//controlattrs:控制#topcontrol这个div距离右下角的像素距离
	//anchorkeyword:滑动到的id标签
	/*state: isvisible:是否#topcontrol这个div为可见
			shouldvisible:是否#topcontrol这个div该出现
	*/

	setting: {startline:100, scrollto: 0, scrollduration:0, fadeduration:[500, 100]},
	controlHTML: '<a href="#top" class="top_stick">&nbsp;</a>',
	controlattrs: {offsetx:20, offsety:30},
	anchorkeyword: '#top',

	state: {isvisible:false, shouldvisible:false},

	scrollup:function(){
		if (!this.cssfixedsupport) {
			this.$control.css({opacity:0})
		};//点击后隐藏#topcontrol这个div
		var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto);
		if (typeof dest=="string" && jQuery('#'+dest).length==1) { //检查若scrollto的值是一个id标记的话
			dest=jQuery('#'+dest).offset().top;
		} else { //检查若scrollto的值是一个整数
			dest=this.setting.scrollto;
		};
		this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
	},

	keepfixed:function(){
		//获得浏览器的窗口对象
		var $window=jQuery(window);
		//获得#topcontrol这个div的x轴坐标
		var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx;
		//获得#topcontrol这个div的y轴坐标
		var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety;
		//随着滑动块的滑动#topcontrol这个div跟随着滑动
		this.$control.css({left:controlx+'px', top:controly+'px'});
	},

	togglecontrol:function(){
		//当前窗口的滑动块的高度
		var scrolltop=jQuery(window).scrollTop();
		if (!this.cssfixedsupport) {
			this.keepfixed();
		};
		//若设置了startline这个参数，则shouldvisible为true
		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false;
		//若shouldvisible为true，且!isvisible为true
		if (this.state.shouldvisible && !this.state.isvisible){
			this.$control.stop().animate({opacity:1}, this.setting.fadeduration[0]);
			this.state.isvisible=true;
		} //若shouldvisible为false，且isvisible为false
		else if (this.state.shouldvisible==false && this.state.isvisible){
			this.$control.stop().animate({opacity:0}, this.setting.fadeduration[1]);
			this.state.isvisible=false;
		}
	},

	init:function(){
		jQuery(document).ready(function($){
			var mainobj=scrolltotop;
			var iebrws=document.all;
			mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest; //not IE or IE7+ browsers in standards mode
			mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body');

			//包含#topcontrol这个div
			mainobj.$control=$('<div id="topcontrol">'+mainobj.controlHTML+'</div>')
				.css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:mainobj.controlattrs.offsety+59+"px", right:mainobj.controlattrs.offsetx, opacity:0, cursor:'pointer'})
				.css('right', '70px')
				.attr({title:L('PUBLIC_MOVE_TOP')})
				.click(function(){mainobj.scrollup(); return false;})
				.appendTo('body');

			if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') {//loose check for IE6 and below, plus whether control contains any text
				mainobj.$control.css({width:mainobj.$control.width()}); //IE6- seems to require an explicit width on a DIV containing text
			};

			mainobj.togglecontrol();

			//点击控制
			$('a[href="' + mainobj.anchorkeyword +'"]').click(function(){
				mainobj.scrollup();
				return false;
			});

			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol();
			});
		});
	}
};
scrolltotop.init();
// JavaScript双语方法
function L(key, obj) {
	if('undefined' == typeof(LANG[key])) {
		return key;
	}
	if('object' != typeof(obj)) {
		return LANG[key];
	} else {
		var r = LANG[key];
		for(var i in obj) {
			r = r.replace("{"+i+"}", obj[i]);
		}
		return r;
	}
};
/**
 * 数组去重
 * @param array arr 去重数组
 * @return array 已去重的数组
 */
var unique = function(arr)
{
	var obj = {};
	for(var i = 0, j = arr.length; i < j; i++) {
		obj[arr[i]] = true;
	}
	var data = [];
	for(var i in obj) {
		data.push[i];
	}

	return data;
};
var shortcut = function (shortcut,callback,opt) {
	//Provide a set of default options
	var default_options = {
		'type':'keydown',
		'propagate':false,
		'target':document
	}
	if(!opt) opt = default_options;
	else {
		for(var dfo in default_options) {
			if(typeof opt[dfo] == 'undefined') opt[dfo] = default_options[dfo];
		}
	}

	var ele = opt.target
	if(typeof opt.target == 'string') ele = document.getElementById(opt.target);
	var ths = this;

	//The function to be called at keypress
	var func = function(e) {
		e = e || window.event;

		//Find Which key is pressed
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		var character = String.fromCharCode(code).toLowerCase();

		var keys = shortcut.toLowerCase().split("+");
		//Key Pressed - counts the number of valid keypresses - if it is same as the number of keys, the shortcut function is invoked
		var kp = 0;
		
		//Work around for stupid Shift key bug created by using lowercase - as a result the shift+num combination was broken
		var shift_nums = {
			"`":"~",
			"1":"!",
			"2":"@",
			"3":"#",
			"4":"$",
			"5":"%",
			"6":"^",
			"7":"&",
			"8":"*",
			"9":"(",
			"0":")",
			"-":"_",
			"=":"+",
			";":":",
			"'":"\"",
			",":"<",
			".":">",
			"/":"?",
			"\\":"|"
		}
		//Special Keys - and their codes
		var special_keys = {
			'esc':27,
			'escape':27,
			'tab':9,
			'space':32,
			'return':13,
			'enter':13,
			'backspace':8,

			'scrolllock':145,
			'scroll_lock':145,
			'scroll':145,
			'capslock':20,
			'caps_lock':20,
			'caps':20,
			'numlock':144,
			'num_lock':144,
			'num':144,
			
			'pause':19,
			'break':19,
			
			'insert':45,
			'home':36,
			'delete':46,
			'end':35,
			
			'pageup':33,
			'page_up':33,
			'pu':33,

			'pagedown':34,
			'page_down':34,
			'pd':34,

			'left':37,
			'up':38,
			'right':39,
			'down':40,

			'f1':112,
			'f2':113,
			'f3':114,
			'f4':115,
			'f5':116,
			'f6':117,
			'f7':118,
			'f8':119,
			'f9':120,
			'f10':121,
			'f11':122,
			'f12':123
		}


		for(var i=0; k=keys[i],i<keys.length; i++) {
			//Modifiers
			if(k == 'ctrl' || k == 'control') {
				if(e.ctrlKey) kp++;

			} else if(k ==  'shift') {
				if(e.shiftKey) kp++;

			} else if(k == 'alt') {
					if(e.altKey) kp++;

			} else if(k.length > 1) { //If it is a special key
				if(special_keys[k] == code) kp++;

			} else { //The special keys did not match
				if(character == k) kp++;
				else {
					if(shift_nums[character] && e.shiftKey) { //Stupid Shift key bug created by using lowercase
						character = shift_nums[character]; 
						if(character == k) kp++;
					}
				}
			}
		}

		if(kp == keys.length) {
			if (lock == 0) {
				lock = 1;
				setTimeout(function(){
					lock = 0;
				}, 1500);
			} else {
				return false;
			}
			callback(e);

			if(!opt['propagate']) { //Stop the event
				//e.cancelBubble is supported by IE - this will kill the bubbling process.
				e.cancelBubble = true;
				e.returnValue = false;

				//e.stopPropagation works only in Firefox.
				if (e.stopPropagation) {
					e.stopPropagation();
					e.preventDefault();
				}
				return false;
			}
		}
	}

	//Attach the function with the event
	var lock = 0;
	if(ele.addEventListener) ele.addEventListener(opt['type'], func, false);
	else if(ele.attachEvent) ele.attachEvent('on'+opt['type'], func);
	else ele['on'+opt['type']] = func;
};
var atWho = function (obj){
	obj.atWho("@",{
        tpl:"<li id='${id}' data-value='${searchkey}' input-value='${name}'><img src='${faceurl}'  height='20' width='20' /> ${name}</li>"
        ,callback:function(query,callback) {
        	if ( keyname.text=='' ){
        		$.ajax({
                    url:U('widget/SearchUser/searchAt')
                    ,type:'GET'
                    ,dataType: "json"
                    ,success:function(res) {
                    	if ( res.data == null ){
                    		$('#at-view').hide();
                    		return;
                    	} else {
    	                    datas = $.map(res.data,function(value,i){
    	                        return {'id':value.uid,'key':value.uname+":",'name':value.uname,'faceurl':value.avatar_small,'searchkey':value.search_key}
    	                        })
                    	}
                        callback(datas)
                    }
                })
        	} else {
        		$.ajax({
                    url:U('widget/SearchUser/search')
                    ,type:'GET'
                    ,data: "type=at&key="+keyname.text
                    ,dataType: "json"
                    ,success:function(res) {
                    	if ( res.data == null ){
                    		$('#at-view').hide();
                    		return;
                    	} else {
    	                    datas = $.map(res.data,function(value,i){
    	                        return {'id':value.uid,'key':value.uname+":",'name':value.uname,'faceurl':value.avatar_small,'searchkey':value.search_key}
    	                        })
                    	}
                        callback(datas)
                    }
                })
        	}
        }
        // })
		// .atWho("#",{
        //     tpl:"<li id='${id}' data-value='${searchkey}' input-value='${name}#'>${name}</li>"
        //         ,callback:function(query,callback) {
        //     		$.ajax({
        //                 url:U('widget/TopicList/searchTopic')
        //                 ,type:'GET'
        //                 ,data: "key="+keyname.text
        //                 ,dataType: "json"
        //                 ,success:function(res) {
        //                 	if ( res == null ){
        //                 		$('#at-view').hide();
        //                 		return;
        //                 	} else {
        // 	                    datas = $.map(res,function(value,i){
        // 	                        return {'id':value.topic_id,'name':value.topic_name,'searchkey':value.topic_name}
        // 	                        })
        //                 	}
        //                     callback(datas)
        //                 }
        //             });
        //         }
         }).atWho("＃",{
             tpl:"<li id='${id}' data-value='${searchkey}' input-value='${name}＃'>${name}</li>"
                 ,callback:function(query,callback) {
             		$.ajax({
                         url:U('widget/TopicList/searchTopic')
                         ,type:'GET'
                         ,data: "key="+keyname.text
                         ,dataType: "json"
                         ,success:function(res) {
                         	if ( res == null ){
                         		$('#at-view').hide();
                         		return;
                         	} else {
         	                    datas = $.map(res,function(value,i){
         	                        return {'id':value.topic_id,'name':value.topic_name,'searchkey':value.topic_name}
         	                        })
                         	}
                             callback(datas)
                         }
                     });
                 }
          });
};
$(function(){
    $.fn.extend({
        inputToEnd: function(myValue){
            var $t=$(this)[0];
            if (document.selection) {
                this.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            } else if ($t.selectionStart || $t.selectionStart == '0') {
                var startPos = $t.selectionStart;
                var endPos = $t.selectionEnd;
                var scrollTop = $t.scrollTop;
                $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                this.focus();
                $t.selectionStart = startPos + myValue.length;
                $t.selectionEnd = startPos + myValue.length;
                $t.scrollTop = scrollTop;
            } else {
                this.value = myValue;
                this.focus();
            }
        },
        inputToDIV: function(myValue){
        	
            var obj=$(this)[0];

			obj.focus();

			var selection = window.getSelection ? window.getSelection() : document.selection;

			var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);

			if (!window.getSelection){
				
				var selection = window.getSelection ? window.getSelection() : document.selection;

				var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);

				range.pasteHTML(myValue);

				range.collapse(false);

				range.select();

			}else{

				range.collapse(false);

				var hasR = range.createContextualFragment(myValue);

				var hasR_lastChild = hasR.lastChild;

				while (hasR_lastChild && hasR_lastChild.nodeName.toLowerCase() == "br" && hasR_lastChild.previousSibling && hasR_lastChild.previousSibling.nodeName.toLowerCase() == "br") {

					var e = hasR_lastChild;

					hasR_lastChild = hasR_lastChild.previousSibling;

					hasR.removeChild(e)

				}                                

				range.insertNode(hasR);

				if (hasR_lastChild) {

					range.setEndAfter(hasR_lastChild);

					range.setStartAfter(hasR_lastChild);

				}

				selection.removeAllRanges();

				selection.addRange(range);

			}

        }
    });
});
/**
 * 去掉字符串中的HTML标签
 * @param string str 需要处理的字符串
 * @return string 已去掉HTML的字符串
 */
var removeHTMLTag = function(str)
{
	str = str.replace(/<\/?[^>]*>/g,'');
	return str;
};
var removeScriptHTMLTag = function(str) { 
	return str.replace(/<(script|link|style|iframe)(.|\n)*\/\1>\s*/ig,""); 
} 
var quickLogin = function (){
	ui.box.load(U('public/Passport/quickLogin'),'快速登录');
};
var quickLogin = function (url){
	ui.box.load(U('public/Passport/quickLogin')+'&url='+url,'快速登录');
};
/* 图片切换 */
(function(){
var fSwitchPic = function( oPicSection, nInterval ) {
	try {
		this.dPicSection = "string" === typeof oPicSection ? document.getElementById( oPicSection ) : oPicSection;
		this.nInterval = nInterval > 0 ? nInterval : 2000;
		this.dPicList  = this.dPicSection.getElementsByTagName( "div" );
		this.nPicNum   = this.dPicList.length;
	} catch( e ) {
		return e;
	}
	this.nCurrentPic = this.nPicNum - 1;
	this.nNextPic = 0;
	this.fInitPicList();

	this.dPicNav = this.dPicSection.getElementsByTagName( "ul" )[0];
	this.fInitPicNav();

	clearTimeout( this.oTimer );
	this.fSwitch();
	this.fStart();
};

fSwitchPic.prototype = {
	constructor: fSwitchPic,
	fInitPicList: function() {
		var oSwitchPic = this;
		this.dPicSection.onmouseover = function() {
			oSwitchPic.fPause();
		};
		this.dPicSection.onmouseout  = function() {
			oSwitchPic.fGoon();
		};
	},
	fInitPicNav: function() {
		var oSwitchPic = this,
			sPicNav = '',
			nPicNum = this.nPicNum;

		for ( var i = 0; i < nPicNum; i ++ ) {
			//sPicNav += '<li style="list-style-type:none;"><a href="javascript:;" target="_self">' + ( i + 1 ) + '</a></li>';
			sPicNav += '<li style="list-style-type:none;"><a href="javascript:;" target="_self"></a></li>';//不显示数字
		}
		this.dPicNav.innerHTML = sPicNav;

		// 追加属性和Event
		var dPicNavMenu = this.dPicNav.getElementsByTagName( "a" ),
		    nL = dPicNavMenu.length;

		while ( nL -- > 0 ) {
			dPicNavMenu[nL].nIndex = nL;
			dPicNavMenu[nL].onclick     = function() {
				oSwitchPic.fGoto( this.nIndex );
				return false;
			};
		}
		this.dPicNavMenu = dPicNavMenu;
	},
	fSwitch: function() {
		if ( this.nPicNum <= 1 ){
			return;
		}
		var nCurrentPic = this.nCurrentPic,
			nNextPic    = this.nNextPic;
		$(this.dPicList[nNextPic]).stop().fadeIn(500);
		this.dPicList[nCurrentPic].style.display = "none";

		this.dPicNavMenu[nNextPic].className = "sel";
		this.dPicNavMenu[nCurrentPic].className = "";

		this.nCurrentPic = nNextPic;
		this.nNextPic = ( nNextPic < this.nPicNum - 1 ) ? ( nNextPic + 1 ) : 0;
	},
	fStart: function() {
		var oSwitchPic = this;
		this.oTimer = setTimeout( function() {
			oSwitchPic.fSwitch();
			oSwitchPic.fStart();
		}, this.nInterval );
	},
	fPause: function() {
		clearTimeout( this.oTimer );
	},
	fGoon: function() {
		clearTimeout( this.oTimer );
		this.fStart();
	},
	fGoto: function( nIndex ) {
		var nIndex = parseInt( nIndex );
		if ( nIndex == this.nCurrentPic ) {
			return false;
		}
		
		clearTimeout( this.oTimer );
		this.nNextPic = nIndex;
		this.fSwitch();
	}
};

window.fSwitchPic = fSwitchPic;

})();

var switchVideo = function(id,app_row_id,type,host,flashvar,flashimg, width, height){

	var repost_str = '';
	if(app_row_id){
		repost_str = app_row_id+'_';
	}

	if( type == 'close' ){
		$("#video_mini_show_"+repost_str+id).show().next().show();
		$("#video_content_"+repost_str+id).html( '' );
		$("#video_show_"+repost_str+id).hide();
	}else{
		$.post(U('public/Feed/video_exist'),{id:id,flashvar:flashvar,host:host},function(res){
			if(res.status == 1){
				if(host){
					$("div[id*='video_content_']").html( '' );
					$("div[id*='video_show_']").hide();
					$("div[id*='video_mini_show_']").show();
					$("#video_mini_show_"+repost_str+id).hide().next().hide();
					$("#video_content_"+repost_str+id).html( showFlash_online(host,flashvar, width) );
					$("#video_show_"+repost_str+id).show();
				}else{
					$("div[id*='video_content_']").html( '' );
					$("div[id*='video_show_']").hide();
					$("div[id*='video_mini_show_']").show();
					$("#video_mini_show_"+repost_str+id).hide().next().hide();
					document.getElementById("video_content_"+repost_str+id).innerHTML = showFlash_upload(host,flashvar,flashimg, width, height);
					$("#video_show_"+repost_str+id).show();
				}
			}else{
				ui.error(res.msg);
				return false;
			}
		},'json');
	}
}

//显示视频
var showFlash_online = function ( host, flashvar, width) {
	typeof width == 'undefined' && (width = 554);
	var height = Math.round(width/4*3.1);
	if(host=='youtube.com'){
		var flashHtml = '<iframe width="'+width+'" height="'+height+'"  src="http://www.youtube.com/embed/'+flashvar+'" frameborder="0" allowfullscreen></iframe>';
	}else{
		var flashHtml = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+width+'" height="'+height+'" autostart="true">'
	        + '<param value="transparent" name="wmode"/>'
			+ '<param value="'+flashvar+'" name="movie" />'
			+ '<param value="autostart" name="true" />'
			+ '<embed src="'+flashvar+'" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash" style="width:'+width+'px;*width:'+width+'px;height:'+height+'px;" autostart="true"></embed>'
			+ '</object>';
		}
	return flashHtml;
}
var showFlash_upload = function ( host, flashvar, flashimg, width, height) {
	width  = width || 554;
	height = height || width;
	if(host=='youtube.com'){
		var flashHtml = '<iframe width="'+width+'" height="'+height+'"  src="http://www.youtube.com/embed/'+flashvar+'" frameborder="0" allowfullscreen></iframe>';
	}else{
	 	var swf_url = THEME_URL + '/image/flowplayer-3.2.5.swf';
		var flashHtml = ''
	 	    + '<video id="example_video_1" class="video-js" width="'+width+'" height="'+height+'" controls="controls" preload="auto" autoplay="autoplay" poster="'+flashimg+'">'
	        + '<source src="'+flashvar+'" type="video/mp4" />'
	        + '<object id="flash_fallback_1" class="vjs-flash-fallback" width="'+width+'" height="'+height+'" type="application/x-shockwave-flash" data="'+swf_url+'">'
	        + '  <param name="movie" value="'+swf_url+'" />'
	        + '  <param name="allowfullscreen" value="true" />'
	        + '  <param name="flashvars" value=\'config={"playlist":["'+flashimg+'", {"url": "'+flashvar+'","autoPlay":true,"autoBuffering":true}]}\' />'
	        + '  <img src="'+flashimg+'" width="'+width+'" height="'+height+'" alt="Poster Image" title="No video playback capabilities." />'
	        + '</object>'
	        + '</video>';
	}
	return flashHtml;
}

//过滤html标签
function strip_tags (input, allowed) {    
allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

//旋转图片
var revolving = function(type, id) {
  var img = $("#image_index_"+id);
  img.rotate(type);
}


//获取url参数
var GetQueryString = function (name) { 
  var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); 
  var r = window.location.search.substr(1).match(reg); 
  if (r!=null) return unescape(r[2]); return null; 
}


$(function() {
	$.fn.rotate = function(p) {

    var img = $(this)[0],
    n = img.getAttribute('step');
    // 保存图片大小数据
    if (!this.data('width') && !$(this).data('height')) {
        this.data('width', img.width);
        this.data('height', img.height);
    };
    this.data('maxWidth', img.getAttribute('maxWidth'))

    if (n == null) n = 0;
    if (p == 'left') { (n == 0) ? n = 3 : n--;
    } else if (p == 'right') { (n == 3) ? n = 0 : n++;
    };
    img.setAttribute('step', n);

    // IE浏览器使用滤镜旋转
    if (document.all) {
        if (this.data('height') > this.data('maxWidth') && (n == 1 || n == 3)) {
            if (!this.data('zoomheight')) {
                this.data('zoomwidth', this.data('maxWidth'));
                this.data('zoomheight', (this.data('maxWidth') / this.data('height')) * this.data('width'));
            }
            img.height = this.data('zoomwidth');
            img.width = this.data('zoomheight');

        } else {
            img.height = this.data('height');
            img.width = this.data('width');
        }

        img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation=' + n + ')';
        // IE8高度设置
        if ($.browser.version == 8) {
            switch (n) {
            case 0:
                this.parent().height('');
                //this.height(this.data('height'));
                break;
            case 1:
                this.parent().height(this.data('width') + 10);
                //this.height(this.data('width'));
                break;
            case 2:
                this.parent().height('');
                //this.height(this.data('height'));
                break;
            case 3:
                this.parent().height(this.data('width') + 10);
                //this.height(this.data('width'));
                break;
            };
        };
        // 对现代浏览器写入HTML5的元素进行旋转： canvas
    } else {
        var c = this.next('canvas')[0];
        if (this.next('canvas').length == 0) {
            this.css({
                'visibility': 'hidden',
                'position': 'absolute'
            });
            c = document.createElement('canvas');
            c.setAttribute('class', 'maxImg canvas');
            img.parentNode.appendChild(c);
        }
        var canvasContext = c.getContext('2d');
        switch (n) {
        default:
        case 0:
            img.setAttribute('height', this.data('height'));
            img.setAttribute('width', this.data('width'));
            c.setAttribute('width', img.width);
            c.setAttribute('height', img.height);
            canvasContext.rotate(0 * Math.PI / 180);
            canvasContext.drawImage(img, 0, 0);
            break;
        case 1:
            if (img.height > this.data('maxWidth')) {
                h = this.data('maxWidth');
                w = (this.data('maxWidth') / img.height) * img.width;
            } else {
                h = this.data('height');
                w = this.data('width');
            }
            c.setAttribute('width', h);
            c.setAttribute('height', w);
            canvasContext.rotate(90 * Math.PI / 180);
            canvasContext.drawImage(img, 0, -h, w, h);
            break;
        case 2:
            img.setAttribute('height', this.data('height'));
            img.setAttribute('width', this.data('width'));
            c.setAttribute('width', img.width);
            c.setAttribute('height', img.height);
            canvasContext.rotate(180 * Math.PI / 180);
            canvasContext.drawImage(img, -img.width, -img.height);
            break;
        case 3:
            if (img.height > this.data('maxWidth')) {
                h = this.data('maxWidth');
                w = (this.data('maxWidth') / img.height) * img.width;
            } else {
                h = this.data('height');
                w = this.data('width');
            }
            c.setAttribute('width', h);
            c.setAttribute('height', w);
            canvasContext.rotate(270 * Math.PI / 180);
            canvasContext.drawImage(img, -w, 0, w, h);
            break;
        };
    };
};
});
