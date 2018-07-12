function r_mail(x) {//判断邮箱
	return /\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,4}/ig.test(x);
}
function r_password(x) {//判断邮箱
	return /.{6,15}$/ig.test(x);
}
function r_weibo(x){//判断分享内容
	return /^\w{1,140}$/ig.test(x);
}
function r_null(x){//判断分享内容
	// return /[\u4e00-\u9fa5\w]+/ig.test(x);//纯中文
	return /^[\n\t ]*$/ig.test(x);
}
function r_uname(x){
	var count_zh = x.match(/[\u4e00-\u9fa5]{1}/g);
	var count_other = x.match(/\w{1}/g);
	if(count_zh!=null){
		count_zh=count_zh.length*2
	}else{
		count_zh=0;
	}
	if(count_other!=null){
		count_other=count_other.length
	}else{
		count_other=0;
	}
	var count = count_zh+count_other;
	if(count >=4 && count<=20){
		return true;
	}else{
		return false;
	}
}