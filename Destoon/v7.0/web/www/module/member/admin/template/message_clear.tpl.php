<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">信件</td>
<td>
<input type="radio" value="0" name="message[status]" checked="checked"/> 全部&nbsp;&nbsp;
<input type="radio" value="3" name="message[status]" /> 收件箱&nbsp;&nbsp;
<input type="radio" value="2" name="message[status]" /> 已发送&nbsp;&nbsp;
<input type="radio" value="1" name="message[status]" /> 草稿箱&nbsp;&nbsp;
<input type="radio" value="4" name="message[status]" /> 回收站&nbsp;&nbsp;
</td>
</tr>
<tr>
<td class="tl">日期范围</td>
<td>
<?php echo dcalendar('message[fromdate]');?> 至 <?php echo dcalendar('message[todate]', $todate);?> 不指定表示不限
</td>
</tr>
<tr>
<td class="tl">选项</td>
<td>
<input type="checkbox" value="1" name="message[isread]" checked="checked"/> 保留未读信件
</td>
</tr>
</tbody>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 清 理 " class="btn-r" onclick="if(!confirm('确定要清理吗？此操作将不可撤销')) return false;"/></div>
</form>
<script type="text/javascript">Menuon(4);</script>
<?php include tpl('footer');?>