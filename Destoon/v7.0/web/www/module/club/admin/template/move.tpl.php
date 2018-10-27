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
<td class="tl">移动方式</td>
<td>
<input type="radio" name="fromtype" value="gid" <?php echo $itemid ? '' : 'checked';?> id="f_1"/><label for="f_1">从指定商圈ID</label>&nbsp;&nbsp;
<input type="radio" name="fromtype" value="itemid" <?php echo $itemid ? 'checked' : '';?> id="f_2"/><label for="f_2">从指定帖子ID</label>
</td>
</tr>
<tr>
<td class="tl"></td>
<td>
<textarea style="height:50px;width:300px;" name="fromids"><?php echo $itemid;?></textarea><br/>
<span class="f_gray">多个ID用,分开 结尾和开头不能有,</span>
</td>
</tr>
<tr>
<td class="tl">目标商圈</td>
<td><input name="tocatid" type="text" id="tocatid" size="30" value="点击选择" onfocus="Dwidget('?moduleid=<?php echo $moduleid;?>&file=group&itemid=1', '选择商圈');"/></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="移 动" class="btn-g"/></div>
</form>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>