<!--
$(document).ready(function()
{
	//用户类型
	if($('.usermtype2').attr("checked")==true) $('#uwname').text('公司名称：'); 
	$('.usermtype').click(function()
	{
		$('#uwname').text('用户笔名：');
	});
	$('.usermtype2').click(function()
	{
		$('#uwname').text('公司名称：');
	});
	//checkSubmit
	$('#regUser').submit(function ()
	{
		if(!$('#agree').get(0).checked) {
			alert("你必须同意注册协议！");
			return false;
		}
		if($('#txtUsername').val()==""){
			$('#txtUsername').focus();
			alert("用户名不能为空！");
			return false;
		}
		if($('#txtPassword').val()=="")
		{
			$('#txtPassword').focus();
			alert("登陆密码不能为空！");
			return false;
		}
		if($('#userpwdok').val()!=$('#txtPassword').val())
		{
			$('#userpwdok').focus();
			alert("两次密码不一致！");
			return false;
		}
		if($('#uname').val()=="")
		{
			$('#uname').focus();
			alert("用户昵称不能为空！");
			return false;
		}
		if($('#vdcode').val()=="")
		{
			$('#vdcode').focus();
			alert("验证码不能为空！");
			return false;
		}
	})
	
	//AJAX changChickValue
	$("#txtUsername").change( function() {
		$.ajax({type: reMethod,url: "index_do.php",
		data: "dopost=checkuser&fmdo=user&cktype=1&uid="+$("#txtUsername").val(),
		dataType: 'html',
		success: function(result){$("#_userid").html(result);}}); 
	});
	
	/*
	$("#uname").change( function() {
		$.ajax({type: reMethod,url: "index_do.php",
		data: "dopost=checkuser&fmdo=user&cktype=0&uid="+$("#uname").val(),
		dataType: 'html',
		success: function(result){$("#_uname").html(result);}}); 
	});
	*/
	
	$("#email").change( function() {
		var sEmail = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
		if(!sEmail.exec($("#email").val()))
		{
			$('#_email').html("<font color='red'><b>×Email格式不正确</b></font>");
			$('#email').focus();
		}else{
			$.ajax({type: reMethod,url: "index_do.php",
			data: "dopost=checkmail&fmdo=user&email="+$("#email").val(),
			dataType: 'html',
			success: function(result){$("#_email").html(result);}}); 
		}
	});	
	
	$('#txtPassword').change( function(){
		if($('#txtPassword').val().length < pwdmin)
		{
			$('#_userpwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else if($('#userpwdok').val()!=$('txtPassword').val())
		{
			$('#_userpwdok').html("<font color='red'><b>×两次输入密码不一致</b></font>");
		}
		else if($('#userpwdok').val().length < pwdmin)
		{
			$('#_userpwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else
		{
			$('#_userpwdok').html("<font color='#4E7504'><b>√填写正确</b></font>");
		}
	});
	
	$('#userpwdok').change( function(){
		if($('#txtPassword').val().length < pwdmin)
		{
			$('#_userpwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else if($('#userpwdok').val()=='')
		{
			$('#_userpwdok').html("<b>请填写确认密码</b>");
		}
		else if($('#userpwdok').val()!=$('#txtPassword').val())
		{
			$('#_userpwdok').html("<font color='red'><b>×两次输入密码不一致</b></font>");
		}
		else
		{
			$('#_userpwdok').html("<font color='#4E7504'><b>√填写正确</b></font>");
		}
	});
	
	$("a[href*='#vdcode'],#vdimgck").bind("click", function(){
		$("#vdimgck").attr("src","../include/vdimgck.php?tag="+Math.random());
		return false;
	});
});
-->