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
<tr class="on">
<td>
<input type="radio" name="fromtype" value="catid" <?php echo $itemid ? '' : 'checked';?> id="f_1"/><label for="f_1">从指定分类ID</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="fromtype" value="itemid" <?php echo $itemid ? 'checked' : '';?> id="f_2"/><label for="f_2">从指定<?php echo $MOD['name'];?>ID</label>
</td>
<td></td>
<td>&nbsp;目标分类</td>
</tr>
<tr>
<td width="220" align="center" title="多个ID用,分开 结尾和开头不能有,">
<textarea style="height:300px;width:200px;" name="fromids"><?php echo $itemid;?></textarea>
</td>
<td width="100" align="center"><strong>&rarr;</strong></td>
<td id="tocatids"><?php echo category_select('tocatid', '', 0, $moduleid, 'size="2" style="height:300px;width:150px;"');?></td>
</tr>
<tr align="center">
<td><a href="###" onclick="showid()" class="t">[分类ID查询]</a></td>
<td><input type="submit" name="submit" value="移 动" class="btn-g"/></td>
<td>&nbsp;</td>
</tr>
</table>
</div>
</form>
<script type="text/javascript">
function showid() {
	if($('#tocatids').html().indexOf('ID') == -1) {
		$('#tocatids').find('option').each(function(i){
			var o = $(this);
			o.html(o.html()+'- ID:'+o.val());
		});
	}
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>