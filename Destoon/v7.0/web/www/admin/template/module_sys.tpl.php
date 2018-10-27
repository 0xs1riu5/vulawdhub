<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb ls">
<tr>
<th>模型</th>
<th>目录</th>
<th width="60">可复制</th>
<th width="60">可卸载</th>
<th>作者</th>
<th>官方网站</th>
</tr>
<?php foreach($sysmodules as $k=>$v) {?>
<tr align="center">
<td align="left">&nbsp;<img src="admin/image/folder.gif" align="absmiddle"/>&nbsp; <?php echo $v['name'];?></td>
<td title="位于module/<?php echo $v['module'];?>/"><?php echo $v['module'];?></td>
<td><?php echo $v['copy'] ? '<span class="f_green">是</span>' : '<span class="f_red">否</span>'; ?></td>
<td><?php echo $v['uninstall'] ? '<span class="f_green">是</span>' : '<span class="f_red">否</span>'; ?></td>
<td><?php echo $v['author'];?></td>
<td><a href="<?php echo 'http://'.$v['homepage'];?>" target="_blank"><?php echo $v['homepage'];?></a></td>
</tr>
<?php
}
?>
</table>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>