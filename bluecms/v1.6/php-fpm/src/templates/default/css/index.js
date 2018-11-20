if (top.location != self.location) {top.location=self.location;}

function createQueryString(){
	var user_name = encodeURI($("#user_name").val());
	var pwd = encodeURI($("#pwd").val());
	var queryString = {user_name:first_name,pwd:pwd};
	return queryString;
}
function loging(){
	$.get("user.php?act=index_login",createQueryString(),
		function(data){
			$("#logining").html(decodeURI(data));
		}
	);
}

function checkLog(form){
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
	//document.log_form.submit();
	loging();
}