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
<td>
&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词"/>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<select name="type">
<option value="0">结果</option>
<option value="1" <?php if($type == 1) echo 'selected';?>>成功</option>
<option value="2" <?php if($type == 2) echo 'selected';?>>失败</option>
</select>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>流水号</th>
<th>收件地址</th>
<th>标题</th>
<th width="130">发送时间</th>
<th>结果</th>
<th>备注</th>
<th width="40">重发</th>
</tr>
<?php foreach($records as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><?php echo $v['itemid'];?></td>
<td align="left"><a href="javascript:_user('<?php echo $v['email'];?>', 'email');"><?php echo $v['email'];?></a></td>
<td align="left"><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show&itemid=<?php echo $v['itemid'];?>"><?php echo $v['title'];?></a></td>
<td class="px12"><?php echo $v['addtime'];?></td>
<td><?php echo $v['status'] == 3 ? '<span class="f_green">成功</span>' : '<span class="f_red">失败</span>';?></td>
<td title="<?php echo $v['note'];?>"><input type="text" size="15" value="<?php echo $v['note'];?>"/></td>
<td>
<?php if($v['status'] == 3) { ?>
--
<?php } else { ?>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=resend&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/start.png" width="16" height="16" title="重发" alt=""/></a>
<?php } ?>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 批量重发 " class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=resend';"/>&nbsp;
<input type="submit" value=" 批量删除 " class="btn-r" onclick="if(confirm('确定要删除选中记录吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete_record'}else{return false;}"/>&nbsp;
<input type="submit" value=" 清理记录 " class="btn-r" onclick="if(confirm('为了系统安全,系统仅删除30天之前的记录\n此操作不可撤销，请谨慎操作')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=clear'}else{return false;}"/>&nbsp;

</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(1);</script>
<br/>
<?php include tpl('footer');?>