$.feat.nativeTouchScroll=false;//No native scrolling
var TS;
TS={
    siteTitle:'ThinkSNS',
    detailId:1,
    hashChanged:false,
    oldHash:window.location.hash,
    newHash:window.location.hash,
    spliteHash:function(hash){
        hash=hash.split('-');
        hash[0]=hash[0].replace(/^\#/,'');
        if(hash.length===3){
            if(hash[2]===undefined){hash[2]='1';}
        }
        return hash;
    },
    casePage:function(autoLoad,hash){
        console.log('run casePage');
        if(!hash){
            var hash=window.location.hash;
        }
        if(this.cache.logged){//logged
            var originHash=hash;
            hash=this.spliteHash(hash);
            if(this.app[hash[0]]){
                if(this.app[hash[0]].page[hash[1]] && autoLoad){
//                    console.log(this.app[hash[0]].page[hash[1]]);
                    this.loadNewPage(this.app[hash[0]].url(hash),originHash,this.app[hash[0]].page[hash[1]].name,hash[0]+'-'+hash[1],this.app[hash[0]].page[hash[1]].footer,'TS_APP_ONLOAD');
                }else{
                    console.log('404');
                }
            }else{
                console.log('no such app');
                if($.query("#" + hash[0]).get(0)){
//                    $.ui.load()
                }
            }
        }else{//unlogged
            console.log(hash);
            if(hash===''){
                this.cache.prePage=false;
                this.loadNewPage(U('w3g/Public/home'),'home','home','cache','none',false,'sys');
            }else{
                this.cache.prePage=hash;
                console.log('this.cache.prePage='+this.cache.prePage);
                //TODO:run more than one time T_T
                if(hash!=='#home'&&hash!=='#login'&&hash!=='#register'){
                    console.log('case not in list');
                    this.loadNewPage(U('w3g/Public/login'),'login','login','cache','none','TS_APP_ONLOAD','sys');
                }else{
                    //TODO:something not workT_T
                    if (!$.query(hash).get(0)){
                        hash=hash.replace(/^\#/,'');
                        console.log(hash);
                        this.loadNewPage(U('w3g/Public/'+hash),hash,hash,'cache','none','TS_APP_ONLOAD','sys');
                    }
                }
            }
//            this.casePage(true,'#home');
        }
    },
    loadNewPage:function(url,target,title,pagetype,footer,onload,addClass){
        this.loadTSurl(url,target,title,pagetype,footer,onload,addClass,function(data,target,title,pagetype,footer,onload,addClass){
            var pagetype='ts-'+pagetype;
            $('.'+pagetype).remove();
            var what = target.replace("#", "");
            var slashIndex = what.indexOf('/');
            var hashLink = "";
            if (slashIndex != -1) {
                // Ignore everything after the slash for loading
                hashLink = what.substr(slashIndex);
                what = what.substr(0, slashIndex);
            }
            if (!$.query("#" + what).get(0)) {
                $.ui.addContentDiv(what,data,title);
                $('#'+what).addClass(pagetype)
//                .data('load','TSloadpage')
                    .data('TSurl',url);
                if(onload){
                    $('#'+what).data('load',onload);
                }
                if(footer){
                    $('#'+what).data('footer',footer);
                }else{
                    $('#'+what).data('footer','none');
                }
                if(addClass){
                    $('#'+what).addClass(addClass);
                }
                $.ui.loadDiv(what);
            }
        });
    },
    loadTSurl:function(url,target,title,pagetype,footer,onload,addClass,callback){
        if(url!==false){
            $.ui.showMask();
            $.ajax({
                url:url,
                success:function(data){
                    $.ui.hideMask();
                    callback(data,target,title,pagetype,footer,onload,addClass);
                }
            });
        }else{
            callback('',target,title,pagetype,footer,onload,addClass);
        }
    },
    setTitle:function(title){
        $.ui.setTitle(title);
        document.title=title+' -'+this.siteTitle;
    },
    unload:function(){
        c('run TS.unload()');
        switch (true){
            case TS.newHash==='#reply':
                break;
            case TS.newHash==='#retweet':
                break;
            case TS.newHash==='#main':
                c('#main');
                $('.TScache').remove();
                break;
        }
    },
    post:function(){
        var hash = window.location.hash;

    },
    append:function(elName,divId,content,el,callback){
        $($.ui.scrollingDivs[elName].el).find('#'+divId).append(content);
        if(el && callback){
            callback(el);
        }
    },
    html:function(elName,divId,content,el,callback){
        $($.ui.scrollingDivs[elName].el).find('#'+divId).html(content);
        if(el && callback){
            callback(el);
        }
    },
    checkLogin:function(data,callback,callback2){
        if(data==='UNLOGINED'){
            c('you are unlogined:(');
//            TS.casePage(true,'login');//TODO:add login page
            this.cache.logged=false;
            if(callback2){
                callback2();//unlogined func
            }else{
                $.ui.loadDiv('home');
            }
        }else{
            callback(data);
        }
    },
    cache:{
        logged:false,
        prePage:false,
        activeApp:'weibo',
        profile:{}
    },
    addScrollListen:function(el,url){
        var elName = el.getAttribute('id');
        $.ui.scrollingDivs[elName].addPullToRefresh();
        //set scroller
        var listScroller=$("#"+elName).scroller();
        listScroller.addInfinite();
        $.bind(listScroller,"refresh-release",function(){
            TS.app.weibo.func.loadList(listScroller,'refresh',elName);
            return false;
        });
        listScroller.enable();
        $.bind(listScroller, "infinite-scroll", function () {
            var self = this;
            $(this.el).append("<div id='infinite' style='height:60px;line-height:60px;text-align:center;font-weight:bold'>加载更多...</div>");
            $.bind(listScroller, "infinite-scroll-end", function () {
                $.unbind(listScroller, "infinite-scroll-end");
//                self.scrollToBottom();
//            var max_id = TS.app.weibo.cache.weiboListStart();
                $.ajax({
                    url:U('w3g/Index/listbyid',['since_id','max_id='+TS.app.weibo.cache.weiboListStart(),'count=10']),
                    type:'GET',
                    timeout:5000,
                    success:function(data){
//                        alert(data);
                        TS.checkLogin(data,function(data){//TODO:finish checklogin func
//                            alert('ok');
                            if(data!==''){
                                $(self.el).find('#ts-list').append(data);
                                $(self.el).find("#infinite").remove();
//                            TS.append(elName,'microblog-list',data);
                                self.clearInfinite();
//                            self.scrollToBottom();
                            }
                        })
                    },
                    dataType:'html'
                });
            });
        });
    },
    showMask:function(text){
        $.ui.showMask(text);
        window.setTimeout(function(){
            $.ui.hideMask();
        }, 2500);
    },
    showMaskOK:function(){
        $('body').append('<div id="ts_mask" class="ui-loader"><span class="ts-font fa-check"></span></div>');
        window.setTimeout(function(){
            $('#ts_mask').remove();
        },800);
    },
    r:{
        mail:function(x) {//判断邮箱
            return /\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,4}/ig.test(x);
        },
        password:function(x) {//判断邮箱
            return /.{6,15}$/ig.test(x);
        },
        weibo:function(x){//判断分享内容
            return /^\w{1,140}$/ig.test(x);
        },
        null:function(x){//判断分享内容kong
            return /^[\n\t ]*$/ig.test(x);
        },
        uname:function(x){
            var count_zh = x.match(/[\u4e00-\u9fa5]{1}/g);
            var count_other = x.match(/\w{1}/g);
            if(count_zh!=null){
                count_zh=count_zh.length*2
            }else{
                count_zh=0;
            }
            if(count_other!=null){
                count_other=count_other.length
            }else{
                count_other=0;
            }
            var count = count_zh+count_other;
            if(count >=4 && count<=20){
                return true;
            }else{
                return false;
            }
        }
    },
    localStorage:{
        tsListTop:function(top){
            if(top){
                if(typeof top !== 'number'){
                    top = Number(top.replace(/(^translate3d\(\w+\, )|(px\, \w+\)$)/g,''));
                }
                localStorage.setItem('tsListTop',top);
            }else{
                return Number(localStorage.getItem('tsListTop'));
            }
        }
    },
    app:{}
    //TS-basic-func
}


//basic func
var focus = function(el){
    if($(el).find('.ts-textarea').length!==0){
        $(el).find('.ts-textarea').focus();
    }
}
String.prototype.trim = function()
{
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
/**
 * wechat
 * */
function onBridgeReady(){
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady()
    {  WeixinJSBridge.call('showToolbar');
    });
}

if (typeof WeixinJSBridge == "undefined"){
    if( document.addEventListener ){
        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
    }else if (document.attachEvent){
        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
    }
}else{
    onBridgeReady();
}