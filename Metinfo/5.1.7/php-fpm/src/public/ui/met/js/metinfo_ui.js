/*圆角插件↓*/
(function(jQuery){var style=document.createElement('div').style,moz=style['MozBorderRadius']!==undefined,webkit=style['WebkitBorderRadius']!==undefined,radius=style['borderRadius']!==undefined||style['BorderRadius']!==undefined,mode=document.documentMode||0,noBottomFold=jQuery.browser.msie&&((jQuery.browser.version<8&&!mode)||mode<8),expr=jQuery.browser.msie&&(function(){var div=document.createElement('div');try{div.style.setExpression('width','0+0');div.style.removeExpression('width')}catch(e){return false};return true})();jQuery.support=jQuery.support||{};jQuery.support.borderRadius=moz||webkit||radius;function sz(el,p){return parseInt(jQuery.css(el,p))||0};function hex2(s){s=parseInt(s).toString(16);return(s.length<2)?'0'+s:s};function gpc(node){while(node){var v=jQuery.css(node,'backgroundColor'),rgb;if(v&&v!='transparent'&&v!='rgba(0, 0, 0, 0)'){if(v.indexOf('rgb')>=0){rgb=v.match(/\d+/g);return'#'+hex2(rgb[0])+hex2(rgb[1])+hex2(rgb[2])};return v};if(node.nodeName.toLowerCase()=='html')break;node=node.parentNode};return'#ffffff'};function getWidth(fx,i,width){switch(fx){case'round':return Math.round(width*(1-Math.cos(Math.asin(i/width))));case'cool':return Math.round(width*(1+Math.cos(Math.asin(i/width))));case'sharp':return width-i;case'bite':return Math.round(width*(Math.cos(Math.asin((width-i-1)/width))));case'slide':return Math.round(width*(Math.atan2(i,width/i)));case'jut':return Math.round(width*(Math.atan2(width,(width-i-1))));case'curl':return Math.round(width*(Math.atan(i)));case'tear':return Math.round(width*(Math.cos(i)));case'wicked':return Math.round(width*(Math.tan(i)));case'long':return Math.round(width*(Math.sqrt(i)));case'sculpt':return Math.round(width*(Math.log((width-i-1),width)));case'dogfold':case'dog':return(i&1)?(i+1):width;case'dog2':return(i&2)?(i+1):width;case'dog3':return(i&3)?(i+1):width;case'fray':return(i%2)*width;case'notch':return width;case'bevelfold':case'bevel':return i+1;case'steep':return i/2+1;case'invsteep':return(width-i)/2+1}};jQuery.fn.corner=function(options){if(this.length==0){if(!jQuery.isReady&&this.selector){var s=this.selector,c=this.context;jQuery(function(){jQuery(s,c).corner(options)})};return this};return this.each(function(index){var jQuerythis=jQuery(this),o=[jQuerythis.attr(jQuery.fn.corner.defaults.metaAttr)||'',options||''].join(' ').toLowerCase(),keep=/keep/.test(o),cc=((o.match(/cc:(#[0-9a-f]+)/)||[])[1]),sc=((o.match(/sc:(#[0-9a-f]+)/)||[])[1]),width=parseInt((o.match(/(\d+)px/)||[])[1])||10,re=/round|bevelfold|bevel|notch|bite|cool|sharp|slide|jut|curl|tear|fray|wicked|sculpt|long|dog3|dog2|dogfold|dog|invsteep|steep/,fx=((o.match(re)||['round'])[0]),fold=/dogfold|bevelfold/.test(o),edges={T:0,B:1},opts={TL:/top|tl|left/.test(o),TR:/top|tr|right/.test(o),BL:/bottom|bl|left/.test(o),BR:/bottom|br|right/.test(o)},strip,pad,cssHeight,j,bot,d,ds,bw,i,w,e,c,common,jQueryhorz;if(!opts.TL&&!opts.TR&&!opts.BL&&!opts.BR)opts={TL:1,TR:1,BL:1,BR:1};if(jQuery.fn.corner.defaults.useNative&&fx=='round'&&(radius||moz||webkit)&&!cc&&!sc){if(opts.TL)jQuerythis.css(radius?'border-top-left-radius':moz?'-moz-border-radius-topleft':'-webkit-border-top-left-radius',width+'px');if(opts.TR)jQuerythis.css(radius?'border-top-right-radius':moz?'-moz-border-radius-topright':'-webkit-border-top-right-radius',width+'px');if(opts.BL)jQuerythis.css(radius?'border-bottom-left-radius':moz?'-moz-border-radius-bottomleft':'-webkit-border-bottom-left-radius',width+'px');if(opts.BR)jQuerythis.css(radius?'border-bottom-right-radius':moz?'-moz-border-radius-bottomright':'-webkit-border-bottom-right-radius',width+'px');return};strip=document.createElement('div');jQuery(strip).css({overflow:'hidden',height:'1px',minHeight:'1px',fontSize:'1px',backgroundColor:sc||'transparent',borderStyle:'solid'});pad={T:parseInt(jQuery.css(this,'paddingTop'))||0,R:parseInt(jQuery.css(this,'paddingRight'))||0,B:parseInt(jQuery.css(this,'paddingBottom'))||0,L:parseInt(jQuery.css(this,'paddingLeft'))||0};if(typeof this.style.zoom!=undefined)this.style.zoom=1;if(!keep)this.style.border='none';strip.style.borderColor=cc||gpc(this.parentNode);cssHeight=jQuery(this).outerHeight();for(j in edges){bot=edges[j];if((bot&&(opts.BL||opts.BR))||(!bot&&(opts.TL||opts.TR))){strip.style.borderStyle='none '+(opts[j+'R']?'solid':'none')+' none '+(opts[j+'L']?'solid':'none');d=document.createElement('div');jQuery(d).addClass('jquery-corner');ds=d.style;bot?this.appendChild(d):this.insertBefore(d,this.firstChild);if(bot&&cssHeight!='auto'){if(jQuery.css(this,'position')=='static')this.style.position='relative';ds.position='absolute';ds.bottom=ds.left=ds.padding=ds.margin='0';if(expr)ds.setExpression('width','this.parentNode.offsetWidth');else ds.width='100%'}else if(!bot&&jQuery.browser.msie){if(jQuery.css(this,'position')=='static')this.style.position='relative';ds.position='absolute';ds.top=ds.left=ds.right=ds.padding=ds.margin='0';if(expr){bw=sz(this,'borderLeftWidth')+sz(this,'borderRightWidth');ds.setExpression('width','this.parentNode.offsetWidth - '+bw+'+ "px"')}else ds.width='100%'}else{ds.position='relative';ds.margin=!bot?'-'+pad.T+'px -'+pad.R+'px '+(pad.T-width)+'px -'+pad.L+'px':(pad.B-width)+'px -'+pad.R+'px -'+pad.B+'px -'+pad.L+'px'};for(i=0;i<width;i++){w=Math.max(0,getWidth(fx,i,width));e=strip.cloneNode(false);e.style.borderWidth='0 '+(opts[j+'R']?w:0)+'px 0 '+(opts[j+'L']?w:0)+'px';bot?d.appendChild(e):d.insertBefore(e,d.firstChild)};if(fold&&jQuery.support.boxModel){if(bot&&noBottomFold)continue;for(c in opts){if(!opts[c])continue;if(bot&&(c=='TL'||c=='TR'))continue;if(!bot&&(c=='BL'||c=='BR'))continue;common={position:'absolute',border:'none',margin:0,padding:0,overflow:'hidden',backgroundColor:strip.style.borderColor};jQueryhorz=jQuery('<div/>').css(common).css({width:width+'px',height:'1px'});switch(c){case'TL':jQueryhorz.css({bottom:0,left:0});break;case'TR':jQueryhorz.css({bottom:0,right:0});break;case'BL':jQueryhorz.css({top:0,left:0});break;case'BR':jQueryhorz.css({top:0,right:0});break};d.appendChild(jQueryhorz[0]);var jQueryvert=jQuery('<div/>').css(common).css({top:0,bottom:0,width:'1px',height:width+'px'});switch(c){case'TL':jQueryvert.css({left:width});break;case'TR':jQueryvert.css({right:width});break;case'BL':jQueryvert.css({left:width});break;case'BR':jQueryvert.css({right:width});break};d.appendChild(jQueryvert[0])}}}}})};jQuery.fn.uncorner=function(){if(radius||moz||webkit)this.css(radius?'border-radius':moz?'-moz-border-radius':'-webkit-border-radius',0);jQuery('div.jquery-corner',this).remove();return this};jQuery.fn.corner.defaults={useNative:true,metaAttr:'data-corner'}})(jQuery);
/*IE6png透明↓*/
(function(jQuery){var jspath=jQuery('script').last().attr('src');var basepath='';if(jspath.indexOf('/')!=-1){basepath+=jspath.substr(0,jspath.lastIndexOf('/')+1);}jQuery.fn.fixpng=function(options){function _fix_img_png(el,emptyGIF){var images=jQuery('img[src*="png"]',el||document),png;images.each(function(){png=this.src;width=this.width;height=this.height;this.src=emptyGIF;this.width=width;this.height=height;this.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+png+"',sizingMethod='scale')";});}function _fix_bg_png(el){var bg=jQuery(el).css('background-image');if(/url\([\'\"]?(.+\.png)[\'\"]?\)/.test(bg)){var src=RegExp.jQuery1;jQuery(el).css('background-image','none');jQuery(el).css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+src+"',sizingMethod='scale')");}}if(jQuery.browser.msie&&jQuery.browser.version<7){return this.each(function(){var opts={scope:'',emptyGif:basepath+'blank.gif'};jQuery.extend(opts,options);switch(opts.scope){case'img':_fix_img_png(this,opts.emptyGif);break;case'all':_fix_img_png(this,opts.emptyGif);_fix_bg_png(this);break;default:_fix_bg_png(this);break;}});}}})(jQuery);
//全局函数↓
function pressCaptcha(obj){
	obj.value=obj.value.toUpperCase();
}
function ResumeError(){return true};
window.onerror=ResumeError;

function ifie(){
	return document.all;
}
function SetHome(obj,vrl,info){
	if(!ifie()){
		alert(info);
	}
	try{
		obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
	}catch(e){
		if(window.netscape){
			try{
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}catch(e){
				alert("Your Browser does not support");
			}
			var prefs=Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
				prefs.setCharPref('browser.startup.homepage',vrl);
		}
	}
}
function addFavorite(info){
	if(!ifie()){
		alert(info);
	}
	var vDomainName=window.location.href;
	var description=document.title;
	try{
		window.external.AddFavorite(vDomainName,description);
	}catch(e){
		window.sidebar.addPanel(description,vDomainName,"");
	}
}
function metHeight(group){
	tallest=0;
	group.each(function(){
		thisHeight=jQuery(this).height();
		if(thisHeight>tallest){
			tallest=thisHeight;
		}
	});
	group.height(tallest);
}
function metmessagesubmit(info3,info4){
	if(document.myform.pname.value.length==0){
		alert(info3);
		document.myform.pname.focus();
		return false;
	}
	if(document.myform.info.value.length==0){
		alert(info4);
		document.myform.info.focus();
		return false;
	}
}
function addlinksubmit(info2,info3){
	if(document.myform.webname.value.length==0){
		alert(info2);
		document.myform.webname.focus();
		return false;
	}
	if(document.myform.weburl.value.length==0||document.myform.weburl.value=='http://'){
		alert(info3);
		document.myform.weburl.focus();
		return false;
	}
}
function textWrap(my){
	var text='',txt=my.text();
		txt=txt.split("");
		for(var i=0;i<txt.length;i++){
			text+=txt[i]+'<br/>';
		}
		my.html(text);
}
function DownWdith(group){
	tallest=0;
	group.each(function(){
		thisWdith=jQuery(this).width();
		if(thisWdith>tallest){
			tallest=thisWdith;
		}
	})
	group.width(tallest);
}
//以下为执行代码
var module=Number(jQuery("#metuimodule").data('module'));
$(document).ready(function(){
	switch(module){
		case 3://产品模块
			var plist = jQuery('#productlist');
			if(plist.size()>0){
				metHeight(plist.find('li'));
			}else{
				var pshow=jQuery(".pshow");
				var pshow_ddwh=pshow.width()-pshow.find("dt").width()-5;
				pshow.find("dt").width(function(){ return $(this).width()});
				pshow.find("dd").width(pshow_ddwh);
				var parlt=jQuery('.pshow dd li');
				parlt.each(function(){
					var parst=jQuery(this).find('span');
					jQuery(this).css('padding-left',parst.outerWidth()+5);
				});
			}
		break;
		case 4://下载模块
			var showdownload = jQuery('#showdownload');
			if(showdownload.size()>0){
				var parlt=jQuery('.paralist li');
				parlt.each(function(){
					var parst=jQuery(this).find('span');
					if(parst.height()<jQuery(this).height())parst.height(jQuery(this).height());
				});
				DownWdith(jQuery('.paralist li span'));
				jQuery('.paralist li:last').css("border-bottom","0");
			}
		break;
		case 5://图片模块
			var ilist = jQuery('#imglist');
			if(ilist.size()>0){
				metHeight(ilist.find('li'));
			}
		break;
	}
	jQuery('.myCorner').corner();//圆角
	var ie6 = (navigator.appName == "Microsoft Internet Explorer" && parseInt(navigator.appVersion) == 4 && navigator.appVersion.indexOf("MSIE 6.0") != -1);
	if (jQuery.browser.msie && ie6){
		jQuery(document.body).fixpng({scope:'img'});
	}
});