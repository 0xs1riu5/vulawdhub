<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $type_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
宽度：<input type="text" size="3" name="width" value="<?php echo $width;?>"/>&nbsp;
高度：<input type="text" size="3" name="height" value="<?php echo $height;?>"/>&nbsp;
<span data-hide="1200">
<select name="open">
<option value="-1"<?php if($open == -1) echo ' selected';?>>前台</option>
<option value="1"<?php if($open == 1) echo ' selected';?>>显示</option>
<option value="0"<?php if($open == 0) echo ' selected';?>>隐藏</option>
</select>&nbsp;
</span>
<input type="checkbox" name="thumb" value="1"<?php echo $thumb ? ' checked' : '';?>/> 示意图&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="40">排序</th>
<th>ID</th>
<th>广告类型</th>
<th width="15"></th>
<th>广告位名称</th>
<th data-hide="1200">规格(px)</th>
<th data-hide="1200" title="(<?php echo $DT['money_unit'];?>/月)">价格</th>
<th>广告</th>
<th>HTML调用代码</th>
<th>JS调用代码</th>
<th width="130">操作</th>
</tr>
<?php foreach($places as $k=>$v) {?>
<tr align="center" name="编辑:<?php echo $v['editor'];?>&#10;更新时间:<?php echo $v['editdate'];?>">
<td><input type="checkbox" name="pids[]" value="<?php echo $v['pid'];?>"/></td>
<td><input type="text" size="2" name="listorder[<?php echo $v['pid'];?>]" value="<?php echo $v['listorder'];?>"/></td>
<td><?php echo $v['pid'];?></td>
<td><a href="<?php echo $v['typeurl'];?>" target="_blank"><?php echo $v['typename'];?></td>
<td><?php if($v['thumb']) {?> <a href="javascript:_preview('<?php echo $v['thumb'];?>');"><img src="admin/image/img.gif" width="10" height="10" title="广告位示意图,点击查看" alt=""/></a><?php } ?></td>
<td align="left" title="添加时间:<?php echo $v['adddate'];?>&#10;编辑:<?php echo $v['editor'];?>&#10;上次修改:<?php echo $v['editdate'];?>"><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list&pid=<?php echo $v['pid'];?>', '[<?php echo $v['alt'];?>] 广告管理');"><?php echo $v['name'];?></td>
<td data-hide="1200"><?php echo $v['width'];?> x <?php echo $v['height'];?></td>
<td data-hide="1200"><?php echo $v['price'] ? $v['price'].$unit : '面议';?></td>

<td><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list&pid=<?php echo $v['pid'];?>', '[<?php echo $v['alt'];?>] 广告管理');"><?php echo $v['ads'];?></a></td>

<td><input type="text" size="12" <?php if($v['typeid'] == 6 || $v['typeid'] == 7) { ?>value="{ad($moduleid,$catid,$kw,<?php echo $v['typeid'];?>)}"<?php } else { ?>value="{ad(<?php echo $v['pid'];?>)}"<?php } ?> onmouseover="this.select();"/></td>

<td><input type="text" size="12" <?php if($v['typeid'] > 1 && $v['typeid'] < 5) { ?>value="<script type=&quot;text/javascript&quot; src=&quot;{DT_PATH}file/script/A<?php echo $v['pid'];?>.js&quot;></script>"<?php } else { ?>value="不支持" disabled<?php } ?> onmouseover="this.select();"/></td>

<td>
<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=add&pid=<?php echo $v['pid'];?>', '[<?php echo $v['alt'];?>] 广告管理');"><img src="admin/image/add.png" width="16" height="16" title="向此广告位添加广告" alt=""/></a>&nbsp;
<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list&pid=<?php echo $v['pid'];?>', '[<?php echo $v['alt'];?>] 广告管理');"><img src="admin/image/child.png" width="16" height="16" title="此广告位广告列表" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=view&pid=<?php echo $v['pid'];?>" target="_blank"/><img src="admin/image/view.png" width="16" height="16" title="预览此广告位" alt=""></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit_place&pid=<?php echo $v['pid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改此广告位" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete_place&pids=<?php echo $v['pid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除此广告位" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="更新排序" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=order_place';"/>&nbsp;
<input type="submit" value="删 除" class="btn-r" onclick="if(confirm('确定要删除选中广告位吗？\n\n广告位下的所有广告也将被删除\n\n此操作不可撤销\n\n强烈建议不要删除系统自带的广告位')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete_place'}else{return false;}"/>&nbsp;&nbsp;&nbsp;
提示：系统会定期自动更新广告，如果需要立即看到效果，请点更新广告
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<?php if(isset($id) && isset($tm) && $id && $tm > $DT_TIME) { ?>
<script type="text/javascript">Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=add&pid=<?php echo $id;?>', '请添加广告');</script>
<?php } ?>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>