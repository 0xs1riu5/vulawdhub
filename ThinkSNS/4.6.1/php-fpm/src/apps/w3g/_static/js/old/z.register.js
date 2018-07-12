$(function(){
	//隐藏loading
	//载入函数
  	var U = function(url, params) {
		var website = SITE_URL+'/index.php';
		url = url.split('/');
		if(url[0]=='' || url[0]=='@')
			url[0] = APPNAME;
		if (!url[1])
			url[1] = 'Index';
		if (!url[2])
			url[2] = 'index';
		website = website+'?app='+url[0]+'&mod='+url[1]+'&act='+url[2];
		if(params) {
			params = params.join('&');
			website = website + '&' + params;
		}
		return website;
	};
	$("#load_tip").hide();
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
		if(z==1){
			setTimeout(tiph,2000);
		}
	}
	function tiph(){
		$("#tip").hide();
		$("#tip_shadow").hide();
	}
	$(document).on('tap','#tip_ik',function(){
		tiph();
	});
	//tip end!
	$(document).on('tap','#reg_submit',function(){
		console.log('reg_submit');
		email=$("#email").val();
		uname=$("#uname").val();
		pw=$("#password").val();
		if(email=="" || pw=="" || uname==""){
			tips("请完整填写注册信息:)",1,0);
		}else if(!r_mail(email)){
			tips("请检查您的邮件地址格式:)",1,0);
		}else if(!r_uname(uname)){
			tips("昵称长度为2-10个汉字，仅支持中英文，数字，下划线，不允许重名:)",1,0);
		}else if(!r_password(pw)){
			tips("密码由字母，数字，符号组成，6-15个字符，区分大小写:)",1,0);
		}else{
			tips("注册中...",2,0);
			$.ajax({//验证邮箱是否已注册
				type:"POST",
				url :U('public/Register/isEmailAvailable'),
				data:{"email":email},
				dataType:"json",
				timeout:10000,
				success:function(r){
					if(r.status==false){
						tips(r.info,1,0);
					}else if(r.status==true){
						$.ajax({//检测昵称是否已注册
							type:"POST",
							url :U('public/Register/isUnameAvailable'),
							data:{"uname":uname},
							dataType:"json",
							timeout:10000,
							success:function(q){
								console.log(q.info);
								if(q.status==false){
									tips(q.info,1,0);
								}else if(q.status==true){
									$.ajax({//提交注册
										type:"POST",
										url :U('w3g/Public/doRegister'),
										data:{
											"email":email,
											"uname":uname,
											"password":pw
										},
										timeout:10000,
										success:function(w){
											if(w==1){
												location.href=U("w3g/Index/index");
											}else{
												tips("注册失败，请重试:)",1,0);
											}
										},
										error:function(xhr,type){
											tips("连接服务器失败，请重试:)",1,0);
										}
									});
								}
							},
							error:function(xhr,type){
								tips("连接服务器失败，请重试:)",1,0);
							}
						});
					}
				},
				error:function(xhr,type){
					tips("连接服务器失败，请重试:)",1,0);
				}
			});
		}
		// return false;
	});
	var setInputWidth = function(){
		$('.ib').width($('.li4input').width()-80);
	}
	setInputWidth();
	$(window).resize(function(){
		setInputWidth();
	});
});