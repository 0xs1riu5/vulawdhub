<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="dir" value="<?php echo $dir;?>"/>
<input type="hidden" name="dfileid" value="<?php echo $fileid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 模板路径</td>
<td><?php echo $template_path.$fileid;?>.htm</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 模板名称</td>
<td><input type="text" size="20" name="name" value="<?php echo $name;?>"/> <span class="f_gray">可以为中文</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 文件名</td>
<td><input type="text" size="20" name="fileid" value="<?php echo $fileid;?>"/>.htm <span class="f_gray">只能为小写字母、数字、中划线、下划线</span></td>
</tr>
<tr>
<td colspan="2">
<textarea name="content" id="content" style="width:100%;height:300px;font-family:Fixedsys,verdana;overflow:visible;"><?php echo $content;?></textarea>
</td>
</tr>
</table>
<div class="btns"><span class="f_r"><input type="checkbox" name="backup" value="1"/> 保存时，创建一个备份文件&nbsp;&nbsp;</span><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;<input type="button" value="预 览" class="btn" onclick="Preview();"/>&nbsp;&nbsp;<input type="reset" value="重 置" class="btn"/>&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="try{window.parent.cDialog();}catch(e){window.history.go(-1);}"/></td>
</div>
</form>
<form method="post" action="?file=<?php echo $file;?>&action=preview&dir=<?php echo $dir;?>" target="_blank" id="p">
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
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>