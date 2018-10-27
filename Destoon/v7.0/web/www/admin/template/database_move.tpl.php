<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 转移类型</td>
<td>&nbsp;
<select name="fmid" id="fmid">
<option value="0">来源模块</option>
<?php
foreach($MODULE as $m) {
	if($m['moduleid'] > 4 && !$m['islink']) {
		echo '<option value="'.$m['moduleid'].'">'.$m['name'].'</option>';
	}
}
?>
</select>
&rarr;
<select name="tmid" id="tmid" onchange="loadc(this.value);">
<option value="0">目标模块</option>
<?php
foreach($MODULE as $m) {
	if($m['moduleid'] > 4 && !$m['islink']) {
		echo '<option value="'.$m['moduleid'].'">'.$m['name'].'</option>';
	}
}
?>
</select>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 转移条件</td>
<td class="f_gray">&nbsp;
<input type="text" name="condition" value="" size="80" id="condition"/>
<br/>
&nbsp;- 如果转移单条信息，则直接填写信息ID，例如 <span class="f_blue">123</span><br/>
&nbsp;- 如果转移多条信息，则填用,分隔信息ID，例如 <span class="f_blue">123,124,125</span> (结尾和开头不需要,)<br/>
&nbsp;- 可直接写SQL调用条件，必须以and开头<br/>
&nbsp;&nbsp;例如 <span class="f_blue">and catid=123</span> 表示调用分类ID为123的信息<br/>
&nbsp;&nbsp;例如 <span class="f_blue">and itemid>0</span> 表示调用源模块所有信息<br/>
&nbsp;&nbsp;例如 <span class="f_blue">and price>0</span> 表示调用有价格的信息(一般为供应)<br/>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 新分类</td>
<td>&nbsp;
<?php echo ajax_category_select('catid', '请选择', 0, 16, 'size="2" style="height:120px;width:180px;"');?>
<?php tips('数据将被转移到此分类下');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 删除源数据</td>
<td>&nbsp;
<input type="radio" name="delete" value="1" id="d_1"/> 是&nbsp;&nbsp;&nbsp;
<input type="radio" name="delete" value="0" id="d_0" checked/> 否
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 注意事项</td>
<td class="f_gray">
&nbsp;- 转移成功后请进入目标模型管理，更新新转移的信息，如果模型内容设置生成HTML，需要生成一下<br/>
&nbsp;- 可能需要按信息ID降序搜索才可以看到新转移的信息<br/>
&nbsp;- 如果待转移的数据较多，请设置条件分批转移，以免转移程序卡死<br/>
</td>
</tr>
<tr>
<td class="tl">&nbsp;</td>
<td>&nbsp;<input type="submit" name="submit" value="执 行" class="btn-r"/></td> 
</tr>
</table>
</form>
<script type="text/javascript">
function loadc(i) {
	if(i) {
		category_moduleid[1] = i;
		load_category(0, 1);
	}
}
function check() {
	if(Dd('fmid').value == 0) {
		alert('请选择信息来源模块');
		Dd('fmid').focus();
		return false;
	}
	if(Dd('tmid').value == 0) {
		alert('请选择信息目标模块');
		Dd('tmid').focus();
		return false;
	}
	if(Dd('fmid').value == Dd('tmid').value) {
		alert('来源模块和目标模块不能相同');
		Dd('tmid').focus();
		return false;
	}
	if(Dd('condition').value.length < 1) {		
		alert('请填写转移条件');
		Dd('condition').focus();
		return false;
	}
	if(Dd('catid_1').value == 0) {		
		alert('请选择新分类');
		return false;
	}
	return confirm('确定要转移吗？此操作将不可恢复');
}
</script>
<script type="text/javascript">Menuon(5);</script>
<?php include tpl('footer');?>