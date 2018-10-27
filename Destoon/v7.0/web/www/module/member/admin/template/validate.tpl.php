<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词"/>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<select name="action">
<option value="">认证类型</option>
<?php foreach($V as $k=>$v) { ?>
<option value="<?php echo $k;?>"<?php echo $k == $action ? ' selected' : '';?>><?php echo $v;?></option>
<?php } ?>
</select>&nbsp;
<select name="status">
<option value="0">状态</option>
<option value="3"<?php echo $status == 3 ? ' selected' : '';?>>已认证</option>
<option value="2"<?php echo $status == 2 ? ' selected' : '';?>>未认证</option>
</select>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>认证名称</th>
<th>证件1</th>
<th>证件2</th>
<th>证件3</th>
<th>会员</th>
<th>IP</th>
<th width="130">提交时间</th>
<th>操作人</th>
<th>状态</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<?php if($action == 'company') { ?>
<td><a href="<?php echo DT_PATH;?>api/company.php?wd=<?php echo $v['title'];?>" title="查询企业信息"><?php echo $v['title'];?></a></td>
<?php } elseif($action == 'mobile') { ?>
<td><a href="javascript:_mobile('<?php echo $v['title'];?>');" title="显示手机所在地"><?php echo $v['title'];?></a></td>
<?php } else { ?>
<td><?php echo $v['title'];?></td>
<?php } ?>
<td><?php if($v['thumb']) {?> <a href="javascript:_preview('<?php echo $v['thumb'];?>');"><img src="admin/image/img.gif" width="10" height="10" alt=""/></a><?php } ?></td>
<td><?php if($v['thumb1']) {?> <a href="javascript:_preview('<?php echo $v['thumb1'];?>');"><img src="admin/image/img.gif" width="10" height="10" alt=""/></a><?php } ?></td>
<td><?php if($v['thumb2']) {?> <a href="javascript:_preview('<?php echo $v['thumb2'];?>');"><img src="admin/image/img.gif" width="10" height="10" alt=""/></a><?php } ?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td class="px12"><a href="javascript:_ip('<?php echo $v['ip'];?>');" title="显示IP所在地"><?php echo $v['ip'];?></a></td>
<td class="px12"><?php echo $v['addtime'];?></td>
<td title="<?php echo timetodate($v['edittime']);?>"><?php echo $v['editor'];?></td>
<td><?php echo $v['status'] == 3 ? '<span class="f_green">已认证</span>' : '<span class="f_red">未认证</span>';?></td>
</tr>
<?php }?>
</table>
<div class="btns">
<textarea style="width:300px;height:16px;" name="reason" id="reason" onfocus="if(this.value=='操作原因')this.value='';"/>操作原因</textarea>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="msg" id="msg" value="1" onclick="Dn();" checked/><label for="msg"> 站内通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="eml" id="eml" value="1" onclick="Dn();"/><label for="eml"> 邮件通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="sms" id="sms" value="1" onclick="Dn();"/><label for="sms"> 短信通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="wec" id="wec" value="1" onclick="Dn();"/><label for="wec"> 微信通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<div class="btns">
<input type="submit" value="通过认证" class="btn-g" onclick="if(_check()){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=check';}else{return false;}"/>&nbsp;
<input type="submit" value="拒绝认证" class="btn-r" onclick="if(_reject()){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=reject';}else{return false;}"/>&nbsp;
<input type="submit" value="取消认证" class="btn-r" onclick="if(_cancel()){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=cancel';}else{return false;}"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">
Menuon(<?php echo $menuid;?>);
function is_reason() {
	return Dd('reason').value.length > 2 && Dd('reason').value != '操作原因';
}
function _check() {
	return true;
}
function _reject() {
	if((Dd('msg').checked || Dd('eml').checked) && !is_reason()) {
		alert('请填写操作原因或者取消通知');
		return false;
	}
	if(is_reason() && (!Dd('msg').checked && !Dd('eml').checked)) {
		alert('至少需要选择一种通知方式');
		return false;
	}
	return true;
}
function _cancel() {
	if((Dd('msg').checked || Dd('eml').checked) && !is_reason()) {
		alert('请填写操作原因或者取消通知');
		return false;
	}
	if(is_reason() && (!Dd('msg').checked && !Dd('eml').checked)) {
		alert('至少需要选择一种通知方式');
		return false;
	}
	return confirm('此操作不可撤销，确定要继续吗？');
}
</script>
<?php include tpl('footer');?>