/*
 * pureUI
 * */
/* Copyright (c) 2010-2013 Marcus Westin */
this.JSON||(this.JSON={}),function(){function f(e){return e<10?"0"+e:e}function quote(e){return escapable.lastIndex=0,escapable.test(e)?'"'+e.replace(escapable,function(e){var t=meta[e];return typeof t=="string"?t:"\\u"+("0000"+e.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+e+'"'}function str(e,t){var n,r,i,s,o=gap,u,a=t[e];a&&typeof a=="object"&&typeof a.toJSON=="function"&&(a=a.toJSON(e)),typeof rep=="function"&&(a=rep.call(t,e,a));switch(typeof a){case"string":return quote(a);case"number":return isFinite(a)?String(a):"null";case"boolean":case"null":return String(a);case"object":if(!a)return"null";gap+=indent,u=[];if(Object.prototype.toString.apply(a)==="[object Array]"){s=a.length;for(n=0;n<s;n+=1)u[n]=str(n,a)||"null";return i=u.length===0?"[]":gap?"[\n"+gap+u.join(",\n"+gap)+"\n"+o+"]":"["+u.join(",")+"]",gap=o,i}if(rep&&typeof rep=="object"){s=rep.length;for(n=0;n<s;n+=1)r=rep[n],typeof r=="string"&&(i=str(r,a),i&&u.push(quote(r)+(gap?": ":":")+i))}else for(r in a)Object.hasOwnProperty.call(a,r)&&(i=str(r,a),i&&u.push(quote(r)+(gap?": ":":")+i));return i=u.length===0?"{}":gap?"{\n"+gap+u.join(",\n"+gap)+"\n"+o+"}":"{"+u.join(",")+"}",gap=o,i}}typeof Date.prototype.toJSON!="function"&&(Date.prototype.toJSON=function(e){return isFinite(this.valueOf())?this.getUTCFullYear()+"-"+f(this.getUTCMonth()+1)+"-"+f(this.getUTCDate())+"T"+f(this.getUTCHours())+":"+f(this.getUTCMinutes())+":"+f(this.getUTCSeconds())+"Z":null},String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(e){return this.valueOf()});var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={"\b":"\\b","	":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},rep;typeof JSON.stringify!="function"&&(JSON.stringify=function(e,t,n){var r;gap="",indent="";if(typeof n=="number")for(r=0;r<n;r+=1)indent+=" ";else typeof n=="string"&&(indent=n);rep=t;if(!t||typeof t=="function"||typeof t=="object"&&typeof t.length=="number")return str("",{"":e});throw new Error("JSON.stringify")}),typeof JSON.parse!="function"&&(JSON.parse=function(text,reviver){function walk(e,t){var n,r,i=e[t];if(i&&typeof i=="object")for(n in i)Object.hasOwnProperty.call(i,n)&&(r=walk(i,n),r!==undefined?i[n]=r:delete i[n]);return reviver.call(e,t,i)}var j;text=String(text),cx.lastIndex=0,cx.test(text)&&(text=text.replace(cx,function(e){return"\\u"+("0000"+e.charCodeAt(0).toString(16)).slice(-4)}));if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,"")))return j=eval("("+text+")"),typeof reviver=="function"?walk({"":j},""):j;throw new SyntaxError("JSON.parse")})}(),function(e){function o(){try{return r in e&&e[r]}catch(t){return!1}}var t={},n=e.document,r="localStorage",i="script",s;t.disabled=!1,t.set=function(e,t){},t.get=function(e){},t.remove=function(e){},t.clear=function(){},t.transact=function(e,n,r){var i=t.get(e);r==null&&(r=n,n=null),typeof i=="undefined"&&(i=n||{}),r(i),t.set(e,i)},t.getAll=function(){},t.forEach=function(){},t.serialize=function(e){return JSON.stringify(e)},t.deserialize=function(e){if(typeof e!="string")return undefined;try{return JSON.parse(e)}catch(t){return e||undefined}};if(o())s=e[r],t.set=function(e,n){return n===undefined?t.remove(e):(s.setItem(e,t.serialize(n)),n)},t.get=function(e){return t.deserialize(s.getItem(e))},t.remove=function(e){s.removeItem(e)},t.clear=function(){s.clear()},t.getAll=function(){var e={};return t.forEach(function(t,n){e[t]=n}),e},t.forEach=function(e){for(var n=0;n<s.length;n++){var r=s.key(n);e(r,t.get(r))}};else if(n.documentElement.addBehavior){var u,a;try{a=new ActiveXObject("htmlfile"),a.open(),a.write("<"+i+">document.w=window</"+i+'><iframe src="/favicon.ico"></iframe>'),a.close(),u=a.w.frames[0].document,s=u.createElement("div")}catch(f){s=n.createElement("div"),u=n.body}function l(e){return function(){var n=Array.prototype.slice.call(arguments,0);n.unshift(s),u.appendChild(s),s.addBehavior("#default#userData"),s.load(r);var i=e.apply(t,n);return u.removeChild(s),i}}var c=new RegExp("[!\"#$%&'()*+,/\\\\:;<=>?@[\\]^`{|}~]","g");function h(e){return e.replace(/^d/,"___$&").replace(c,"___")}t.set=l(function(e,n,i){return n=h(n),i===undefined?t.remove(n):(e.setAttribute(n,t.serialize(i)),e.save(r),i)}),t.get=l(function(e,n){return n=h(n),t.deserialize(e.getAttribute(n))}),t.remove=l(function(e,t){t=h(t),e.removeAttribute(t),e.save(r)}),t.clear=l(function(e){var t=e.XMLDocument.documentElement.attributes;e.load(r);for(var n=0,i;i=t[n];n++)e.removeAttribute(i.name);e.save(r)}),t.getAll=function(e){var n={};return t.forEach(function(e,t){n[e]=t}),n},t.forEach=l(function(e,n){var r=e.XMLDocument.documentElement.attributes;for(var i=0,s;s=r[i];++i)n(s.name,t.deserialize(e.getAttribute(s.name)))})}try{var p="__storejs__";t.set(p,p),t.get(p)!=p&&(t.disabled=!0),t.remove(p)}catch(f){t.disabled=!0}t.enabled=!t.disabled,typeof module!="undefined"&&module.exports&&this.module!==module?module.exports=t:typeof define=="function"&&define.amd?define(t):e.store=t}(Function("return this")());
 //public
Array.prototype.inArray = function (value)
// Returns true if the passed value is found in the
// array.  Returns false if it is not.
{
    var i;
    for (i=0; i < this.length; i++) {
        // Matches identical (===), not just similar (==).
        if (this[i] === value) {
            return true;
        }
    }
    return false;
};
$.ui = {
    cache:{
        hashContentDivSelected:false,
        activeContentDiv:'',
        defaultHash:'',
        historyLength:0,
        history:new Array(),
        blurSetTimeout:0
    },
    show:function(el,transformation){
        /**
         * run active panel's unload function
         * */
        var activePanel = $($('#'+$.ui.cache.history[$.ui.cache.history.length-1]).get(0));
        if(activePanel.data('unload')!==null){
            eval(activePanel.data('unload'));
        }
        /*
        * hide active content div and load new content div
        * */
        var activeContentDiv = $($('#'+$.ui.cache.activeContentDiv).get(0));
        if(activeContentDiv.data('rememberScroll')===true){
            activeContentDiv.data('scrolly',String($.ui.cache.scrolly));
        }
        activeContentDiv.removeClass('active');
        activeContentDiv.css({'height': '100%','overflow-y': 'hidden','display':'none'});
        el.addClass('active');
        el.css({'height': 'auto','overflow-y': 'auto','display':'block'});
        if(el.data('scrolly')!==null && el.data('rememberScroll')===true){
            window.setTimeout(function(){
                window.scrollTo(0,el.data('scrolly'));
            },10);
        }else{
            window.setTimeout(function(){
                window.scrollTo(0,0);
            },10);
        }
        $('#layout,#menu,#menuLink').removeClass('active');
//        $('#main').removeClass('blur');
        //run data-defer
        if(el.data('defer')!==null){
            if(el.data('defered')===null){
                var deferUrl = String(el.data('defer'));
                var success = function(data){
                    el.html(data);
                    el.removeClass('defer-loading');
                    if(el.data('load')!==null && el.data('loaded')===null){
                        setTimeout(function(){
                            //run data-load func
                            eval(el.data('load'));
                            el.data('loaded','true');
                        },200);
                    }
                }
                el.addClass('defer-loading');
                $.get(
                    deferUrl,
                    success,
                    function(){},
                    10000
                );
                el.data('defered',true);
            }
        }else{
            if(el.data('load')!==null /*&& el.data('loaded')===null*/){
                setTimeout(function(){
                    //run data-load func
                    eval(el.data('load'));
                    el.data('loaded','true');
                },200);
            }
        }
        //history
        $.ui.cache.history.push(el.attr('id'));
        //run header
        if(el.find('header').length===1 && el.data('header')!=='none'){
            $('#header').html($(el.find('header')).html());
            window.document.title = el.data('title');
            $('.hasback').html(el.data('title'));
        }else{
            $('#header').html($.ui.cache.headerHtml);
            if($.ui.cache.history[$.ui.cache.history.length-1]===$.ui.cache.history[$.ui.cache.history.length-3] && $.ui.cache.history[0]===el.attr('id')){
                $('#back').hide();
                $('#back').prependTo('#header-buttons');
                $('#header>h1').removeClass('hasback');
            }else if($.ui.cache.history.length===1){
                $('#back').hide();
                $('#back').prependTo('#header-buttons');
                $('#header>h1').removeClass('hasback');
            }else{
                $('#back').show();
                $('#back').prependTo('#header-buttons');
                $('#header>h1').addClass('hasback');
            }
            if(el.data('title')){
                $('#header>h1').html(el.data('title'));
                $('.hasback').html(el.data('title'));
                window.document.title = el.data('title');
            }
        }
        //run footer
        if(el.find('footer').length===1 && el.data('footer')!=='none'){
            $('#footer').html($(el.find('footer')).html());
            $('#footer').show();
        }else if(el.find('footer').length===0 && el.data('footer')!=='none' && el.data('footer')!==null){
            if($('#content').find('footer#'+String(el.data('footer'))).length===1){
                $('#footer').html($($('#content').find('footer#'+String(el.data('footer')))).html());
                $('#footer').show();
            }else{
                $('#footer').html('');
                $('#footer').hide();
            }
        }else{
            $('#footer').html('');
            $('#footer').hide();
        }
        //run menu
        if(el.find('menu').length===1 && el.data('menu')!=='none'){
            $('#menu').html($(el.find('menu')).html());
        }else if(el.find('menu').length===0 && el.data('menu')!=='none' && el.data('menu')!==null){
            if($('#content').find('menu#'+String(el.data('menu'))).length===1){
                $('#menu').html($($('#content').find('menu#'+String(el.data('menu')))).html());
            }else{
                $('#menu').html($.ui.cache.menuHtml);
            }
        }else if(el.data('menu')==='default'){
            $('#menu').html($.ui.cache.menuHtml);
        }else{
            //do nothing
        }
        //run custom
        if(el.find('custom').length===1 && el.data('custom')!=='none'){
            $('#custom').html($(el.find('custom')).html());
        }else if(el.find('custom').length===0 && el.data('custom')!=='none' && el.data('custom')!==null){
            if($('#content').find('custom#'+String(el.data('custom'))).length===1){
                $('#custom').html($($('#content').find('custom#'+String(el.data('custom')))).html());
            }else{
                $('#custom').html('');
            }
        }else if(el.data('custom')==='default'){
            $('#menu').html('');
        }else{
            //do nothing
        }
        this.cache.activeContentDiv=el.attr('id');
     },
    loadContentDiv:function(item){
        if(typeof item === 'object'){
            item.show();
            $.ui.show(item);
//            $('#'+this.cache.activeContentDiv).hide();

        }else if(typeof item === 'string' && $('#'+item).length>0){
            $($('#'+item).get(0)).show();
            $.ui.show($($('#'+item).get(0)));
//            $('#'+this.cache.activeContentDiv).hide();
//            this.cache.activeContentDiv=item;
        }else{
            console.log('Error in $.ui.loadContentDiv: no element found to load:(');
        }
    },
    loadDiv:function(hash){//afui func
        window.location.hash = hash;
    },
    showDeafultContentDiv:function(){
        /*
         * show default content div
         * */
        var hash = window.location.hash;
        hash = hash.replace(/^\#/,'');
        var contentItemArray = new Array();
        var contentDiv = $('#content>div');
        for(var i=0;i<contentDiv.length;i++){
            var item = $(contentDiv[i]);
            contentItemArray.push(item.attr('id'));
        }
        if(contentItemArray.inArray(hash)){
            $.ui.loadContentDiv(hash);
            $.ui.cache.defaultHash=hash;
            //change menu hightlight
            if($('#menu>div>ul>li>a[href="#'+hash+'"]').length>0){
                $('#menu>div>ul>li').removeClass('pure-menu-selected');
                $($($('#menu>div>ul>li>a[href="#'+hash+'"]').get(0)).parent('li')).addClass('pure-menu-selected');
            }
        }else{
            for(var i=0;i<contentDiv.length;i++){
                var item = $(contentDiv[i]);
                if(item.data('selected')===true){
                    $.ui.cache.hashContentDivSelected=true;
                    $.ui.loadContentDiv(item);
                    $.ui.cache.defaultHash=item.attr('id');
                    break;
                }
            }
            if(!$.ui.cache.hashContentDivSelected){
                $.ui.loadContentDiv($(contentDiv[0]));
                $.ui.cache.defaultHash=$(contentDiv[0]).attr('id');
            }
        }
        /**
         * save element which has selected attr into cache.originalSelected
         * */
        for(var i=0;i<contentDiv.length;i++){
            var item = $(contentDiv[i]);
            if(item.data('selected')===true){
                $.ui.cache.originalSelected=item.attr('id');
                break;
            }
        }
        if($.ui.cache.originalSelected===undefined){
            $.ui.cache.originalSelected = $(contentDiv[0]).attr('id');
        }
     },
    hashChange:function(){
        var hash = window.location.hash;
        hash = hash.replace(/^\#/,'');
        if($.ui.cache.activeContentDiv !== hash){
            var contentItemArray = new Array();
            var contentDiv = $('#content>div');
            for(var i=0;i<contentDiv.length;i++){
                var item = $(contentDiv[i]);
                contentItemArray.push(item.attr('id'));
            }
            if(contentItemArray.inArray(hash)){
                /**
                 * load new panel
                 * */
                $.ui.loadContentDiv(hash);
                //change menu hightlight
                if($('#menu>div>ul>li>a[href="#'+hash+'"]').length>0){
                    $('#menu>div>ul>li').removeClass('pure-menu-selected');
                    $($($('#menu>div>ul>li>a[href="#'+hash+'"]').get(0)).parent('li')).addClass('pure-menu-selected');
                }
            }else if(hash===''){
                $.ui.loadContentDiv($.ui.cache.defaultHash);
            }
        }
    },
    showMask:function(text,autohide){
        if(text && typeof text === 'string'){
            $('#pure-mask').remove();
            $('body').append('<div id="pure-mask">'+text+'</div>');
            $('#pure-shadow').addClass('mask-shadow');
        }
        if(autohide===true){
            setInterval(function(){
                $.ui.hideMask();
            },2000);
        }
    },
    hideMask:function(){
        $('#pure-mask').remove();
        $('#pure-shadow').removeClass('mask-shadow');
    },
    goBack:function(){
        window.history.back();
        $.ui.cache.historyLength-=2;
    },
    doScroll:function(){
        setTimeout(function(){
            window.scrollTo(0,$.ui.cache.scrolly)
        },200);
    },
    loadImage:function (url, callback) {
        var img = new Image(); //创建一个Image对象，实现图片的预下载
        img.src = url;

        if (img.complete) { // 如果图片已经存在于浏览器缓存，直接调用回调函数
            callback.call(img);
            return; // 直接返回，不用再处理onload事件
        }

        img.onload = function () { //图片下载完毕时异步调用callback函数。
            callback.call(img);//将回调函数的this替换为Image对象
        };
    },
    css3prefix:['-webkit-','-moz-','-ms-','-o-',''],
    getTransform:function(el){
        var transform = 'none';
        for(var i=0;i< $.ui.css3prefix.length;i++){
            var transformCache = el.css($.ui.css3prefix[i]+'transform');
            if(transformCache!=undefined && transformCache!='none'){
                transform = transformCache;
            }
        }
        if(/^matrix/.test(transform)){
            var transformData = transform.replace('matrix(','').replace(')','').split(/,/);
            for(var i=0;i<transformData.length;i++){
                transformData[i] = Number(transformData[i]);
            }
            var transformData_X = transformData[0]*0+transformData[2]*0+transformData[4];
            var transformData_Y = transformData[1]*0+transformData[3]*0+transformData[5];
        }else{
            var transformData = transform.replace('translate3d(','').replace(')','').split(/,/);
//            alert(transformData);
            var transformData_X = Number(transformData[0].replace('px',''));
            var transformData_Y = Number(transformData[1].replace('px',''));
        }
//        alert(new Array(transformData_X,transformData_Y));
        return new Array(transformData_X,transformData_Y);
    },
    getTransformScale:function(el){
        var transform = 'none';
        for(var i=0;i< $.ui.css3prefix.length;i++){
            var transformCache = el.css($.ui.css3prefix[i]+'transform');
            if(transformCache!=undefined && transformCache!='none'){
                transform = transformCache;
            }
        }
        if(/^matrix/.test(transform)){
            var transformData = transform.replace('matrix(','').replace(')','').split(/,/);
            for(var i=0;i<transformData.length;i++){
                transformData[i] = Number(transformData[i]);
            }
            return transformData[0];
        }else{
            return 1;
        }
    },
    isMoblie:function(){
        if($.os.android || $.os.android || $.os.webos || $.os.touchpad || $.os.blackberry || $.os.ipad || $.os.iphone || $.os.ios){return true;}else{return false;}
    },
    isWeChat:function(){
        /* # 临时解决，关闭微信适配 */
        return false;
        var ua = navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i)=="micromessenger") {
            return true;
        } else {
            return false;
        }
    },
    wechat:{
        init:function(){
            $.ui.wechat.func.t();
        },
        data:{
            n:[]
        },
        func:{
            e:function (e) {
                alert( $.ui.wechat.data.n.toString());
                typeof window.WeixinJSBridge != "undefined" && WeixinJSBridge.invoke("imagePreview", {
                    current: e,
                    urls: $.ui.wechat.data.n
                });
            },
            t:function () {
                var t = $('.m-i-b-p img.ts-listen,.m-i-b-p-50 img.ts-listen,.m-i-b-p-100 img.ts-listen');
//                t = t ? t.getElementsByTagName("img") : [];
                $.each(t,function(index,item){
                    $.ui.wechat.data.n.push($(item).data('src'));
                    /*$(document).on('tap','.m-i-b-p img',function(){
                        $.ui.wechat.func.e(t);
                    });*/

                });
            }
        }
    },
    squareContainer:function(){
        $.each($('.square'),function(index,item){
            if(($(item).height()>$(item).width() || $(item).height()===0) && $(item).width()!==0){
                $(item).height(Math.round($(item).width()));
            }else{
                $(item).width(Math.round($(item).height()));
            }
        });
    },
    uploadFile:function(element,url,callback,errfunc){
        "use strict";
        if(url===undefined){
            url = 'http://dev.thinksns.com/t4/index.php?app=w3g&mod=Index&act=ajax_image_upload';
        }
        if(callback===undefined){
            callback=function(data){
                console.log(data);
            }
        }
        if(errfunc===undefined){
            errfunc=function(err){
                console.log(err);
            }
        }
        if(element){
            var fd = new FormData();
            fd.append('file',element.get(0).files[0]);
            $.ajax({
                url: url,
                type: "POST",
                data: fd,
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success:callback,
                error:errfunc
            });
        }
    }
};


$(document).ready(function () {
    if($.ui.isMoblie()){
        $(document).on('tap','#menuLink,#pure-shadow,#wechat_menu', function (e) {
            var active = 'active';
            var menuLink = document.getElementById('menuLink');
            $('#header,#layout,#menu,#menuLink,#pure-shadow,#footer,#header-tip').toggleClass('active');
//            $('#header,#main,#footer').toggleClass('blur');
            if($(this).attr('id')==='menuLink' || $(this).attr('id')==='wechat_menu'){
                $.ui.cache.menuLinkOpening = true;
                $.ui.cache.menuLinkOpeningId = setTimeout(function(){
                    $.ui.cache.menuLinkOpening=false;
                },200);
            }else{
                clearTimeout($.ui.cache.menuLinkOpeningId);
            }
//            console.log($.ui.cache.menuLinkOpening);
        });
        $(document).on('tap','#menu a',function(event){
            if($.ui.cache.menuLinkOpening){
                event.preventDefault();
            }
        });
        $(document).on('tap','#menu>div>ul>li>a',function(){
            $('#header,#layout,#menu,#menuLink,#pure-shadow,#footer,#header-tip').removeClass('active');
//            $('#header,#main,#footer').removeClass('blur');
        });
        $(document).on('tap','#back',function(){
            if($('#back').data('back')!==false){
//                $('#header,#main,#footer').removeClass('blur');
                $.ui.goBack();
            }
        });
    }else{
        $(document).on('click','#menuLink,#pure-shadow,#wechat_menu', function (e) {
            var active = 'active';
            var menuLink = document.getElementById('menuLink');
            $('#header,#layout,#menu,#menuLink,#pure-shadow,#footer,#header-tip').toggleClass('active');
            /*clearTimeout($.ui.blurSetTimeout);
            $.ui.blurSetTimeout=setTimeout(function(){
                $('#header,#main,#footer').toggleClass('blur');
            },200);*/
            if($(this).attr('id')==='menuLink' || $(this).attr('id')==='wechat_menu'){
                $.ui.cache.menuLinkOpening = true;
                $.ui.cache.menuLinkOpeningId = setTimeout(function(){
                    $.ui.cache.menuLinkOpening=false;
                },200);
            }else{
                clearTimeout($.ui.cache.menuLinkOpeningId);
            }
        });
        $(document).on('click','#menu a',function(event){
            if($.ui.cache.menuLinkOpening){
                event.preventDefault();
            }
        });

        $(document).on('click','#menu>div>ul>li>a',function(){
            $('#header,#layout,#menu,#menuLink,#pure-shadow,#footer,#header-tip').removeClass('active');
            /*clearTimeout($.ui.blurSetTimeout);
            $('#header,#main,#footer').removeClass('blur');*/
        });
        $(document).on('click','#back',function(){
            if($('#back').data('back')!==false){
                /*clearTimeout($.ui.blurSetTimeout);
                $('#header,#main,#footer').removeClass('blur');*/
                $.ui.goBack();
            }
        });
        $(document).on('click','#wechat_back',function(){
            /*clearTimeout($.ui.blurSetTimeout);
            $('#header,#main,#footer').removeClass('blur');*/
            $.ui.goBack();
        });
        $(document).on('click','#wechat_forward',function(){
            /*clearTimeout($.ui.blurSetTimeout);
            $('#header,#main,#footer').removeClass('blur');*/
            window.history.forward();
        });
    }
    if($.ui.isWeChat()){
        //TODO:custom web for wechat
        $('#header').hide();
        $('#wechat_bar').removeClass('hide');
        $('#content').addClass('wechat');
        $('#footer').addClass('wechat');
        $(document).on('tap','#wechat_back',function(){
            $.ui.goBack();
        });
        $(document).on('tap','#wechat_forward',function(){
            window.history.forward();
        });
        $.ui.wechat.init();
    }

    $.ui.cache.headerHtml = $('#header').html();
    $.ui.cache.menuHtml = $('#menu').html();
    $.ui.showDeafultContentDiv();
    window.onhashchange = function(){$.ui.hashChange();}//add hash listener
 });

/**
* drag
* */
var getZoomClass=(function(){
    var SupportsTouches = ("createTouch" in document),//判断是否支持触摸
        select=function(id){
            return document.getElementById(id);
        },
        preventDefault=function(ev){
            if(ev)ev.preventDefault();
            else window.event.returnValue = false;
        },
        getTwoPointSub=function(ev){
            var x1=x2=y1=y2=0,sub;
            x1=ev.touches.item(0).pageX;x2=ev.touches.item(1).pageX;
            y1=ev.touches.item(0).pageY;y2=ev.touches.item(1).pageY;
            sub=Math.round(Math.sqrt(Math.pow(x1-x2,2)+Math.pow(y1-y2,2)));
            return sub;
        },
        getSize=function(img){
            return {width:img.width,height:img.height};
        },
        getCenterPoint=function(ev){
            return {
                x:Math.round((ev.touches.item(0).pageX+ev.touches.item(1).pageX)/2),
                y:Math.round((ev.touches.item(0).pageY+ev.touches.item(1).pageY)/2)
            };
        },
        setOriginalSize=function(ev){
            if(ev.originalSize === undefined){
                ev.originalSize = {
                    width:Number(ev.width),
                    height:Number(ev.height)
                }
            }
        }
        ;
    function _zoom(opt){
        if(!SupportsTouches)return false;
        this.el=typeof opt.el=='string'?select(opt.el):opt.el;
        this.onstart=opt.start || new Function();//
        this.onmove=opt.move || new Function();
        this.onend=opt.end || new Function();
        this.action=false;
        this.init();
    }
    _zoom.prototype={
        init:function(){
            this.el['ontouchstart']=this.bind(function(e){//绑定节点的 [鼠标按下/触摸开始] 事件

                setOriginalSize(this.el);
                if(this.action){//正在进行缩放动作，不能进行新的缩放操作
                    return false;
                }else if(e.touches.length===1){//这里监测了触摸点的数量，小于两个说明不是在进行缩放操作
                    return false;
                    /*console.log('one touch');
                    preventDefault(e);
                    document.ontouchmove=this.bind(function(e){
                        console.log('on touch move');
                        var originTransform = $.ui.getTransform($('#'+this.el.id));
                        var moveX = e.touches.item(0).pageX - this.startOnePoint.x + originTransform[0];
                        var moveY = e.touches.item(0).pageY - this.startOnePoint.y + originTransform[1];
                        var transform = 'translate3d('+moveX+','+moveY+',0)';
                        $('#'+this.el.id).css({
                            '-webkit-transform':transform,
                            '-moz-transform':transform,
                            '-ms-transform':transform,
                            '-o-transform':transform,
                            'transform':transform
                        });
                    },this);*/
                }else{
                    this.action=true;
                    preventDefault(e);
                    this.startSub=getTwoPointSub(e);
                    this.startSize=getSize(this.el);
//                    this.centerPoint=getCenterPoint(e);//new func
//                    var originalScale = $.ui.getTransformScale($('#'+this.el.id));
                    this.startOnePoint = {x:e.touches.item(0).pageX,y:e.touches.item(0).pageY};
                    this.onstart();
                    document.ontouchmove=this.bind(function(e){
//                    $('#header h1').text(e.touches.length);
                        if(e.touches.length>1){
                            preventDefault(e);//取消文档的默认行为[鼠标移动、触摸移动]
                            this.nowSub=getTwoPointSub(e);
                            this.el.style.maxWidth = 'none';
                            this.el.style.maxHeight = 'none';
//                        var scale = this.nowSub/this.startSub * originalScale;
//                        var transform = 'matrix('+scale+',0,0,'+scale+','+(this.centerPoint.x-this.el.offsetLeft)*(1-scale)/2+','+(this.centerPoint.y-this.el.offsetTop)*(1-scale)/2+')';
//                        alert(transform);
                            var scale = this.nowSub/this.startSub;
                            var elWidth = this.startSize.width*scale;
                            var elHeight = this.startSize.height*scale;
//                        alert('elWidth: '+elWidth+'\n origin');
                            if(elWidth < this.el.originalSize.width){
                                this.el.width = this.el.originalSize.width;
                                this.el.height = this.el.originalSize.height;
                                $('#'+this.el.id).data('scale',1);
                            }else{
                                this.el.width=elWidth;
                                this.el.height=elHeight;
                                $('#'+this.el.id).data('scale',elWidth/this.el.originalSize.width);
                            }
                            var transformX = (this.startSize.width-this.el.width)/2;
                            var transformY = (this.startSize.height-this.el.height)/2;
                            if(transformX>0){transformX=0;}
                            if(transformY>0){transformY=0;}
                            var transform = 'translate3d('+transformX+'px,'+transformY+'px,0)';
                            $('#'+this.el.id).css({
                                '-webkit-transform':transform,
                                '-moz-transform':transform,
                                '-ms-transform':transform,
                                '-o-transform':transform,
                                'transform':transform
                            });
//                        $('#header h1').text(transform);
//                        $.ui.getTransform($('#'+this.el.id));
                        }
                        this.onmove();
                    },this);
                    document.ontouchend=document.ontouchcancel=this.bind(function(){
                        document.ontouchend=document.ontouchcancel=document.ontouchmove=null;
                        this.action=false;
                        this.onend();
                    },this);
                }
            },this);
        },
        bind:function(fn,obj){
            return function(){
                fn.apply(obj,arguments);
            }
        },
        tool:null
    }
    return function(opt){
        return new _zoom(opt);
    }
})();

//add listen
$(document).ready(function(){
    if($.ui.isMoblie()){
        $(document).on('tap','.ts-listen',function(e){
            $.ui.cache.scrolly = document.documentElement.scrollTop || document.body.scrollTop;
            var listen = $(this).data('listen').split('-');
            if(listen[0] in TS.app){if(listen[1] in TS.app[listen[0]].listen){
                if(listen.length===3){
                    TS.app[listen[0]].listen[listen[1]](listen[2],$(this),e);
                }else{
                    TS.app[listen[0]].listen[listen[1]]();
                }
            }}
        });
    }else{
        $(document).on('click','.ts-listen',function(e){
            $.ui.cache.scrolly = document.documentElement.scrollTop || document.body.scrollTop;
            var listen = $(this).data('listen').split('-');
            if(listen[0] in TS.app){if(listen[1] in TS.app[listen[0]].listen){
                if(listen.length===3){
                    TS.app[listen[0]].listen[listen[1]](listen[2],$(this),e);
                }else{
                    TS.app[listen[0]].listen[listen[1]]();
                }
            }}
        });
    }
    $(document).on('change','.ts-listen-change',function(e){
        $.ui.cache.scrolly = document.documentElement.scrollTop || document.body.scrollTop;
        var listen = $(this).data('listen').split('-');
        if(listen[0] in TS.app){if(listen[1] in TS.app[listen[0]].listen){
            if(listen.length===3){
                TS.app[listen[0]].listen[listen[1]](listen[2],$(this),e);
            }else{
                TS.app[listen[0]].listen[listen[1]]();
            }
        }}
    });
    //read message
    TS.app.weibo.func.readNewMsg();
    TS.cache.readMsg = setInterval(function(){TS.app.weibo.func.readNewMsg()},60*1000);
    /**
     * picView Touchmove
     * */
    $(document).on('touchstart','#pic-view img',function(e){
        e.preventDefault();
        var originTransform = $.ui.getTransform($(this));
        $(this).data("startx",e.touches.item(0).pageX);
        $(this).data("starty",e.touches.item(0).pageY);
        $(this).data("ox",originTransform[0]);
        $(this).data("oy",originTransform[1]);
    });
    $(document).on('touchmove','#pic-view img',function(e){
        if(e.touches.length===1){
            e.preventDefault();
            var moveX = Number(e.touches.item(0).pageX) - Number($(this).data('startx')) + Number($(this).data("ox"));
            var moveY = Number(e.touches.item(0).pageY) - Number($(this).data('starty')) + Number($(this).data("oy"));
            var transform = 'translate3d('+moveX+'px,'+moveY+'px,0)';
            if(Number($(this).data('scale')) <= 1){
                transform = 'translate3d(0,0,0)';
            }
//             console.log(transform);
            $(this).css({
                '-webkit-transform':transform,
                '-moz-transform':transform,
                '-ms-transform':transform,
                '-o-transform':transform,
                'transform':transform
            });
        }
    });
});