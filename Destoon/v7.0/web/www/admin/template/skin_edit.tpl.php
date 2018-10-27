<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="dfileid" value="<?php echo $fileid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 文件路径</td>
<td><?php echo $skin_path.$fileid;?>.css</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 文件名</td>
<td><input type="text" size="20" name="fileid" value="<?php echo $fileid;?>"/>.css 不支持中文</td>
</tr>
<tr>
<td colspan="2">
<textarea name="content" style="width:100%;height:300px;font-family:Fixedsys,verdana;overflow:visible;"><?php echo $content;?></textarea>
</td>
</tr>
<tr>
<td colspan="2"><span class="f_r"><input type="checkbox" name="backup" value="1"/> 保存时创建一个备份&nbsp;&nbsp;</span><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;<input type="reset" value="重 置" class="btn"/>&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="history.back(-1);"/></td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>