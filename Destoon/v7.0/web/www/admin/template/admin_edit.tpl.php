<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<input type="hidden" name="username" value="<?php echo $user['username'];?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 会员名</td>
<td><a href="javascript:_user('<?php echo $user['username'];?>');" class="t">[<?php echo $user['username'];?>]</a> <span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 管理员类别</td>
<td>
<div class="b10">&nbsp;</div>
<input type="radio" name="admin" value="1" id="admin_1"<?php echo $user['admin'] == 1 ? ' checked' : '';?>/><label for="admin_1"> 超级管理员</label> <span class="f_gray">拥有除创始人特权外的所有权限</span>
<div class="b10">&nbsp;</div>
<input type="radio" name="admin" value="2" id="admin_2"<?php echo $user['admin'] == 2 ? ' checked' : '';?>/><label for="admin_2"> 普通管理员</label> <span class="f_gray">拥有系统分配的权限</span>
<div class="b10">&nbsp;</div>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 分站权限</td>
<td><?php echo ajax_area_select('aid', '请选择', $user['aid']);?> <span class="f_gray">分站权限仅对<span class="f_red">普通管理员</span>生效</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 角色名称</td>
<td><input type="text" size="20" name="role" id="role" value="<?php echo $user['role'];?>"/> <span class="f_gray">可以为角色名称，例如编辑、美工、某分站编辑等，也可以为该管理员的备注</span></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"></div>
</form>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>