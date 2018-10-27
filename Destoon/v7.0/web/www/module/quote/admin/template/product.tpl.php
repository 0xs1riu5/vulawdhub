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
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo category_select('catid', '不限分类', $catid, $moduleid);?>&nbsp;
<?php echo $level_select;?>&nbsp;
<?php echo $order_select;?>&nbsp;
ID：<input type="text" size="4" name="itemid" value="<?php echo $itemid;?>"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>分类</th>
<th width="14"> </th>
<th>产品</th>
<th>属性参数</th>
<th width="130">添加时间</th>
<th>最新价格</th>
<th>报价数</th>
<th width="130">报价时间</th>
<th>浏览</th>
<th width="100">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>

<td><a href="<?php echo $v['caturl'];?>" target="_blank"><?php echo $v['catname'];?></a></td>

<td><?php if($v['level']) {?><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&level=<?php echo $v['level'];?>"><img src="admin/image/level_<?php echo $v['level'];?>.gif" title="<?php echo $v['level'];?>级" alt=""/></a><?php } ?></td>

<td align="left">
&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['title'];?></a></td>
<td align="left" class="f_gray">
<?php if(($v['n1'] && $v['v1']) || ($v['n2'] && $v['v2']) || ($v['n3'] && $v['v3'])) { ?>
<?php if(($v['n1'] && $v['v1'])) echo '&nbsp;'.$v['n1'].':'.$v['v1'];?>
<?php if(($v['n2'] && $v['v2'])) echo '&nbsp;'.$v['n2'].':'.$v['v2'];?>
<?php if(($v['n3'] && $v['v3'])) echo '&nbsp;'.$v['n3'].':'.$v['v3'];?>
<?php } ?>
</td>

<td class="px12"><?php echo $v['adddate'];?></td>
<td><?php echo $v['price'];?>/<?php echo $v['unit'];?></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=price&pid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 报价记录');"><?php echo $v['item'];?></a></td>
<td class="px12"><?php echo $v['editdate'];?></td>
<td><?php echo $v['hits'];?></td>
<td>
<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=price&action=add&pid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 报价记录');"><img src="admin/image/add.png" width="16" height="16" title="添加报价" alt=""/></a>&nbsp;
<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=price&pid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 报价记录');"><img src="admin/image/poll.png" width="16" height="16" title="报价记录" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="删 除" class="btn-r" onclick="if(confirm('确定要删除选中产品吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
<?php echo level_select('level', '设置级别为</option><option value="0">取消', 0, 'onchange="this.form.action=\'?moduleid='.$moduleid.'&file='.$file.'&action=level\';this.form.submit();"');?>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>