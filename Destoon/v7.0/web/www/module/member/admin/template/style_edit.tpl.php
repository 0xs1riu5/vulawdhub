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
<td class="tl"><span class="f_hid">*</span> 模板分类</td>
<td><?php echo type_select($TYPE, 1, 'post[typeid]', '请选择分类', $typeid, 'id="typeid"');?> <span id="dtypeid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 模板名称</td>
<td><input name="post[title]" type="text" id="title" size="30" value="<?php echo $title;?>"/> <span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 风格目录</td>
<td>
<select name="post[skin]" id="skin" onchange="if(this.value){Dskin(this.value);}">
<option value="">请选择</option>
<?php
$d = DT_ROOT.'/'.$MODULE[4]['moduledir'].'/skin/';
foreach(glob($d.'*') as $v) {
	if(is_dir($v) && is_file($v.'/style.css')) {
		$n = basename($v);
		echo '<option value="'.$n.'"'.($skin == $n ? ' selected' : '').'>'.$n.'</option>';
	}
}
?>
</select>
<?php tips('请上传目录至 ./'.$MODULE[4]['moduledir'].'/skin/ 名称为数字、字母组合');?> <span id="dskin" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 模板目录</td>
<td>
<select name="post[template]" id="template">
<option value="">请选择</option>
<?php
$d = DT_ROOT.'/template/'.$CFG['template'].'/';
foreach(glob($d.'*') as $v) {
	if(is_dir($v) && is_file($v.'/side_search.htm')) {
		$n = basename($v);
		echo '<option value="'.$n.'"'.($template == $n ? ' selected' : '').'>'.$n.'</option>';
	}
}
?>
</select>
<?php tips('请上传目录至 ./template/'.$CFG['template'].'/ 名称为数字、字母组合');?> <span id="dtemplate" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 预览</td>
<td id="preview"></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 模板作者</td>
<td><input name="post[author]" type="text" size="20" value="<?php echo $author;?>" /></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员组</td>
<td><?php echo group_checkbox('post[groupid][]', $groupid, '1,2,3,4');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 价格(/月)</td>
<td>
<input name="post[fee]" type="text" size="6" value="<?php echo $fee;?>"/>&nbsp;&nbsp;
<input type="radio" name="post[currency]" value="money"  <?php if($currency == 'money') echo 'checked';?>/> <?php echo $DT['money_name'];?>&nbsp;&nbsp;
<input type="radio" name="post[currency]" value="credit"  <?php if($currency == 'credit') echo 'checked';?>/> <?php echo $DT['credit_name'];?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 人气</td>
<td><input type="text" size="6" name="post[hits]" value="<?php echo $hits;?>"/></td>
</tr>
<tr title="请保持时间格式">
<td class="tl"><span class="f_hid">*</span> 添加时间</td>
<td><?php echo dcalendar('post[addtime]', $addtime, '-', 1);?></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function Dskin(i) {
	var nopic = '<?php echo $MODULE[4]['linkurl'];?>image/nothumb.gif';
	Dd('preview').innerHTML = '<img src="<?php echo $MODULE[4]['linkurl'];?>skin/'+i+'/thumb.gif" onerror="this.src=\''+nopic+'\';"/>';
}
Dskin('<?php echo $skin;?>');
function check() {
	var f;
	f = 'title';
	if(Dd(f).value == '') {
		Dmsg('请填写模板名称', f);
		return false;
	}
	f = 'skin';
	if(Dd(f).value == '') {
		Dmsg('请选择风格', f);
		return false;
	}
	f = 'template';
	if(Dd(f).value == '') {
		Dmsg('请选择模板', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>