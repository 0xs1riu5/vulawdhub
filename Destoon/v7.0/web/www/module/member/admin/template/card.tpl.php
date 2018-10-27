<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<?php if($print) { ?>
<table cellspacing="0" class="tb ls">
<tr>
<th>卡号</th>
<th>密码</th>
<th>面额</th>
<th>有效期至</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><?php echo $v['number'];?></td>
<td><?php echo $v['password'];?></td>
<td class="f_blue"><?php echo $v['amount'];?></td>
<td><?php echo $v['totime'];?></td>
</tr>
<?php }?>
</table>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Dh('destoon_menu');</script>
<?php exit; } ?>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词"/>&nbsp;
<select name="status">
<option value="0">状态</option>
<option value="1" <?php if($status == 1) echo 'selected';?>>已使用</option>
<option value="2" <?php if($status == 2) echo 'selected';?>>已过期</option>
</select>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</td>
</tr>
<tr>
<td>&nbsp;
<select name="timetype">
<option value="updatetime" <?php if($timetype == 'updatetime') echo 'selected';?>>使用时间</option>
<option value="totime" <?php if($timetype == 'totime') echo 'selected';?>>到期时间</option>
<option value="addtime" <?php if($timetype == 'addtime') echo 'selected';?>>制卡时间</option>
</select>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
面额：
<input type="text" name="minamount" value="<?php echo $minamount;?>" size="5"/> 至 
<input type="text" name="maxamount" value="<?php echo $maxamount;?>" size="5"/>&nbsp;
会员名：<input type="text" name="username" value="<?php echo $username;?>" size="10"/>&nbsp;
卡号：<input type="text" name="number" value="<?php echo $number;?>" size="10"/>&nbsp;
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>卡号</th>
<th>密码</th>
<th>面额</th>
<th>有效期至</th>
<th>充值会员</th>
<th>充值时间</th>
<th>充值IP</th>
<th>制卡时间</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><?php echo $v['number'];?></td>
<td><?php echo $v['password'];?></td>
<td class="f_blue"><?php echo $v['amount'];?></td>
<td><?php echo $v['totime'];?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td><?php echo $v['updatetime'];?></td>
<td><a href="javascript:_ip('<?php echo $v['ip'];?>');" title="显示IP所在地"><?php echo $v['ip'];?></a></td>
<td title="制卡人:<?php echo $v['editor'];?>"><?php echo $v['addtime'];?></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 批量删除 " class="btn-r" onclick="if(confirm('确定要删除选中充值卡吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
<input type="button" value=" 打印卡号 " class="btn" onclick="window.open('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&print=1');"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(1);</script>
<br/>
<?php include tpl('footer');?>