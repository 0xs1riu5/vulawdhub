<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<input type="hidden" name="forward" value="<?php echo $DT_URL;?>"/>
<input type="hidden" name="tb" value="<?php echo $tb;?>"/>
<input type="hidden" name="action" value="update"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">删除</th>
<th>排序</th>
<th>字段</th>
<th>字段名称</th>
<th>显示</th>
<th>前台</th>
<th>字段属性</th>
<th>表单类型</th>
<th width="40">调用</th>
<th width="40">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input name="post[<?php echo $v['itemid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][listorder]" type="text" size="2" value="<?php echo $v['listorder'];?>"/></td>
<td><?php echo $v['name'];?></td>
<td><input name="post[<?php echo $v['itemid'];?>][title]" type="text" size="10" value="<?php echo $v['title'];?>"/></td>
<td><select name="post[<?php echo $v['itemid'];?>][display]"><option value="1"<?php echo $v['display'] ? ' selected' : '';?>>是</option><option value="0"<?php echo $v['display'] ? '' : ' selected';?>>否</option></select></td>
<td><select name="post[<?php echo $v['itemid'];?>][front]"><option value="1"<?php echo $v['front'] ? ' selected' : '';?>>是</option><option value="0"<?php echo $v['front'] ? '' : ' selected';?>>否</option></select></td>
<td><?php echo $v['type'];?><?php echo $v['length'] ? '('.$v['length'].')' : '';?></td>
<td><?php echo $v['html'];?></td>
<td><a href="javascript:Dcall('<?php echo $v['itemid'];?>', '<?php echo $v['name'];?>');" class="t">查看</a></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&tb=<?php echo $tb;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a></td>
</tr>
<?php }?>
<tr>
<td align="center"><input type="checkbox" onclick="checkall(this.form);" title="全选/反选"/></td>
<td colspan="12"><input type="submit" name="submit" value="更 新" onclick="if($(':checkbox:checked').length && !confirm('提示：您选择删除'+$(':checkbox:checked').length+'个字段，确定要删除吗？此操作将不可撤销')) return false;" class="btn-g"/>
</td>
</tr>
</table>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">
function Dcall(id, name) {
	var tips = '';
	tips += '表单名称：post_fields['+name+']<br/>';
	tips += '表单调用：{fields_show('+id+')}<br/>';
	tips += '标签调用：{$t['+name+']}<br/>';
	tips += '内容调用：{$'+name+'}<br/>';
	Dalert(tips);
}
Menuon(1);
</script>
<?php include tpl('footer');?>