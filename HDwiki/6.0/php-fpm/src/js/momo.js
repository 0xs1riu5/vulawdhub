/*
 * hudong摸摸(hdmomo) v2.0β - New Javascript Editor
 *
 * Copyright (c) 2008 互动在线（北京）科技有限公司(baike.com) 
 * 
 * @Date: 2008-12-18
 *
 * @Author: wolf
 */
(function(){
    var momo = window.momo = function(){
    };
    momo.e = function(){
        var target = arguments[0] ||
        {}, i = 1, length = arguments.length, deep = false, options;
        if (target.constructor == Boolean) {
            deep = target;
            target = arguments[1] ||
            {};
            i = 2;
        }
        if (typeof target != "object" && typeof target != "function") 
            target = {};
        if (length == i) {
            target = this;
            --i;
        }
        for (; i < length; i++) 
            if ((options = arguments[i]) != null) 
                for (var name in options) {
                    var src = target[name], copy = options[name];
                    if (target === copy) 
                        continue;
                    if (deep && copy && typeof copy == "object" && !copy.nodeType) 
                        target[name] = momo.e(deep, src || (copy.length != null ? [] : {}), copy);
                    else 
                        if (copy !== undefined) 
                            target[name] = copy;
                }
        return target;
    };
	
    momo.tag = {
        init: function(){
            try {
                $tag = "a";
                $class = "innerlink";
                var tags = document.getElementsByTagName($tag);
                for (var i = 0; i < tags.length; i++) {
                    if (tags[i].className == $class) {
                        tags[i].onmouseover = function(){
                            if (momo.tag.flag) {
                                p = momo.tag.position(this);
                                momo.div.display(this.innerHTML);
                                momo.div.position('tag', p.left, p.top);
                            }
                        };
                    }
                }
            } 
            catch (ex) {
                alert("tag : " + ex.description);
            }
        },
        position: function($tag){
            var vleft = 30;
            var vtop = 30;
            element = $tag;
            while (element != null && element != document.body.offsetParent) {
                vleft += element.offsetLeft;
                vtop += element.offsetTop;
                if (document.all) {
                    parseInt(element.currentStyle.borderLeftWidth) > 0 ? vleft += parseInt(element.currentStyle.borderLeftWidth) : "";
                    parseInt(element.currentStyle.borderTopWidth) > 0 ? vtop += parseInt(element.currentStyle.borderTopWidth) : "";
                }
                element = element.offsetParent;
            }
            return {
                top: vtop,
                left: vleft
            }
        },
        flag : 1
    }
    momo.mouse = {
    	flag : 1,
        down: function(){
            momo.tag.flag = 0;
        },
        up: function(ev){
        	if(momo.mouse.flag){
	            var word = momo.common.selection();
	            word = word.replace(/^\s*|\s*$/g, "");
	            if (typeof word == 'string' && word && word.length < momo.config.keylength) {
	                momo.div.display(word);
	                momo.div.position('mouse', ev);
	            }
	            momo.tag.flag = 1;
	        }
        },
        position: function(ev){
            try {
                if (ev.pageX || ev.pageY) {
                    return {
                        x: ev.pageX,
                        y: ev.pageY
                    };
                }
                return {
                    x: ev.clientX + document.body.scrollLeft - document.body.clientLeft,
                    y: ev.clientY + document.body.scrollTop - document.body.clientTop
                };
            } 
            catch (ex) {
                alert("position : " + ex.description);
            }
        }
    };
    momo.div = {
    	momodiv : document.getElementById('momo_div'),
        position: function($type, ev){
            var momodiv = document.getElementById('momo_div');
            switch ($type) {
                case 'mouse':
                    var p = momo.mouse.position(ev);
                    var left = p.x + 10;
                    var top = p.y + 10;
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
            if (button_y >= screenheight) {
                retop = top - momodiv.offsetHeight;
                if (retop < 0) {
                    retop = top;
                }
            }
            if (button_x >= screenwidth) {
                releft = left - momodiv.offsetWidth;
                if (releft < 0) {
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
            	momo.event.remove(momo.config.area, "mousedown", momo.div.undisplay);
            	momo.mouse.flag = 0;
            };
            momodiv.onmouseout = function(){
            	momo.event.add(momo.config.area, "mousedown", momo.div.undisplay);
            	momo.mouse.flag = 1;
            };
            
            document.getElementById("momoFrame").src = 'about:blank';
            setTimeout(function(){
                document.getElementById("momoFrame").src = momo.config.wikiurl + '/index.php?doc-summary-' + encodeURI($word);
            }, 200);
            try {
                //var iframeWin = window.frames['momoFrame'];
                //iframeWin.document.open();
                //iframeWin.document.write('<html><body> <span style="color:green;font-weight:bold;">'+$word+'</span> <br /><br /> <img src="http://www.baike.com/hdmomo/img/loading.gif" /> <p style="font-size:12px;">&#27491;&#22312;&#36733;&#20837;&#20869;&#23481;...</p> </body></html>');
                //iframeWin.document.close();
            } 
            catch (ex) {
                return;
            }
        },
        undisplay: function(){
            var div = document.getElementById('momo_div');
            if (div.style.display == "block") {
                div.style.display = "none";
            }
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
                }
                else {
                    document.onmousemove = "";
                    document.onmouseup = "";
                }
            }
            if (document.addEventListener) {
                document.addEventListener("mousemove", startDrag, true);
                document.addEventListener("mouseup", stopDrag, true);
            }
            else {
                document.onmousemove = startDrag;
                document.onmouseup = stopDrag;
            }
            
        }
    }
    momo.event = {
        init: function(){
            try {
                this.add(momo.config.area, "mouseup", momo.mouse.up);
                this.add(momo.config.area, "mousedown", momo.mouse.down);
            } 
            catch (ex) {
                alert("init : " + ex.description);
            }
        },
        add: function($el, $ev, $func){
            try {
                if (document.all) {
                    $el.attachEvent("on" + $ev, $func);
                }
                else {
                    $el.addEventListener($ev, $func, true);
                }
            } 
            catch (ex) {
                alert("add : " + ex.description);
            }
        },
        remove: function($el, $ev, $func){
            if (document.all) {
                $el.detachEvent("on" + $ev, $func);
            }
            else {
                $el.removeEventListener($ev, $func, true);
            }
        },
        stop: function(ev){
            var evt = (document.all) ? window.event : ev;
            if (document.all) {
                evt.cancelBubble = true;
                evt.returnValue = false;
            }
            else {
                evt.preventDefault();
                evt.stopPropagation();
            }
        }
    };
    momo.config = {
        wikiurl: "http://kaiyuan.hudong.com",
        areaid: '',
        area: document.getElementById(this.elementid) || document,
        divstr: '<p style="margin: 0pt; padding: 0pt 0pt 5px; width: 315px; float: left;">' +
        '<span style="float: left; font-size: 12px; margin-left: 5px;">' +
        '<img align="absmiddle" src="http://www.baike.com/hdmomo/img/logo.png"/>互动摸摸' +
        '</span>' +
        '<span style="float: right; margin-right: 5px">' +
        '<img  border="0" src="http://www.baike.com/images/momo/guanbi_normal.gif" onclick = "momo.div.undisplay();"/>' +
        '</span>' +
        '</p>' +
        '<div style="padding: 5px; margin: 0pt; padding: 5px; float: left; width: 305px; background-color: #FFFFFF;">' +
        '<iframe width="100%" scrolling="no" height="145" frameborder="0" src="" name="momoFrame" id="momoFrame"/>' +
        '</div><div align="center" style="margin: 0pt; padding: 0pt 0pt 5px; width: 290px; float: left;">' +
        '<span style=" font-size: 12px;">©2003-2008<a target="_blank" href="http://www.baike.com/"><b><font color="#2ea8ed">baike.com</font></b></a>互动百科</span>' +
        '</div>',
        
        keylength: 16
    };
    momo.common = {
        selection: function(){
            try {
                if (window.getSelection) {
                    return window.getSelection().toString();
                }
                else 
                    if (document.getSelection) {
                        return document.getSelection();
                    }
                    else 
                        if (document.selection) {
                            return document.selection.createRange().text;
                        }
                        else 
                            return '';
            } 
            catch (ex) {
                alert(ex.message);
            }
        },
        write: function($str){
            try {
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
            catch (ex) {
                alert(ex.message);
            }
        }
    };
    momo.e({
        start: function(){
            momo.common.write(momo.config.divstr);
            momo.event.init();
            momo.tag.init();
        }
    });
    window.onload = momo.start;
})();
