/***********************************************************
 * 使用iframe模拟ajax的窗体，本JS需要引用jquery框架
 * 为了支持拖动,需要同时引入jquery.ui.core以及ui.draggable.js
 * 修改自 thickbox 源码
************************************************************/
var $ = jQuery;
var tb_pathToImage = "images/loadinglit.gif";
var ref_parent = false;
var tb_frameid = 0;

/**
 * 对于指定了class为'thickbox'的超链接自动监听其超链接，其中可以指定 rel=(0|1) 属性决定点击关闭后是否刷新上级窗口
 * 如果不需要侦听超链接事件，可以禁用些初始化方法
 */
$(document).ready(function()
{   
    tb_init('a.thickbox, area.thickbox, input.thickbox');
    imgLoader = new Image();
    imgLoader.src = tb_pathToImage;
});

function tb_init(domChunk)
{
    $(domChunk).click(function(){
        var t = this.title || this.name || null;
        var a = this.href || this.alt;
        var g = this.rel || false;
        tb_show(t, a, g);
        this.blur();
        return false;
    });
}

/**
 * 弹窗警告窗口让用户确认操作
 * refParent 参数(0|1)决定点击关闭后是否刷新上级窗口
 */
function tb_action(msg, gourl)
{
    msg += "<br/><a href='javascript:tb_remove();'>&lt;&lt;点错了</a> &nbsp;|&nbsp; <a href='"+gourl+"'>确定要操作&gt;&gt;</a>";
    tb_showmsg(msg);
}

/**
 * 弹窗主函数
 * refParent 参数(0|1)决定点击关闭后是否刷新上级窗口
 */
function tb_show(caption, url, refParent)
{
    ref_parent = refParent;
    if (typeof document.body.style.maxHeight === "undefined")
    {
            $("body","html").css({height: "100%", width: "100%"});
            $("html").css("overflow","hidden");
            if (document.getElementById("TB_HideSelect") === null) {
                $("body").append("<iframe id='TB_HideSelect'></iframe><div id='TB_overlay'></div><div id='TB_window'></div>");
                $("#TB_overlay").click(tb_remove);
            }
    }
    else
    {
            if(document.getElementById("TB_overlay") === null){
                $("body").append("<div id='TB_overlay'></div><div id='TB_window'></div>");
                $("#TB_overlay").click(tb_remove);
            }
    }
        
    if(tb_detectMacXFF()){
            $("#TB_overlay").addClass("TB_overlayMacFFBGHack");
    }else{
            $("#TB_overlay").addClass("TB_overlayBG");
    }
        
    if(caption===null) caption="消息窗口";
        $("body").append("<div id='TB_load'><img src='"+imgLoader.src+"' /></div>");
        $('#TB_load').show();
        
        var baseURL;
    if(url.indexOf("?")!==-1){
              baseURL = url.substr(0, url.indexOf("?"));
    }else{ 
               baseURL = url;
    }
            
    var queryString = url.replace(/^[^\?]+\??/,'');
    var params = tb_parseQuery( queryString );

    TB_WIDTH = (params['width']*1) + 30 || 630;
    TB_HEIGHT = (params['height']*1) + 40 || 420;
    ajaxContentW = TB_WIDTH - 30;
    ajaxContentH = TB_HEIGHT - 45;
            
     // either iframe or ajax window
     if(url.indexOf('TB_iframe') != -1)
     {        
                    urlNoQuery = url.split('TB_');
                    $("#TB_iframeContent").remove();
                    tb_frameid++;
                    if(params['modal'] != "true"){
                        $("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='javascript:void(0);' id='TB_closeWindowButton' title='关闭'>关闭</a></div></div><iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1000)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;' > </iframe>");
                    }else{
                        $("#TB_overlay").unbind();
                        $("#TB_window").append("<iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+tb_frameid+"' onload='tb_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;'> </iframe>");
                    }
            }
            // not an iframe, ajax
            else
            {
                    if($("#TB_window").css("display") != "block"){
                        if(params['modal'] != "true"){
                            $("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='javascript:void(0);' id='TB_closeWindowButton' title='关闭'>关闭</a></div></div><div id='TB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px'></div>");
                        }else{
                            $("#TB_overlay").unbind();
                            $("#TB_window").append("<div id='TB_ajaxContent' class='TB_modal' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>");    
                        }
                    }else{
                        $("#TB_ajaxContent")[0].style.width = ajaxContentW +"px";
                        $("#TB_ajaxContent")[0].style.height = ajaxContentH +"px";
                        $("#TB_ajaxContent")[0].scrollTop = 0;
                        $("#TB_ajaxWindowTitle").html(caption);
                    }
            }
                    
            $("#TB_closeWindowButton").click(tb_remove);
            
            if(url.indexOf('TB_inline') != -1)
            {    
                    $("#TB_ajaxContent").append($('#' + params['inlineId']).children());
                    $("#TB_window").unload(function () {
                        $('#' + params['inlineId']).append( $("#TB_ajaxContent").children() ); // move elements back when you're finished
                    });
                    tb_position();
                    $("#TB_load").remove();
                    $("#TB_window").css({display:"block"}); 
            }
            else if(url.indexOf('TB_iframe') != -1)
            {
                    tb_position();
                    if($.browser.safari){//safari needs help because it will not fire iframe onload
                        $("#TB_load").remove();
                        $("#TB_window").css({display:"block"});
                    }
            }
            else
            {
                    $("#TB_ajaxContent").load(url += "&random=" + (new Date().getTime()),function(){//to do a post change this load method
                        tb_position();
                        $("#TB_load").remove();
                        tb_init("#TB_ajaxContent a.thickbox");
                        $("#TB_window").css({display:"block"});
                    });
            }
            $("#TB_window").draggable(); //支持窗口拖动
            //alert( $("#TB_window").get(0).innerHTML );
        if(!params['modal'])
        {
                document.onkeyup = function(e){ kc = (e == null ? event.keyCode : e.which); if(kc == 27){ tb_remove(); } };
          }
}

/**
 * 弹窗信息框
 */
function tb_showmsg(msg, caption, talign, ww, wh)
{
        //默认参数
        if(!caption || caption=="") caption="消息窗口";
        if(!talign) talign = "center";
        if(!ww) ww = "350px";
        if(!wh) wh = "180px";
        
        if (typeof document.body.style.maxHeight === "undefined") {
            $("body","html").css({height: "100%", width: "100%"});
            $("html").css("overflow","hidden");
        }
        if(document.getElementById("TB_overlay") === null){
                $("body").append("<div id='TB_overlay'></div><div id='TB_window'></div>");
                $("#TB_overlay").click(tb_remove);
        }
        
        if(tb_detectMacXFF()){
            $("#TB_overlay").addClass("TB_overlayMacFFBGHack");
        }else{
            $("#TB_overlay").addClass("TB_overlayBG");
        }
        
        
        $("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton' title='关闭'>关闭</a></div></div><div id='TB_ajaxContent'><table width='100%'><tr><td valign='middle' style='height:100%;font-size:14px;line-height:28px;' align='"+talign+"'>"+ msg +"</td></tr></table></div>");
        
        $("#TB_closeWindowButton").click(tb_remove);
        
        $("#TB_window").css({display:"block"});
		topx = ($.browser.msie)? 150 : 50;
        $("#TB_window").css({top: topx + "px"});
        $("#TB_window")[0].style.width = ww;
        $("#TB_window")[0].style.height = wh;
		$("#TB_window").draggable(); //支持窗口拖动
            
      document.onkeyup = function(e){ kc = (e == null ? event.keyCode : e.which); if(kc == 27){ tb_remove(); } };
}

//helper functions below
function tb_showIframe()
{
    $("#TB_load").remove();
    $("#TB_window").css({display:"block"});
}

// 增加父级框架关闭内容的方法
function tb_remove()
{
    var isparent = $("#TB_imageOff",parent.document).length;
    if(isparent )
    {
        $("#TB_imageOff",parent.document).unbind("click");
        $("#TB_closeWindowButton",parent.document).unbind("click");
        $("#TB_window",parent.document).fadeOut("fast",function(){$('#TB_window,#TB_overlay,#TB_HideSelect',parent.document).trigger("unload").unbind().remove();});
        $("#TB_load",parent.document).remove();
        //if IE 6
        if (typeof parent.document.body.style.maxHeight == "undefined")
        {
            $("body","html",parent.document).css({height: "auto", width: "auto"});
            $("html",parent.document).css("overflow","");
        }
        document.onkeydown = "";
        document.onkeyup = "";
        if( ref_parent ) location.reload();
        return;
    } else {
        $("#TB_imageOff").unbind("click");
        $("#TB_closeWindowButton").unbind("click");
        $("#TB_window").fadeOut("fast",function(){$('#TB_window,#TB_overlay,#TB_HideSelect').trigger("unload").unbind().remove();});
        $("#TB_load").remove();
        //if IE 6
        if (typeof document.body.style.maxHeight == "undefined")
        {
            $("body","html").css({height: "auto", width: "auto"});
            $("html").css("overflow","");
        }
        document.onkeydown = "";
        document.onkeyup = "";
        if( ref_parent ) location.reload();
        return;
    }
}

function tb_position()
{
  $("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH + 'px'});
    // take away IE6
    if ( !(jQuery.browser.msie && jQuery.browser.version < 7))
    {
         $("#TB_window").css({marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
    }
}

function tb_parseQuery ( query )
{
   var Params = {};
   if ( ! query ) {return Params;}// return empty object
   var Pairs = query.split(/[;&]/);
   for ( var i = 0; i < Pairs.length; i++ ) {
      var KeyVal = Pairs[i].split('=');
      if ( ! KeyVal || KeyVal.length != 2 ) {continue;}
      var key = unescape( KeyVal[0] );
      var val = unescape( KeyVal[1] );
      val = val.replace(/\+/g, ' ');
      Params[key] = val;
   }
   return Params;
}

function tb_getPageSize()
{
    var de = document.documentElement;
    var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
    var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
    arrayPageSize = [w,h];
    return arrayPageSize;
}

function tb_detectMacXFF()
{
  var userAgent = navigator.userAgent.toLowerCase();
  if (userAgent.indexOf('mac') != -1 && userAgent.indexOf('firefox')!=-1) {
     return true;
  }
}


