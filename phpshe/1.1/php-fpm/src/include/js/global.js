/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2010-1001 koyshe <koyshe@gmail.com>
 */
//$.ajaxSettings.async = false;
//常用正则规则
var rule_phone = /^((1[0-9]{10})|(029[0-9]{8}))$/;
var rule_qq = /^[0-9]{5,10}$/;
var rule_email = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[a-z]{2,3}$/;
var rule_zh = /^[\u4e00-\u9fa5]+$/;

/* ====================== jq全局操作函数 ====================== */
//全选操作(修正版) by koyshe 2012-03-09
function pe_checkall(_this, inputname) {
	var checkname = $(_this).attr("name");
	if ($(_this).is(":checked")) {
		$("input[name='"+inputname+"[]']").add("input[name='"+checkname+"']").attr("checked","checked");
	}
	else {
		$("input[name='"+inputname+"[]']").add("input[name='"+checkname+"']").removeAttr("checked");
	}
} 
//带提醒批量操作(修正版) by koyshe 2012-03-09
function pe_cfall(_this, inputname, formid, show) {
	if ($("input[name='"+inputname+"[]']").filter(":checked").length == 0) {
		alert('请先勾选需要'+show+'的信息!');
		return false;
	}
	else if (confirm('您确认执行'+show+'操作吗?')) {
		$("#"+formid).attr("action", $(_this).attr("href")).submit();
	}
	return false;
}
//带提醒单个操作(修正版) by koyshe 2012-11-29
function pe_cfone(_this, show) {
	if (confirm('您确认执行'+show+'操作吗?')) {
		if ($(_this).is("a")) {
			return true;
		}
		else {
			if ($(_this).attr("target") == "_blank") {
				window.open($(_this).attr("href"));
				return false;
			}
			if (document.all) {  
				var referer_url = document.createElement('a');  
				referer_url.href = $(_this).attr("href");  
				document.body.appendChild(referer_url);  
				referer_url.click();  
			}
			else {
				window.location.href = $(_this).attr("href");
			}
		}
	}
	return false;
};
//批量操作 by koyshe 2012-03-09
function pe_doall(_this, formid) {
	$("#"+formid).attr("action", $(_this).attr("href")).submit();
}
//dialog函数 by koyshe 2011-11-12
function pe_dialog(_this, title, width, height, id) {
	art.dialog.open($(_this).attr("href"), {title:title, width: width, height: height, id: id});
	return false;
}
//商品购买数量
function pe_numchange(inputname, type, limit) 
{
	var _input = $(":input[name='"+inputname+"']");
	var _input_val = parseInt(_input.val());
	var limit = parseInt(limit);
	if (type == '+') {
		if (_input_val < limit) _input.val(_input_val + 1)
	}
	else {
		if (_input_val > limit) _input.val(_input_val - 1)
	}
}