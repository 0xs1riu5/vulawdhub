<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 新组名称</td>
<td><input name="name" type="text" size="20" value="<?php echo $name;?>"/> <span class="f_gray">中文名称，例如 关于我们</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 新组标识</td>
<td><input name="item" type="text" size="20" value="<?php echo $item;?>"/> <span class="f_gray">数字和字母组合，例如 aboutus</span></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="创 建" class="btn-g"/></div>
</form>
<script type="text/javascript">Menuon(3);</script>
<?php include tpl('footer');?>