<?php /* Smarty version 2.6.22, created on 2018-11-20 09:49:37
         compiled from news_list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'news_list.htm', 31, false),array('modifier', 'date_format', 'news_list.htm', 37, false),array('block', 'dynamic', 'news_list.htm', 38, false),)), $this); ?>
<?php $this->_cache_serials['/www/data/compile//%%4A^4AE^4AEB1A18%%news_list.htm.inc'] = '9a90be255c5638bb1f790612ebc891ee'; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<meta name="Keywords" content="<?php echo $this->_tpl_vars['arc_cat']['keywords']; ?>
" />
<meta name="Description" content="<?php echo $this->_tpl_vars['arc_cat']['description']; ?>
" />
<title><?php if ($this->_tpl_vars['arc_cat']['cat_name']): ?><?php echo $this->_tpl_vars['arc_cat']['cat_name']; ?>
<?php elseif ($this->_tpl_vars['arc_cat']['title']): ?><?php echo $this->_tpl_vars['arc_cat']['title']; ?>
<?php else: ?>本地新闻<?php endif; ?> - <?php echo $this->_tpl_vars['site_name']; ?>
 - Powered by BlueCMS</title>
<link href="templates/default/css/category.css" rel="stylesheet" type="text/css" />
<link href="templates/default/css/common.css" rel="stylesheet" type="text/css" />
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
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="main">
		<div class="main_l">
			<div class="active_act">
				您所在的位置：<a href="./">首页</a> <?php if ($this->_tpl_vars['arc_cat']['cat_name']): ?>&raquo; <?php echo $this->_tpl_vars['arc_cat']['cat_name']; ?>
<?php else: ?>&raquo; 本地新闻<?php endif; ?>
			</div>
			<div id="news_list">
				<ul><?php unset($this->_sections['news']);
$this->_sections['news']['name'] = 'news';
$this->_sections['news']['loop'] = is_array($_loop=$this->_tpl_vars['news_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['news']['show'] = true;
$this->_sections['news']['max'] = $this->_sections['news']['loop'];
$this->_sections['news']['step'] = 1;
$this->_sections['news']['start'] = $this->_sections['news']['step'] > 0 ? 0 : $this->_sections['news']['loop']-1;
if ($this->_sections['news']['show']) {
    $this->_sections['news']['total'] = $this->_sections['news']['loop'];
    if ($this->_sections['news']['total'] == 0)
        $this->_sections['news']['show'] = false;
} else
    $this->_sections['news']['total'] = 0;
if ($this->_sections['news']['show']):

            for ($this->_sections['news']['index'] = $this->_sections['news']['start'], $this->_sections['news']['iteration'] = 1;
                 $this->_sections['news']['iteration'] <= $this->_sections['news']['total'];
                 $this->_sections['news']['index'] += $this->_sections['news']['step'], $this->_sections['news']['iteration']++):
$this->_sections['news']['rownum'] = $this->_sections['news']['iteration'];
$this->_sections['news']['index_prev'] = $this->_sections['news']['index'] - $this->_sections['news']['step'];
$this->_sections['news']['index_next'] = $this->_sections['news']['index'] + $this->_sections['news']['step'];
$this->_sections['news']['first']      = ($this->_sections['news']['iteration'] == 1);
$this->_sections['news']['last']       = ($this->_sections['news']['iteration'] == $this->_sections['news']['total']);
?>
					<li>
						<a class="title" href="<?php echo $this->_tpl_vars['news_list'][$this->_sections['news']['index']]['url']; ?>
"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['news_list'][$this->_sections['news']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 24) : smarty_modifier_mb_substr($_tmp, 0, 24)); ?>
</b></a><br/>
						<p class="news_content">
							<?php echo $this->_tpl_vars['news_list'][$this->_sections['news']['index']]['descript']; ?>

						</p>
						<div class="news">
							作者：<span><?php echo $this->_tpl_vars['news_list'][$this->_sections['news']['index']]['author']; ?>
</span>
							发表于：<span><?php echo ((is_array($_tmp=$this->_tpl_vars['news_list'][$this->_sections['news']['index']]['pub_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</span>
							<?php if ($this->caching && !$this->_cache_including): echo '{nocache:9a90be255c5638bb1f790612ebc891ee#0}'; endif;$this->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>点击：<span><?php echo $this->_tpl_vars['news_list'][$this->_sections['news']['index']]['click']; ?>
</span>
							评论：<span><?php echo $this->_tpl_vars['news_list'][$this->_sections['news']['index']]['comment']; ?>
</span><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:9a90be255c5638bb1f790612ebc891ee#0}'; endif;?>
						</div>
						<div class="all"><a href="<?php echo $this->_tpl_vars['news_list'][$this->_sections['news']['index']]['url']; ?>
">查看全文…</a></div>
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
			<div id="cat">
				<div class="title1">分类列表</div>
				<div class="content1">
					<ul>
					<?php unset($this->_sections['cat']);
$this->_sections['cat']['name'] = 'cat';
$this->_sections['cat']['loop'] = is_array($_loop=$this->_tpl_vars['arc_cat_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cat']['show'] = true;
$this->_sections['cat']['max'] = $this->_sections['cat']['loop'];
$this->_sections['cat']['step'] = 1;
$this->_sections['cat']['start'] = $this->_sections['cat']['step'] > 0 ? 0 : $this->_sections['cat']['loop']-1;
if ($this->_sections['cat']['show']) {
    $this->_sections['cat']['total'] = $this->_sections['cat']['loop'];
    if ($this->_sections['cat']['total'] == 0)
        $this->_sections['cat']['show'] = false;
} else
    $this->_sections['cat']['total'] = 0;
if ($this->_sections['cat']['show']):

            for ($this->_sections['cat']['index'] = $this->_sections['cat']['start'], $this->_sections['cat']['iteration'] = 1;
                 $this->_sections['cat']['iteration'] <= $this->_sections['cat']['total'];
                 $this->_sections['cat']['index'] += $this->_sections['cat']['step'], $this->_sections['cat']['iteration']++):
$this->_sections['cat']['rownum'] = $this->_sections['cat']['iteration'];
$this->_sections['cat']['index_prev'] = $this->_sections['cat']['index'] - $this->_sections['cat']['step'];
$this->_sections['cat']['index_next'] = $this->_sections['cat']['index'] + $this->_sections['cat']['step'];
$this->_sections['cat']['first']      = ($this->_sections['cat']['iteration'] == 1);
$this->_sections['cat']['last']       = ($this->_sections['cat']['iteration'] == $this->_sections['cat']['total']);
?>
						<li><a href="<?php echo $this->_tpl_vars['arc_cat_list'][$this->_sections['cat']['index']]['url']; ?>
"><?php echo $this->_tpl_vars['arc_cat_list'][$this->_sections['cat']['index']]['cat_name']; ?>
</a></li>
					<?php endfor; endif; ?>
					</ul>
				</div>
				<div class="clear"></div>
					<div class="t_l"></div>
					<div class="t_r"></div>
					<div class="b_l"></div>
					<div class="b_r"></div>
			</div>
			<div id="rec_news">
				<div class="title1">新闻推荐</div>
				<div class="content1">
					<ul>
					<?php unset($this->_sections['news']);
$this->_sections['news']['name'] = 'news';
$this->_sections['news']['loop'] = is_array($_loop=$this->_tpl_vars['rec_news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['news']['show'] = true;
$this->_sections['news']['max'] = $this->_sections['news']['loop'];
$this->_sections['news']['step'] = 1;
$this->_sections['news']['start'] = $this->_sections['news']['step'] > 0 ? 0 : $this->_sections['news']['loop']-1;
if ($this->_sections['news']['show']) {
    $this->_sections['news']['total'] = $this->_sections['news']['loop'];
    if ($this->_sections['news']['total'] == 0)
        $this->_sections['news']['show'] = false;
} else
    $this->_sections['news']['total'] = 0;
if ($this->_sections['news']['show']):

            for ($this->_sections['news']['index'] = $this->_sections['news']['start'], $this->_sections['news']['iteration'] = 1;
                 $this->_sections['news']['iteration'] <= $this->_sections['news']['total'];
                 $this->_sections['news']['index'] += $this->_sections['news']['step'], $this->_sections['news']['iteration']++):
$this->_sections['news']['rownum'] = $this->_sections['news']['iteration'];
$this->_sections['news']['index_prev'] = $this->_sections['news']['index'] - $this->_sections['news']['step'];
$this->_sections['news']['index_next'] = $this->_sections['news']['index'] + $this->_sections['news']['step'];
$this->_sections['news']['first']      = ($this->_sections['news']['iteration'] == 1);
$this->_sections['news']['last']       = ($this->_sections['news']['iteration'] == $this->_sections['news']['total']);
?>
						<li><a href="<?php echo $this->_tpl_vars['rec_news'][$this->_sections['news']['index']]['url']; ?>
" <?php if ($this->_tpl_vars['rec_news'][$this->_sections['news']['index']]['color']): ?>style="<?php echo $this->_tpl_vars['rec_news'][$this->_sections['news']['index']]['color']; ?>
"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['rec_news'][$this->_sections['news']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 18) : smarty_modifier_mb_substr($_tmp, 0, 18)); ?>
</a></li>
					<?php endfor; endif; ?>
					</ul>
				</div>
				<div class="clear"></div>
					<div class="t_l"></div>
					<div class="t_r"></div>
					<div class="b_l"></div>
					<div class="b_r"></div>
			</div>
			<div id="hot_news">
				<div class="title1">热点导读</div>
				<div class="content1">
					<ul><?php unset($this->_sections['h_news']);
$this->_sections['h_news']['name'] = 'h_news';
$this->_sections['h_news']['loop'] = is_array($_loop=$this->_tpl_vars['hot_news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['h_news']['show'] = true;
$this->_sections['h_news']['max'] = $this->_sections['h_news']['loop'];
$this->_sections['h_news']['step'] = 1;
$this->_sections['h_news']['start'] = $this->_sections['h_news']['step'] > 0 ? 0 : $this->_sections['h_news']['loop']-1;
if ($this->_sections['h_news']['show']) {
    $this->_sections['h_news']['total'] = $this->_sections['h_news']['loop'];
    if ($this->_sections['h_news']['total'] == 0)
        $this->_sections['h_news']['show'] = false;
} else
    $this->_sections['h_news']['total'] = 0;
if ($this->_sections['h_news']['show']):

            for ($this->_sections['h_news']['index'] = $this->_sections['h_news']['start'], $this->_sections['h_news']['iteration'] = 1;
                 $this->_sections['h_news']['iteration'] <= $this->_sections['h_news']['total'];
                 $this->_sections['h_news']['index'] += $this->_sections['h_news']['step'], $this->_sections['h_news']['iteration']++):
$this->_sections['h_news']['rownum'] = $this->_sections['h_news']['iteration'];
$this->_sections['h_news']['index_prev'] = $this->_sections['h_news']['index'] - $this->_sections['h_news']['step'];
$this->_sections['h_news']['index_next'] = $this->_sections['h_news']['index'] + $this->_sections['h_news']['step'];
$this->_sections['h_news']['first']      = ($this->_sections['h_news']['iteration'] == 1);
$this->_sections['h_news']['last']       = ($this->_sections['h_news']['iteration'] == $this->_sections['h_news']['total']);
?>
						<li><a href="<?php echo $this->_tpl_vars['hot_news'][$this->_sections['h_news']['index']]['url']; ?>
" <?php if ($this->_tpl_vars['hot_news'][$this->_sections['news']['index']]['color']): ?>style="<?php echo $this->_tpl_vars['hot_news'][$this->_sections['news']['index']]['color']; ?>
"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['hot_news'][$this->_sections['h_news']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 18) : smarty_modifier_mb_substr($_tmp, 0, 18)); ?>
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