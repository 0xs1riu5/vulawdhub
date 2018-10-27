<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">电子邮件</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">标题</td>
<td class="f_b"><?php echo $title;?></td>
</tr>
<tr>
<td class="tl">收件人</td>
<td><a href="javascript:_user('<?php echo $email;?>', 'email');"><?php echo $email;?></a></td>
</tr>
<tr>
<td class="tl">发送时间</td>
<td><?php echo timetodate($addtime, 6);?></td>
</tr>
<tr>
<td class="tl">发送结果</td>
<td><?php echo $status == 3 ? '<span class="f_green">成功</span>' : '<span class="f_red">失败</span>';?></td>
</tr>
<tr>
<td class="tl">内容</td>
<td><?php echo $content;?></td>
</tr>
<tr>
<td class="tl">备注</td>
<td><?php echo $note;?></td>
</tr>
</tbody>
</table>
<div class="sbt"><input type="button" value=" 返 回 " class="btn" onclick="history.back(-1);"/></div>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>