<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return Dcheck();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="area[parentid]" value="<?php echo $parentid;?>"/>
<table cellspacing="0" class="tb">
<?php if($parentid) {?>
<tr>
<td class="tl"><span class="f_hid">*</span> 上级地区</td>
<td><?php echo $AREA[$parentid]['areaname'];?></td>
</tr>
<?php }?>
<tr>
<td class="tl"><span class="f_hid">*</span> 地区名称</td>
<td><textarea name="area[areaname]"  id="areaname" style="width:200px;height:100px;overflow:visible;"></textarea><?php tips('允许批量添加，一行一个，点回车换行');?></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="添 加" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function Dcheck() {
	if(Dd('areaname').value == '') {
		Dtip('请填写地区名称。允许批量添加，一行一个，点回车换行');
		Dd('areaname').focus();
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>