/**
 * 
 * @version        $Id: context_menu.js 1 22:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

var MenuWidth = 120;
var ItemHeight = 16;
var ItemNumber = 0;

function curNav(){
    if(window.navigator.userAgent.indexOf("MSIE")>=1) return 'IE';
    else if(window.navigator.userAgent.indexOf("Firefox")>=1) return 'FF';
    else return 'OT';
}

function insertHtm(op,code,isStart){ 
    if(curNav()=='IE') {
        op.insertAdjacentHTML(isStart ? "beforeEnd" : "afterEnd",code); 
    } else { 
    var range=op.ownerDocument.createRange(); 
    range.setStartBefore(op); 
    var fragment = range.createContextualFragment(code); 
    if(isStart) op.insertBefore(fragment,op.firstChild); 
    else op.appendChild(fragment);
  } 
}

    ContextMenu.WebFX_PopUp = null;
    ContextMenu.WbFX_PopUpcss = null;

    ContextMenu.intializeContextMenu=function(){
	insertHtm(document.body,'<iframe src="#" scrolling="no" class="WebFX-ContextMenu" marginwidth="0" marginheight="0" frameborder="0" style="position:absolute;display:none;z-index:50000000;" id="WebFX_PopUp"></iframe>',true);
	
	if(curNav()=='IE') WebFX_PopUp = document.frames['WebFX_PopUp'];
	else WebFX_PopUp = document.getElementById('WebFX_PopUp');
		
	WebFX_PopUpcss = document.getElementById('WebFX_PopUp');	
	
	WebFX_PopUpcss.onfocus  = function(){WebFX_PopUpcss.style.display="inline"};
	WebFX_PopUpcss.onblur  = function(){WebFX_PopUpcss.style.display="none"};
	
	if(curNav()=='IE') document.body.attachEvent("onmousedown",function(){WebFX_PopUpcss.style.display="none"});
	else  document.addEventListener("onblur",function(){WebFX_PopUpcss.style.display="none"},false);
		
	if(curNav()=='IE') document.attachEvent("onblur",function(){WebFX_PopUpcss.style.display="none"});
	else document.addEventListener("onblur",function(){WebFX_PopUpcss.style.display="none"},false);
		
}


function ContextSeperator(){}

function ContextMenu(){}

ContextMenu.showPopup=function(x,y){
    WebFX_PopUpcss.style.display = "block"
}

ContextMenu.display=function(evt,popupoptions){ 
    var eobj,x,y;
  
	eobj = evt ? evt:(window.event ? window.event : null);
	
	if(curNav()=='IE'){ 
        x  = eobj.x;y  = eobj.y
	} else	{ 
        x = eobj.clientX; y = eobj.clientY; 
	}
	
	ContextMenu.populatePopup(popupoptions,window)	
	ContextMenu.showPopup(x,y);
	ContextMenu.fixSize();
	ContextMenu.fixPos(x,y);
    eobj.cancelBubble = true;
    eobj.returnValue  = false;
}

//TODO
 ContextMenu.getScrollTop=function(){
 	return document.body.scrollTop;
	//window.pageXOffset and window.pageYOffset for moz
}
 
 ContextMenu.getScrollLeft=function(){
 	return document.body.scrollLeft;
}
 

ContextMenu.fixPos=function(x,y){
	/*var docheight,docwidth,dh,dw;
	if(!x) { x=0; y=0; }	
	docheight = document.body.clientHeight;
	docwidth  = document.body.clientWidth;
	dh = (WebFX_PopUpcss.offsetHeight+y) - docheight;
	dw = (WebFX_PopUpcss.offsetWidth+x)  - docwidth;
	if(dw>0){
		WebFX_PopUpcss.style.left = (x - dw) + ContextMenu.getScrollLeft() + "px"; 
	}else { 
	    WebFX_PopUpcss.style.left = x + ContextMenu.getScrollLeft(); 
	} if(dh>0)	{ 
	    WebFX_PopUpcss.style.top = (y - dh) + ContextMenu.getScrollTop() + "px" 
	}else{
	    WebFX_PopUpcss.style.top  = y + ContextMenu.getScrollTop(); }*/
	 WebFX_PopUpcss.style.top = y + "px";
	 WebFX_PopUpcss.style.left = x + "px";
}

ContextMenu.fixSize=function(){
	WebFX_PopUpcss.style.height = ItemHeight * ItemNember + "px";
	WebFX_PopUpcss.style.width =  MenuWidth + "px";
	ItemNember = 0;
}

ContextMenu.populatePopup=function(arr,win){
	var alen,i,tmpobj,doc,height,htmstr;
	alen = arr.length;
	ItemNember = alen;
	
	if(curNav()=='IE') doc = WebFX_PopUp.document;
	else doc  = WebFX_PopUp.contentWindow.document;
	
	doc.body.innerHTML  = '';
	//if (doc.getElementsByTagName("LINK").length == 0){
		doc.open();
		doc.write('<html><head><link rel="StyleSheet" type="text/css" href="js/contextmenu.css"></head><body></body></html>');
		doc.close();
	//}
	for(i=0;i<alen;i++)	{
		if(arr[i].constructor==ContextItem)	{
			tmpobj=doc.createElement("DIV");
			tmpobj.noWrap = true;
			tmpobj.className = "WebFX-ContextMenu-Item";
			if(arr[i].disabled)			{
				htmstr  = '<span class="WebFX-ContextMenu-DisabledContainer">'
				htmstr += arr[i].text+'</span>'
				tmpobj.innerHTML = htmstr
				tmpobj.className = "WebFX-ContextMenu-Disabled";
				tmpobj.onmouseover = function(){this.className="WebFX-ContextMenu-Disabled-Over"}
				tmpobj.onmouseout  = function(){this.className="WebFX-ContextMenu-Disabled"}
			}else{
				tmpobj.innerHTML = arr[i].text;
				tmpobj.onclick = (function (f)
				{
				   	return function () {
			    			win.WebFX_PopUpcss.style.display='none'
								if (typeof(f)=="function"){ f(); }
             };
				})(arr[i].action);
					
				tmpobj.onmouseover = function(){this.className="WebFX-ContextMenu-Over"}
				tmpobj.onmouseout  = function(){this.className="WebFX-ContextMenu-Item"}
			}
			doc.body.appendChild(tmpobj);
		}else{
			doc.body.appendChild(doc.createElement("DIV")).className = "WebFX-ContextMenu-Separator";
		}
	}
	doc.body.className  = "WebFX-ContextMenu-Body" ;
	doc.body.onselectstart = function(){return false;}
}

function ContextItem(str,fnc,disabled){
    this.text     = str;
	this.action   = fnc; 
	this.disabled = disabled || false;
}