<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 会员名</td>
<td class="tr"><input type="text" size="20" name="username" id="username" value=""/> <a href="javascript:_user(Dd('username').value);" class="t">[资料]</a> <span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 优惠名称</td>
<td class="tr"><?php echo $title;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 所属商家</td>
<td class="tr"><a href="javascript:_user('<?php echo $username;?>');" class="t"><?php echo $username ? $username : '全站通用';?></a></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 优惠金额</td>
<td class="tr"><?php echo $price;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 最低消费</td>
<td class="tr"><?php echo $cost;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 数量限制</td>
<td class="tr"><?php echo $amount;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 有效时间</td>
<td class="tr"><?php echo $fromtime;?> 至 <?php echo $totime;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 备注信息</td>
<td class="tr"><input name="note" type="text" id="note" size="60" value="<?php echo $note;?>"/> <span id="dnote" class="f_red"></span></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="赠 送" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="parent.cDialog();"/></div>
</form>
<script type="text/javascript">
function check() {
	var f;
	var l;
	f = 'username';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('请填写会员名称', f);
		return false;
	}
	return true;
}
</script>
<?php include tpl('footer');?>