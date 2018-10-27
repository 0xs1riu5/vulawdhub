<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>IP</th>
<th>来自</th>
<th>锁定时间</th>
<th width="30">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="ip[]" value="<?php echo $v['ip'];?>"/></td>
<td><?php echo $v['ip'];?></td>
<td><?php echo ip2area($v['ip']);?></td>
<td><?php echo $v['addtime'];?></td>
<td><a href="?file=<?php echo $file;?>&action=unban&ip=<?php echo $v['ip'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="删除选定" class="btn-r" onclick="if(confirm('确定要删除选中IP吗？')){this.form.action='?file=<?php echo $file;?>&action=unban'}else{return false;}"/>
</div>
</form>
<div class="tt">IP解锁</div>
<form action="?" method="post">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="unban"/>
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
IP地址： <input type="text" name="ip" size="30"/> &nbsp; <input type="submit" name="submit" value="删 除" class="btn-r"/>
</td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>