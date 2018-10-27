<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<a name="q<?php echo $k;?>"></a>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">回复会员</td>
<td><a href="javascript:_user('<?php echo $R['username'];?>');" class="t"><?php echo $R['username'] ? $R['username'] : 'Guest';?></a>&nbsp; IP:<?php echo $R['ip'];?> 来自 <?php echo ip2area($R['ip']);?></td>
</tr>
<tr>
<td class="tl">回复时间</td>
<td><?php echo timetodate($R['addtime']);?></td>
</tr>
<?php foreach($Q as $k=>$v) {?>
<tr>
<td class="tl"><?php echo $v['name'];?></td>
<td>
<?php echo $A[$k]['content'];?>
<?php echo $A[$k]['other'] ? '&nbsp;&nbsp;&nbsp;(填写其他:'.$A[$k]['other'].')' : '';?>
</td>
</tr>
<?php } ?>
<tr>
<td class="tl"></td>
<td><input type="button" value="返 回" class="btn-g" onclick="window.history.back(-1);"/></td>
</tr>
</table>
<script type="text/javascript">Menuon(3);</script>
<?php include tpl('footer');?>