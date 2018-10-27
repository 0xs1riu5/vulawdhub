<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="job" value="<?php echo $job;?>"/>
<input type="hidden" name="fid" value="<?php echo $fid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 复制方式</td>
<td>
	<input type="radio" name="type" value="1" id="t1" onclick="Ds('f1');Dh('f2');" checked/> <label for="t1">批量</label>&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="type" value="0" id="t2" onclick="Ds('f2');Dh('f1');"/> <label for="t2">单项</label>
</td>
</tr>
<tr id="f1" style="display:;">
<td class="tl"><span class="f_red">*</span> 表单ID</td>
<td><input type="text" size="10" name="ffid" id="ffid"/><span id="dffid" class="f_red"></span></td>
</tr>
<tr id="f2" style="display:none;">
<td class="tl"><span class="f_red">*</span> 选项ID</td>
<td><input type="text" size="10" name="fqid" id="fqid"/><span id="dfqid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 同名过滤</td>
<td>
	<input type="radio" name="name" value="1" id="n1" checked/> <label for="n1">是</label>&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="name" value="0" id="n2"/> <label for="n2">否</label>
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="复 制" class="btn-g"/></div>
</form>
<script type="text/javascript">
function check() {
	if(Dd('t1').checked) {
		if(Dd('ffid').value=='') {
			Dmsg('请填写表单ID', 'ffid');
			return false;
		}
		if(Dd('ffid').value==<?php echo $fid;?>) {
			Dmsg('表单ID与当前表单相同', 'ffid');
			return false;
		}
	} else {
		if(Dd('fqid').value=='') {
			Dmsg('请填写选项ID', 'fqid');
			return false;
		}
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>