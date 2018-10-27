<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="job" value="<?php echo $job;?>"/>
<input type="hidden" name="fid" value="<?php echo $fid;?>"/>
<input type="hidden" name="qid" value="<?php echo $qid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 选项名称</td>
<td><input name="post[name]" type="text"  size="30" id="name" value="<?php echo $name;?>"/> <span id="dname" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 添加方式</td>
<td>
<?php
foreach($TYPE as $k=>$v) { 
?>
<input type="radio" name="post[type]" value="<?php echo $k;?>" id="t_<?php echo $k;?>" onclick="c(<?php echo $k;?>)" <?php echo $k == $type ? 'checked' : '';?>/><label for="t_<?php echo $k;?>"> <?php echo $v;?></label>&nbsp;&nbsp;&nbsp;&nbsp;
<?php }?>
</td>
</tr>
<tr style="display:">
<td class="tl" id="v_l"><span class="f_hid">*</span> 默认值</td>
<td><textarea name="post[value]" style="width:98%;height:30px;overflow:visible;" id="value"><?php echo $value;?></textarea><br/><span id="v_r"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 输入限制</td>
<td><input type="text" name="post[required]" id="required" size="20" value="<?php echo $required;?>"/><br/>
直接填数字表示限制最小长度,如果要限制长度范围例如6到20之间,则填写 6-20<br/>
对于列表选择(select) 和单选框(radio),填非0数字表示必选<br/>
对于多选框(checkbox),填非0数字表示必选个数 填长度范围表示必选个数范围<br/>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 扩展代码</td>
<td><textarea name="post[extend]" style="width:98%;height:30px;overflow:visible;"><?php echo $extend;?></textarea></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $action == 'edit' ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $action == 'edit' ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=question&fid=<?php echo $fid;?>');"/></div>
</form>
<script type="text/javascript">
function c(id) {
	if(id == 2 || id == 3 || id == 4) {
		Dd('v_l').innerHTML = '<span class="f_red">*</span> 备选值';
		Dd('v_r').innerHTML = '多个选项用 | 分隔，例如 红色|绿色(*)|蓝色 (*)表示默认选中<br/>对于复选和单选框，如果选项名为其他，其后会显示一个输入框';
	} else if(id == 0 || id == 1) {
		Dd('v_l').innerHTML = '<span class="f_hid">*</span> 默认值';
		Dd('v_r').innerHTML = '';
	}
}
c(<?php echo $type;?>);
function r(id) {
	if(id == 'notnull') {
		Dd('required').value = '1';
	} else if(id == 'numeric') {
		Dd('required').value = '[0-9]{1,}';
	} else if(id == 'letter') {
		Dd('required').value = '[a-z]{1,}';
	} else if(id == 'nl') {
		Dd('required').value = '[a-z0-9]{1,}';
	} else if(id == 'email') {
		Dd('required').value = 'is_email';
	} else if(id == 'date') {
		Dd('required').value = 'is_date';
	} else {
		Dd('required').value = '';
	}
}
function check() {
	var l;
	var f;
	f = 'name';
	l = Dd(f).value.length;
	if(l < 1) {
		Dmsg('请填写选项名称', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>