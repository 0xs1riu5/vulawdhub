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
	r_s=$("#reg_submit");
	l_s=$("#login_submit");
	mail=$("#email");
	password=$("#password");
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
	$(document).on('tap','#find_submit',function(){
		tips('3G版找回密码功能暂缓开放,敬请关注:)',1,0);
	});
	var setInputWidth = function(){
		$('.ib').width($('.li4input').width()-80);
	}
	setInputWidth();
	$(window).resize(function(){
		setInputWidth();
	});
});