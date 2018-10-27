<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">升级申请</div>
<table cellspacing="0" class="tb">
<?php if($user) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 会员名</td>
<td><a href="javascript:_user('<?php echo $username;?>');" class="t"><?php echo $username;?></a></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司名称</td>
<td><?php echo $user['company'];?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 当前组</td>
<td><?php echo $GROUP[$user['groupid']]['groupname'];?></td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 升级为</td>
<td><?php echo $GROUP[$groupid]['groupname'];?></td>
</tr>
<?php if($company != $user['company']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 新公司名</td>
<td><?php echo $company;?></td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 已付金额</td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=record&username=<?php echo $username;?>', '<?php echo $DT['money_name'];?>流水');"><span class="f_red"><?php echo $amount;?></span></a></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 申请时间</td>
<td><?php echo $addtime;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 申请IP</td>
<td><?php echo $ip;?> - <?php echo ip2area($ip);?></td>
</tr>
</table>
<div class="tt">申请受理</div>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<table cellspacing="0" class="tb">
<?php if($status == 2) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 受理状态</td>
<td>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<input type="radio" name="post[status]" value="3" id="s_3" onclick="S(this.value);"/><label for="s_3"> 通过</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[status]" value="2" id="s_2" onclick="S(this.value);" checked/><label for="s_2"> 待审</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[status]" value="1" id="s_1" onclick="S(this.value);"/><label for="s_1">  拒绝</label>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
<tbody id="pass" style="display:none;">
<tr>
<td class="tl"><span class="f_hid">*</span> 新公司名</td>
<td><input type="text" name="post[company]" size="30" value="<?php echo $company;?>"/></td>
</tr>
<?php if($user && $fee) { ?>
<tr>
<td class="tl"><span class="f_red">*</span> 服务有效期</td>
<td><?php echo dcalendar('post[fromtime]', $fromtime);?> 至 <?php echo dcalendar('post[totime]', $totime);?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 企业资料是否通过认证</td>
<td>
<input type="radio" name="post[validated]" value="1"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[validated]" value="0" checked/> 否
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 认证名称或机构</td>
<td><input type="text" name="post[validator]" size="30"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 认证日期</td>
<td><?php echo dcalendar('post[validtime]', $fromtime);?></td>
</tr>
<?php } ?>
</tbody>
<tbody id="send" style="display:none;">
<?php if($user) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 发送通知</td>
<td>
<input type="checkbox" name="post[msg]" id="msg" value="1" onclick="Dn();" checked/><label for="msg"> 站内通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="post[eml]" id="eml" value="1" onclick="Dn();" checked/><label for="eml"> 邮件通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="post[sms]" id="sms" value="1" onclick="Dn();"/><label for="sms"> 短信通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="post[wec]" id="wec" value="1" onclick="Dn();"/><label for="wec"> 微信通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 操作原因</td>
<td><textarea name="post[reason]" rows="4" cols="60" id="reason"></textarea></td>
</tr>
<?php } ?>
</tbody>
<tr>
<td class="tl"><span class="f_hid">*</span> 受理备注</td>
<td><textarea name="post[note]" rows="4" cols="60"><?php echo $note;?></textarea></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 注意事项</td>
<td class="f_gray">
- 如果拒绝申请，请尽量填写原因<br/>
- 如果拒绝申请，系统会返还会员已支付的金额<br/>
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="确 定" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<?php } else { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 受理状态</td>
<td><?php echo $status == 1 ? '已拒绝' : '已通过';?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 受理通知</td>
<td><?php echo $message == 1 ? '已发送' : '未通知';?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 受理人</td>
<td><?php echo $editor;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 受理时间</td>
<td><?php echo $edittime;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 操作原因</td>
<td><?php echo $reason;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 备注</td>
<td><?php echo $note;?></td>
</tr>
</table>
<?php } ?>
<script type="text/javascript">
function check() {
	return confirm('确定要执行此操作吗？');
}
function S(i) {
	if(i==1) {
		Dh('pass');Ds('send');
	} else if(i==2) {
		Dh('pass');Dh('send');
	} else if(i==3) {
		Ds('pass');Ds('send');
	}
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuon[$status];?>);</script>
<?php include tpl('footer');?>