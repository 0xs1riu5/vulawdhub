(function($){var jspath=$('script').last().attr('src');var basepath='';if(jspath.indexOf('/')!=-1){basepath+=jspath.substr(0,jspath.lastIndexOf('/')+1);}$.fn.fixpng=function(options){function _fix_img_png(el,emptyGIF){var images=$('img[src*="png"]',el||document),png;images.each(function(){png=this.src;width=this.width;height=this.height;this.src=emptyGIF;this.width=width;this.height=height;this.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+png+"',sizingMethod='scale')";});}function _fix_bg_png(el){var bg=$(el).css('background-image');if(/url\([\'\"]?(.+\.png)[\'\"]?\)/.test(bg)){var src=RegExp.$1;$(el).css('background-image','none');$(el).css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+src+"',sizingMethod='scale')");}}if($.browser.msie&&$.browser.version<7){return this.each(function(){var opts={scope:'',emptyGif:basepath+'blank.gif'};$.extend(opts,options);switch(opts.scope){case'img':_fix_img_png(this,opts.emptyGif);break;case'all':_fix_img_png(this,opts.emptyGif);_fix_bg_png(this);break;default:_fix_bg_png(this);break;}});}}})(jQuery);function timehide(dom,time){if(!dom.is(':hidden')){t=setTimeout(function(){dom.hide();},time);dom.hover(function(){clearTimeout(t);},function(){timehide(dom,time);});}}
function mhover(dom,cs){if(dom)dom.hover(function(){$(this).addClass(cs);},function(){$(this).removeClass(cs);});}
function cknav(d){if(d instanceof jQuery){if(d.attr("id")!='top_quick_a'){$('#topnav a').removeClass("onnav");d.addClass("onnav");$("ul[id^='ul_']").hide();var u=String(d.attr('id'));u=u.split('_');u=u[1];$.cookie('conav',null);$.cookie('conav',u,{path:'/'});$("#ul_"+u).show();u=$("#ul_"+u).find('li a').eq(0);frameget(u);}}else{cknav($('#nav_'+d));}}
function frameget(u){if(u.attr('target')){if(u.attr('target')=='_blank')return false;$("#leftnav a").removeClass("on");$("#main").attr("src",u.attr('href'));$("#main").src=$("#main").src;u.addClass("on");var l=u.attr('id');l=l.split('_');$.cookie('coul',null);$.cookie('coul',l[2],{path:'/'});}}
function dheight(){var l=$(document).height()-77;var m=$("#main").contents().find("body").height();l=m>l?m:l;if(m<700&&l>700)l=700;$('#metleft').height(l);$('#metright').height(l);$('#main').height(l);var n=l-10-25-35;$('#leftnav').height(n);}
function qielang(lt,msg){var l=$('#langcion').attr('title');lt.empty().append(msg);var m=$('div.langlist li');mhover(m,'hv');m.each(function(){var d=$(this);d.attr('title')==l?d.addClass('dlang'):d.removeClass('dlang');});lt.show();$('div.langlist li').click(function(){var d=$(this);var t=d.attr('title');var l=$('#langcion').attr('title');if(t!=l){var h=$('#leftnav li a.on').attr('href');if(!h){h='system/sysadmin.php?anyid=8&';}else{h=h.split('lang=');h=h[0];}
var n=$("#leftnav a[id^='nav_']");n.each(function(){var a=$(this).attr('href');a=a.split('lang=');a=a[0]+'lang='+t;$(this).attr('href',a);});$('#langcion').attr('title',t);var hr=h+'lang='+t;$('#main').attr('src',hr);$('.langtxt #langcion').empty().append(d.text());m.removeClass('dlang');d.addClass('dlang');if(t!=l)$.cookie('clang',t);adminp(l,t);}
lt.hide();});}
function adminp(l,t){if(langar[l]==langar[t]){}else{window.location.href='index.php?lang='+t;}}
function commercial_license(){$.ajax({url:'system/authcode.php?lang='+lang+'&autcod=1&cs=1&action_ajax=1',type:"GET",success:function(data){if(data==0){var txt='未'+'购'+'买'+'商'+'业'+'授'+'权';if(!window.atop){data=txt;}else{data=atop==''?txt:atop;}}
$(".top-"+"r-t").append('<div id="met'+'info'+'_lice'+'nse"></div>');var met=$("#metinfo_license");met.html('<a href="javascript:;" title="'+data+'" id="license_isok">'+data+'</a>');met.find('a').css({'color':'#f2fb02'});$('#license_isok').live('click',function(){$('#nav_1').click();$('#nav_1_15').click();});}});}

var conav = $.cookie('conav');
var coul = $.cookie('coul');
if (conav) {
	cknav(conav);
	if (coul) frameget($('#nav_' + conav + '_' + coul));
}
$("#main").load(function() {
	dheight();
})
 $('#topnav a').click(function() {
	cknav($(this));
});
$("#leftnav a").click(function() {
	frameget($(this));
});
var lg = $('#langcig');
var lt = lg.next('div.langlist');
lg.click(function() {
	lt.empty().append('<div style="text-align:center; color:#666;">' + user_msg['jsx1'] + '</div>');
	if (lt.is(':hidden')) {
		$.ajax({
			type: "POST",
			url: "include/return.php?type=lang",
			success: function(msg) {
				if (msg != '') {
					qielang(lt, msg);
				}
			}
		});
	}
	lt.is(':hidden') ? $("#langcig").addClass('nowt') : $("#langcig").removeClass('nowt');
	lt.is(':hidden') ? lt.show() : lt.hide();
	return false;
});
$('#hthome').click(function() {
	$.cookie('conav', 1);
	$.cookie('coul', 8);
});
$('#met_logo').click(function() {
	$.cookie('conav', 1);
	$.cookie('coul', 8);
});
$('#mydata').click(function() {
	$.cookie('conav', 7);
	$.cookie('coul', 48);
});
$('#qthome').click(function() {
	var h = '../index.php?lang=';
	var l = $('#langcion').attr('title') ? $('#langcion').attr('title') : lang;
	$(this).attr('href', h + l);
});
$('#outhome').click(function() {
	$.cookie('conav', null);
	$.cookie('clang', null);
});
$("body").click(function() {
	$("#langcig").removeClass('nowt');
	$(".langlist").hide();
})
 $(".langkkkbox").hover(function() {
	$(this).addClass('now');
},
function() {
	$(this).removeClass('now');
});
$(document).ready(function() {
	commercial_license();
});