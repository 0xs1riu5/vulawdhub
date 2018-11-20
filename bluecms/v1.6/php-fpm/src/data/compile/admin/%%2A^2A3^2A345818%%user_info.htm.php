<?php /* Smarty version 2.6.22, created on 2018-11-20 12:27:34
         compiled from user_info.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'user_info.htm', 49, false),array('function', 'html_select_date', 'user_info.htm', 53, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<?php if ($this->_tpl_vars['act'] == add): ?>
<table>
<form method="post" action="user.php" name="adduserform" onsubmit="return check();">
  <tr>
    <td>用户名:</td>
    <td><input type="text" name="username" maxlength="60" value="" />&nbsp;<span class="warning">*</span></td>
  </tr>
  <tr>
    <td>密码:</td>
    <td><input type="password" name="password" maxlength="100" value="" />&nbsp;<span class="warning">*</span></td>
  </tr>
  <tr>
    <td>确认密码:</td>
    <td><input type="password" name="confirm_password" maxlength="100" value="" />&nbsp;<span class="warning">*</span></td>
  </tr>
  <tr>
    <td>电子邮件:</td>
    <td><input type="text" name="email" size="30" maxlength="100" value="" />&nbsp;<span class="warning">*</span></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="提交" />
      <input type="reset" value="重置" />
      <input type="hidden" name="act" value="do_add" />
   </td>
  </tr>
</form>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['act'] == edit): ?>
<table width="100%" >
<form method="post" action="user.php" name="adduserform" onsubmit="return check();">
  <tr>
    <td>用户名:</td>
    <td><?php echo $this->_tpl_vars['user']['username']; ?>
</td>
  </tr>
  <tr>
    <td>电子邮件:</td>
    <td><input type="text" name="email" maxlength="60" size="30" value="<?php echo $this->_tpl_vars['user']['email']; ?>
" /></td>
  </tr>
  <tr>
    <td>密码:</td>
    <td><input type="password" name="password" maxlength="20" size="20" />&nbsp;&nbsp;密码不变则不填</td>
  </tr>
  <tr>
    <td>性别:</td>
    <td><?php echo smarty_function_html_radios(array('name' => 'sex','options' => $this->_tpl_vars['sexarr'],'checked' => $this->_tpl_vars['user']['sex']), $this);?>
</td>
  </tr>
  <tr>
    <td>生日:</td>
    <td><?php echo smarty_function_html_select_date(array('field_order' => 'YMD','prefix' => 'birthday','time' => $this->_tpl_vars['user']['birthday'],'start_year' => "-60",'end_year' => "+1",'display_days' => true,'month_format' => "%m"), $this);?>
</td>
  </tr>
  <tr>
    <td>地址:</td>
    <td><input type="text" name="address" size="40" value="<?php echo $this->_tpl_vars['user']['address']; ?>
" /></td>
  </tr>
  <tr>
    <td>msn:</td>
    <td><input type="text" name="msn" size="30" value="<?php echo $this->_tpl_vars['user']['msn']; ?>
" /></td>
  </tr>
    </tr>
  <tr>
    <td>qq:</td>
    <td><input type="text" name="qq" size="20" value="<?php echo $this->_tpl_vars['user']['qq']; ?>
" /></td>
  </tr>
    </tr>
  <tr>
    <td>办公室电话:</td>
    <td><input type="text" name="office_phone" size="20" value="<?php echo $this->_tpl_vars['user']['officephone']; ?>
" /></td>
  </tr>
    </tr>
  <tr>
    <td>电话:</td>
    <td><input type="text" name="home_phone" size="20" value="<?php echo $this->_tpl_vars['user']['homephone']; ?>
" /></td>
  </tr>
    </tr>
  <tr>
    <td>移动电话:</td>
    <td><input type="text" name="mobile_phone" size="20" value="<?php echo $this->_tpl_vars['user']['mobilephone']; ?>
" /></td>
  </tr>
  <tr>
    <td>金币数额:</td>
    <td><input type="text" name="money" size="10" value="<?php echo $this->_tpl_vars['user']['money']; ?>
" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="提交" class="button" />
      <input type="reset" value="重置" class="button" />
      <input type="hidden" name="act" value="do_edit" />
      <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user']['user_id']; ?>
" />
	</td>
  </tr>
</form>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['act'] == 'del'): ?>
<table>
<form method="post" action="user.php" name="delform">
  <tr>
    <td>用户名:</td>
    <td><input type="text" name="username" maxlength="60" value="<?php echo $this->_tpl_vars['user_name']; ?>
" readonly = "true" /></td>
  </tr>
  <tr>
    <td>是否删除该用户所有分类信息:</td>
    <td><select name="del_info"><option value="1">是</option><option value="0">否</option></select></td>
  </tr>
  <tr>
    <td>是否删除该用户评论:</td>
    <td><select name="del_comment"><option value="1">是</option><option value="0">否</option></select></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="提交" />
      <input type="reset" value="重置" />
      <input type="hidden" name="act" value="do_del" />
      <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
   </td>
  </tr>
</form>
</table>
<?php endif; ?>
<script type="text/javascript">
function check()
{
	if(adduserform.username.value=='')
	{
		alert("用户名不能为空");
		adduserform.username.focus();
		return false;
	}
	if(adduserform.password.value=='')
	{
		alert("用户密码不能为空");
		adduserform.password.focus();
		return false;
	}
	if(adduserform.confirm_password.value=='')
	{
		alert("用户密码不能为空");
		adduserform.confirm_password.focus();
		return false;
	}
	if(adduserform.password.value!=adduserform.confirm_password.value)
	{
		alert("两次输入密码不一样！");
		return false;
	}
	if(adduserform.email.value=='')
	{
		alert("电子邮件不能为空");
		adduserform.email.focus();
		return false;
	}
}
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>