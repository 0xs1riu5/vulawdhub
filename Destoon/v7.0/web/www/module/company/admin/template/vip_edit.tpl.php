<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 会员名</td>
<td><input type="text" size="20" name="vip[username]" id="username" value="<?php echo $username;?>"/> <span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员组</td>
<td>
<?php foreach($GROUP as $g) {
	if($g['vip'] > 0) echo '<input type="radio" name="vip[groupid]" value="'.$g['groupid'].'" '.($groupid == $g['groupid'] ? 'checked' : '').'/> '.$g['groupname'].'&nbsp;';
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
<input type="radio" name="vip[validated]" value="1" <?php if($validated) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="vip[validated]" value="0" <?php if(!$validated) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 认证名称或机构</td>
<td><input type="text" name="vip[validator]" size="30" value="<?php echo $validator;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 认证日期</td>
<td><?php echo dcalendar('vip[validtime]', $validtime);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> <?php echo VIP;?>指数修正值</td>
<td><input type="text" name="vip[vipr]" size="2" value="<?php echo $vipr;?>"/></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
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
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>