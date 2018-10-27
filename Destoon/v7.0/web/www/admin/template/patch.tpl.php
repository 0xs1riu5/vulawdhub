<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">选择目录</td>
<td>
<table cellspacing="2" width="600" class="ctb">
<?php foreach($dirs as $k=>$d) { ?>
<?php if($k%4==0) {?><tr><?php } ?>
<td width="150"><input type="checkbox" name="filedir[]" value="<?php echo $d;?>"<?php echo in_array($d, $sys) ? ' checked' : '';?> id="dir_<?php echo $d;?>"/><label for="dir_<?php echo $d;?>">&nbsp;<img src="admin/image/folder.gif" width="16" height="14" alt="" align="absmiddle"/> <?php echo $d;?></label></td>
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
<td class="tl">文件类型</td>
<td>&nbsp;<input type="text" size="68" name="fileext" value="<?php echo $ext;?>" class="f_fd"/></td>
</tr>
<tr>
<td class="tl">修改时间</td>
<td>
&nbsp;<?php echo dcalendar('fd', $fd, '-', 1);?>
&nbsp; 至 
&nbsp;<?php echo dcalendar('td', $td, '-', 1);?>
</td>
</tr>
<tr>
<td class="tl">备注信息</td>
<td>&nbsp;<input type="text" size="68" name="note" value="" placeholder="本次备份相关的备注事项" class="f_fd"/></td>
</tr>
<tr>
<td></td>
<td height="30">&nbsp;<input type="submit" name="submit" value="开始备份" class="btn-g" onclick="this.value='备份中..';this.blur();this.className='btn f_gray';"/></td>
</tr>
</table>
</form>

<?php if($baks) { ?>
<table cellspacing="0" class="tb ls">
<tr>
<th width="158">备份时间</th>
<th>目录</th>
<th width="150">文件数量</th>
<th width="200">备注信息</th>
<th width="40">操作</th>
</tr>
<?php foreach($baks as $v) { ?>
<tr align="center">
<td class="px12"><?php echo $v['time'];?></td>
<td align="left">&nbsp;&nbsp;<img src="admin/image/folder.gif" alt="" align="absmiddle"/> <a href="javascript:Dwidget('?file=<?php echo $file;?>&action=view&fid=<?php echo $v['file'];?>', '[<?php echo $v['file'];?>]文件列表');" title="位于 file/patch/<?php echo $v['file'];?> 点击查看文件列表"><?php echo $v['file'];?></a></td>
<td class="px12"><?php echo $v['num'];?></td>
<td><textarea style="width:160px;height:15px;" title="<?php echo $v['note'];?>" onmouseover="this.select();"><?php echo $v['note'];?></textarea></td>
<td><a href="?file=<?php echo $file;?>&action=delete&fid=<?php echo $v['file'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php } ?>
</table>
<?php } ?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>