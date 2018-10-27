<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="40" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词"/>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</td>
</tr>
<tr>
<td>&nbsp;
<select name="timetype">
<option value="addtime" <?php if($timetype == 'addtime') echo 'selected';?>>添加时间</option>
<option value="edittime" <?php if($timetype == 'edittime') echo 'selected';?>>更新时间</option>
<option value="fromtime" <?php if($timetype == 'fromtime') echo 'selected';?>>开始时间</option>
<option value="totime" <?php if($timetype == 'totime') echo 'selected';?>>结束时间</option>
</select>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<select name="mtype">
<option value="price" <?php if($mtype == 'price') echo 'selected';?>>优惠额度</option>
<option value="cost" <?php if($mtype == 'cost') echo 'selected';?>>最低消费</option>
<option value="amount" <?php if($mtype == 'amount') echo 'selected';?>>数量限制</option>
<option value="number" <?php if($mtype == 'number') echo 'selected';?>>领券人数</option>
</select>&nbsp;
<input type="text" name="minamount" value="<?php echo $minamount;?>" size="5"/> 至 
<input type="text" name="maxamount" value="<?php echo $maxamount;?>" size="5"/>&nbsp;
<select name="open">
<option value="-1" <?php if($open == -1) echo 'selected';?>>会员领取</option>
<option value="1" <?php if($open == 1) echo 'selected';?>>开启</option>
<option value="0" <?php if($open == 0) echo 'selected';?>>关闭</option>
</select>&nbsp;
卖家: <input type="text" name="username" value="<?php echo $username;?>" size="10"/>&nbsp;
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>优惠名称</th>
<th>卖家</th>
<th>额度</th>
<th>最低消费</th>
<th>数量限制</th>
<th>领券人数</th>
<th width="130">开始时间</th>
<th width="130">结束时间</th>
<th width="130">添加时间</th>
<th width="70">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><?php echo $v['title'];?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td title="备注:<?php echo $v['note'];?>"><?php echo $v['price'];?></td>
<td><?php echo $v['cost'];?></td>
<td><?php echo $v['amount'];?></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=coupon&pid=<?php echo $v['itemid'];?>', '领券记录');"><?php echo $v['number'];?></a></td>
<td class="px12"><?php echo timetodate($v['fromtime'], 5);?></td>
<td class="px12"><?php echo timetodate($v['totime'], 5);?></td>
<td class="px12" title="修改时间:<?php echo timetodate($v['edittime'], 5);?>"><?php echo timetodate($v['addtime'], 5);?></td>
<td>
<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=give&itemid=<?php echo $v['itemid'];?>', '赠送优惠券');"><img src="admin/image/add.png" width="16" height="16" title="赠送" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 删 除 " class="btn-r" onclick="if(confirm('确定要删除选中优惠吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>