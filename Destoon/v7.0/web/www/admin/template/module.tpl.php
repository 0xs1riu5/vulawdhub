<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?" method="post">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="order"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">排序</th>
<th width="40">ID</th>
<th>名称</th>
<th width="100">目录</th>
<th width="50">模型</th>
<th width="50">导航</th>
<th width="50">状态</th>
<th width="50" data-hide="1200">分类</th>
<th width="50">字段</th>
<th width="50" data-hide="1200">模板</th>
<th width="50">统计</th>
<th width="50" data-hide="1200">更新</th>
<th width="50" data-hide="1200">设置</th>
<th width="60">管理</th>
</tr>
<?php foreach($modules as $k=>$v) {?>
<tr align="center">
<td><input type="text" size="2" name="listorder[<?php echo $v['moduleid'];?>]" value="<?php echo $v['listorder'];?>"/></td>
<td><?php echo $v['moduleid'];?></td>
<td><a href="<?php echo $v['linkurl'];?>" target="_blank" class="t"><?php echo set_style($v['name'], $v['style']);?></a></td>
<td><a href="?file=<?php echo $file;?>&action=remkdir&modid=<?php echo $v['moduleid'];?>" title="重建目录"><?php echo $v['moduledir'] ? $v['moduledir'] : '--';?></a></td>
<td><div class="h"><?php echo $v['modulename'];?></div></td>
<td><?php echo $v['ismenu'] ? '<span class="f_green">是</span>' : '<span class="f_red">否</span>'; ?></td>
<td>
<?php if($v['disabled']) {?>
<a href="?file=<?php echo $file;?>&action=disable&value=0&modid=<?php echo $v['moduleid'];?>" title="已禁用,点击启用"><span class="f_red">禁用</span></a>
<?php } else {?>
<a href="javascript:Dconfirm('确定要禁用[<?php echo $v['name'];?>]模块吗?', '?file=<?php echo $file;?>&action=disable&value=1&modid=<?php echo $v['moduleid'];?>');" title="正常运行,点击禁用"><span class="f_green">正常</span></a>
<?php } ?>
</td>
<td data-hide="1200">
<?php if($v['islink'] || $v['moduleid'] < 4) { ?>
--
<?php } else { ?>
<a href="javascript:Dwidget('?file=category&mid=<?php echo $v['moduleid'];?>', '分类管理 - <?php echo $v['name'];?>');">分类</a>
<?php } ?>
</td>
<td>
<?php if($v['islink'] || $v['moduleid'] == 3) { ?>
--
<?php } else { ?>
<a href="javascript:Dwidget('?file=fields&tb=<?php echo get_table($v['moduleid']);?>', '定义字段 - <?php echo $v['name'];?>');">字段</a>
<?php } ?>
</td>
<td data-hide="1200">
<?php if($v['islink']) { ?>
--
<?php } else { ?>
<a href="javascript:Dwidget('?file=template&dir=<?php echo $v['moduledir'];?>', '模板管理 - <?php echo $v['name'];?>');">模板</a>
<?php } ?>
</td>
<td>
<?php if($v['islink'] || $v['moduleid'] == 3) { ?>
--
<?php } else { ?>
<a href="javascript:Dwidget('?file=count&action=stats&mid=<?php echo $v['moduleid'];?>', '数据统计 - <?php echo $v['name'];?>');">统计</a>
<?php } ?>
</td>
<td data-hide="1200">
<?php if($v['islink'] || $v['moduleid'] == 2) { ?>
--
<?php } else { ?>
<a href="javascript:Dwidget('?file=html&moduleid=<?php echo $v['moduleid'];?>', '数据更新 - <?php echo $v['name'];?>');">更新</a>
<?php } ?>
</td>
<td data-hide="1200">
<?php if($v['islink']) { ?>
--
<?php } else { ?><a href="javascript:Dwidget('?moduleid=<?php echo $v['moduleid'];?>&file=setting', '模块设置 - <?php echo $v['name'];?>');">设置</a>
<?php } ?>
</td>
<td><a href="?file=<?php echo $file;?>&action=edit&modid=<?php echo $v['moduleid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=delete&modid=<?php echo $v['moduleid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
<?php if($_modules) { ?>
<?php foreach($_modules as $k=>$v) {?>
<tr align="center">
<td><input type="text" size="2" name="listorder[<?php echo $v['moduleid'];?>]" value="<?php echo $v['listorder'];?>"/></td>
<td><?php echo $v['moduleid'];?></td>
<td><a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo set_style($v['name'], $v['style']);?></a></td>
<td><?php echo $v['moduledir'] ? $v['moduledir'] : '--';?></td>
<td><?php echo $v['modulename'];?></td>
<td>--</td>
<td>
<?php if($v['disabled']) {?>
<a href="?file=<?php echo $file;?>&action=disable&value=0&modid=<?php echo $v['moduleid'];?>" title="已禁用,点击启用"><span class="f_red">禁用</span></a>
<?php } else {?>
<a href="javascript:Dconfirm('确定要禁用[<?php echo $v['name'];?>]模块吗?', '?file=<?php echo $file;?>&action=disable&value=1&modid=<?php echo $v['moduleid'];?>');" title="正常运行,点击禁用"><span class="f_green">正常</span></a>
<?php } ?>
</td>
<td data-hide="1200">--</td>
<td>--</td>
<td data-hide="1200">--</td>
<td>--</td>
<td data-hide="1200">--</td>
<td data-hide="1200">--</td>
<td><a href="?file=<?php echo $file;?>&action=edit&modid=<?php echo $v['moduleid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=delete&modid=<?php echo $v['moduleid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
<?php } ?>
</table>
<div class="btns">
<input type="submit" value=" 更新排序 " class="btn-g"/>&nbsp;
</div>
</form>
<script type="text/javascript">Menuon(1);</script>
<?php if(isset($update)) { ?>
<script type="text/javascript">window.parent.frames[0].location.reload();</script>
<?php } ?>
<?php include tpl('footer');?>