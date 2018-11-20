<?php /* Smarty version 2.6.22, created on 2018-11-20 12:33:39
         compiled from guest_book.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'guest_book.htm', 85, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<meta name="Keywords" content="<?php echo $this->_tpl_vars['cat_info']['keywords']; ?>
" />
<meta name="Description" content="<?php echo $this->_tpl_vars['cat_info']['description']; ?>
" />
<title><?php echo $this->_tpl_vars['current_act']; ?>
 - <?php echo $this->_tpl_vars['site_name']; ?>
 - Powered by BlueCMS</title>
<link href="templates/default/css/category.css" rel="stylesheet" type="text/css" />
<script src="templates/default/css/jquery.js" type="text/javascript"></script>
<link rel="shortcut icon" href="images/favicon.ico" />
<script type="text/javascript">
function reply(e){
	var name = $("#name_"+e).text();
	$("#rid").val(e);
	$("#g_content").val('回复 '+name+'：');
	$("#g_content").focus();
}
function del(e){
	$.get("guest_book.php", {id:e, act:'del'});
	$("#guest_"+e).remove();
}
</script>
</head>
<body>
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
<div id="wapper">
	<div id="top"><a href="./" target="_self" class="logo"><img src="templates/default/images/logo.gif" alt="<?php echo $this->_tpl_vars['site_name']; ?>
" border="0" /></a></div>
	<div class="clears"></div>
	<div id="ann_nav">
	<span class="n_left"></span>
	<div class="ann_nav">
		<ul><li class="index"><a href="./">首页</a></li>
			<?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['ann_cat']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['a']['show'] = true;
$this->_sections['a']['max'] = $this->_sections['a']['loop'];
$this->_sections['a']['step'] = 1;
$this->_sections['a']['start'] = $this->_sections['a']['step'] > 0 ? 0 : $this->_sections['a']['loop']-1;
if ($this->_sections['a']['show']) {
    $this->_sections['a']['total'] = $this->_sections['a']['loop'];
    if ($this->_sections['a']['total'] == 0)
        $this->_sections['a']['show'] = false;
} else
    $this->_sections['a']['total'] = 0;
if ($this->_sections['a']['show']):

            for ($this->_sections['a']['index'] = $this->_sections['a']['start'], $this->_sections['a']['iteration'] = 1;
                 $this->_sections['a']['iteration'] <= $this->_sections['a']['total'];
                 $this->_sections['a']['index'] += $this->_sections['a']['step'], $this->_sections['a']['iteration']++):
$this->_sections['a']['rownum'] = $this->_sections['a']['iteration'];
$this->_sections['a']['index_prev'] = $this->_sections['a']['index'] - $this->_sections['a']['step'];
$this->_sections['a']['index_next'] = $this->_sections['a']['index'] + $this->_sections['a']['step'];
$this->_sections['a']['first']      = ($this->_sections['a']['iteration'] == 1);
$this->_sections['a']['last']       = ($this->_sections['a']['iteration'] == $this->_sections['a']['total']);
?>
			<li><a href="ann.php?cid=<?php echo $this->_tpl_vars['ann_cat'][$this->_sections['a']['index']]['cid']; ?>
"><?php echo $this->_tpl_vars['ann_cat'][$this->_sections['a']['index']]['cat_name']; ?>
</a></li>
			<?php endfor; endif; ?>
			<li><a href="guest_book.php" target="_blank">留言建议</a></li>
			</ul>
	</div>
	<span class="n_right"></span>
	</div>
	<div class="clear"></div>
	<div class="g_active_act">
		<div class="g_a_l"></div>
		<div class="g_a_m">您所在的位置：<a href="./">首页</a> &raquo; <?php echo $this->_tpl_vars['current_act']; ?>
</div>
		<div class="g_a_r"></div>
	</div>
	<div id="g_main">
		<form style="margin: 0pt;" onsubmit="return check();" method="post" name="guest_form" action="guest_book.php">
			<div class="guest">
				<textarea id="g_content" name="content"></textarea>
				<div class="user_info">
					<span class="left"><?php if ($this->_tpl_vars['user_name']): ?>您好，<a href="user.php"><?php echo $this->_tpl_vars['user_name']; ?>
</a><?php else: ?>您现在是匿名发表！ <a href="user.php?act=login&from=<?php echo $this->_tpl_vars['url']; ?>
">登录</a>&nbsp;|&nbsp;<a href="user.php?act=reg&from=<?php echo $this->_tpl_vars['url']; ?>
">注册</a><?php endif; ?></span>
					<span class="right"><input type="submit" value="发表留言" class="guest_submit"/><input type="hidden" name="act" value="send" />
					<input type="hidden" id="rid" name="rid" value="" />
					<input type="hidden" name="page_id" value="<?php echo $this->_tpl_vars['page_id']; ?>
" />
					</span>
				</div>
			</div>
		</form>
		<div id="guest_list">
			<div id="guest_title">
				用户留言<span class="count">共有留言数 <?php echo $this->_tpl_vars['guest_total']; ?>
 条</span>
			</div>
			<?php unset($this->_sections['g']);
$this->_sections['g']['name'] = 'g';
$this->_sections['g']['loop'] = is_array($_loop=$this->_tpl_vars['guest_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['g']['show'] = true;
$this->_sections['g']['max'] = $this->_sections['g']['loop'];
$this->_sections['g']['step'] = 1;
$this->_sections['g']['start'] = $this->_sections['g']['step'] > 0 ? 0 : $this->_sections['g']['loop']-1;
if ($this->_sections['g']['show']) {
    $this->_sections['g']['total'] = $this->_sections['g']['loop'];
    if ($this->_sections['g']['total'] == 0)
        $this->_sections['g']['show'] = false;
} else
    $this->_sections['g']['total'] = 0;
if ($this->_sections['g']['show']):

            for ($this->_sections['g']['index'] = $this->_sections['g']['start'], $this->_sections['g']['iteration'] = 1;
                 $this->_sections['g']['iteration'] <= $this->_sections['g']['total'];
                 $this->_sections['g']['index'] += $this->_sections['g']['step'], $this->_sections['g']['iteration']++):
$this->_sections['g']['rownum'] = $this->_sections['g']['iteration'];
$this->_sections['g']['index_prev'] = $this->_sections['g']['index'] - $this->_sections['g']['step'];
$this->_sections['g']['index_next'] = $this->_sections['g']['index'] + $this->_sections['g']['step'];
$this->_sections['g']['first']      = ($this->_sections['g']['iteration'] == 1);
$this->_sections['g']['last']       = ($this->_sections['g']['iteration'] == $this->_sections['g']['total']);
?>
			<div class="guest_content" id="guest_<?php echo $this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['id']; ?>
">
				<div class="g_content">
					<p class="g_t">
						<span class="right">第<?php echo $this->_sections['g']['index']+1; ?>
楼</span>
						用户 <b id="name_<?php echo $this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['user_name']; ?>
</b> 发表于：<?php echo ((is_array($_tmp=$this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
&nbsp;&nbsp;&nbsp;IP:&nbsp;<?php echo $this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['ip']; ?>

						<span>&nbsp;&nbsp;
						<?php if ($this->_tpl_vars['user_id'] == 1): ?><input type="button" id="reply" onclick="return reply(<?php echo $this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['id']; ?>
);" name="reply" value="回复" />&nbsp;&nbsp;&nbsp;<input type="button" id="del" name="del" onclick="return del(<?php echo $this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['id']; ?>
);" value="删除" /></span><?php endif; ?>
					</p>
					<p><?php echo $this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['content']; ?>
</p>
				</div>
				<?php if ($this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['reply_content']): ?>
				<div class="r_content">
					<p><?php echo $this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['reply_content']; ?>
</p>
					<p class="g_b">管理员回复于 <?php echo ((is_array($_tmp=$this->_tpl_vars['guest_list'][$this->_sections['g']['index']]['reply_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</p>
				</div>
				<?php endif; ?>
			</div>
			<?php endfor; endif; ?>
			<div class="page"><?php echo $this->_tpl_vars['page']; ?>
</div>
		</div>
	</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
</body>
</html>