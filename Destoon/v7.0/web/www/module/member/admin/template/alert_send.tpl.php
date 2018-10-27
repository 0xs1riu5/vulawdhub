<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="send" value="1"/>
<input type="hidden" name="first" value="1"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 邮件标题</td>
<td><input type="text" size="50" name="title" value="<?php echo $title;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 每轮发送</td>
<td><input type="text" size="5" name="num" value="<?php echo $num;?>"/> 封</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 商机数量</td>
<td><input type="text" size="5" name="total" value="<?php echo $total;?>"/> 条</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 查询条件</td>
<td><input type="text" size="60" name="sql" value="<?php echo $sql;?>"/> <?php tips('附加的SQL查询条件 以AND开头');?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 排序方式</td>
<td><input type="text" size="20" name="ord" value="<?php echo $ord;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 选择模板</td>
<td><?php echo tpl_select('alert', 'mail', 'template', '默认模板', '');?></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 开始发送 " class="btn-g"/></div>
</form>
<script type="text/javascript">Menuon(3);</script>
<?php include tpl('footer');?>