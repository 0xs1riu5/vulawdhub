<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb ls">
<tr>
<th width="60">头像</th>
<th>会员名</th>
<th>所在模块</th>
<th>IP</th>
<th>IP所在地</th>
<th>访问时间</th>
<th>URL</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><img src="<?php echo useravatar($v['username']);?>" style="padding:5px;" width="48" height="48"/></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>')"><span<?php echo $sid == $v['sid'] ? ' style="color:red;" title="我"' : '';?>><?php echo $v['username'];?></span></a></td>
<td><a href="<?php echo $MODULE[$v['moduleid']]['linkurl'];?>" target="_blank"><?php echo $MODULE[$v['moduleid']]['name'];?></a></td>
<td><?php echo $v['ip'];?></td>
<td><?php echo ip2area($v['ip']);?></td>
<td><?php echo $v['lasttime'];?></td>
<td><input type="text" size="30" value="<?php echo $v['qstring'];?>" title="<?php echo $v['qstring'];?>"/> <a href="?<?php echo $v['qstring'];?>" target="_blank"><img src="admin/image/link.gif" width="16" height="16" title="点击打开网址" alt="" align="absmiddle"/></a></td>
</tr>
<?php }?>
</table>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>