$(document).ready(function(){
	//设置变量
	open_write_twitter=$("#open_write_twitter");
	open_menu=$("#open_menu");
	close_menu=$("#close_menu");
	open_post_twitter=$("#open_post_twitter");
	open_zf_twitter=$("#open_zf_twitter");
	close_post_twitter=$("#close_post_twitter");
	close_zf_twitter=$("#close_zf_twitter");
	post_twitter_input=$("#post_twitter_input");
	zf_twitter=$("#zf_twitter");
	menu=$("#menu");
	post_twitter=$("#post_twitter");
	no=$("#no");
	default_menu_box_option=$("#default_menu_box_option");
	more_menu_box_option=$("#more_menu_box_option");
	open_more=$("#open_more");
	back2_default_menu_box_option=$("#back2_default_menu_box_option");
	post_tip=$("#post_tip");
	menu_box=$("#menu_box");
	c=$(".c");
	var i=0;
  	var start_x=0;
  	var start_y=0;
  	//设置头部图片宽度
  	function resize_logo(){
	  	logo_width=$(window).width();
	  	logo_height=logo_width/720*300;
	  	$("#header_logo").attr({
	  		"width":logo_width,
	  		"height":logo_height
	  	});
	  	$("#content").css("margin-top",logo_height);
  	}
  	resize_logo();
  	$(window).resize(function(){
  		resize_logo();
  		// window.location.reload();
  	});
	function x_click(x,box){
		dw2=$(window).width()/2;
		dh2=$(window).height()/2;
		box_w=box.width();
		box_h=box.height();
		x.click(function(e){
			to_top=$(document).scrollTop();
			page_x=e.pageX-box_w/2*0.1;
			page_y=e.pageY-box_h/2*0.1-to_top;
			if(i==0){
              	start_x=page_x;
  				start_y=page_y;
				box.show();
				box.css({
					"top":page_y+"px",
					"left":page_x+"px",
					// "top":"50%",
					// "left":"50%",
					"margin-top":"-" + box_h/2 + "px",
					"margin-left":"-25%"
				});
				box.removeClass("menu_ani_hide");
				box.addClass("menu_ani_show");
				box.animate({
					opacity:1,
					top:dh2 +"px",
					left:dw2+"px"
				},300);
				i=1;
				$(this).addClass("c_mousedown");
			}else{
				// box.fadeOut(500);
				box.removeClass("menu_ani_show");
				box.addClass("menu_ani_hide");
				box.animate({
					top:start_y+"px",
					left:start_x+"px",
					opacity:0
				},500,function(){
					$(this).hide();
				});
				i=0;
				more_menu_box_option.hide();
				default_menu_box_option.show();
				x.removeClass("c_mousedown");
			}
		});
	}//end of function x_click()
	function open_box(x,openbox,hidebox){
		x.click(function(){
			hidebox.slideUp(300);
			openbox.slideDown(300);
			i=0;
			c.removeClass("c_mousedown");
		});
	}
	//修正i数值
	function click4i(x,x_i){
		x.click(function(){
			i=x_i;
		});
	};
	//输入框提示
	function p_tip(x){
		post_tip.text(x);
	}
	//点开选项菜单
	x_click(c,menu_box);
	//打开主菜单
	open_box(open_menu,menu,menu_box);
	open_box(close_menu,no,menu);
	//写分享
	open_box(open_write_twitter,post_twitter,menu_box);
	open_write_twitter.click(function(){
		post_twitter_input.focus();
		p_tip("发表分享：");
	});
	//回复
	open_box(open_post_twitter,post_twitter,menu_box);
	open_post_twitter.click(function(){
		post_twitter_input.focus();
		p_tip("回复分享：");
	});
	open_box(close_post_twitter,no,post_twitter);
	//转发
	open_box(open_zf_twitter,zf_twitter,menu_box);
	open_zf_twitter.click(function(){
		zf_twitter_input.focus();
	});
	open_box(close_zf_twitter,no,zf_twitter);
	open_box(open_more,more_menu_box_option,default_menu_box_option);
	open_box(back2_default_menu_box_option,default_menu_box_option,more_menu_box_option);
});