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
<td class="tl"><span class="f_red">*</span> 排名模块</td>
<td>
<select name="post[mid]">
<?php 
foreach($MODULE as $v) {
	if(($v['moduleid'] > 0 && $v['moduleid'] < 4) || $v['islink']) continue;
	echo '<option value="'.$v['moduleid'].'"'.($mid == $v['moduleid'] ? ' selected' : '').'>'.$v['name'].'</option>';
} 
?>
</select>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 关键词</td>
<td><input type="text" size="40" name="post[word]" id="word" value="<?php echo $word;?>"/> <span id="dword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 出价</td>
<td><input type="text" size="20" name="post[price]" id="price" value="<?php echo $price;?>"/> <span id="dprice" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 单位</td>
<td>
<input type="radio" name="post[currency]" value="money" <?php if($currency == 'money') echo 'checked';?>/> <?php echo $DT['money_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[currency]" value="credit" <?php if($currency == 'credit') echo 'checked';?>/> <?php echo $DT['credit_name'];?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 信息ID</td>
<td><input type="text" size="10" name="post[tid]" id="key_id" value="<?php echo $tid;?>" onfocus="Sid();"/> <span id="dkey_id" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 投放时段</td>
<td><?php echo dcalendar('post[fromtime]', $fromtime);?> 至 <?php echo dcalendar('post[totime]', $totime);?> <span id="dtime" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员名称</td>
<td><input type="text" size="20" name="post[username]" id="username" value="<?php echo $username;?>"/>&nbsp;&nbsp;<a href="javascript:_user(Dd('username').value);" class="t">[资料]</a> <span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 排名状态</td>
<td>
<input type="radio" name="post[status]" value="3" <?php if($status == 3) echo 'checked';?>/> 通过&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[status]" value="2" <?php if($status == 2) echo 'checked';?>/> 待审
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 备注事项</td>
<td><input type="text" size="60" name="post[note]" value="<?php echo $note;?>"/></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $action == 'edit' ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $action == 'edit' ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
function Sid() {
	if(Dd('m_4').checked) {
		select_item('4&itemid='+Dd('key_id').value);
	} else if(Dd('m_5').checked) {
		select_item('5&itemid='+Dd('key_id').value);
	} else if(Dd('m_6').checked) {
		select_item('6&itemid='+Dd('key_id').value);
	} else if(Dd('m_16').checked) {
		select_item('16&itemid='+Dd('key_id').value);
	}
}
function check() {
	var l;
	var f;
	f = 'word';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('请输入关键词', f);
		return false;
	}
	f = 'price';
	l = Dd(f).value.length;
	if(l < 1) {
		Dmsg('请填写出价', f);
		return false;
	}
	f = 'key_id';
	l = Dd(f).value.length;
	if(l < 1) {
		Dmsg('请填写信息ID', f);
		return false;
	}	
	if(Dd('postfromtime').value.length != 10 || Dd('posttotime').value.length != 10) {
		Dmsg('请选择投放时段', 'time');
		return false;
	}
	f = 'username';
	l = Dd(f).value.length;
	if(l < 3) {
		Dmsg('请填写会员名称', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>