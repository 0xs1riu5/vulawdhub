<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>IP地址/段</th>
<th>有效期至</th>
<th>状态</th>
<th>操作人</th>
<th>禁止时间</th>
<th width="30">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><?php echo $v['ip'];?></td>
<td><?php echo $v['totime'];?></td>
<td><?php echo $v['status'];?></td>
<td><?php echo $v['editor'];?></td>
<td><?php echo $v['addtime'];?></td>
<td><a href="?file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 批量删除 " class="btn-r" onclick="if(confirm('确定要删除选中记录吗？此操作将不可撤销')){this.form.action='?file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;&nbsp;
<input type="submit" value=" 清空过期 " class="btn-r" onclick="if(confirm('确定要清空过期记录吗？此操作将不可撤销')){this.form.action='?file=<?php echo $file;?>&action=clear'}else{return false;}"/>
</div>
</form>
<?php if($pages) { ?>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<?php } ?>
<div class="tt">IP禁止</div>
<form action="?" method="post">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="add"/>
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
IP地址/段 <input type="text" size="30" name="ip"/>&nbsp;
有效期至 <?php echo dcalendar('todate');?>&nbsp;
<input type="submit" value="添 加" class="btn-g"/>&nbsp;
</td>
</tr>
<tr>
<td>
&nbsp;1、IP禁止仅对网站前台生效，建议不要添加过多，以免影响程序效率<br/>
&nbsp;2、支持禁用IP段，例如填192.168.*.*将禁用所有192.168开头的IP<br/>
&nbsp;3、有效期不填表示永久禁用<br/>
</td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>