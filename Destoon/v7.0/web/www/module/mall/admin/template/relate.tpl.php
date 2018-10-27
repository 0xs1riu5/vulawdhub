<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<div class="tt">关联商品</div>
<form method="post" action="?" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">关联名称</td>
<td class="f_gray"><input type="text" size="20" name="relate_name" id="relate_name" value="<?php echo $M['relate_name'];?>"/>&nbsp;&nbsp; 例如“颜色”、“尺寸”、“型号”等</td>
</tr>
<tr>
<td colspan="2">
<?php foreach($lists as $k=>$v) { ?>
<div style="width:130px;float:left;">
	<table width="120">
	<tr align="center" height="110" class="c_p">
	<td width="120"><a href="<?php echo $MOD['linkurl'];?><?php echo $v['linkurl'];?>" target="_blank"><img src="<?php echo $v['thumb'];?>" width="100" height="100" alt="" title="<?php echo $v['title'];?>"/></a></td>
	</tr>
	<tr align="center">
	<td>标题 <input type="text" size="8" name="post[<?php echo $v['itemid'];?>][relate_title]" value="<?php echo $v['relate_title'];?>"/></td>
	</tr>
	<tr align="center">
	<td>排序 <input type="text" size="8" name="post[<?php echo $v['itemid'];?>][listorder]" value="<?php echo $k;?>"/></td>
	</tr>
	<tr align="center">
	<td><a href="?moduleid=<?php echo $moduleid;?>&action=relate_del&itemid=<?php echo $itemid;?>&id=<?php echo $v['itemid'];?>" onclick="return _delete();" class="t">[移除]</a></td>
	</tr>
	</table>
</div>
<?php } ?>
</td>
</tr>
<tr>
<td colspan="2">
&nbsp;<input type="submit" name="submit" value="更 新" class="btn"/>
&nbsp;<input type="button" value="新增商品" onclick="add();" class="btn"/></td>
</tr>
</table>
</form>
<form method="post" action="?" id="dform_add">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="action" value="relate_add"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="id" id="id" value="0"/>
<input type="hidden" name="relate_name" id="relate_name_add" value=""/>
</form>
<script type="text/javascript">
function add() {
	if(Dd('relate_name').value.length < 2) {
		alert('请填写关联名称');
		Dd('relate_name').focus();
		return;
	}
	Dd('relate_name_add').value = Dd('relate_name').value;
	select_item('<?php echo $moduleid;?>&username=<?php echo $M['username'];?>', 'relate');
}
</script>
<?php include tpl('footer');?>