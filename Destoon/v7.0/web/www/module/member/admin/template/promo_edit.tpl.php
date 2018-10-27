<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 会员名</td>
<td class="tr"><input type="text" size="20" name="post[username]" id="username" value="<?php echo $username;?>"/> <a href="javascript:_user(Dd('username').value);" class="t">[资料]</a> <span class="f_gray">不填代表全站通用</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 优惠名称</td>
<td class="tr"><input name="post[title]" type="text" id="title" size="20" value="<?php echo $title;?>" /> <span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 优惠金额</td>
<td class="tr"><input name="post[price]" type="text" id="price" size="20" value="<?php echo $price;?>" /> <span id="dprice" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 最低消费</td>
<td class="tr"><input name="post[cost]" type="text" id="cost" size="20" value="<?php echo $cost;?>"/> <span class="f_gray">0.00代表不限制</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 数量限制</td>
<td class="tr"><input name="post[amount]" type="text" id="amount" size="20" value="<?php echo $amount;?>"/> <span id="damount" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员领取</td>
<td class="tr">
<input type="radio" name="post[open]" value="1" <?php if($open == 1) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[open]" value="0" <?php if($open == 0) echo 'checked';?>/> 否 <?php tips('如果选择否，会员不能自己领取，只能通过后台发放');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 有效时间</td>
<td class="tr"><?php echo dcalendar('post[fromtime]', $fromtime, '-', 1);?> 至 <?php echo dcalendar('post[totime]', $totime, '-', 1);?> <span id="dtime" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 备注信息</td>
<td class="tr"><input name="post[note]" type="text" id="note" size="60" value="<?php echo $note;?>"/> <span id="dnote" class="f_red"></span></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $action == 'edit' ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function check() {
	var f;
	var l;
	f = 'title';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('请填写优惠名称', f);
		return false;
	}
	f = 'price';
	l = parseFloat(Dd(f).value);
	if(!l || l < 0.1) {
		Dmsg('请填写优惠金额', f);
		return false;
	}
	f = 'amount';
	l = parseInt(Dd(f).value);
	if(!l || l < 1) {
		Dmsg('请填写数量限制', f);
		return false;
	}
	l = Dd('postfromtime').value.length;
	if(l != 19) {
		Dmsg('请选择开始时间', 'time');
		return false;
	}
	l = Dd('posttotime').value.length;
	if(l != 19) {
		Dmsg('请选择结束时间', 'time');
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>