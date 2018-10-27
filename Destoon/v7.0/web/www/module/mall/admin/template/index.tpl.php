<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td>
&nbsp;<?php echo $fields_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo $level_select;?>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</td>
</tr>
<tr>
<td>
&nbsp;<select name="datetype">
<option value="edittime" <?php if($datetype == 'edittime') echo 'selected';?>>更新日期</option>
<option value="addtime" <?php if($datetype == 'addtime') echo 'selected';?>>发布日期</option>
</select>&nbsp;
<?php echo dcalendar('fromdate', $fromdate, '');?> 至 <?php echo dcalendar('todate', $todate, '');?>&nbsp;
<?php echo category_select('catid', '所属分类', $catid, $moduleid);?>&nbsp;
<?php echo ajax_area_select('areaid', '所在地区', $areaid);?>&nbsp;
ID：<input type="text" size="4" name="itemid" value="<?php echo $itemid;?>"/>&nbsp;
<input type="checkbox" name="elite" value="1"<?php echo $elite ? ' checked' : '';?>/>橱窗&nbsp;
<input type="checkbox" name="cod" value="1"<?php echo $cod ? ' checked' : '';?>/>货到付款&nbsp;
<input type="checkbox" name="mp" value="1"<?php echo $mp ? ' checked' : '';?>/>阶梯价格&nbsp;
<input type="checkbox" name="rl" value="1"<?php echo $rl ? ' checked' : '';?>/>关联商品&nbsp;
</td>
</tr>
<tr>
<td>
&nbsp;单价：<input type="text" size="3" name="minprice" value="<?php echo $minprice;?>"/> ~ <input type="text" size="3" name="maxprice" value="<?php echo $maxprice;?>"/>&nbsp;
订单：<input type="text" size="3" name="minorders" value="<?php echo $minorders;?>"/> ~ <input type="text" size="3" name="maxorders" value="<?php echo $maxorders;?>"/>&nbsp;
销量：<input type="text" size="3" name="minsales" value="<?php echo $minsales;?>"/> ~ <input type="text" size="3" name="maxsales" value="<?php echo $maxsales;?>"/>&nbsp;
库存：<input type="text" size="3" name="minamount" value="<?php echo $minamount;?>"/> ~ <input type="text" size="3" name="maxamount" value="<?php echo $maxamount;?>"/>&nbsp;
评论：<input type="text" size="3" name="mincomments" value="<?php echo $mincomments;?>"/> ~ <input type="text" size="3" name="maxcomments" value="<?php echo $maxcomments;?>"/>&nbsp;
<?php echo VIP;?>：<input type="text" size="3" name="minvip" value="<?php echo $minvip;?>"/> ~ <input type="text" size="3" name="maxvip" value="<?php echo $maxvip;?>"/>&nbsp;
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>分类</th>
<th width="14"> </th>
<th width="70">图片</th>
<th>商品</th>
<th>会员</th>
<th>价格</th>
<th>订单</th>
<th>销量</th>
<th>库存</th>
<th>评论</th>
<th>人气</th>
<th width="70">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><a href="<?php echo $v['caturl'];?>" target="_blank"><?php echo $v['catname'];?></a></td>
<td><?php if($v['level']) {?><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&level=<?php echo $v['level'];?>"><img src="admin/image/level_<?php echo $v['level'];?>.gif" title="<?php echo $v['level'];?>级" alt=""/></a><?php } ?></td>
<td><a href="javascript:_preview('<?php echo $v['thumb'];?>');"><img src="<?php echo $v['thumb'] ? $v['thumb'] : DT_SKIN.'image/nopic60.gif';?>" width="60" style="padding:5px;"/></a></td>
<td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank" class="t f_b"><?php echo $v['title'];?></a><?php if($v['vip']) {?> <img src="<?php echo DT_SKIN;?>image/vip_<?php echo $v['vip'];?>.gif" title="<?php echo VIP;?>:<?php echo $v['vip'];?>" align="absmiddle"/><?php } ?><br/>
<span class="f_gray">
&nbsp;更新:<span class="px12"><?php echo timetodate($v['edittime'], 6);?></span><br/>
&nbsp;添加:<span class="px12"><?php echo timetodate($v['addtime'], 6);?></span>
</span>
</td>
<td>
<?php if($v['username']) { ?>
<a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a>
<?php } else { ?>
	<a href="javascript:_ip('<?php echo $v['ip'];?>');" title="游客"><?php echo $v['ip'];?></a>
<?php } ?>
</td>
<td class="f_price"><?php echo $v['price'];?></td>
<td class="px12"><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=order&id=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 订单列表');"><?php echo $v['orders'];?></a></td>
<td class="px12"><?php echo $v['sales'];?></td>
<td class="px12"><?php echo $v['amount'];?></td>
<td class="px12"><?php echo $v['comments'];?></td>
<td class="px12"><?php echo $v['hits'];?></td>
<td>
<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=relate&itemid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 关联商品');"><img src="admin/image/child.png" width="16" height="16" title="关联商品" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php } ?>
</table>
<?php include tpl('notice_chip');?>
<div class="btns">

<?php if($action == 'check') { ?>

<input type="submit" value="通过审核" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=check';"/>&nbsp;
<input type="submit" value="拒 绝" class="btn-r" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=reject';"/>&nbsp;
<input type="submit" value="移动分类" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=move';"/>&nbsp;
<input type="submit" value="回收站" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&recycle=1';"/>&nbsp;
<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中商品吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;

<?php } else if($action == 'expire') { ?>

<input type="submit" value=" 上 架 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=onsale';"/>&nbsp;
<input type="submit" value="回收站" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&recycle=1';"/>&nbsp;
<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中商品吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;

<?php } else if($action == 'reject') { ?>

<input type="submit" value="回收站" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&recycle=1';"/>&nbsp;
<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中商品吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;

<?php } else if($action == 'recycle') { ?>

<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中商品吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
<input type="submit" value="还 原" class="btn" onclick="if(confirm('确定要还原选中商品吗？状态将被设置为已通过')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=restore'}else{return false;}"/>&nbsp;
<input type="submit" value="清 空" class="btn-r" onclick="if(confirm('确定要清空回收站吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=clear';}else{return false;}"/>

<?php } else { ?>

<input type="submit" value="刷新信息" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=refresh';" title="刷新时间为最新"/>&nbsp;
<input type="submit" value="更新信息" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=update';"/>&nbsp;
<?php if($MOD['show_html']) { ?><input type="submit" value=" 生成网页 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=tohtml';"/>&nbsp; <?php } ?>
<input type="submit" value="回收站" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&recycle=1';"/>&nbsp;
<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中商品吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
<input type="submit" value="移动分类" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=move';"/>&nbsp;
<input type="submit" value="批量下架" class="btn-r" onclick="if(confirm('确定要批量下架选中商品吗？')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=unsale'}else{return false;}"/>&nbsp;
<?php echo level_select('level', '设置级别为</option><option value="0">取消', 0, 'onchange="this.form.action=\'?moduleid='.$moduleid.'&file='.$file.'&action=level\';this.form.submit();"');?>&nbsp;
<?php } ?>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<br/>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>