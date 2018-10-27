<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb ls">
<tr>
<th>配置名称</th>
<th>配置说明</th>
<th>数据库</th>
<th width="130">修改时间</th>
<th width="130">上次导入</th>
<th width="150">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><a href="?file=<?php echo $file;?>&action=config&name=<?php echo $v['name'];?>"><?php echo $v['name'];?></a></td>
<td align="left">&nbsp;<?php echo $v['title'];?></td>
<td><?php echo $v['database'];?></td>
<td class="px12"><?php echo $v['edittime'];?></td>
<td class="px12"><?php echo $v['lasttime'];?></td>
<td>
<a href="?file=<?php echo $file;?>&action=config&name=<?php echo $v['name'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=import&name=<?php echo $v['name'];?>" onclick="return confirm('确定要导入此配置文件吗？\n\n此操作不可恢复，请在导入前备份相关的数据表');"><img src="admin/image/import.png" width="16" height="16" title="导入本系列配置文件" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=view&name=<?php echo $v['name'];?>"><img src="admin/image/view.png" width="16" height="16" title="效果预览" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=download&name=<?php echo $v['name'];?>"><img src="admin/image/save.png" width="16" height="16" title="下载" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=delete&name=<?php echo $v['name'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="config"/>
<div class="tt">新建配置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 导入类型</td>
<td>
<input type="radio" name="type" value="0" id="t_0" onclick="Ds('p_0');Dh('p_2');" checked/><label for="t_0"/> 模块</label>&nbsp;&nbsp;&nbsp;
<input type="radio" name="type" value="1" id="t_1" onclick="Dh('p_0');Dh('p_2');"/><label for="t_1"/> 会员</label>&nbsp;&nbsp;&nbsp;
<input type="radio" name="type" value="2" id="t_2" onclick="Dh('p_0');Ds('p_2');"/><label for="t_2"/> 其他</label>
</td>
</tr>
<tr id="p_0" style="display:">
<td class="tl"><span class="f_red">*</span> 选择模块</td>
<td>
<select name="mid" id="mid">
<option value="0">请选择</option>
<?php 
foreach($MODULE as $m) {
	if($m['moduleid'] > 4 && !$m['islink']) {
?>
<option value="<?php echo $m['moduleid'];?>"><?php echo $m['name'];?></option>
<?php
	}
}
?>
</td>
</tr>
<tr id="p_2" style="display:none">
<td class="tl"><span class="f_red">*</span> 选择表</td>
<td>
<select name="tb" id="tb">
<option value="">请选择</option>
<?php 
foreach($tables as $t) {
?>
<option value="<?php echo $t['name'];?>"><?php echo $t['name'].' ['.$t['note'].']';?></option>
<?php
}
?>
</td>
</tr>
<tr>
<td class="tl"> </td>
<td height="30"><input type="submit" value="下一步" class="btn-g"/></td>
</tr>
</table>
</form>
<script type="text/javascript">
function check() {
	if(Dd('t_0').checked) {
		if(Dd('mid').value == 0) {
			alert('请选择模块');
			Dd('mid').focus();
			return false;
		}
	}
	if(Dd('t_2').checked) {
		if(Dd('tb').value == '') {
			alert('请选择表');
			Dd('tb').focus();
			return false;
		}
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(6);</script>
<?php include tpl('footer');?>