<?php /* Smarty version 2.6.22, created on 2018-11-20 09:49:30
         compiled from index.htm */

$this->_cache_serials['/www/data/compile//%%43^43B^43B32F4B%%index.htm.inc'] = '48245b5c51f9cbd79fc14dfc3f32df63';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'index.htm', 67, false),array('block', 'dynamic', 'index.htm', 94, false),)), $this); ?>
<?php
function _smarty_tplfunc_48245b5c51f9cbd79fc14dfc3f32df63_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php if ($_smarty->_tpl_vars['user_name'] && $_smarty->_tpl_vars['active'] == 0): ?>
			<div id="userinfo">
					<h3>用户登录</h3>
					<div class="welcome">你好：<strong><?php echo $_smarty->_tpl_vars['user_name']; ?>
</strong>，欢迎登录 </div>
					<div class="userface">
						<a href=""><img width="64" height="64" src="<?php if ($_smarty->_tpl_vars['face_pic']): ?><?php echo $_smarty->_tpl_vars['face_pic']; ?>
<?php else: ?>templates/default/images/nopic.gif<?php endif; ?>"/></a>
					</div>
					<div class="mylink">
						<ul>
							<li><a href="user.php">会员中心</a></li>
							<li><a href="user.php?act=buy">金币充值</a></li>
							<li><a href="user.php?act=my_info">个人资料</a></li>
							<li><a href="user.php?act=logout">退出登录</a></li>
						</ul>
					</div>
			</div>
			<?php elseif ($_smarty->_tpl_vars['active'] == 1): ?>
			<div id="userinfo">
			    <div class="welcome">你好：<strong><?php echo $_smarty->_tpl_vars['user_name']; ?>
</strong>，欢迎登录 </div>
			    <div class="userface">
			        <a href=""><img width="64" height="64" src="<?php if ($_smarty->_tpl_vars['face_pic']): ?><?php echo $_smarty->_tpl_vars['face_pic']; ?>
<?php else: ?>templates/default/images/nopic.gif<?php endif; ?>"/></a>
			    </div>
			    <div class="mylink">
			        <ul>
			            <li><a href="user.php">激活</a></li>
			        </ul>
			    </div>
			</div>
			<?php else: ?>
			<div id="logining">
				<h3>用户登录</h3><form action="user.php?act=index_login" method="post" name="log_form">
				<dl class="log">
					<dt class="login">用户名：</dt>
					<dd><input type="text" name="user_name" value="" id="user_name" class="login_input" /></dd>
				</dl>
				<dl class="log">
					<dt class="login">密&nbsp;&nbsp;&nbsp;码：</dt>
					<dd><input type="password" name="pwd" id="pwd" value="" class="login_input" /></dd>
				</dl>
				<dl class="log">
					<dt class="login"><input type="checkbox" name="remember" value="1" /></dt>
					<dd>是否记住我</dd>
				</dl>
				<dl class="log">
					<dt class="login"><input type="image" src="templates/default/images/login_btn.gif" class="submit" /></dt>
					<dd><a href="user.php?act=reg">新用户注册</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="publish.php">发布新信息</a></dd>
				</dl></form>
			</div>
			<?php endif; ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
