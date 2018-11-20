<?php /* Smarty version 2.6.22, created on 2018-11-20 12:26:04
         compiled from ann_list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'ann_list.htm', 62, false),array('modifier', 'mb_substr', 'ann_list.htm', 88, false),)), $this); ?>
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
	$(function(){
		$("#cat ul li:nth-child(2n)").addClass("color");
	});
</script>
<style type="text/css">
.color{background-color:#F8F8F8;}
</style>
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
	<div class="main">
		<div class="main_l">
			<div class="active_act">
				您所在的位置：<a href="./">首页</a>&raquo; <?php echo $this->_tpl_vars['current_act']; ?>

			</div>
			<div id="ann_list">
				<ul><?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['ann_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<li>
						<div class="ann_title clear">
						<span><?php echo ((is_array($_tmp=$this->_tpl_vars['ann_list'][$this->_sections['a']['index']]['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
 发布</span>
							<h1><a href="ann.php?ann_id=<?php echo $this->_tpl_vars['ann_list'][$this->_sections['a']['index']]['ann_id']; ?>
"><?php echo $this->_tpl_vars['ann_list'][$this->_sections['a']['index']]['title']; ?>
</a></h1>
						</div>
						<div class="ann_content">
							<?php echo $this->_tpl_vars['ann_list'][$this->_sections['a']['index']]['content']; ?>

						</div>
						<div class="ann_bottom">分类：<a href="ann.php?cid=<?php echo $this->_tpl_vars['ann_list'][$this->_sections['a']['index']]['cid']; ?>
"><?php echo $this->_tpl_vars['ann_list'][$this->_sections['a']['index']]['cat_name']; ?>
</a> <a href="ann.php?ann_id=<?php echo $this->_tpl_vars['ann_list'][$this->_sections['a']['index']]['ann_id']; ?>
">阅读全文</a>
						</div>
					</li>
					<?php endfor; else: ?>
					<li>&nbsp;&nbsp;还没有发布信息</li>
					<?php endif; ?>
				</ul>
				<div><?php echo $this->_tpl_vars['page']; ?>
</div>
			</div>

		</div>
		<div class="main_r">
			<div id="hot_ann">
				<h2>
					<span class="l_top"></span>
					<p class="m_title">热门公告</p>
					<span class="r_top"></span>
				</h2>
				<div class="content1">
					<ul><?php unset($this->_sections['h_ann']);
$this->_sections['h_ann']['name'] = 'h_ann';
$this->_sections['h_ann']['loop'] = is_array($_loop=$this->_tpl_vars['hot_ann']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['h_ann']['show'] = true;
$this->_sections['h_ann']['max'] = $this->_sections['h_ann']['loop'];
$this->_sections['h_ann']['step'] = 1;
$this->_sections['h_ann']['start'] = $this->_sections['h_ann']['step'] > 0 ? 0 : $this->_sections['h_ann']['loop']-1;
if ($this->_sections['h_ann']['show']) {
    $this->_sections['h_ann']['total'] = $this->_sections['h_ann']['loop'];
    if ($this->_sections['h_ann']['total'] == 0)
        $this->_sections['h_ann']['show'] = false;
} else
    $this->_sections['h_ann']['total'] = 0;
if ($this->_sections['h_ann']['show']):

            for ($this->_sections['h_ann']['index'] = $this->_sections['h_ann']['start'], $this->_sections['h_ann']['iteration'] = 1;
                 $this->_sections['h_ann']['iteration'] <= $this->_sections['h_ann']['total'];
                 $this->_sections['h_ann']['index'] += $this->_sections['h_ann']['step'], $this->_sections['h_ann']['iteration']++):
$this->_sections['h_ann']['rownum'] = $this->_sections['h_ann']['iteration'];
$this->_sections['h_ann']['index_prev'] = $this->_sections['h_ann']['index'] - $this->_sections['h_ann']['step'];
$this->_sections['h_ann']['index_next'] = $this->_sections['h_ann']['index'] + $this->_sections['h_ann']['step'];
$this->_sections['h_ann']['first']      = ($this->_sections['h_ann']['iteration'] == 1);
$this->_sections['h_ann']['last']       = ($this->_sections['h_ann']['iteration'] == $this->_sections['h_ann']['total']);
?>
						<li><a href="ann.php?ann_id=<?php echo $this->_tpl_vars['hot_ann'][$this->_sections['h_ann']['index']]['ann_id']; ?>
" <?php if ($this->_tpl_vars['hot_ann'][$this->_sections['h_ann']['index']]['color']): ?>style="<?php echo $this->_tpl_vars['hot_ann'][$this->_sections['h_ann']['index']]['color']; ?>
"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['hot_ann'][$this->_sections['h_ann']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 18) : smarty_modifier_mb_substr($_tmp, 0, 18)); ?>
</a></li>
						<?php endfor; endif; ?>
					</ul>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
					<div class="t_l"></div>
					<div class="t_r"></div>
					<div class="b_l"></div>
					<div class="b_r"></div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
</body>
</html>