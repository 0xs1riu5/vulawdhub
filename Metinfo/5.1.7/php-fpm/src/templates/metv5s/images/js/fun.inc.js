/*模板自定义函数*/
function proxy(dom,lei,type){
	if(dom){
		dom.hover(function(){
			jQuery(this).addClass(lei);
			if(type==1){
				if(jQuery(this).find('.sub').length>0){
					jQuery(this).find('.sub').show()
				}
				else{
					jQuery(this).addClass(lei+'er')
				}
			}
		}
		,function(){
			jQuery(this).removeClass(lei);
			if(type==1){
				if(jQuery(this).find('.sub').length>0){
					jQuery(this).find('.sub').hide()
				}
				else{
					jQuery(this).removeClass(lei+'er');
				}
			}
		})
	}
}
function navnow(dom,part2,part3,none){
	var li=dom.find(".navnow dt[id*='part2_']");
	var dl=li.next("dd.sub");
	if(none)dl.hide();
	if(part2.next("dd.sub").length>0)part2.next("dd.sub").show();
	if(part3.length>0)part3.parent('dd.sub').show();
	li.bind('click',function(){
		var fdl=jQuery(this).next("dd.sub");
		if(fdl.length>0){
			fdl.is(':hidden')?fdl.show():fdl.hide();
			fdl.is(':hidden')?jQuery(this).removeClass('launched'):jQuery(this).addClass('launched');
			fdl.is(':hidden')?jQuery(this).addClass('launchedshow'):jQuery(this).removeClass('launchedshow');
		}
	})
}
function partnav(c2,c3,jsok){
	var part2=jQuery('#part2_'+c2);
	var part3=jQuery('#part3_'+c3);
	if(part2)part2.addClass('on');
	if(part3){
		part3.addClass('on');
		part3.parent('dd').prev('dt').addClass('on');
	}
	if(jsok!=0)navnow(jQuery('#sidebar'),part2,part3);
}
/*自定义函数结束*/
//以下为执行代码
var module=Number(jQuery("#metuimodule").data('module'));//获取当前模块
jQuery("#web_logo,nav ul li a").bind("focus",function(){if(this.blur)this.blur();});//IE
proxy(jQuery("nav ul li[class!='line']"),'hover');//鼠标经过导航
if(module==10001){//首页
	$('.dl-jqrun').each(function(){
		var dt = $(this).find('dt');
		var dd = $(this).find('dd');
		var wt = $(this).width()-dt.width();
			dd.width(wt);
	});
}else{//内页
   jQuery("#sidebar dt,#sidebar h4").hover(
       function () {
          jQuery(this).addClass("dthover");
        },
       function () {
          jQuery(this).removeClass("dthover");
       }
    );
	var csnow=jQuery("#sidebar").attr('data-csnow'),class3=jQuery("#sidebar").attr('data-class3'),jsok=jQuery("#sidebar").attr('data-jsok');
	partnav(csnow,class3,jsok);//侧栏导航点击展开隐藏
	jQuery('#ny_navx a:last').addClass('now_navx');
}