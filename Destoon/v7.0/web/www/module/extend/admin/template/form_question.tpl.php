<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="job" value="order"/>
<input type="hidden" name="fid" value="<?php echo $fid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">排序</th>
<th>ID</th>
<th>选项名称</th>
<th>添加方式</th>
<th>必填</th>
<th>输入限制</th>
<th>默认(备选)值</th>
<th width="70" colspan="2">操作</th>
</tr>
<?php foreach($lists as $k=>$v) { ?>
<tr align="center">
<td><input type="text" size="2" name="listorder[<?php echo $v['qid'];?>]" value="<?php echo $v['listorder'];?>"/></td>
<td><?php echo $v['qid'];?></td>
<td><?php echo $v['name'];?></td>
<td><?php echo $TYPE[$v['type']];?></td>
<td><?php echo $v['required'] ? '<span class="f_red">是</span>' : '否';?></td>
<td><?php echo $v['required'];?></td>
<td><input type="text" style="width:300px;" value="<?php echo $v['value'];?>"/></td>
<?php if($v['type'] > 1) { ?>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=answer&fid=<?php echo $fid;?>&job=stats&qid=<?php echo $v['qid'];?>"><img src="admin/image/poll.png" width="16" height="16" title="统计报表" alt=""/></a></td>
<?php } else { ?>
<td></td>
<?php } ?>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=question&job=edit&fid=<?php echo $fid;?>&qid=<?php echo $v['qid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=question&job=delete&fid=<?php echo $fid;?>&qid=<?php echo $v['qid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php } ?>
</table>
<div class="btns"><input type="submit" value="更新排序" class="btn-g"/></div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">
Menuon(1);
$(function(){
	if($('body').width()<900) $('body').width(900);
});
</script>
<?php include tpl('footer');?>