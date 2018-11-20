if (top.location != self.location) {top.location=self.location;}

function updateimg() {
	document.images['safecode'].src="include/safecode.php";
	return false;
}
function $(e) {return document.getElementById(e);}
function check_user_name() {
	if (strlen(document.reg_form.user_name.value)<4 || strlen(document.reg_form.user_name.value)>16) {
		msg = "<span style='color:red'>用户名长度不正确</span>";
		$("check_user_name_warning").innerHTML = msg;
		return false;
	}
	getData('user.php', 'user_name', 'check_user_name_warning', 'check_user_name');
}
function check_pwd() {
	if (strlen(document.reg_form.pwd.value) < 6) {
		msg="<span style='color:red'>密码长度不能少于6个字符</span>";
		$("check_pwd_warning").innerHTML=msg;
		return false;
	} else $("check_pwd_warning").innerHTML="";

	msg=(document.reg_form.pwd.value!=document.reg_form.pwd1.value)?"<span style='color:red'>两次输入的密码不相同</span>":"<span style='color:#006CCE'>两次输入的密码相同</span>";
	document.getElementById("check_pwd1_warning").innerHTML=msg
}
function check_email(){
	var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
	if (!pattern.test(document.reg_form.email.value)) {
		msg = "<span style='color:red'>邮箱格式不正确</span>";
		$("check_email_warning").innerHTML = msg;
		return false;
	}else{
		msg = "<span style='color:#006CCE'>邮箱格式正确</span>";
		$("check_email_warning").innerHTML = msg;
	}
}
function strlen(str) {
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) len += 2; else len++;
    }
    return len;
}
function check_form(form) {
	if (strlen(form.user_name.value)<4 || strlen(form.user_name.value)>20) {
		alert('用户名长度应该在4到16个字符之间');
		form.user_name.focus();
		return false;
	}

	if (form.pwd.value.length<6) {
		alert('您的密码最少必须在6位以上');
		form.pwd.focus();
		return false;
	}
	if (form.pwd.value!=form.pwd1.value) {
		alert('两次输入的密码不相同');
		form.pwd1.focus();
		return false;
	}
	if (form.email.value!=''){
		var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
		if (!pattern.test(form.email.value)) {
			alert('请填写正确的Email地址');
			form.email.focus();
			return false;
		}
	}

	if (form.safecode.value=='') {
		alert('请输入验证码');
		form.safecode.focus();
		return false;
	}
	form.submit.disabled=true;
	return true;
}