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
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词"/>&nbsp;
<?php echo $order_select;?>&nbsp;
优惠ID: <input type="text" name="pid" value="<?php echo $pid;?>" size="6"/>&nbsp;
订单ID: <input type="text" name="oid" value="<?php echo $oid;?>" size="6"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</td>
</tr>
<tr>
<td>&nbsp;
<select name="timetype">
<option value="addtime" <?php if($timetype == 'addtime') echo 'selected';?>>领取时间</option>
<option value="edittime" <?php if($timetype == 'edittime') echo 'selected';?>>更新时间</option>
<option value="fromtime" <?php if($timetype == 'fromtime') echo 'selected';?>>开始时间</option>
<option value="totime" <?php if($timetype == 'totime') echo 'selected';?>>结束时间</option>
</select>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<select name="mtype">
<option value="price" <?php if($mtype == 'price') echo 'selected';?>>优惠额度</option>
<option value="cost" <?php if($mtype == 'cost') echo 'selected';?>>最低消费</option>
</select>&nbsp;
<input type="text" name="minamount" value="<?php echo $minamount;?>" size="5"/> 至 
<input type="text" name="maxamount" value="<?php echo $maxamount;?>" size="5"/>&nbsp;
会员: <input type="text" name="username" value="<?php echo $username;?>" size="10"/>&nbsp;
卖家: <input type="text" name="seller" value="<?php echo $seller;?>" size="10"/>&nbsp;
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>优惠名称</th>
<th>会员</th>
<th>卖家</th>
<th>额度</th>
<th>最低消费</th>
<th width="130">开始时间</th>
<th width="130">结束时间</th>
<th width="130">领取时间</th>
<th width="80">订单ID</th>
<th width="80">状态</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><?php echo $v['title'];?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td><a href="javascript:_user('<?php echo $v['seller'];?>');"><?php echo $v['seller'];?></a></td>
<td title="备注:<?php echo $v['note'];?>"><?php echo $v['price'];?></td>
<td><?php echo $v['cost'];?></td>
<td class="px12"><?php echo timetodate($v['fromtime'], 5);?></td>
<td class="px12"><?php echo timetodate($v['totime'], 5);?></td>
<td class="px12" title="修改时间:<?php echo timetodate($v['edittime'], 5);?>"><?php echo timetodate($v['addtime'], 5);?></td>
<td class="px12"><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=order&itemid=<?php echo $v['oid'];?>', '订单详情');"><?php echo $v['oid'] ? $v['oid'] : '';?></a></td>
<td>
<?php if($v['oid']) { ?>
<span class="f_green">已使用</span>
<?php } else if($v['fromtime'] > $DT_TIME) { ?>
<span class="f_gray">未开始</span>
<?php } else if($v['totime'] < $DT_TIME) { ?>
<span class="f_red">已过期</span>
<?php } else { ?>
<span class="f_blue">待使用</span>
<?php } ?>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 删 除 " class="btn-r" onclick="if(confirm('确定要删除选中优惠券吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=del'}else{return false;}"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>