<?php /* Smarty version 2.6.22, created on 2018-11-20 09:50:12
         compiled from login.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link href="css/login.css" rel="stylesheet" type="text/css" /><!--
 <div id="top_nav">
		<span id="go_back"><a href="../">返回主页</a></span>
	</div>
</div>
 --><div id="login">
 	<h1><a title="Powered by BlueCMS" href="http://www.bluecms.net">BlueCMS</a></h1>
 	<form name="login_form" id="login_form" action="login.php" method="post">
		<p>
			<label>用户名<br />
			<input type="text" name="admin_name" id="user_login" value="" size="20" /></label>
		</p>
		<p>
			<label>密码<br />
			<input type="password" name="admin_pwd" id="user_pass" value="" size="20" /></label>
		</p>
		<p class="remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="1" /> 记住我</label></p>
		<p class="submit">
			<input type="submit" name="submit" id="submit" value="登录" />
			<input type="hidden" name="act" value="do_login" />
		</p>
    </form>
 </div>
  <br/>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>