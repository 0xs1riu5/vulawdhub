<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="item" value="<?php echo $item;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">删除</th>
<th width="120">排序</th>
<th>名称</th>
<th>上级分类</th>
</tr>
<?php if(is_array($lists['0'])) { foreach($lists['0'] as $k0 => $v0) { ?>
<tr align="center">
<td><input name="post[<?php echo $v0['typeid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="post[<?php echo $v0['typeid'];?>][listorder]" type="text" size="5" value="<?php echo $v0['listorder'];?>" maxlength="3"/></td>
<td align="left"><input name="post[<?php echo $v0['typeid'];?>][typename]" type="text" size="20" value="<?php echo $v0['typename'];?>" maxlength="50" style="width:140px;color:<?php echo $v0['style'];?>"/> <?php echo $v0['style_select'];?></td>
<td><?php echo $v0['parent_select'];?></td>
</tr>
<?php if(isset($lists['1'][$v0['typeid']])) { ?>
<?php if(is_array($lists['1'][$v0['typeid']])) { foreach($lists['1'][$v0['typeid']] as $k1 => $v1) { ?>
<tr align="center">
<td><input name="post[<?php echo $v1['typeid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="post[<?php echo $v1['typeid'];?>][listorder]" type="text" size="5" value="<?php echo $v1['listorder'];?>" maxlength="3"/></td>
<td align="left"><img src="admin/image/tree.gif" align="absmiddle"/><input name="post[<?php echo $v1['typeid'];?>][typename]" type="text" size="20" value="<?php echo $v1['typename'];?>" maxlength="50" style="width:120px;color:<?php echo $v1['style'];?>"/> <?php echo $v1['style_select'];?></td>
<td><?php echo $v1['parent_select'];?></td>
</tr>
<?php } } ?>
<?php } ?>
<?php } } ?>
<tr align="center">
<td class="f_green">新增</td>
<td><input name="post[0][listorder]" type="text" size="5" value="" maxlength="3"/></td>
<td align="left"><input name="post[0][typename]" type="text" size="20" value="" maxlength="20" style="width:140px;"/> <?php echo $new_style;?></td>
<td><?php echo $parent_select;?></td>
</tr>
<tr>
<td align="center"><input type="checkbox" onclick="checkall(this.form);" title="全选/反选"/></td>
<td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="更 新" onclick="if($(':checkbox:checked').length && !confirm('提示：您选择删除'+$(':checkbox:checked').length+'个分类，确定要删除吗？此操作将不可撤销')) return false;" class="btn-g"/>
</td>
</tr>
</table>
</form>
<?php include tpl('footer');?>