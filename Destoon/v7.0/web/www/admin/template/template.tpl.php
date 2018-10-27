<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb ls">
<tr>
<th>文件名</th>
<th width="120">模板名称</th>
<th width="120">模板系列</th>
<th width="80" data-hide="1200">文件大小</th>
<th width="130">修改时间</th>
<th width="50" data-hide="1200">属性</th>
<th width="100">操作</th>
</tr>
<?php foreach($dirs as $k=>$v) {?>
<tr align="center">
<td align="left">&nbsp;<img src="admin/image/folder.gif" alt="" align="absmiddle"/> <a href="?file=<?php echo $file;?>&dir=<?php echo $v['dirname'];?>" title="修改"><?php echo $v['dirname'];?></a></td>
<td><input type="text" style="width:130px;" value="<?php echo $v['name'];?>" onblur="template_name('<?php echo $v['dirname'];?>', this.value);"/></td>
<td>&lt;目录&gt;</td>
<td data-hide="1200">&lt;目录&gt;</td>
<td><?php echo $v['mtime'];?></td>
<td data-hide="1200"><?php echo $v['mod'];?></td>
<td>
<a href="?file=<?php echo $file;?>&action=add&dir=<?php echo $v['dirname'];?>"><img src="admin/image/new.png" width="16" height="16" title="新建" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&dir=<?php echo $v['dirname'];?>"><img src="admin/image/edit.png" width="16" height="16" title="管理" alt=""/></a>
</td>
</tr>
<?php }?>

<?php foreach($templates as $k=>$v) {?>
<tr align="center">
<td align="left">&nbsp;<a href="<?php echo $template_path.$v['filename'];?>" title="查看" target="_blank"><img src="admin/image/htm.gif" width="16" height="16" alt="" align="absmiddle"/></a> <a href="?file=<?php echo $file;?>&action=edit&fileid=<?php echo $v['fileid'];?>&dir=<?php echo $dir;?>" title="修改"><?php echo $v['filename'];?></a></td>
<td><input type="text" style="width:130px;" value="<?php echo $v['name'];?>" onblur="template_name('<?php echo $v['fileid'];?>', this.value);"/></td>
<td>&nbsp;<a href="?file=<?php echo $file;?>&action=add&type=<?php echo $v['type'];?>&dir=<?php echo $dir;?>" title="新建"><?php echo $v['type'];?></a></td>
<td data-hide="1200"><?php echo $v['filesize'];?> Kb</td>
<td><?php echo $v['mtime'];?></td>
<td data-hide="1200"><?php echo $v['mod'];?></td>
<td>
<a href="?file=<?php echo $file;?>&action=add&type=<?php echo $v['type'];?>&dir=<?php echo $dir;?>"><img src="admin/image/new.png" width="16" height="16" title="新建" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=edit&fileid=<?php echo $v['fileid'];?>&dir=<?php echo $dir;?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=download&fileid=<?php echo $v['fileid'];?>&dir=<?php echo $dir;?>"><img src="admin/image/save.png" width="16" height="16" title="下载" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=delete&fileid=<?php echo $v['fileid'];?>&dir=<?php echo $dir;?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<?php if($baks) { ?>
<table cellspacing="0" class="tb ls">
<tr>
<th>备份名</th>
<th width="120">所属模板</th>
<th width="100">备份编号</th>
<th width="80">文件大小</th>
<th width="130">备份时间</th>
<th width="50">属性</th>
<th width="110">操作</th>
</tr>
<?php foreach($baks as $k=>$v) {?>
<tr align="center">
<td align="left">&nbsp;<img src="admin/image/unknow.gif" width="16" height="16" alt="" align="absmiddle"/> <a href="<?php echo $template_path.$v['filename'];?>" title="查看" target="_blank"><?php echo $v['filename'];?></a></td>
<td>&nbsp;<?php echo $v['type'];?>.htm</td>
<td>&nbsp;<?php echo $v['number'];?></td>
<td><?php echo $v['filesize'];?> Kb</td>
<td><?php echo $v['mtime'];?></td>
<td><?php echo $v['mod'];?></td>
<td>
<a href="javascript:Dconfirm('确定要恢复<?php echo $v['fileid'];?>备份吗？此操作将不可撤销<br/>文件<?php echo $v['type'];?>.htm的内容将被<?php echo $v['filename'];?>覆盖', '?file=<?php echo $file;?>&action=import&fileid=<?php echo $v['type'];?>&bakid=<?php echo $v['number'];?>&dir=<?php echo $dir;?>');"><img src="admin/image/import.png" width="16" height="16" title="恢复" alt=""/></a>&nbsp;
<a href="<?php echo $template_path.$v['filename'];?>" target="_blank"><img src="admin/image/view.png" width="16" height="16" title="查看" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=download&fileid=<?php echo $v['type'];?>&bakid=<?php echo $v['number'];?>&dir=<?php echo $dir;?>"><img src="admin/image/save.png" width="16" height="16" title="下载" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=delete&fileid=<?php echo $v['type'];?>&bakid=<?php echo $v['number'];?>&dir=<?php echo $dir;?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<?php }?>
<div class="btns">
<?php if($dir) { ?>
<input type="button" value="返回上级" class="btn-b" onclick="Go('?file=<?php echo $file;?>');"/>&nbsp;
<?php } else { ?>
<span class="f_r"><input type="button" value="重建缓存" class="btn-g" onclick="Go('?file=<?php echo $file;?>&action=cache');"/>&nbsp;</span>
<?php
$select = '';
$dirs = list_dir('template');
foreach($dirs as $v) {
	$selected = ($tpl && $v['dir'] == $tpl) ? 'selected' : '';
	$select .= "<option value='".$v['dir']."' ".$selected.">".$v['name']."(".$v['dir'].")</option>";
}
$select = '<select onchange="if(this.value) Go(\'?file='.$file.'&action=change&to=\'+this.value);"><option value="">切换模板</option>'.$select.'</select>';
echo $select;
?>
<?php } ?>
</div>
<script type="text/javascript">
function template_name(fileid, name) {
	$.get('?file=<?php echo $file;?>&dir=<?php echo $dir;?>&action=template_name&fileid='+fileid+'&name='+name, function(data) {
		if(data) showmsg('模板名修改成功');
	});
}
Menuon(1);
</script>
<?php include tpl('footer');?>