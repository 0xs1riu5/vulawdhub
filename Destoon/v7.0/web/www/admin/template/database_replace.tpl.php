<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>

<form method="post" action="?" onsubmit="return fcheck();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="file_replace"/>
<div class="tt">备份内容替换</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 备份系列</td>
<td>
<select name="file_pre">
<option value="">选择备份文件系列</option>
<?php echo $sql_select;?>
</select> <span id="dfile_pre" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 查找</td>
<td><input type="text" name="file_from" value="" size="60" id="file_from"/><br/><span id="dfile_from" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 替换为 </td>
<td><input type="text" name="file_to" value="" size="60" id="file_to"/><br/><span id="dfile_to" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">&nbsp;</td>
<td><input type="submit" name="submit" value="执 行" class="btn-r"/></td> 
</tr>
</table>
</form>

<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<div class="tt">数据内容替换</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 数据字段</td>
<td>
<select name="table" onchange="get_fields(this.value);">
<option value="">选择表</option>
<?php echo $table_select;?>
</select>
&nbsp;&nbsp;&nbsp;
<span id="fields"><select name="fields" id="fd"><option value="">选择字段</option></select></span> <span id="dfd" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 替换类型</td>
<td>
<input name="type" type="radio" value="1" checked id="type" onclick="Dd('adds').style.display='none';Dd('replace').style.display='';"/> 直接替换&nbsp;&nbsp;
<input name="type" type="radio" value="2" onclick="Dd('adds').style.display='';Dd('replace').style.display='none';"/> 头部追加&nbsp;&nbsp;
<input name="type" type="radio" value="3" onclick="Dd('adds').style.display='';Dd('replace').style.display='none';"/> 尾部追加
</td>
</tr>
<tbody id="replace" style="display:;">
<tr>
<td class="tl"><span class="f_red">*</span> 查找</td>
<td><textarea name="from" id="from" style="width:500px;height:50px;overflow:visible;"></textarea><br/><span id="dfrom" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 替换为 </td>
<td><textarea name="to" id="to" style="width:500px;height:50px;overflow:visible;"></textarea><br/><span id="dto" class="f_red"></span></td>
</tr>
</tbody>
<tbody id="adds" style="display:none;">
<tr>
<td class="tl"><span class="f_red">*</span> 追加内容</td>
<td><textarea name="add" id="add" style="width:500px;height:50px;overflow:visible;"></textarea><br/><span id="dadd" class="f_red"></span></td>
</tr>
</tbody>
<tr>
<td class="tl"><span class="f_hid">*</span> 替换条件</td>
<td><input name="conditon" type="text" size="50"/> <span class="f_gray">请输入MySQL条件语句（AND开头）</span></td> 
</tr>
<tr>
<td class="tl">&nbsp;</td>
<td><input type="submit" name="submit" value="执 行" class="btn-r"/></td> 
</tr>
</table>
</form>

<script type="text/javascript">
var vid = '';
function get_fields(tb) {
	if(!tb) return false;
	$.get('?file=<?php echo $file;?>&action=fields&table='+tb, function(data) {
		if(data) Dd('fields').innerHTML = data;
	});
}
function fcheck() {
	if(Dd('file_pre').value == '') {
		Dmsg('请选择备份系列', 'file_pre');
		return false;
	}
	if(Dd('file_from').value == '') {
		Dmsg('请填写查找内容', 'file_from');
		return false;
	}
	return confirm('您确定要开始替换吗？');
}
function check() {
	if(Dd('fd').value == '') {
		Dmsg('请选择数据字段', 'fd');
		return false;
	}
	if(Dd('type').checked) {
		if(Dd('from').value == '') {
			Dmsg('请填写查找内容', 'from');
			return false;
		}
	} else {
		if(Dd('add').value == '') {
			Dmsg('请填写追加内容', 'add');
			return false;
		}
	}
	return confirm('重要提示:为防止操作失误，请务必在操作之前备份数据\n\n此操作不可恢复，您确定要执行吗？');
}
</script>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>