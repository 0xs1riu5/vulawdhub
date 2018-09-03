/**
 * sina flash class
 * @version 1.1.4.2
 * @author [sina ui]zhangping1@
 * @update 2008-7-7 
 */
 
if(typeof(sina)!="object"){var sina={}}
sina.$=function(i){if(!i){return null}
return document.getElementById(i)};var sinaFlash=function(V,x,X,Z,v,z,i,c,I,l,o){var w=this;if(!document.createElement||!document.getElementById){return}
w.id=x?x:'';var O=function(I,i){for(var l=0;l<I.length;l++){if(I[l]==i){return l}}
return-1},C='8.0.42.0';if(O(['eladies.sina.com.cn','ent.sina.com.cn'],document.domain)>-1){w.ver=C}else{w.ver=v?v:C}
w.ver=w.ver.replace(/\./g,',');w.__classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000";w.__codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version="+w.ver;w.width=X;w.height=Z;w.movie=V;w.src=w.movie;w.bgcolor=z?z:'';w.quality=c?c:"high";w.__pluginspage="http://www.macromedia.com/go/getflashplayer";w.__type="application/x-shockwave-flash";w.useExpressInstall=(typeof(i)=="boolean")?i:false;w.xir=I?I:window.location;w.redirectUrl=l?l:window.location;w.detectKey=(typeof(o)=="boolean")?o:true;w.escapeIs=false;w.__objAttrs={};w.__params={};w.__embedAttrs={};w.__flashVars=[];w.__flashVarsStr="";w.__forSetAttribute("id",w.id);w.__objAttrs["classid"]=w.__classid;w.__forSetAttribute("codebase",w.__codebase);w.__forSetAttribute("width",w.width);w.__forSetAttribute("height",w.height);w.__forSetAttribute("movie",w.movie);w.__forSetAttribute("quality",w.quality);w.__forSetAttribute("pluginspage",w.__pluginspage);w.__forSetAttribute("type",w.__type);w.__forSetAttribute("bgcolor",w.bgcolor)}
sinaFlash.prototype={getFlashHtml:function(){var I=this,i='<object ';for(var l in I.__objAttrs){i+=l+'="'+I.__objAttrs[l]+'"'+' '}
i+='>\n';for(var l in I.__params){i+='	<param name="'+l+'" value="'+I.__params[l]+'" \/>\n'}
if(I.__flashVarsStr!=""){i+='	<param name="flashvars" value="'+I.__flashVarsStr+'" \/>\n'}
i+='	<embed ';for(var l in I.__embedAttrs){i+=l+'="'+I.__embedAttrs[l]+'"'+' '}
i+='><\/embed>\n<\/object>';return i},__forSetAttribute:function(I,i){var l=this;if(typeof(I)=="undefined"||I==''||typeof(i)=="undefined"||i==''){return}
I=I.toLowerCase();switch(I){case "classid":break;case "pluginspage":l.__embedAttrs[I]=i;break;case "onafterupdate":case "onbeforeupdate":case "onblur":case "oncellchange":case "onclick":case "ondblClick":case "ondrag":case "ondragend":case "ondragenter":case "ondragleave":case "ondragover":case "ondrop":case "onfinish":case "onfocus":case "onhelp":case "onmousedown":case "onmouseup":case "onmouseover":case "onmousemove":case "onmouseout":case "onkeypress":case "onkeydown":case "onkeyup":case "onload":case "onlosecapture":case "onpropertychange":case "onreadystatechange":case "onrowsdelete":case "onrowenter":case "onrowexit":case "onrowsinserted":case "onstart":case "onscroll":case "onbeforeeditfocus":case "onactivate":case "onbeforedeactivate":case "ondeactivate":case "codebase":l.__objAttrs[I]=i;break;case "src":case "movie":l.__embedAttrs["src"]=i;l.__params["movie"]=i;break;case "width":case "height":case "align":case "vspace":case "hspace":case "title":case "class":case "name":case "id":case "accesskey":case "tabindex":case "type":l.__objAttrs[I]=l.__embedAttrs[I]=i;break;default:l.__params[I]=l.__embedAttrs[I]=i}},__forGetAttribute:function(i){var I=this;i=i.toLowerCase();if(typeof I.__objAttrs[i]!="undefined"){return I.__objAttrs[i]}else if(typeof I.__params[i]!="undefined"){return I.__params[i]}else if(typeof I.__embedAttrs[i]!="undefined"){return I.__embedAttrs[i]}else{return null}},setAttribute:function(I,i){this.__forSetAttribute(I,i)},getAttribute:function(i){return this.__forGetAttribute(i)},addVariable:function(I,i){var l=this;if(l.escapeIs){I=escape(I);i=escape(i)}
if(l.__flashVarsStr==""){l.__flashVarsStr=I+"="+i}else{l.__flashVarsStr+="&"+I+"="+i}
l.__embedAttrs["FlashVars"]=l.__flashVarsStr},getVariable:function(I){var o=this,i=o.__flashVarsStr;if(o.escapeIs){I=escape(I)}
var l=new RegExp(I+"=([^\\&]*)(\\&?)","i").exec(i);if(o.escapeIs){return unescape(RegExp.$1)}
return RegExp.$1},addParam:function(I,i){this.__forSetAttribute(I,i)},getParam:function(i){return this.__forGetAttribute(i)},write:function(i){var I=this;if(typeof i=="string"){document.getElementById(i).innerHTML=I.getFlashHtml()}else if(typeof i=="object"){i.innerHTML=I.getFlashHtml()}}}