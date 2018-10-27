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
<input type="text" size="15" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php if($M) { ?>
<select name="market">
<?php
foreach($M as $k=>$v) {
	echo '<option value="'.$k.'"'.($k == $market ? ' selected' : '').'>'.$v.'</option>';
}
?>
</select>&nbsp;
<?php } ?>
<?php echo ajax_area_select('areaid', '所在地区', $areaid);?>&nbsp;
<?php echo $order_select;?>&nbsp;
价格：<input type="text" size="3" name="minprice" value="<?php echo $minprice;?>"/> ~ <input type="text" size="3" name="maxprice" value="<?php echo $maxprice;?>"/>&nbsp;
产品ID：<input type="text" size="4" name="pid" value="<?php echo $pid;?>"/>&nbsp;
ID：<input type="text" size="4" name="itemid" value="<?php echo $itemid;?>"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&pid=<?php echo $pid;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<?php if(!$pid) { ?><th>产品</th><?php } ?>
<th>价格</th>
<th>单位</th>
<th>备注</th>
<th>公司</th>
<th>电话</th>
<th>会员</th>
<th width="130"><?php echo $timetype == 'add' ? '报价' : '更新';?>时间</th>
<th width="50">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<?php if(!$pid) { ?><td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['title'];?></a></td><?php } ?>
<td><?php echo $v['price'];?></td>
<td><?php echo $v['unit'];?></td>
<td><?php echo $v['note'];?></td>
<td><?php echo $v['company'];?></td>
<td><?php echo $v['telephone'];?></td>
<td>
<?php if($v['username']) { ?>
<a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a>
<?php } else { ?>
	<a href="javascript:_ip('<?php echo $v['ip'];?>');" title="游客"><?php echo $v['ip'];?></a>
<?php } ?>
</td>
<?php if($timetype == 'add') {?>
<td class="px12" title="更新时间<?php echo $v['editdate'];?>"><?php echo $v['adddate'];?></td>
<?php } else { ?>
<td class="px12" title="报价时间<?php echo $v['adddate'];?>"><?php echo $v['editdate'];?></td>
<?php } ?>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>&pid=<?php echo $pid;?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>&pid=<?php echo $pid;?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<?php if($action == 'check') { ?>
<input type="submit" value="通过审核" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&pid=<?php echo $pid;?>&action=check';"/>&nbsp;
<?php } ?>
<input type="submit" value="删 除" class="btn-r" onclick="if(confirm('确定要删除选中<?php echo $MOD['name'];?>吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&pid=<?php echo $pid;?>&action=delete'}else{return false;}"/>&nbsp;
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>