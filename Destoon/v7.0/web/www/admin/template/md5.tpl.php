<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<?php if($submit) { ?>
<table cellspacing="0" class="tb">
<?php if($lists) { ?>
<tr>
<th>文件</th>
<th width="150">大小</th>
<th width="150">修改时间</th>
</tr>
	<?php foreach($lists as $f) { ?>
	<tr align="center">
	<td align="left" class="f_fd">&nbsp;<?php echo $f;?></td>
	<td class="px12"><?php echo dround(filesize(DT_ROOT.'/'.$f)/1024);?> Kb</td>
	<td class="px12"><?php echo timetodate(filemtime(DT_ROOT.'/'.$f), 6);?></td>
	</tr>
	<?php } ?>
	<tr>
	<td colspan="3" height="30" class="f_blue">&nbsp; - 以上文件曾被修改或创建，请下载手动检查文件内容是否安全&nbsp;&nbsp;&nbsp;&nbsp;<a href="?file=<?php echo $file;?>" class="t">[重新校验]</a></td>
	</tr>
<?php } else { ?>
<tr>
<td class="f_green" height="40">&nbsp; - 没有文件被修改或创建&nbsp;&nbsp;&nbsp;&nbsp;<a href="?file=<?php echo $file;?>" class="t">[重新校验]</a></td>
</tr>
<?php } ?>
</table>

<?php } else { ?>
<form method="post" id="dform">
<div class="tt">文件校验</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">&nbsp;选择目录</td>
<td>
<table cellspacing="2" width="600" class="ctb">
<?php foreach($dirs as $k=>$d) { ?>
<?php if($k%4==0) {?><tr><?php } ?>
<td width="150"><input type="checkbox" name="filedir[]" value="<?php echo $d;?>"<?php echo in_array($d, $sys) ? ' checked' : '';?><?php echo in_array($d, $fbs) ? ' disabled' : '';?> id="cdir_<?php echo $d;?>"/><label for="cdir_<?php echo $d;?>">&nbsp;<img src="admin/image/folder.gif" width="16" height="14" alt="" align="absmiddle"/> <?php echo $d;?></label></td>
<?php if($k%4==3) {?></tr><?php } ?>
<?php } ?>
</table>
<div>&nbsp;
<a href="javascript:" onclick="checkall(Dd('dform'), 1);" class="t">反选</a>&nbsp;&nbsp;
<a href="javascript:" onclick="checkall(Dd('dform'), 2);" class="t">全选</a>&nbsp;&nbsp;
<a href="javascript:" onclick="checkall(Dd('dform'), 3);" class="t">全不选</a>&nbsp;&nbsp;
</div>
</td>
</tr>
<tr>
<td class="tl">&nbsp;文件类型</td>
<td>&nbsp;<input type="text" size="40" name="fileext" value="php|js|htm" class="f_fd"/></td>
</tr>
<tr>
<td class="tl">&nbsp;镜像文件</td>
<td>&nbsp;<select name="mirror" id="mirror">
<option value="">系统默认</option>
<?php 
	foreach($mfiles as $f) {
	$n = basename($f, '.php');
	if(strlen($n) < 16) continue;
?>
<option value="<?php echo $n;?>"><?php echo $n.' '.dround(filesize($f)/1024, 2);?> K</option>
<?php } ?>
</select>
&nbsp;<input type="submit" name="submit" value="开始校验" class="btn-g" onclick="this.form.action='?file=<?php echo $file;?>';this.value='校验中..';this.blur();this.className='btn f_gray';"/>
&nbsp;<input type="submit" name="submit" value="删除镜像" class="btn-r" onclick="if(Dd('mirror').value==''){alert('请选择需要删除的镜像文件');Dd('mirror').focus();return false;}if(confirm('确定要删除吗？此操作将不可恢复')){this.form.action='?file=<?php echo $file;?>&action=delete';}else{return false;}"/>
</td>
</tr>
</table>
</form>
<form method="post" action="?" id="dmd5">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="add"/>
<div class="tt">创建镜像</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">&nbsp;选择目录</td>
<td>
<table cellspacing="2" width="600">
<?php foreach($dirs as $k=>$d) { ?>
<?php if($k%4==0) {?><tr><?php } ?>
<td width="150"><input type="checkbox" name="filedir[]" value="<?php echo $d;?>"<?php echo in_array($d, $sys) ? ' checked' : '';?><?php echo in_array($d, $fbs) ? ' disabled' : '';?> id="adir_<?php echo $d;?>"/><label for="adir_<?php echo $d;?>">&nbsp;<img src="admin/image/folder.gif" width="16" height="14" alt="" align="absmiddle"/> <?php echo $d;?></label></td>
<?php if($k%4==3) {?></tr><?php } ?>
<?php } ?>
</table>
<div>&nbsp;
<a href="javascript:" onclick="checkall(Dd('dmd5'), 1);" class="t">反选</a>&nbsp;&nbsp;
<a href="javascript:" onclick="checkall(Dd('dmd5'), 2);" class="t">全选</a>&nbsp;&nbsp;
<a href="javascript:" onclick="checkall(Dd('dmd5'), 3);" class="t">全不选</a>&nbsp;&nbsp;
</div>
</td>
</tr>
<tr>
<td class="tl">&nbsp;文件类型</td>
<td>&nbsp;<input type="text" size="40" name="fileext" value="php|js|htm" class="f_fd"/></td>
</tr>
<tr>
<td></td>
<td height="30">&nbsp;<input type="submit" name="submit" value="创建镜像" class="btn-g" onclick="this.value='创建中..';this.blur();this.className='btn f_gray';"/></td>
</tr>
</table>
</form>
<?php } ?>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>