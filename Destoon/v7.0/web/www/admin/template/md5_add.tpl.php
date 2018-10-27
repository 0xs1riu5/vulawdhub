<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<div class="tt">文件校验</div>
<table cellspacing="0" class="tb">
<tr>
<td width="80">&nbsp;镜像文件</td>
<td>
&nbsp;<input type="submit" name="submit" value="开始校验" class="btn"/>
&nbsp;<input type="submit" name="submit" value="删除镜像" class="btn"/>
</td>
</tr>
</table>
</form>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="add"/>
<div class="tt">创建镜像</div>
<table cellspacing="0" class="tb">
<tr>
<td width="80">&nbsp;选择目录</td>
<td>
<table cellspacing="2" width="600">
<?php foreach($dirs as $k=>$d) { ?>
<?php if($k%4==0) {?><tr><?php } ?>
<td width="150"><input type="checkbox" name="dirs[]" value="<?php echo $d;?>"<?php echo in_array($d, $sys) ? ' checked' : '';?>/>&nbsp;<img src="admin/image/dir.gif" width="16" height="16" alt="" align="absmiddle"/> <?php echo $d;?></td>
<?php if($k%4==3) {?></tr><?php } ?>
<?php } ?>
</table>
</td>
</tr>
<tr>
<td>&nbsp;文件类型</td>
<td>&nbsp;<input type="text" size="50" name="fileext" value="php|js|htm"/></td>
</tr>
<tr>
<td></td>
<td height="30">&nbsp;<input type="submit" name="submit" value="创建镜像" class="btn"/></td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>