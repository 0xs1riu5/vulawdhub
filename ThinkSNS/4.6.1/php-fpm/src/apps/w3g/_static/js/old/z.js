$(function(){
	//设置变量
	post_twitter=$("#post_twitter");
	default_menu_box_option=$("#default_menu_box_option");
	more_menu_box_option=$("#more_menu_box_option");
	back2_default_menu_box_option=$("#back2_default_menu_box_option");
	post_tip=$("#post_tip");
	menu_box=$("#menu_box");
	shadow=$("#shadow");
	sc=$(".sc");
	part_shadow=$("#part_shadow");
	sys_menu=$("#sys_menu");
	menu_button=$("#menu_button");
	window_width=$(window).width();
	window_height=$(window).height();
	feed_img_view=$("#feed_img_view");
	feed_img_view_img=$("#feed_img_view_img");
	feed_img_view_img_box=$("#feed_img_view_img_box");
	post_twitter_type="fb";
	msg_tip=$("#msg_tip");
	fi_st=0;//图片预览状态判断
	feed_id=0;
	app_id=0;
	row_id=0;
	to_uid=0;
	cc_id=0;
	cname='';
	commentid=0;
	isdel=0;
	attach_id='';
	feed_attach_type='';
	page=$('#content').attr('interface');
	if(page=='index_list' || page=='square_list'){page_interface=1}else{page_interface=0}
	// 根据页面判断显示
	switch(page){
		case 'weibo':
			$('#open_zz').addClass('hide');
			break;
		case 'detail':
			$('#open_hf_twitter,#open_yw').addClass('hide');
			break;
	}
	//缓存用户ID
	uid=Number($("body").attr("uid"));
	//粉丝页面缓存
	fensi_box=$(".fensi_box");
	fensi_box_guanzhu_button=$(".fensi_box_guanzhu_button,#i_info_follow");
	//设置hash动作缓存
	back2do="";
	hash_old="";
  	var start_x=0;
  	var start_y=0;
  	$(window).resize(function(){
  		window_width=$(window).width();
		window_height=$(window).height();
  	});
 
//点开选项菜单
	$(document).on('tap','.c,.c_replyme,.c_atme',function(){
		back2do="shadow.tap";
		feed_id=$(this).attr("cid");
		row_id=$(this).attr("rowid");
		app_id=$(this).attr("appid");
		to_uid=$(this).attr("touid");
		cc_id=$(this).attr("ccid");
		// 若原文已被删除，则禁止转发
		if($(this).attr('isdel')=='1' || $(this).attr('isdel2')=='1'){
			isdel=1;
			$('#open_zf_twitter').addClass('isdel');
		}else{
			isdel=0;
			$('#open_zf_twitter').removeClass('isdel');
		}
		commentid=$(this).attr("commentid");
		//若为收到的评论页面，则隐藏转发和赞
		if($(this).hasClass("c_replyme") || $(this).attr("type")=="comment"){
			$("#open_zf_twitter,#digit").addClass('hide');
		}else{
			$("#open_zf_twitter,#digit").removeClass('hide');
			if(Number(app_id)==uid){
				$("#digit").addClass('hide');
			}else if($(this).attr("isdig")=="0"){
				$("#digit").text("赞").removeClass("isdig hide");
			}else{
				//已关注，改变menu_box中的文字
				$("#digit").text("已赞").addClass("isdig").removeClass("hide");
			}
		}
		// 判断下划线显示问题
		if(!$("#digit").hasClass('hide')){
			$('.menu_box_option').removeClass('noborder');
			$("#digit").addClass('noborder');
		}else if(!$('#open_zz').hasClass('hide')){
			$('.menu_box_option').removeClass('noborder');
			$("#open_zz").addClass('noborder');
		}else if(!$('#open_yw').hasClass('hide')){
			$('.menu_box_option').removeClass('noborder');
			$("#open_yw").addClass('noborder');
		}
		// 计算menu_box高度
		var menuBoxHeight=($('#default_menu_box_option>.menu_box_option').length-$('#default_menu_box_option>.hide').length)*45;
		menu_box.css({
			"margin-top":"-"+menuBoxHeight/2+"px",
			"margin-left":"-"+window_width*0.2+"px",
			"top":$(window).height()/2+"px",
			"left":$(window).width()/2+"px"
		});
		// menu_box.show();
		menu_box.show();
		i=1;
		$(this).addClass("c_mousedown");
		shadow.show();
	});
//赞功能
$(document).on('tap','#digit',function(){
	var p = {feed_id:feed_id};
	if(!$(this).hasClass('ismy')){
		if($(this).hasClass('isdig')){
			var url = U('public/Feed/delDigg');
			$.ajax({
				type:"POST",
				url :url,
				data:p,
				dataType:"json",
				timeout:10000,
				success:function(r){
					if(r.status=="1"){
						$('#digg_'+feed_id).removeClass('digged');
						tips('取消赞成功:)',0,1);
						$('.c_mousedown').attr("isdig","0");
						i=0;
						more_menu_box_option.hide();
						default_menu_box_option.show();
						$(".c,.c_replyme").removeClass("c_mousedown");
						menu_box.hide();
						shadow.hide();
			        } else {
						tips('亲，取消赞失败，可能您已经取消!',1,0);
			        }
				},
				error:function(xhr,type){
					tips('亲，服务器连接出错，求重试!',1,0);
				}
			});
		}else{
			var url = U('public/Feed/addDigg');
			$.ajax({
				type:"POST",
				url :url,
				data:p,
				dataType:"json",
				timeout:10000,
				success:function(r){
					if(r.status!="0"){
						$('#digg_'+feed_id).addClass('digged');
						tips('已赞!',0,1);
						$('.c_mousedown').attr("isdig","1");
						menu_box.removeClass("menu_ani_show");
						menu_box.addClass("menu_ani_hide");
						i=0;
						more_menu_box_option.hide();
						default_menu_box_option.show();
						$(".c,.c_replyme").removeClass("c_mousedown");
						menu_box.hide();
						shadow.hide();
			        } else {
						tips('亲，没有赞成功，求重试!',1,0);
			        }
				},
				error:function(xhr,type){
					tips('亲，服务器连接出错，求重试!',1,0);
				}
			});
		}
	}
});
$(document).on('tap','#shadow',function(){
	back2do="c.tap";
	i=0;
	$(".c_mousedown").removeClass("c_mousedown");
	menu_box.hide();
	shadow.hide();
});
	//打开菜单--更多
	menu_box.on('tap','#open_more',function(){
		menu_box_height=menu_box.height();
		menu_box.css({
			"overflow":"hidden"
		});
		default_menu_box_option.hide();
		more_menu_box_option.show();
	});
	//返回默认菜单
	$(document).on('tap','#back2_default_menu_box_option',function(){
		default_menu_box_option.show();
		more_menu_box_option.hide();
	});
	//赞
	function digg_tap(){
		$('.digg').bind('tap',function(){
			feed_id=$(this).data('cid');
			var p = {feed_id:feed_id};
			if($(this).hasClass('digged')){
				$(this).removeClass('digged');
				$.ajax({
					type:"POST",
					url :U('public/Feed/delDigg'),
					data:p,
					dataType:"json",
					timeout:10000,
					success:function(r){
						if(r.status=="0"){
							tips('亲，取消赞失败，求重试!',1,0);
				        }else{
				        	$('#c_'+feed_id).attr('isdig','0');
				        }
					},
					error:function(xhr,type){
						tips('亲，服务器连接出错，求重试!',1,0);
					}
				});
			}else{
				$(this).addClass('digged');
				$.ajax({
					type:"POST",
					url :U('public/Feed/addDigg'),
					data:p,
					dataType:"json",
					timeout:10000,
					success:function(r){
						if(r.status=="0"){
							tips('亲，没有赞成功，求重试!',1,0);
				        }else{
				        	$('#c_'+feed_id).attr('isdig','1');
				        }
					},
					error:function(xhr,type){
						tips('亲，服务器连接出错，求重试!',1,0);
					}
				});
			}
			event.stopPropagation();
		});
	}
	digg_tap();
	//收藏
	function sc_tap(){
		$(".sc").bind("tap",function(){
			var sc_cid = $(this).attr("cid");
			var sc_type = $(this).attr("type");
			if($(this).hasClass("sc_1")){
				$("#sc_"+sc_cid).removeClass("sc_1");
				$.ajax({
					type:'POST',
					url:U('w3g/Index/doUnFavorite'),
					dataType:'text',
					timeout:10000,
					data:{
						"feed_id":sc_cid,
						"type":sc_type
					},
					success:function(data,status){
						if(data==0 || status!='success'){
							$("#sc_"+sc_cid).addClass("sc_1");
						}
					},
					error:function(data,status){
						$("#sc_"+sc_cid).addClass("sc_1");
					}
				});
			}else{
				$("#sc_"+sc_cid).addClass("sc_1");
				$.ajax({
					type:'POST',
					url:U('w3g/Index/doFavorite'),
					dataType:'text',
					timeout:10000,
					data:{
						"feed_id":sc_cid,
						"type":sc_type
					},
					success:function(data,status){
						if(data==0 || status!='success'){
							$("#sc_"+sc_cid).addClass("sc_1");
						}
					},
					error:function(data,status){
						$("#sc_"+sc_cid).removeClass("sc_1");
					}
				});
			}
			event.stopPropagation();
		});
	}
	sc_tap();
	//回复和转发分享
	menu_box.on('tap','#open_hf_twitter',function(){
		location.hash="hf_twitter";
	});
	menu_box.on('tap','#open_zf_twitter',function(){
		if(isdel==0){location.hash="zf_twitter";}
	});
	//分享详情页回复
	$(document).on('tap','#c_comment_post_box',function(){
		feed_id=$(this).attr("weibo_id");
		row_id=feed_id;
		app_id=$(this).attr("appid");
		location.hash="hf_twitter_detail";
	});
	$(document).on('tap','.c_comments',function(){
		cc_id=$(this).attr("ccid");
		row_id=$(this).attr("rowid");
		app_id=$(this).attr("appuid");
		to_uid=$(this).attr("touid");
		commentid=$(this).attr('ccid');
		cname=$('#cc_name_'+cc_id).text();
		location.hash="hf_twitter_c_comments";
	});
	$(document).on('tap','#close_post_twitter',function(){
		location.hash="thinksns";
	});
	//点击头部输入框
	$(document).on('tap','#to_post_twitter_input',function(){
		location.hash="post_twitter";
	});
	// 右上角按钮点击
	menu_button_tap=0;
	if_scroll=0;
	$("#menu_button").addClass("menu_button_normal");
	$(document).on('tap','#menu_button',function(){
		if(menu_button_tap==0){
			part_shadow.show();
			sys_menu.show();
			if(if_scroll==1){
				$(this).css({
					"background-image": "url(img/menu_button.fw.png)",
					"background-color":"white",
					"opacity":1
				});
			}else{
				$(this).css({
					"background-image": "url(img/menu_button.fw.png)",
					"background-color":"white",
					"opacity":1
				});
			}
			menu_button_tap=1;
		}else{
			if(if_scroll==1){
				menu_button.css({
					"background-image": "url(img/menu_button_0.fw.png)",
					"background-color":"#2980B9",
					"opacity":0.5
				});
			}else{
				menu_button.css({
					"background-image": "url(img/menu_button_0.fw.png)",
					"background-color":"",
					"opacity":1
				});
			}
			sys_menu.hide();
			part_shadow.hide()
			menu_button_tap=0;
		}
	});
	//用户选择评论+转发//转发+评论
	$(document).on('tap','.no_check',function(){
		$(this).toggleClass('checked');
	});
	//用户提交回复，ajax
	$(document).on('tap','#post_twitter_button_submit',function(){
		$(this).focus();
		content=$("#post_twitter_input").val();
		var at='';
		var content_length_max=Number($('#ptibox').attr("nums"));
		var comment_old='';
		if(commentid!=null && commentid>0){
			post_feed_id=commentid;
		}else{
			post_feed_id=feed_id;
		}
		if($("#ifShareFeed").hasClass("checked")){
			var ifShareFeed = 1;
			if(!$('#c_'+feed_id).hasClass('c_replyme')){
				var comment_old=$("#c_content_"+feed_id).html();
			}else{
				var comment_old=$("#c_content_"+feed_id).html()+'//'+$('#c_content_yw_'+feed_id).html();
			}
			if(/^[\t \n]*$/ig.test(comment_old)){
				comment_old='';
			}
			if($('#c_'+feed_id).has('.c_zf_box').length==0){
				comment_old='';
			}
			at=$('#c_info_name_'+feed_id).text();
		}else{
			var ifShareFeed = 0;
		}
		if($("#ifAsComment").hasClass("checked")){
			var comment=1;
			var comment_old=$("#c_content_"+feed_id).html();
			if(/^[\t \n]*$/ig.test(comment_old)){
				comment_old='';
			}
			if($('#c_'+feed_id).has('.c_zf_box').length==0){
				comment_old='';
			}
			at=$('#c_info_name_'+feed_id).text();
		}else{
			var comment=0;
		}
		if($('#post_twitter_input').data('type')==='image' || $('#post_twitter_input').data('type')==='file'){
			feed_attach_type=$('#post_twitter_input').data('type');
			attach_id=$('#post_twitter_input').data('attach-id');
		}
		if(content.length>content_length_max){
			tips("分享超过"+ content_length_max +"字，请重新编辑后发送",1,0);
		}else if(r_null(content)){
			tips("内容不能为空!",1,0);
		}else{
			var post_type=$("#post_form").attr("type");
			//处理评论+转发
			if(post_type=='hf' && ifShareFeed==1){
				post_type='zf';
				comment=1;
				var comment_old=$("#c_content_"+feed_id).html();
				if(/^[\t \n]*$/ig.test(comment_old)){
					comment_old='';
				}
				if($('#c_'+feed_id).has('.c_zf_box').length==0){
					comment_old='';
				}
				at=$('#c_info_name_'+feed_id).text();
				ifShareFeed=0;
				$("#c_"+feed_id).attr('feedtype');
				$("#post_form").attr("action",U('w3g/Index/doForward'));
			}
			switch(post_type){
				case "fb":
					tips("发表分享中...",2,0);
					break;

				case "zf":
					if(ifAsComment==0){
						tips("转发分享中...",2,0);
					}else{
						tips("评论转发中...",2,0);
					}
					var comment_old=$("#c_content_"+feed_id).text();
					break;

				case "hf":
					tips("发表评论中...",2,0);
					break;

				case "xq_hf":
					tips("发表评论中...",2,0);
					break;
			}
			$.ajax({
				type:'POST',
				url:$("#post_form").attr("action"),
				data:{
					feed_id:post_feed_id,
					content:content,
					comment_id:commentid,
					rowid:row_id,
					appid:app_id,
					touid:to_uid,
					ccid:cc_id,
					ifShareFeed:ifShareFeed,
					comment:comment,
					comment_old:comment_old,
					at:at,
					type:$("#c_"+feed_id).attr('feedtype'),
					feed_attach_type:feed_attach_type,
					attach_id:attach_id
				},
				timeout: 10000,
				dataType:'text',
				success:function(data){
					if(data!="参数错误" && data!="内容不能为空" && data!="0" && data!="只能上传图片附件" &&data!="发布失败，字数超过限制"){
						$(".c").removeClass("c_mousedown");
						location.hash="thinksns";
						hash_old="thinksns";
						switch(post_type){
							case "fb":
							tips("发表成功！",0,1);
							$("#to_post_twitter").after(data);
							// 阻止事件冒泡
							$('.attachs_a').click(function(){event.stopPropagation();});
							$('.attachs_a').tap(function(){event.stopPropagation();});
							sc_tap();
							break;

							case "zf":
							if(ifAsComment==0){
								tips("转发成功！",0,1);
							}else{
								tips("评论并转发成功！",0,1);
							}
							$("#to_post_twitter").after(data);
							view_pic();
							sc_tap();
							break;

							case "hf":
							console.log(ifShareFeed);
							tips("评论成功！",0,1);
							break;

							case "xq_hf":
							tips("评论成功！",0,1);
							$("#c_comment_box").html(data);
							break;
						}
						location.hash="thinksns";
						view_pic();
						viewWeiba();
						s2();
					}else{
						if(data!="0"){
							tips(data,1,0);
						}else{
							tips("发表间隔过短，请稍候重试:)",1,0);
						}
					}
				},
				error:function(xhr,type){
					tips('服务器反馈错误，请稍候重试:)',1,0);
					// tips('error:\mxhr:'+xhr+'\ntype:'+type,1,0);
				}
			});
		}
		return false;
	});
	//去除菜单底线
	$(".sys_menu_option").last().css({
		"border":0
	});
	//点击part_shadow
	part_shadow.tap(function(){
		if(if_scroll==1){
				menu_button.css({
					"background-image": "url(img/menu_button_0.fw.png)",
					"background-color":"#2980B9",
					"opacity":0.5
				});
			}else{
				menu_button.css({
					"background-image": "url(img/menu_button_0.fw.png)",
					"background-color":"",
					"opacity":1
				});
			}
		// sys_menu.removeClass("sys_menu_active").addClass("sys_menu_hide");
		sys_menu.hide();
		part_shadow.hide();
		menu_button_tap=0;
	});
	//菜单选择写分享
	$(document).on('tap','#sys_menu_wtire',function(){location.hash="sys_post_twitter";});
	//tap跳转函数
	function tap2url(x,url){
		x.tap(function(){
			location.href=url;
		});
	}
	//点击进入个人主页
	$(document).on('tap','#sys_menu_i',function(){
		sysmenu2skip(U("w3g/Index/weibo"),'载入个人主页中');
	});
	//点击logo进入主页
	$(document).on('tap','#logo',function(){
		location.href=U("w3g/Index/Index");
		// tips('正在载入主页...',2,0);
	});
	//点击个人主页中的粉丝,关注按钮
	$(document).on('tap','#i_info_counts_box_weibo,#i_info_counts_box_fensi,#i_info_counts_box_guanzhu,#i_info_counts_box_shoucang',function(){
		location.href=$(this).attr("linkto");
		// tips('加载'+$(this).children('.i_info_counts_title').text()+'页面中',2,0);
	});
	//进入个人主页
	$(document).on('tap','#open_zz',function(){
		menubox2skip(U("w3g/Index/weibo")+"&uid="+app_id,'载入作者主页中');
		$(".c_mousedown").removeClass("c_mousedown");
	});
	//进入分享广场
	$(document).on('tap','#open_square',function(){
		menubox2skip(U('w3g/Index/index'),'载入分享广场中');
		$(".c_mousedown").removeClass("c_mousedown");
	});
	$(document).on('tap','#sys_menu_square',function(){
		sysmenu2skip(U("w3g/Index/index"),'载入分享广场中');
	});
	//退出登录
	$(document).on('tap','#sys_menu_quit',function(){
		sysmenu2skip(U("w3g/Public/log_out"),'正在退出');
	});
	//查看分享原文
	$(document).on('tap','#open_yw',function(){
		menubox2skip(U("w3g/Index/detail")+"&weibo_id="+feed_id,'载入详情页中');
		$(".c_mousedown").removeClass("c_mousedown");
	});
	//拖动删除或举报分享(测试功能)
	c_x=0;//c_position  //c_p
	c_y=0;// c_top
	c_width=0;//初始化点击滑动水平距离
	c_height=0;//初始化点击滑动垂直距离
	c_left=0;
	c_left_0=0;
	c_top_0=0;//设定垂直滑动距离，防止误操作
	function zc(x,y){//定义整除
		return (x-x%y)/y;
	}
	$(document).on('touchstart','.c',function(e){
		c_x=e.touches[0].pageX;//设置初始点击位置
		c_left_0=0;//设置初始状态
		var c_d = $(this).siblings(".c_d");
		if($(this).attr("appid")!=uid){
			$(this).siblings(".c_d").children(".c_d_yes").children(".c_d_text").text("举报");
			$(this).siblings(".c_d").children(".c_d_yes").addClass("c_d_p");
		}
	});
	// 定义下拉变量
	c_y_length=0;
	c_scrollDownRefresh=0;
	if_rlp=0;
	if_rlp0=0;
	$(document).on('touchmove','.c',function(e){
		c_width=e.touches[0].pageX-c_x;
		c_left=c_left_0+c_width;//实时更新c_left_0
		if(Math.abs(c_width)>=150){
			$(this).css({
				"left":c_left+"px"
			});
		}else{
			$(this).css({
				"left":0
			});
		}
		// 下拉刷新
		if($("body").scrollTop()==0 && page_interface==1){
			if(c_scrollDownRefresh==0){
				c_y=e.touches[0].pageY;
				console.log('c_y = '+c_y);
				c_scrollDownRefresh=1;
			}
			c_height=e.touches[0].pageY-c_y;
			console.log('e.touches[0].pageY = '+e.touches[0].pageY);
			console.log('c_height = '+c_height);
			if(c_height<=60 && c_height>=0){
				if(c_height-60<=0){
					$('#refresh_list').show().css({
						'top':c_height-60+'px'
					});
				}
				$('#content').css({
					'margin-top':c_height+'px'
				});
			}
			if(c_height>=40 && if_rlp==0){
				$('#refresh_list_p').text('松开可以刷新');
				if_rlp=1;
				if_rlp0=0;
			}else if(c_height<40 && if_rlp0==0){
				$('#refresh_list_p').text('下拉可以刷新');
				if_rlp=0;
				if_rlp0=1;
			}
		}
	});
	var closeSdRF = function(){
		$('#refresh_list').animate({
			'top':'-60px'
		},300,'linear',function(){
			$(this).hide();
			$('#refresh_list_p').text('下拉可以刷新');
		});
		$('#content').animate({
			'margin-top':0
		},300);
		if_rlp=0;
		if_rlp0=0;
	}
	var resetScrollDownRefresh = function(){
		if(page_interface==1){
			c_scrollDownRefresh=0;
		}
		if(c_height<40){
			closeSdRF();
		}else{
			$('#refresh_list').animate({
				'top':0
			},300,'linear');
			$('#content').animate({
				'margin-top':'60px'
			},300);
			if(page=='index_list'){
				url=U('w3g/Index/resetScrollDownRefresh');
			}else if(page=='square_list'){
				url=U('w3g/Index/resetScrollDownRefreshSquare');
			}
			$.ajax({
				type:'GET',
				url:url,
				dataType:'text',
				timeout:10000,
				data:{
					since_id:$('.c').first().attr('cid')
				},
				beforeSend:function(){
					$('#refresh_list_p').text('获取数据中...');
				},
				success:function(data){
					if(data=='moreThanTen'){
						$('#content').load(U('w3g/Index/Index')+' #content');
					}else{
						$('#to_post_twitter').after(data);
					}
					closeSdRF();
					view_pic();
					viewWeiba();
					sc_tap();
				},
				error:function(){
					$('#refresh_list_p').text('刷新失败，请稍候重试:)');
					setTimeout(closeSdRF,2000);
				}
			});
		}
	}
	$(document).on('touchend','.c',function(e){
		resetScrollDownRefresh();
		if(Math.abs(c_width)>=150){
			var c_ml = parseInt($(this).css("left"));
			var dw=$(document).width()/2;
			console.log("c_ml="+c_ml);
			console.log("dw="+dw);
			$(this).parent().siblings().children(".c_delete").animate({"left":0},100).removeClass("c_delete");
			if(c_ml<-dw){
				$(this).animate({"left":-dw*3+"px"},100).addClass("c_delete");
			}else if(c_ml>=-dw && c_ml <dw){
				$(this).animate({"left":0},100);
			}else{
				$(this).animate({"left":dw*2+"px"},100).addClass("c_delete");
			}
		}
	});
	// 取消屏蔽或删除
	$(document).on('tap','.c_d_no',function(){
		$(this).parent().siblings(".c").animate({"left":0},100);
	})
	//删除/屏蔽分享动作
	$(document).on('tap','.c_d_yes',function(){
		if($(this).hasClass("c_d_p")){//举报
			var this_c = $(this).parent().siblings(".c");
			feed_id=this_c.attr('cid');
			var content = this_c.children(".c_content").html();
			var url = U('widget/Denouce/post');
			var source_url = U("w3g/Index/detail")+"&weibo_id="+feed_id;
			var app_id = this_c.attr("appid");
			var p = {uid:$("body").attr("uid"), aid:feed_id, content:content, from:"feed", fuid:app_id, reason:'', source_url:source_url};
			tips('已提交你的举报:)',1,0);
			this_c.animate({"left":0},100);
			// 隐藏该条分享
			// var this_parent=this_c.parent();
			// this_parent.hide();
			$.ajax({
				type:"POST",
				url :url,
				data:p,
				dataType:"json",
				timeout:10000,
				success:function(r){
					if(r.status != '0'){
						tips('举报失败，请重试:)',1,0);
						// this_parent.show();
			        }
				}
			});

		}else{//删除
			var this_c = $(this).parent().siblings(".c");
			var weibo_id = this_c.attr("cid");
			var from = this_c.attr("index");
			var this_parent=this_c.parent();
			this_parent.hide();
			$.ajax({
				type:"POST",
				url:U('w3g/Index/doDelete'),
				data:{
					"weibo_id":weibo_id,
					"from":from,
					"type":this_c.attr('feedtype')
				},
				timeout:10000,
				dataType:"json",
				success:function(r){
					if(r.status == '0'){
						tips('删除失败，请重试:)',1,0);
						this_parent.show();
			        }else{
			        	if($('body').has('#c_comment_post_box').length>0){
			        		location.href=U("w3g/Index/Index");
			        	}
			        }
				}
			});
		}
	});
	//检测页面滚动，浮动右上角按钮
	$(window).scroll(function(){
		s_t=$("body").scrollTop();
		if(s_t>=46){
			menu_button.addClass("menu_button_show");
			if_scroll=1;
			if(menu_button_tap==0 && (menu_button.css('background-color')!="#2980B9"||menu_button.css('background-color')!=0.5)){
				menu_button.css({
					"background-color":"#2980B9",
					"opacity":0.5
				});
			}else if(menu_button.css('background-color')!="#FFFFFF"||menu_button.css('background-color')!=1){
				menu_button.css({
					"background-color":"#FFFFFF",
					"opacity":1
				});
			}
		}else{
			menu_button.removeClass("menu_button_show");
			if(menu_button_tap==0 && (menu_button.css('background-color')!=""||menu_button.css('background-color')!=1)){
				menu_button.css({
					"background-color":"",
					"opacity":1
				});
			}else if(menu_button.css('background-color')!="#ffffff"||menu_button.css('background-color')!=1){
				menu_button.css({
					"background-color":"#FFFFFF",
					"opacity":1
				});
			}
			if_scroll=0;
		}
		//判断图片
		if(fi_st==-1){
			if(feed_img_view_img_box.height()>$(window).height()){
				if(s_t>parseInt(feed_img_view.css("top"))+feed_img_view_img_box.height()-$(window).height()){
					window.scrollTo(0,parseInt(feed_img_view.css("top"))+feed_img_view_img_box.height()-$(window).height());
				}else if(s_t<st){
					window.scrollTo(0,st);
				}
			}
		}
		//判断微吧帖子
		if($("#weiba_box").length==1){
			var wbbox=$("#weiba_box").height();
			if(st+wbbox-$(window).height()<s_t){
				window.scrollTo(0,st+wbbox-$(window).height());
			}else if(s_t<st){
				window.scrollTo(0,st);
			}
		}
	});
	//分享列表点击图片
	function view_pic(){
		$(".feed_img_box>.feed_img").tap(function(){
			feed_img_view_img_box.html("");
			rst();
			feed_img_view.css({
				"top":st
			});
			feed_img_view.show();
			if($(this).siblings().length==0){
				fi_st=1;
				$('<div class="fivfb"><img class="feed_img_view_full" src="'+$(this).attr("bm")+'"></div>').appendTo(feed_img_view_img_box);
			}else{
				fi_st=-1;
				index=$(this).index();
				scroll_px=0;
				$(this).parent().children().each(function(){
					this_index=$(this).index();
					$('<div id="fivfb_'+this_index+'" class="fivfb"><img class="feed_img_view_full" src="'+$(this).attr("bm")+'"></div>').appendTo(feed_img_view_img_box);
					if(this_index<index){
						scroll_px=scroll_px+$("#fivfb_"+this_index).height();
					}
				});
				window.scrollTo(0,st+scroll_px);
			}
			event.stopPropagation();
		});
	}
	view_pic();
	//关闭图片查看层
	$(document).on('tap','#feed_img_view_close,#feed_img_view_bg',function(){
		feed_img_view.hide();
		feed_img_view_img_box.html("");
		s2();
		fi_st=0;
	});
	// 初始化hash界面
	function restHashDisplay(){
		$(".no_check").hide().removeClass("checked");
		post_twitter.hide();
		$("#post_twitter_input").val("");
		s2();
		// search
		$("#search_box").hide();
		$("#sibi").val("");
		// tiph();
		// sendmsg
		$("#post_message").hide();
		$("#post_message_input").val("");
		$("#post_message_input").text("");
		$("#post_msg_to").val("");
	}
	//hash动作判断
	function hash2do(){
		hash=location.hash;
		switch(hash)
		{
			case "#hf_twitter":
				$('#file_list').html('');
				$('#ajax_iframe').remove();
				$('#post_twitter_button_file').hide();
				if(isdel==0){
					$("#ifShareFeed").show();
				}else if(isdel==1){
					$("#ifShareFeed").hide();
				}
				post_twitter_type="hf";
				post_twitter.show().show();
				more_menu_box_option.hide();
				default_menu_box_option.show();
				menu_box.hide();
				shadow.hide();
				var c=$(".c");
				post_tip.text("回复分享");
				var c_page ='';
				if(c.first().attr("page")!=null){
					c_page=c.first().attr("page");
				}
				if($('.c_replyme').length>0 || $('.c_mousedown').attr("type")=="comment"){
					var pf_action=U('w3g/Index/doCommentD');
				}else{
					var pf_action=U('w3g/Index/doComment');
				}
				$("#post_form").attr({
					"action":pf_action,
					"type":c_page+"hf"
				});
				$("#post_twitter_input").focus();
				$(".c_mousedown").removeClass("c_mousedown");
				rst();
			break;

			case "#zf_twitter":
				$('#file_list').html('');
				$('#ajax_iframe').remove();
				$('#post_twitter_button_file').hide();
				$("#ifAsComment").show();
				hash_old=location.hash;
				post_twitter_type="zf";
				post_twitter.show();
					more_menu_box_option.hide();
					default_menu_box_option.show();
					menu_box.hide();
					shadow.hide();
				var c=$(".c");
				$(".c_mousedown").removeClass("c_mousedown");
				post_tip.text("转发分享");
				$("#post_form").attr({
					"action":U('w3g/Index/doForward'),
					"type":"zf"
				});
				$("#post_twitter_input").focus();
				rst();
			break;

			case "#hf_twitter_detail":
				$('#file_list').html('');
				$('#ajax_iframe').remove();
				$('#post_twitter_button_file').hide();
				$("#ifShareFeed").show();
				if(isdel==0){
					$("#ifShareFeed").show();
				}else if(isdel==1){
					$("#ifShareFeed").hide();
				}
				post_twitter_type="hf";
				post_twitter.show();
				post_tip.text("回复分享");
				$("#post_form").attr({
					"action":U('w3g/Index/doComment'),
					"type":"xq_hf"
				});
				$("#post_twitter_input").focus();
				rst();
			break;

			case "#hf_twitter_c_comments":
				$('#file_list').html('');
				$('#ajax_iframe').remove();
				$('#post_twitter_button_file').hide();
				$("#ifShareFeed").show();
				if(isdel==0){
					$("#ifShareFeed").show();
				}else if(isdel==1){
					$("#ifShareFeed").hide();
				}
				post_twitter_type="xq_hf";
				post_twitter.show();
				post_tip.text("回复@"+cname);
				$("#post_form").attr({
					"action":U('w3g/Index/doCommentD'),
					"type":"xq_hf"
				});
				$("#post_twitter_input").focus();
				rst();
			break;

			case "#thinksns":
				restHashDisplay();
			break;

			case "#":
				restHashDisplay();
			break;

			case "":
				restHashDisplay();
			break;

			case "#post_twitter":
				$('#post_twitter_input').data('attach-id','');
				$('#file_list').html('');
				if($('#ptibox').has('#ajax_iframe').length===0){
					$('#ptibox').prepend('<iframe src="'+U('w3g/Index/ajax_iframe')+'" id="ajax_iframe"></iframe>');
				}
				$('#post_twitter_button_file').show();
				post_twitter_type="fb";
				post_twitter.show();
				post_tip.text("发表分享");
				$("#post_form").attr({
					"action":U('w3g/Index/doPost'),
					"type":"fb"
				});
				$("#post_twitter_input").focus();
				rst();
			break;

			case "#sys_post_twitter":
				$('#post_twitter_input').data('attach-id','');
				$('#file_list').html('');
				if($('#ptibox').has('#ajax_iframe').length===0){
					$('#ptibox').prepend('<iframe src="'+U('w3g/Index/ajax_iframe')+'" id="ajax_iframe"></iframe>');
				}
				$('#post_twitter_button_file').show();
				post_twitter_type="fb";
				post_twitter.show();
				var c=$(".c");
				c.removeClass("c_mousedown");
				if(if_scroll==1){
					menu_button.css({
						"background-image": "url(img/menu_button_0.fw.png)",
						"background-color":"#2980B9"
					});
				}else{
					menu_button.css({
						"background-image": "url(img/menu_button_0.fw.png)",
						"background-color":""
					});
				}
				// sys_menu.removeClass("sys_menu_active").addClass("sys_menu_hide");
				sys_menu.hide();
				part_shadow.hide();
				menu_button_tap=0;
				post_tip.text("发表分享");
				$("#post_form").attr({
					"action":U('w3g/Index/doPost'),
					"type":"fb"
				});
				$("#post_twitter_input").focus();
				event.stopPropagation();
				rst();
			break;

			case "#search":
				if(if_scroll==1){
					menu_button.css({
						"background-image": "url(img/menu_button_0.fw.png)",
						"background-color":"#2980B9"
					});
				}else{
					menu_button.css({
						"background-image": "url(img/menu_button_0.fw.png)",
						"background-color":""
					});
				}
				// sys_menu.removeClass("sys_menu_active").addClass("sys_menu_hide");
				sys_menu.hide();
				part_shadow.hide();
				menu_button_tap=0;
				$("#search_box").show();
					$("#sibi").focus();
			break;

			case "#sys_menu_sendmsg":
				$("#post_message").show();
				$("#post_msg_to").focus();
				if(if_scroll==1){
					menu_button.css({
						"background-image": "url(img/menu_button_0.fw.png)",
						"background-color":"#2980B9"
					});
				}else{
					menu_button.css({
						"background-image": "url(img/menu_button_0.fw.png)",
						"background-color":""
					});
				}
				// sys_menu.removeClass("sys_menu_active").addClass("sys_menu_hide");
				sys_menu.hide();
				part_shadow.hide();
				menu_button_tap=0;
			break;

			case "#post-msg-to-uid":
				$("#post_message").show();
				$("#post_message_input").focus();
			break;

			default:
			break;
		}
	}
	location.hash='';
	//监听hashchange
	window.addEventListener("hashchange", hash2do, false);
	//翻页
	$("#page_sel").change(function(){
		link=$(this).val();
		location.href=link;
	})
	//记录滚动值
	st=$("body").scrollTop();
	function rst(){
		st=$("body").scrollTop();
	}
	//回滚保持滚动值
	function s2(){
		window.scrollTo(0,st);//未计算ajax添加的dom高度，应+之
	}
	//修正略所图尺寸问题
	function feed_img_size(){
		$(".feed_img_box>.feed_img").each(function(){
			width=$(this).width();
			$(this).css({
				"height":width+"px"
			});
		});
	}
	feed_img_size();
	//粉丝页面动作
	fensi_box_guanzhu_button.tap(function(){//涉及事件冒泡，推至第二轮优化
		if($(this).attr("sort")=="fs_list"){
			type=$(this).attr("type");
			if($(this).attr("type")=="follow"){
				$(this).attr("type","unfollow");
				if($(this).attr("isfollower")==1){
					$(this).text("已互粉");
				}else{
					$(this).text("已关注");
				}
				$(this).removeClass("fensi_box_guanzhu_button_1");
			}else{
				$(this).attr("type","follow");
				$(this).text("加关注");
				$(this).addClass("fensi_box_guanzhu_button_1");
			}
		}else{
			if($(this).hasClass("i_info_followed")){
				$(this).removeClass("i_info_followed");
				$(this).text("关注");
			}else{
				$(this).addClass("i_info_followed");
				$(this).text("取消关注");
			}
		}
		$.get(
			$(this).attr("linkto"),
			function(data){
				if(data!=1){
					if($(this).attr("sort")=="fs_list"){
						if($(this).attr("type")=="follow"){
							$(this).attr("type","unfollow");
							$(this).text("已互粉");
							$(this).removeClass("fensi_box_guanzhu_button_1");
						}else{
							$(this).attr("type","follow");
							$(this).text("加关注");
							$(this).addClass("fensi_box_guanzhu_button_1");
						}
					}else{
						if($(this).hasClass("i_info_followed")){
							$(this).removeClass("i_info_followed");
							$(this).text("关注");
						}else{
							$(this).addClass("i_info_followed");
							$(this).text("取消关注");
						}
					}
				}
			}
		);
		return false;
	});
	//粉丝列表点击跳转个人主页
	$(document).on('tap','.fensi_box',function(){
		location.href=U("w3g/Index/weibo")+"&uid="+$(this).attr("uid");
	});
	//粉丝页面动作结束
	// 监听消息
	readMsgCount=0;
	function readNewMsg()
	{
		$.ajax({
			type:'GET',
			url: U('w3g/Index/mcount'),
			dataType:'json',
			success:function(data,status){
				var msgcount=parseInt(data.at)+parseInt(data.cm)+parseInt(data.notify)+parseInt(data.msg);
				if(msgcount==0){
					$("#msg_tip_box").css({
						"left":"-114px"
					});
				}else if(msgcount>0){
					msg_tip.children("#msg_tip_p").text(msgcount);
					$("#msg_tip_box").css({
						"left":0
					});
					if(data.msg>0){
						$("#msg_tip,#sys_menu_msg").attr('linkto','msg');
					}else if(data.at>0){
						$("#msg_tip,#sys_menu_msg").attr('linkto','at');
					}else if(data.cm>0){
						$("#msg_tip,#sys_menu_msg").attr('linkto','cm');
					}else{
						$("#msg_tip,#sys_menu_msg").attr('linkto','notify');
					}
				}
				readMsgCount=0;
			},
			error:function(){
				console.log(status);
				readMsgCount+=1;
				if(readMsgCount>=10){
					tips("服务器链接超时，请检查您的网络连接:)",1,0);
					readMsgCount=0;
				}
			}
		});
	}
	readNewMsg();
	setInterval(readNewMsg,30000);
	//个人消息提示Tap
	$(document).on('tap','#msg_tip,#sys_menu_msg',function(){
		var linkto=$(this).attr("linkto");
		if($(this).attr('id')=='msg_tip'){
			switch(linkto){
				case "at":
				// tips('加载@我页面',2,0);
				location.href=U('w3g/Index/atme');
				break;
				case "cm":
				// tips('加载评论消息',2,0);
				location.href=U('w3g/Index/replyMe');
				break;
				case "msg":
				// tips('加载私信页面',2,0);
				location.href=U('w3g/Message/index');
				break;
				case "notify":
				// tips('加载系统通知',2,0);
				location.href=U('w3g/Message/notify');
				break;
			}
		}else{
			switch(linkto){
				case "at":
				sysmenu2skip(U("w3g/Index/atme"),'加载@我页面');
				break;
				case "cm":
				sysmenu2skip(U("w3g/Index/replyMe"),'加载评论消息');
				break;
				case "msg":
				sysmenu2skip(U('w3g/Message/index'),'加载私信页面');
				break;
				case "notify":
				sysmenu2skip(U('w3g/Message/notify'),'加载系统通知');
				break;
			}
		}
	});
	$(document).on('tap','#tip_ik',function(){
		tiph();
	});
	//个人页面判断导航
	function iin(){
		var act = $("#i_info_counts").attr("act");
		switch(act){
			case "weibo":
			$("#i_info_counts_box_weibo").addClass("iin");
			break;
			case "following":
			$("#i_info_counts_box_guanzhu").addClass("iin");
			break;
			case "followers":
			$("#i_info_counts_box_fensi").addClass("iin");
			break;
			case "favorite":
			$("#i_info_counts_box_shoucang").addClass("iin");
			break;
		}
	}
	iin();
	//消息页面导航判断+链接
	$(document).on('tap','.msg_navs',function(){
		var act=$(this).attr("linkto");
		switch(act){
			case "msg":
			location.href=U('w3g/Message/index');
			break;
			case "at":
			location.href=U('w3g/Index/atme');
			break;
			case "cm":
			location.href=U('w3g/Index/replyMe');
			break;
			case "notify":
			location.href=U('w3g/Message/notify');
			break;
		}
	});
	function mni(){
		var act=$("#msg_nav").attr("act");
		switch(act){
			case "at":
			$("#mag_nav_at").addClass("mni");
			break;
			case "cm":
			$("#mag_nav_cm").addClass("mni");
			break;
		}
	}
	mni();
	//搜索页面动作
	$(document).on('tap','#search_top_box',function(){var key=$('#search_top_input').text(); $("#sibi").val(key);location.hash='search';});
	$(document).on('tap','#close_search_box',function(){location.hash="thinksns";});
	$(document).on('tap','.ssc',function(){
		$(".ssc").removeClass("ssc_check");
		$(this).addClass("ssc_check");
	});
	$(document).on('tap','#sys_menu_search',function(){location.hash='search';});
	$(document).on('tap','#post_search_submit',function(){
		$(this).focus();
		var s_key = $("#sibi").val();
		var s_type = $(".ssc_check").attr("stype");
		if(s_key==''){
			tips('请输入搜索内容',1,0);
		}else{
			if($("#search_top_box").length>0){
				tips('搜索数据中',2,0);
				$.ajax({
					type:'POST',
					url:U('w3g/Index/doSearch')+"&"+s_type,
					data:{
						key:s_key
					},
					timeout:10000,
					success:function(data){
						$("#serach_result").html(data);
						tiph();
						$("#search_top_input").text(s_key);
						location.hash="thinksns";
						switch(s_type){
							case "weibo":
								sc_tap();
								view_pic();
								viewWeiba();
								digg_tap();
							break;
							case "user":
								$('.fensi_box_guanzhu_button').tap(function(){//涉及事件冒泡，推至第二轮优化
									if($(this).attr("sort")=="fs_list"){
										type=$(this).attr("type");
										if($(this).attr("type")=="follow"){
											$(this).attr("type","unfollow");
											if($(this).attr("isfollower")==1){
												$(this).text("已互粉");
											}else{
												$(this).text("已关注");
											}
											$(this).removeClass("fensi_box_guanzhu_button_1");
										}else{
											$(this).attr("type","follow");
											$(this).text("加关注");
											$(this).addClass("fensi_box_guanzhu_button_1");
										}
									}else{
										if($(this).hasClass("i_info_followed")){
											$(this).removeClass("i_info_followed");
											$(this).text("关注");
										}else{
											$(this).addClass("i_info_followed");
											$(this).text("取消关注");
										}
									}
									$.get(
										$(this).attr("linkto"),
										function(data){
											console.log(data);
											if(data!=1){
												if($(this).attr("sort")=="fs_list"){
													if($(this).attr("type")=="follow"){
														$(this).attr("type","unfollow");
														$(this).text("已互粉");
														$(this).removeClass("fensi_box_guanzhu_button_1");
													}else{
														$(this).attr("type","follow");
														$(this).text("加关注");
														$(this).addClass("fensi_box_guanzhu_button_1");
													}
												}else{
													if($(this).hasClass("i_info_followed")){
														$(this).removeClass("i_info_followed");
														$(this).text("关注");
													}else{
														$(this).addClass("i_info_followed");
														$(this).text("取消关注");
													}
												}
											}
										}
									);
									return false;
								});
							break;
							default:
								sc_tap();
								view_pic();
								viewWeiba();
								digg_tap();
							break;
						}
					}
				});
			}else{
				switch(s_type){
					case "weibo":
					location.href=U('w3g/Index/doSearch')+"&key="+s_key+"&weibo";
					break;
					case "user":
					location.href=U('w3g/Index/doSearch')+"&key="+s_key+"&user";
					break;
					default:
					location.href=U('w3g/Index/doSearch')+"&key="+s_key;
					break;
				}
			}
		}
	});
	//全局发送私信动作
	$(document).on('tap','#sys_menu_sendmsg',function(){
		location.hash="sys_menu_sendmsg";
		rst();
	});
	$(document).on('tap','#close_post_message',function(){
		location.hash="thinksns";
	});
	$("#post_msg_to").on('keydown',function(){//查询用户
		$("#pm2l").width($('#post_msg_to').width()-2);
		var url = U('widget/SearchUser/search');
		var username = $(this).val();
		var p = {key:username, follow:0, noself:1};
		var message_to = 0;
		$.ajax({
			type:"POST",
			url :url,
			data:p,
			dataType:"json",
			timeout:10000,
			success:function(r){
				if(r.data != null){
					var data = r.data;
					var pm2l_html='';
					$.each(data,function(index,item){
						pm2l_html += '<div class="pm2lc" uid="'+ item.uid +'"><img class="pm2lci" src="'+ item.avatar_small +'" width=30 height=30><div class="pm2lcn">'+ item.uname +'</div></div>';
					});
					$("#pm2l").html(pm2l_html).removeAttr("isnull").show();
				}else{
					var pm2l_html = '<div class="pm2lc" uid="0"><div class="pm2lcn">没有该用户</div></div>';
					$("#pm2l").html(pm2l_html).attr("isnull","yes").show();
				}
			},
			error:function(xhr,type){
			}
		});
	});
	$("#post_msg_to").on('focus',function(){
		setTimeout(s2(),1000);
	});
	$(document).on('tap','.pm2lc',function(){//选择用户
		if($(this).attr("isnull")!="yes"){
			$("#post_msg_to").val($(this).children('.pm2lcn').text());
			$("#pm2l").hide();
		}
	});
	$(document).on('tap','#post_message_button_submit',function(){//发送私信
		$(this).focus();
		var url = U('widget/SearchUser/search');
		var username = $("#post_msg_to").val();
		var p = {key:username, follow:0, noself:1};
		var message_content = $("#post_message_input").val();
		var message_to = 0;
		//首先检查用户名是否存在，若存在，则发送私信
		if(username==''){
			tips("用户名不能为空，请填写",1,0);
		}else if(message_content==''){
			tips("私信内容不能为空,请填写:)",1,0);
		}else{
			$.ajax({
				type:"POST",
				url :url,
				data:p,
				dataType:"json",
				timeout:10000,
				beforeSend:function(){
					tips("验证用户信息",2,0);
				},
				success:function(r){
					if(r.data != null && r.data[0].uname == username){
						message_to = Number(r.data[0].uid);
						url = U('public/Message/doPost');//若用户存在，重定义post地址
						var p = {to:message_to, content:message_content, attach_ids:''};
						$.ajax({
							type:"POST",
							url :url,
							data:p,
							dataType:"json",
							timeout:10000,
							// context:x,
							beforeSend:function(){
								tips("发送中",2,0);
							},
							success:function(r){
								if(r.status == 1 && r.data == "发送成功"){
									tips("发送成功！",0,1);
									location.hash="thinksns";
						        } else {
									tips("发送失败，请重试:(",1,0);
						        }
							},
							error:function(xhr,type){
								tips("连接服务器出错，请重试:(",1,0);
							}
						});
					}else{
						tips("没有该用户，检查一下是不是输入错了?",1,0);
					}
				},
				error:function(xhr,type){
					tips("连接服务器出错，请重试:(",1,0);
				}
			});
		}
	});
	//消息盒子->私信列表
	$(document).on('tap','.c_msg',function(){
		location.href=U('w3g/Message/detail')+'&id='+$(this).attr("msid");
	});
	//点击分享列表中的话题和@XXX的连接
	c_a = $(".c_a");
	c_a.tap(function(){
		location.href=$(this).attr('href');
		event.stopPropagation();
		return false;
	});
	//消息盒子->收到的评论页面
	//设置message_header
	if($('#content').attr("msgpage")!=null){
		$(".msg_navs").removeClass("mni");
		switch($('#content').attr("msgpage")){
			case "replyMe":
				$("#mag_nav_cm").addClass("mni");
			break;
			case "atme":
				$("#mag_nav_at").addClass("mni");
			break;
			case "msg_list":
				$("#mag_nav_msg").addClass("mni");
			break;
			default:
				$("#mag_nav_notify").addClass("mni");
			break;
		}
	};
	// 显示微吧内容
	function viewWeiba(){
		$(".loadweiba").tap(function(){
			event.stopPropagation();
			url = U('widget/FeedList/getPostDetail');
			var post_id = $(this).attr("weibaid");
			var p = {post_id:post_id};
			$.ajax({
				type:"POST",
				url :url,
				data:p,
				dataType:"json",
				timeout:10000,
				// context:x,
				beforeSend:function(){
					tips("获取内容中...",2,0);
				},
				success:function(r){
					if(Number(r.feed_id)>0){
						tiph();
						rst();
						var html = '<div id="weiba_box" style="top:'+st+'px"><div id="weiba_close"></div><div id="weiba_title">'+r.title+'</div><div id="weiba_content">'+r.content+'</div></div>';
						$("body").append(html);
						$("#weiba_content table").removeAttr("width");
					}else{
						tips("内容获取出错，请重试:(",1,0);
					}
				},
				error:function(xhr,type){
					tips("连接服务器出错，请重试:(",1,0);
				}
			});
		});
	}
	viewWeiba();
	$(document).on("tap","#weiba_close",function(){
		$("#weiba_box").remove();
		s2();
	});
	// 弹出菜单跳转函数，优化体验
	function menubox2skip(skip2url,tiptext){
		menu_box.removeClass("menu_ani_show");
		menu_box.addClass("menu_ani_hide");
		i=0;
		more_menu_box_option.hide().css({
			"opacity":0
		});
		default_menu_box_option.show().css({
			"opacity":1
		});
		$(".c").removeClass("c_mousedown");
		setTimeout("menu_box.hide()",300);
		shadow.removeClass("shadow_show").addClass("shadow_hide");
		setTimeout("shadow.hide()",300);
		url=skip2url;
		setTimeout('location.href=url',300);
		// setTimeout(tips(tiptext,2,0),300);
	}
	function sysmenu2skip(skip2url,tiptext){
		if(if_scroll==1){
				menu_button.css({
					"background-image": "url(img/menu_button_0.fw.png)",
					"background-color":"#2980B9",
					"opacity":0.5
				});
			}else{
				menu_button.css({
					"background-image": "url(img/menu_button_0.fw.png)",
					"background-color":"",
					"opacity":1
				});
			}
		sys_menu.hide();
		part_shadow.hide();
		menu_button_tap=0;
		url=skip2url;
		setTimeout('location.href=url',300);
		// setTimeout(tips(tiptext,2,0),300);
	}
	// 阻止事件冒泡
	$('.attachs_a').click(function(){event.stopPropagation();});
	$('.attachs_a').tap(function(){event.stopPropagation();});
	//隐藏loading
	$("#load_tip").hide();
	$('.c_content>.feed_img').tap(function(){
		event.stopPropagation();
	})
	// 处理QQ/UC预读问题
	$(document).on('tap','#prev,#next',function(){
		var link = $(this).attr('link');
		if(link!=''){
			window.location.href=link;
		}
	});
	//上传附件
	$(document).on('tap','#post_twitter_button_file',function(){
		var load = $(window.frames["ajax_iframe"].document).find("#loaded").text();
		if(load==='OK'){
			$('.file_list_view_del').remove();
			if($('#post_twitter_input').data('type')==='image'){
				if($('#file_list>img').length<9){
					$(window.frames["ajax_iframe"].document).find("#file").trigger('click');
				}else{
					tips('最多上传9张图片:)',1,0);
				}
			}else{
				if($('#file_list>img').length<4){
					$(window.frames["ajax_iframe"].document).find("#file").trigger('click');
				}else{
					tips('最多上传4个附件:)',1,0);
				}
			}
		}else{
			tips('框架加载失败,请刷新后重试:(',1,0);
		}
	});
	// 删除附件
	$(document).on('doubleTap','.file_list_view',function(){
		$('.file_list_view').append('<div class="file_list_view_del"></div>');
	});
	$(document).on('tap','.file_list_view_del',function(){
		var id=$(this).parent('.file_list_view').data('id');
		var attach_idArray=$('#post_twitter_input').data('attach-id');
		attach_idArray=attach_idArray.replace(String(id)+'|','');
		$('#post_twitter_input').data('attach-id',attach_idArray);
		$(this).parent('.file_list_view').remove();
	});
	//查看原分享
	$('.c_zf_box_detail').bind('tap',function(event){
		window.location.href=U("w3g/Index/detail")+"&weibo_id="+$(this).data('original-id');
		event.stopPropagation();
	});
	tiph();
	//个人主页发送私信
	$(document).on('tap','#post-msg-to-uid',function(){
		$("#post_msg_to").val($(this).data('uname'));
		window.location.hash='post-msg-to-uid';
	});
//end
});
function switchVideo(number,dowhat,videosite,videourl){
	window.open(videourl,"","fullscreen=1");
}
// tip
function tips(x,y,z){
	if(y==1){
		$("#tip_p").text(x).removeClass('tip_p_fl');
		$('#tip_load').hide();
		$("#tip_ik").show();
	}else if(y==0){
		$("#tip_p").text(x).removeClass('tip_p_fl');
		$('#tip_load').hide();
		$("#tip_ik").hide();
	}else if(y==2){
		$("#tip_p").text(x).addClass('tip_p_fl');
		$('#tip_load').show();
		$("#tip_ik").hide();
	}
	$("#tip").show();
	$("#tip_shadow").show();
	if(z===1){
		setTimeout(tiph,2000);
	}
}
function tiph(){
	$("#tip").hide();
	$("#tip_shadow").hide();
}