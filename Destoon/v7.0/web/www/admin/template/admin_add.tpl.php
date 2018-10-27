<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 会员名</td>
<td>
<input type="text" size="20" name="username" id="username" value="<?php echo $username;?>"/>
&nbsp;&nbsp;<a href="javascript:if(Dd('username').value)_user(Dd('username').value);" class="t" title="点击查看填写会员的详细资料">[资料]</a>
&nbsp;&nbsp;<a href="?moduleid=2&action=add" class="t" target="_blank" title="如果会员还没有注册，请点这里添加">[添加]</a>
<span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 管理员类别</td>
<td>
<div class="b10">&nbsp;</div>
<input type="radio" name="admin" value="1" id="admin_1" onclick="Dh('ro');" checked/><label for="admin_1"> 超级管理员</label> <span class="f_gray">拥有除创始人特权外的所有权限</span>
<div class="b10">&nbsp;</div>
<input type="radio" name="admin" value="2" id="admin_2" onclick="Ds('ro');"/><label for="admin_2"> 普通管理员</label> <span class="f_gray">拥有系统分配的权限</span>
<div class="b10">&nbsp;</div>
<style type="text/css">
#ro {padding:5px 10px 10px 10px;border-top:#FFFFFF 1px solid;}
#ro div {width:25%;float:left;height:30px;}
#ro p {margin:2px;color:#FF6600;}
</style>
<div id="ro" style="display:none;">
<p>↓快捷选择一个管理角色(非必选)</p>
<?php 
foreach($MODULE as $m) {
	if($m['moduleid'] == 1 || $m['moduleid'] == 3 || $m['islink']) continue;
?>
<div><input type="checkbox" name="roles[<?php echo $m['moduleid'];?>]" value="1" id="ro_<?php echo $m['moduleid'];?>"/><label for="ro_<?php echo $m['moduleid'];?>"> <?php echo $m['name'];?>模块管理员</label></div>
<?php } ?>
<div><input type="checkbox" name="roles[template]" value="1" id="ro_template"/><label for="ro_template"> 模板风格管理员</label></div>
<div><input type="checkbox" name="roles[database]" value="1" id="ro_database"/><label for="ro_database"> 数据库管理员</label></div>
<div><input type="checkbox" onclick="checkall(this.form);" id="ro_all"/><label for="ro_all"> 全选/反选</label></div>
<p><?php echo ajax_area_select('aid', '分站权限');?></p>
</div>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 角色名称</td>
<td><input type="text" size="20" name="role" id="role"/> <span class="f_gray">可以为角色名称，例如编辑、美工、某分站编辑等，也可以为该管理员的备注</span></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="下一步" class="btn-g"></div>
</form>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'username';
	l = Dd(f).value;
	if(l == '') {
		Dmsg('请填写会员名', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>