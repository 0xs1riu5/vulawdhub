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
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
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
<th>手机号</th>
<th>内容</th>
<th>字数</th>
<th>分条</th>
<th width="80">发送时间</th>
<th>发送人</th>
<th>发送结果</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><?php echo $v['itemid'];?></td>
<td><a href="javascript:_user('<?php echo $v['mobile'];?>', 'mobile');"><?php echo $v['mobile'];?></a></td>
<td align="left" style="width:150px;padding:8px;line-height:20px;"><?php echo $v['message'];?></td>
<td class="px12"><?php echo $v['word'];?></td>
<td class="px12"><?php echo $v['num'];?></td>
<td class="px12"><?php echo $v['sendtime'];?></td>
<td><a href="javascript:_user('<?php echo $v['editor'];?>');"><?php echo $v['editor'];?></a></td>
<td style="width:120px;padding:8px;line-height:20px;"><?php echo $v['code'];?></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 批量删除 " class="btn-r" onclick="if(confirm('确定要删除选中记录吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete_record'}else{return false;}"/>&nbsp;
<input type="submit" value=" 清理记录 " class="btn-r" onclick="if(confirm('为了系统安全,系统仅删除90天之前的记录\n此操作不可撤销，请谨慎操作')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=clear'}else{return false;}"/>&nbsp;
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(1);</script>
<br/>
<?php include tpl('footer');?>