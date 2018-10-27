<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">标题</td>
<td class="f_b"><?php echo $title;?></td>
</tr>
<tr>
<td class="tl">发件人</td>
<td><a href="javascript:_user('<?php echo $fromuser;?>');"><?php echo $fromuser;?></a></td>
</tr>
<tr>
<td class="tl">收件人</td>
<td><a href="javascript:_user('<?php echo $touser;?>');"><?php echo $touser;?></a></td>
</tr>
<tr>
<td class="tl">发信时间</td>
<td><?php echo timetodate($addtime, 6);?></td>
</tr>
<tr>
<td class="tl">发信IP</td>
<td><?php echo $ip;?></td>
</tr>
<tr>
<td class="tl">内容</td>
<td><?php echo $content;?></td>
</tr>
</tbody>
</table>
<div class="sbt"><input type="button" value="返 回" class="btn-g" onclick="history.back(-1);"/>&nbsp;&nbsp;<input type="button" value="删 除" class="btn-r" onclick="if(confirm('确定要删除吗？此操作将不可撤销')) {Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $itemid;?>&forward=<?php echo urlencode($forward);?>');}"/></div>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>