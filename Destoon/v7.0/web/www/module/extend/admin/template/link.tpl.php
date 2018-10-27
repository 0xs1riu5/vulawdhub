<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo $type_select;?>&nbsp;
<span data-hide="1200"><?php echo $level_select;?>&nbsp;</span>
<select name="type">
<option value="0"<?php if($type == 0) echo ' selected';?>>类型</option>
<option value="1"<?php if($type == 1) echo ' selected';?>>文字</option>
<option value="2"<?php if($type == 2) echo ' selected';?>>LOGO</option>
</select>&nbsp;
<?php echo $DT['city'] ? ajax_area_select('areaid', '地区(分站)', $areaid).'&nbsp;' : '';?>
<span data-hide="1200"><?php echo $order_select;?>&nbsp;</span>
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<?php if($action != 'check') {?><th width="50">排序</th><?php } ?>
<th>分类</th>
<th width="14"> </th>
<th>网站名称</th>
<th>网站LOGO</th>
<th>链接类型</th>
<th width="50">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center" title="编辑:<?php echo $v['editor'];?>&#10;添加时间:<?php echo $v['adddate'];?>&#10;更新时间:<?php echo $v['editdate'];?>&#10;网站介绍:<?php echo $v['introduce'];?>">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<?php if($action != 'check') {?><td><input type="text" size="2" name="listorder[<?php echo $v['itemid'];?>]" value="<?php echo $v['listorder'];?>"/></td><?php } ?>
<td><a href="<?php echo $v['typeurl'];?>" target="_blank"><?php echo $v['typename'];?></td>
<td><?php if($v['level']) {?><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&level=<?php echo $v['level'];?>"><img src="admin/image/level_<?php echo $v['level'];?>.gif" title="<?php echo $v['level'];?>级" alt=""/></a><?php } ?></td>
<td><a href="<?php echo DT_PATH;?>api/redirect.php?url=<?php echo urlencode($v['linkurl']);?>" target="_blank"><?php echo $v['title'];?></td>
<td><?php if($v['thumb']) {?><a href="<?php echo DT_PATH;?>api/redirect.php?url=<?php echo urlencode($v['linkurl']);?>" target="_blank"><img src="<?php echo $v['thumb'];?>" width="88" /><?php } ?></a></td>
<td><?php echo $v['thumb'] ? 'LOGO' : '文字';?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<?php if($action == 'check') {?>
<input type="submit" value="通过审核" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=check';"/>&nbsp;
<?php } else { ?>
<input type="submit" value="更新排序" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=order';"/>&nbsp;
<?php } ?>
<input type="submit" value="删 除" class="btn-r" onclick="if(confirm('确定要删除选中链接吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
<?php echo level_select('level', '设置级别为</option><option value="0">取消', 0, 'onchange="this.form.action=\'?moduleid='.$moduleid.'&file='.$file.'&action=level\';this.form.submit();"');?>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>