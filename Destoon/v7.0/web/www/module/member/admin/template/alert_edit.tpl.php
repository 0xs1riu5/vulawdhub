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
<td class="tl"><span class="f_red">*</span> 会员名</td>
<td><input name="post[username]" type="text" size="20" value="<?php echo $username;?>" id="username"/> <a href="javascript:_user(Dd('username').value);" class="t">[资料]</a> <span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 商机类型</td>
<td>
<select name="post[mid]" onchange="ch_mid(this.value);">
<?php foreach($mids as $v) { ?>
<option value="<?php echo $v;?>"<?php echo $mid == $v ? ' selected' : '';?>><?php echo $MODULE[$v]['name'];?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 关键词</td>
<td><input type="text" name="post[word]" id="word" size="30" value="<?php echo $word;?>" maxlength="30"/><span id="dword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 行业分类</td>
<td><div id="catesch"></div><?php echo ajax_category_select('post[catid]', '请选择', $catid, $mid);?><span id="dcatid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 所在地区</td>
<td><?php echo ajax_area_select('post[areaid]', '请选择', $areaid);?> <span id="dareaid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 发送频率</td>
<td>
<select name="post[rate]">
<option value="0"<?php if($rate==0) { ?> selected<?php } ?>>不限</option>
<option value="1"<?php if($rate==1) { ?> selected<?php } ?>>1天</option>
<option value="3"<?php if($rate==3) { ?> selected<?php } ?>>3天</option>
<option value="7"<?php if($rate==7) { ?> selected<?php } ?>>7天</option>
<option value="15"<?php if($rate==15) { ?> selected<?php } ?>>15天</option>
<option value="30"<?php if($rate==30) { ?> selected<?php } ?>>30天</option>
</select>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 提醒状态</td>
<td>
<input type="radio" name="post[status]" value="3" <?php if($status == 3) echo 'checked';?> id="status_3"/><label for="status_3"> 通过</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[status]" value="2" <?php if($status == 2) echo 'checked';?> id="status_2"/><label for="status_2"> 待审</label>
</td>
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
	return true;
}
function ch_mid(i) {
	category_moduleid[1] = i;
	load_category(0, 1);
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>