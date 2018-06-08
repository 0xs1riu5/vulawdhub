function checke_mail(){
	if(!checkemail())
	{
		document.getElementById('div_email').style.cssText = "";
		document.getElementById('div_email').innerHTML = "<font color=red>邮件格式错误!</font>";
		return  false;
	}
	else
	{
		document.getElementById('div_email').style.cssText = "";
		document.getElementById('div_email').innerHTML = "[<font><img src='images/greencheck.png' style='position:relative; top:3px;' /></font>]";
		return  true;
	}
}
function chkpassword(){
	var m = document.getElementById('regpwd').value;
	if(m.length > 20 || m.length < 6 || !fucPWDchk(m))
	{
		document.getElementById('div_pwd').style.cssText = "";
		document.getElementById('div_pwd').innerHTML = "[<font color=red>密码:英文/数字/下划线，长度:6~20</font>]";
		return  false;
	}
	else
	{
		document.getElementById('div_pwd').style.cssText = "";
		document.getElementById('div_pwd').innerHTML = "[<font><img src='images/greencheck.png' style='position:relative; top:3px;' /></font>]";
		return  true;
	}
}
function comfirmpassword(){
	var m = document.getElementById('regpwd').value;
	var n = document.getElementById('aginpwd').value;
	if(m!="")
	{
		if( m != n)
		{
			document.getElementById('div_aginpwd').style.cssText = "";
			document.getElementById('div_aginpwd').innerHTML = "[<font color=red>两次密码不相同</font>]";
			return  false;
		}
		else
		{
			document.getElementById('div_aginpwd').style.cssText = "";
			document.getElementById('div_aginpwd').innerHTML = "[<font><img src='images/greencheck.png' style='position:relative; top:3px;' /></font>]";
			return  true;
		}
	}
}

function fucPWDchk(str)
{
	var strSource ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
	var ch;
	var i;
	var temp;

	for (i=0;i<=(str.length-1);i++)
	{
		ch = str.charAt(i);
		temp = strSource.indexOf(ch);
		if (temp==-1)
		{
			return 0;
		}
	}
	if (strSource.indexOf(ch)==-1)
	{
		return 0;
	}
	else
	{
		return 1;
	}
}
function chkanswer()//回答问题答案
{
	var m = document.getElementById('answer').value;
	var n = document.getElementById('question').value;
	if (n!='0'){
		if(m=='')
		{
			document.getElementById('div_answer').style.cssText = "";
			document.getElementById('div_answer').innerHTML = "[<font color=red>回答问题答案不能为空</font>]";
			return  false;
		}
		else
		{
			document.getElementById('div_answer').style.cssText = "";
			document.getElementById('div_answer').innerHTML = "[<font><img src='images/greencheck.png' style='position:relative; top:3px;' /></font>]";
			return  true;
		}
	}else{
		return  true;
	}
}
function chkname(){
	var m = document.getElementById('regname').value;
	if(m.length > 20 || m.length < 3 )
	{
		document.getElementById('div_regname').style.cssText = "";
		document.getElementById('div_regname').innerHTML = "[<font color=red>用户名长度错误</font>]";
		return  false;
	}
	else
	{
		document.getElementById('div_regname').style.cssText = "";
		document.getElementById('div_regname').innerHTML = "[<font><img src='images/greencheck.png' style='position:relative; top:3px;' /></font>]";
		return  true;
	}
}
function chkreg(){
	var m = document.adminsetup;
	if(!chkname()){
	    m.regname.focus();
		return false;	
	}else if(!checke_mail()){
		m.email.focus();
		return false;
	}else if(!chkpassword()){
		m.regpwd.focus();
		return false;
	}else if(! comfirmpassword()){
		m.aginpwd.focus();
		return false;
	}
}
function checkemail(){//Email
	var email = document.getElementById("email").value;
	var regexp=/^[-a-zA-Z0-9_\.]+@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/;
	return regexp.test(email);
}