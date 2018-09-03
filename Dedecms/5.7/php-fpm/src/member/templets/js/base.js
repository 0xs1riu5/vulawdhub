$(function(){
		//文本框Style
		$(".text").mouseover(function(){
			$(this).addClass("text_o");
		}).mouseout(function(){
			$(this).removeClass("text_o");
		}).focus(function(){
			$(this).addClass("text_s");
		}).blur(function(){
			$(this).removeClass("text_s");
		});
		$(".intxt").mouseover(function(){
			$(this).addClass("text_o");
		}).mouseout(function(){
			$(this).removeClass("text_o");
		}).focus(function(){
			$(this).addClass("text_s");
		}).blur(function(){
			$(this).removeClass("text_s");
		});
		
 })
		