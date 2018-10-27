<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="15" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词"/>&nbsp;
<select name="type">
<option value="0">类型</option>
<option value="1" <?php if($type == 1) echo 'selected';?>>收入</option>
<option value="2" <?php if($type == 2) echo 'selected';?>>支出</option>
</select>&nbsp;
<select name="bank">
<option value="">支付方式</option>
<?php
foreach($BANKS as $k=>$v) {
	echo '<option value="'.$v.'" '.($bank == $v ? 'selected' : '').'>'.$v.'</option>';
}
?>
</select>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</td>
</tr>
<tr>
<td>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<select name="mtype">
<option value="amount" <?php if($mtype == 'amount') echo 'selected';?>>收支</option>
<option value="balance" <?php if($mtype == 'balance') echo 'selected';?>>余额</option>
</select>&nbsp;
<input type="text" name="minamount" value="<?php echo $minamount;?>" size="5"/> 至 
<input type="text" name="maxamount" value="<?php echo $maxamount;?>" size="5"/>&nbsp;
会员名：<input type="text" name="username" value="<?php echo $username;?>" size="10"/>&nbsp;
流水号：<input type="text" name="itemid" value="<?php echo $itemid;?>" size="10"/>&nbsp;
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>流水号</th>
<th>收入</th>
<th>支出</th>
<th>余额</th>
<th>会员名称</th>
<th>支付平台</th>
<th width="130">发生时间</th>
<th>操作人</th>
<th width="130">事由</th>
<th width="130">备注</th>
</tr>
<?php foreach($records as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><?php echo $v['itemid'];?></td>
<td class="f_blue"><?php if($v['amount'] > 0) echo '+'.$v['amount'];?></td>
<td class="f_red"><?php if($v['amount'] < 0) echo $v['amount'];?></td>
<td><?php echo $v['balance'] ? $v['balance'] : '';?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td><?php echo $v['bank'];?></td>
<td class="px12"><?php echo $v['addtime'];?></td>
<td><?php echo $v['editor'];?></td>
<td title="<?php echo $v['reason'];?>"><input type="text" size="15" value="<?php echo $v['reason'];?>"/></td>
<td title="<?php echo $v['note'];?>"><input type="text" size="15" value="<?php echo $v['note'];?>"/></td>
</tr>
<?php }?>
<tr align="center">
<td></td>
<td><strong>小计</strong></td>
<td class="f_blue">+<?php echo $income;?></td>
<td class="f_red"><?php echo $expense;?></td>
<td colspan="7">&nbsp;</td>
</tr>
</table>
<div class="btns">
<input type="submit" value=" 批量删除 " class="btn-r" onclick="if(confirm('确定要删除选中记录吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(1);</script>
<br/>
<?php include tpl('footer');?>