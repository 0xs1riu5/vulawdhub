<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">ID</th>
<th>标题</th>
<th>会员组</th>
<th>时间</th>
<th width="50">操作</th>
</tr>
<?php foreach($messages as $k=>$v) {?>
<tr align="center">
<td><?php echo $v['itemid'];?></td>
<td align="left"><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>">&nbsp;<?php echo $v['title'];?></a></td>
<td><?php echo $v['group'];?></td>
<td><?php echo $v['addtime'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=system_delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<script type="text/javascript">Menuon(2);</script>
<br/>
<?php include tpl('footer');?>