<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="88">排序</th>
<th>ID</th>
<th>会员组</th>
<th>类型</th>
<th><?php echo VIP;?>指数</th>
<th width="40">修改</th>
<th width="40">删除</th>
</tr>
<?php foreach($groups as $k=>$v) {?>
<tr align="center">
<?php if($k > 5) { ?>
<td>&nbsp;<input type="text" size="2" name="listorder[<?php echo $v['groupid'];?>]" value="<?php echo $v['listorder'];?>"/></td>
<?php } else { ?>
<td>&nbsp;<?php echo $v['listorder'];?></td>
<?php } ?>
<td>&nbsp;<?php echo $v['groupid'];?></td>
<td><?php echo $v['groupname'];?></td>
<td>&nbsp;<?php echo $v['type'];?></td>
<td>&nbsp;<?php echo $v['vip'];?></td>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&groupid=<?php echo $v['groupid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a></td>
<td><?php if($v['groupid'] > 7) { ?>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&groupid=<?php echo $v['groupid'];?>"  onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
<?php } else {?>
&nbsp;
<?php } ?>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="更新排序" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=order';"/>
</div>
</form>
<div class="tt">注意事项</div>
<table cellspacing="0" class="tb">
<tr>
<td class="f_gray lh20">
&nbsp;&nbsp;- 会员组请按服务的范围(服务级别)由低到高依次排序，否则将影响会员的升级<br/>
&nbsp;&nbsp;- 扣费模式会员组可以注册时选择，包年模式需要会员在线升级<br/>
</td>
</tr>
</table>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>