/*

织梦科技 “会员中心表格相关” 动作

2008.10.14 10:48 for Fangyu12@gmail.com

Last modified 2008.10.14 17:30

Copyright (c) 2008, dedecms All rights reserved.

*/
$(document).ready(function(){
	//表格奇偶行不同样式	
	$(".list tbody tr:even").addClass("row0");//偶行
	$(".list tbody tr:odd").addClass("row1");//奇行

	$(".submit tbody tr:even").addClass("row0");//偶行
	$(".submit tbody tr:odd").addClass("row1");//奇行
	
	$(".friend:odd").addClass("row1");
	
	//书签
	$("#linkList .flink:odd").addClass("row1");
	
	//点击单元格时该行高亮
	$(".list tbody tr td").click(function(){$(this).parent("tr").toggleClass("click");$(this).parent("tr").toggleClass("hover");	});
	$(".list tbody tr td").hover(function(){$(this).parent("tr").addClass("hover"); },function(){$(this).parent("tr").removeClass("hover"); });
	//checked 全选&反选&单选
	$("#checkedClick").click(function(){
		$(".list tbody [type='checkbox']").each(function(){
			if($(this).attr("checked")){
				$(this).removeAttr("checked");				
				$(".list tbody tr").removeClass("click");
				}
			else{
				$(this).attr("checked",'true');				
				$(".list tbody tr").addClass("click");
				}
		})
	});
	
	//checked 全选&反选&单选
	$("#checkedClick").click(function(){
		$("form [type='checkbox']").each(function(){
			if($(this).attr("checked")){
				$(this).removeAttr("checked");
				}
			else{
				$(this).attr("checked",'true');
				}
		})
	});
	
	//项目-收藏 未完带,续头脑混乱ing 注:方域
	$(".favorite #allDeploy").click(function(){$(".itemBody").toggleClass("invisible");});
	$(".favorite .itemHead").click(function(){$(this).next(".itemBody").toggleClass("invisible");});
	
	//项目-好友friend
	$(".friend .itemHead").click(function(){$(this).next(".itemBody").toggleClass("invisible");});
	//项目-搜索好友friend
	$(".search .itemHead").click(function(){$(this).next(".itemBody").toggleClass("invisible");});
	//项目-探访visit
	$("#allDeploy").click(function(){$(".itemBody").toggleClass("invisible");});
	$(".visit .itemHead").click(function(){$(this).next(".itemBody").toggleClass("invisible");});
	//项目-详细资料info
	$(".info .itemHead").click(function(){
										$(this).next(".itemBody").toggleClass("invisible");
										$(this).children(".icon16").toggleClass("titHide");
										$(this).children(".icon16").toggleClass("titShow")});
	//服务购买
	/*$(".card").load( function() {$(this).addClass("invisible")});
	$(".level").load( function() {$(this).addClass("invisible")});
	$(".rechargeable").load( function() {$(this).addClass("invisible")});
														  
	$("#buy").click(function(){$(".rechargeable").addClass("invisible");$(".card").removeClass("invisible");$(".level").removeClass("invisible");});
	$("#rechargeable").click(function(){$(".rechargeable").removeClass("invisible");$(".card").addClass("invisible");$(".level").addClass("invisible");});
	
	$(".buyCard").click(function(){$(".card").removeClass("invisible");$(".level").addClass("invisible");});
	$(".buyLevel").click(function(){$(".card").addClass("invisible");$(".level").removeClass("invisible");});*/
	
	
	
});