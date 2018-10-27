<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="mid" value="<?php echo $mid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 来源模块</td>
<td>
<select name="fromid" id="fromid">
<option value="0">请选择</option>
<?php
foreach($MODULE as $m) {
	if($m['moduleid'] < 4 || $m['moduleid'] == $mid || $m['islink']) continue;
	echo '<option value="'.$m['moduleid'].'">'.$m['name'].'</option>';
}
?>
</select>
<span id="dfromid" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 当前模块分类数据</td>
<td>
<input type="radio" name="save" value="1" checked/> 保留&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="save" value="0"/> 删除
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="复 制" class="btn-g"/></div>
</form>
<script type="text/javascript">
function check() {
	if(Dd('fromid').value==0) {
		Dmsg('请选择来源模块', 'fromid');
		return false;
	}
	return confirm('此操作不可撤销，确定要执行吗？');
}
</script>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>