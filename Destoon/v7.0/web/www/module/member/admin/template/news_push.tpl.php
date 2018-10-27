<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="aid" value="<?php echo $aid;?>"/>
<input type="hidden" name="ids" value="<?php echo $ids;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 推送模块</td>
<td class="f_b">&nbsp;<?php echo $MODULE[$aid]['name'];?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 选择分类</td>
<td>&nbsp;<?php echo category_select('catid', '请选择分类', 0, $aid);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 提示信息</td>
<td>&nbsp;系统会自动丢弃已经推送过的新闻</td>
</tr>
<tr>
<td class="tl">&nbsp;</td>
<td>&nbsp;<input type="submit" name="submit" value="推 送" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="history.back(-1);"/></td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>