<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">删除</th>
<th width="80">排序</th>
<th width="160">名称</th>
<th>地址</th>
</tr>
<?php foreach($dmenus as $k=>$v) {?>
<tr align="center">
<td><input name="right[<?php echo $v['adminid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="right[<?php echo $v['adminid'];?>][listorder]" type="text" size="3" value="<?php echo $v['listorder'];?>"/></td>
<td><input name="right[<?php echo $v['adminid'];?>][title]" type="text" size="12" value="<?php echo $v['title'];?>"/> <?php echo dstyle('right['.$v['adminid'].'][style]', $v['style']);?></td>
<td align="left"><input name="right[<?php echo $v['adminid'];?>][url]" type="text" size="60" value="<?php echo $v['url'];?>"/></td>
</tr>
<?php }?>
<tr align="center">
<td class="f_green">新增</td>
<td><input name="right[0][listorder]" type="text" size="3" value=""/></td>
<td><input name="right[0][title]" type="text" size="12" value=""/> <?php echo dstyle('right[0][style]');?></td>
<td align="left"><input name="right[0][url]" type="text" size="60" value=""/>
</td>
</tr>
<tr>
<td align="center"><input type="checkbox" onclick="checkall(this.form);" title="全选/反选"/></td>
<td height="30" colspan="4">&nbsp;<input type="submit" name="submit" value="更 新" onclick="if($(':checkbox:checked').length && !confirm('提示：您选择删除'+$(':checkbox:checked').length+'个操作链接，确定要删除吗？此操作将不可撤销')) return false;" class="btn-g"/>&nbsp;&nbsp;<?php tips('提示：复制左侧栏的操作链接，删除“?”之前的地址即为对应操作的地址，同时也支持http开头的外部网址');?></td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(0);</script>
<?php if(isset($update)) { ?>
<script type="text/javascript">window.parent.frames[0].location.reload();</script>
<?php } ?>
<br/>
<?php include tpl('footer');?>