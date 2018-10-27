<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 新登录密码</td>
<td><input type="password" name="password" size="30" id="password" autocomplete="off"/> <span id="dpassword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 重复新密码</td>
<td><input type="password" name="cpassword" size="30" id="cpassword" autocomplete="off"/> <span id="dcpassword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 现有密码</td>
<td><input type="password" name="oldpassword" size="30" id="oldpassword" autocomplete="off"/> <span id="doldpassword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"> </td>
<td><input type="submit" name="submit" value="修 改" class="btn-g"/></td>
</tr>
</form>
</table>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'password';
	l = Dd(f).value.length;
	if(l < 6) {
		Dmsg('新登录密码最少6位，当前已输入'+l+'位', f);
		return false;
	}
	f = 'cpassword';
	l = Dd(f).value;
	if(l != Dd('password').value) {
		Dmsg('重复新密码与新登录密码不一致', f);
		return false;
	}
	f = 'oldpassword';
	l = Dd(f).value.length;
	if(l < 6) {
		Dmsg('现有密码最少6位，当前已输入'+l+'位', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>