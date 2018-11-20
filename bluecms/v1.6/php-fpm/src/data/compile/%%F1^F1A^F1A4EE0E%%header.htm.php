<?php /* Smarty version 2.6.22, created on 2018-11-20 09:49:30
         compiled from header.htm */ ?>
<div id="top_nav">
	<div id="top_nav_left">
		<ul>
		<li>您好,欢迎您的访问!</li>
		<?php if ($this->_tpl_vars['user_name']): ?>
		<li><font style="color:#ff6600;font-weight:bold;"><?php echo $this->_tpl_vars['user_name']; ?>
</font></li>
		<li><a href="user.php?act=logout">退出</a></li>
		<?php else: ?>
		<li><a href="user.php?act=login">登录</a></li>
		<li><a href="user.php?act=reg">免费注册</a></li>
		<?php endif; ?>
		</ul>
	</div>
	<div id="top_nav_right">
	<ul><li><a href="JavaScript:" onClick="var strHref=window.location.href;this.style.behavior='url(#default#homepage)';this.setHomePage('<?php echo $this->_tpl_vars['site_url']; ?>
');">设为首页</a></li><li><a href="javascript:window.external.AddFavorite(location.href, document.title)">加入收藏</a></li></ul>
	</div>
</div>
<div id="top"><a href="./" target="_self" class="logo"><img src="templates/default/images/logo.gif" alt="<?php echo $this->_tpl_vars['site_name']; ?>
" border="0" /></a></div>
	<div class="clears"></div>
	<div class="nav">
		<span class="nav_l"></span>
		<div class="nav_c">
		<ul><li id="index"><a href="./">首页</a></li>
			<li><a href="<?php echo url_rewrite('news_cat', array()); ?>">本地新闻</a></li>
			<li><a href="<?php echo url_rewrite('info_index', array()); ?>">分类信息</a></li>
		<?php unset($this->_sections['add_nav']);
$this->_sections['add_nav']['name'] = 'add_nav';
$this->_sections['add_nav']['loop'] = is_array($_loop=$this->_tpl_vars['add_nav_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['add_nav']['show'] = true;
$this->_sections['add_nav']['max'] = $this->_sections['add_nav']['loop'];
$this->_sections['add_nav']['step'] = 1;
$this->_sections['add_nav']['start'] = $this->_sections['add_nav']['step'] > 0 ? 0 : $this->_sections['add_nav']['loop']-1;
if ($this->_sections['add_nav']['show']) {
    $this->_sections['add_nav']['total'] = $this->_sections['add_nav']['loop'];
    if ($this->_sections['add_nav']['total'] == 0)
        $this->_sections['add_nav']['show'] = false;
} else
    $this->_sections['add_nav']['total'] = 0;
if ($this->_sections['add_nav']['show']):

            for ($this->_sections['add_nav']['index'] = $this->_sections['add_nav']['start'], $this->_sections['add_nav']['iteration'] = 1;
                 $this->_sections['add_nav']['iteration'] <= $this->_sections['add_nav']['total'];
                 $this->_sections['add_nav']['index'] += $this->_sections['add_nav']['step'], $this->_sections['add_nav']['iteration']++):
$this->_sections['add_nav']['rownum'] = $this->_sections['add_nav']['iteration'];
$this->_sections['add_nav']['index_prev'] = $this->_sections['add_nav']['index'] - $this->_sections['add_nav']['step'];
$this->_sections['add_nav']['index_next'] = $this->_sections['add_nav']['index'] + $this->_sections['add_nav']['step'];
$this->_sections['add_nav']['first']      = ($this->_sections['add_nav']['iteration'] == 1);
$this->_sections['add_nav']['last']       = ($this->_sections['add_nav']['iteration'] == $this->_sections['add_nav']['total']);
?>
			<li><a href="<?php echo $this->_tpl_vars['add_nav_list'][$this->_sections['add_nav']['index']]['navlink']; ?>
" <?php if ($this->_tpl_vars['add_nav_list'][$this->_sections['add_nav']['index']]['opennew'] == 1): ?> target="_blank"<?php endif; ?>><?php echo $this->_tpl_vars['add_nav_list'][$this->_sections['add_nav']['index']]['navname']; ?>
</a></li>
		<?php endfor; endif; ?>
			</ul>
		</div>
		<span class="nav_r"></span>
	</div>