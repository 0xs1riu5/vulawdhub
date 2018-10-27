<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb ls">
<tr>
<th>文件名</th>
<th width="180">文件大小</th>
<th width="180">修改时间</th>
<th width="120">操作</th>
</tr>
<?php foreach($skins as $k=>$v) {?>
<tr>

<td>&nbsp;<a href="<?php echo $skin_path.$v['filename'];?>" title="查看" target="_blank"><img src="admin/image/css.gif" width="16" height="16" alt="" align="absmiddle"/></a> <a href="?file=<?php echo $file;?>&action=edit&fileid=<?php echo $v['fileid'];?>" title="修改"><?php echo $v['filename'];?></a></td>

<td align="center"><?php echo $v['filesize'];?> Kb</td>

<td align="center"><?php echo $v['mtime'];?></td>

<td align="center">
<a href="?file=<?php echo $file;?>&action=add"><img src="admin/image/new.png" width="16" height="16" title="新建" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=edit&fileid=<?php echo $v['fileid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=download&fileid=<?php echo $v['fileid'];?>"><img src="admin/image/save.png" width="16" height="16" title="下载" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=delete&fileid=<?php echo $v['fileid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>

</tr>
<?php }?>
</table>
<?php if($baks) { ?>
<div class="tt">风格备份管理</div>
<table cellspacing="0" class="tb ls">
<tr>
<th>文件名</th>
<th width="180">文件大小</th>
<th width="180">备份时间</th>
<th width="150">操作</th>
</tr>
<?php foreach($baks as $k=>$v) {?>
<tr>

<td>&nbsp;<img src="admin/image/unknow.gif" width="16" height="16" alt="" align="absmiddle"/> <a href="<?php echo $skin_path.$v['filename'];?>" title="查看" target="_blank"><?php echo $v['filename'];?></a></td>

<td align="center"><?php echo $v['filesize'];?> Kb</td>

<td align="center"><?php echo $v['mtime'];?></td>

<td align="center">
<a href="javascript:Dconfirm('确定要恢复<?php echo $v['fileid'];?>备份吗？此操作将不可撤销<br/>文件<?php echo $v['type'];?>.css的内容将被<?php echo $v['filename'];?>覆盖', '?file=<?php echo $file;?>&action=import&fileid=<?php echo $v['type'];?>&bakid=<?php echo $v['number'];?>');"><img src="admin/image/import.png" width="16" height="16" title="恢复" alt=""/></a>&nbsp;
<a href="<?php echo $skin_path.$v['filename'];?>" target="_blank"><img src="admin/image/view.png" width="16" height="16" title="查看" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=download&fileid=<?php echo $v['type'];?>&bakid=<?php echo $v['number'];?>"><img src="admin/image/save.png" width="16" height="16" title="下载" alt=""/></a>&nbsp;
<a href="javascript:Dconfirm('确定要删除<?php echo $v['filename'];?>备份吗？此操作不可撤销', '?file=<?php echo $file;?>&action=delete&fileid=<?php echo $v['type'];?>&bakid=<?php echo $v['number'];?>');"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<?php }?>
<div class="btns">
<?php
$select = '';
$dirs = list_dir('skin');
foreach($dirs as $v) {
	$selected = ($skin && $v['dir'] == $skin) ? 'selected' : '';
	$select .= "<option value='".$v['dir']."' ".$selected.">".$v['name']."(".$v['dir'].")</option>";
}
$select = '<select onchange="if(this.value) Go(\'?file='.$file.'&action=change&to=\'+this.value);"><option value="">切换风格</option>'.$select.'</select>';
echo $select;
?>
</div>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>