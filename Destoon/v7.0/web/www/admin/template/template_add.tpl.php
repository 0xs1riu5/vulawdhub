<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="dir" value="<?php echo $dir;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 模板路径</td>
<td><?php echo $template_path;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 模板名称</td>
<td><input type="text" size="20" name="name" value="<?php if(isset($type)) echo $type;?>"/> <span class="f_gray">可以为中文</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 文件名</td>
<td><input type="text" size="20" name="fileid" value="<?php if(isset($type)) echo $type.'-';?>"/>.htm <span class="f_gray">只能为小写字母、数字、中划线、下划线</span></td>
</tr>
<tr>
<td colspan="2">
<textarea name="content" id="content"  style="width:100%;height:300px;font-family:Fixedsys,verdana;overflow:visible;"><?php echo $content;?></textarea>
</td>
</tr>
</table>
<div class="btns"><span class="f_r"><input type="checkbox" name="nowrite" value="1" checked/> 如果模板已经存在,请不要覆盖&nbsp;&nbsp;</span><input type="submit" name="submit" value="保 存" class="btn-g"/>&nbsp;&nbsp;<input type="button" value="预 览" class="btn" onclick="Preview();"/>&nbsp;&nbsp;<input type="reset" value="重 置" class="btn"/>&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="try{window.parent.cDialog();}catch(e){window.history.go(-1);}"/></div>
</form>
<form method="post" action="?" target="_blank" id="p">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="preview"/>
<input type="hidden" name="dir" value="<?php echo $dir;?>"/>
<input type="hidden" id="pcontent" name="content" value=""/>
</form>
<script type="text/javascript">
function Preview() {
	if(Dd('content').value == '') {
		Dtip('模板内容为空');
	} else {
		Dd('pcontent').value = Dd('content').value;
		Dd('p').submit();
	}
}
</script>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>