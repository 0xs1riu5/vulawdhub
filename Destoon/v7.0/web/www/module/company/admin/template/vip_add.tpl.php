<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 会员名</td>
<td><textarea name="vip[username]" id="username" style="width:200px;height:100px;overflow:visible;"><?php echo $username;?></textarea><?php tips('允许批量添加，一行一个，点回车换行');?><br/><span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员组</td>
<td id="groupid">
<?php foreach($GROUP as $g) {
	if($g['vip'] > 0) echo '<input type="radio" name="vip[groupid]" value="'.$g['groupid'].'"'.($g['groupid'] == 7 ? 'checked' : '').'/> '.$g['groupname'].'&nbsp;';
}
?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 服务有效期</td>
<td><?php echo dcalendar('vip[fromtime]', $fromtime);?> 至 <?php echo dcalendar('vip[totime]', $totime);?> <span id="dtime" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 企业资料是否通过认证</td>
<td>
<input type="radio" name="vip[validated]" value="1"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="vip[validated]" value="0" checked/> 否
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 认证名称或机构</td>
<td><input type="text" name="vip[validator]" size="30"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 认证日期</td>
<td><?php echo dcalendar('vip[validtime]', $fromtime);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 赠送<?php echo $DT['money_name'];?></td>
<td><input type="text" name="money" size="5"/> <?php echo $DT['money_unit'];?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 赠送<?php echo $DT['credit_name'];?></td>
<td><input type="text" name="credit" size="5"/> <?php echo $DT['credit_unit'];?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 赠送短信</td>
<td><input type="text" name="sms" size="5"/> 条</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 赠送理由</td>
<td><input type="text" name="reason" size="30" value="升级赠送"/></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="添 加" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'username';
	if(Dd(f).value == '') {
		Dmsg('请填写会员名', f);
		return false;
	}
	if(Dd('vipfromtime').value.length != 10 || Dd('viptotime').value.length != 10) {
		Dmsg('请选择服务有效期', 'time', 1);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>