<?php /* Smarty version 2.6.22, created on 2018-11-20 12:17:48
         compiled from news.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'news.htm', 31, false),array('modifier', 'mb_substr', 'news.htm', 40, false),array('block', 'dynamic', 'news.htm', 31, false),)), $this); ?>
<?php $this->_cache_serials['/www/data/compile//%%AF^AFE^AFE14A46%%news.htm.inc'] = 'ebc4e3b58dd9b7260586b9f1e38aacf4'; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<meta name="Keywords" content="<?php echo $this->_tpl_vars['cat_info']['keywords']; ?>
" />
<meta name="Description" content="<?php echo $this->_tpl_vars['cat_info']['description']; ?>
" />
<title><?php echo $this->_tpl_vars['location']['name2']; ?>
 - <?php echo $this->_tpl_vars['site_name']; ?>
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
<div id="wapper">
	<div class="main">
		<div class="main_l">
			<div class="active_act">
				您所在的位置：<a href="./">首页</a> &raquo; <a href="<?php echo $this->_tpl_vars['location']['url1']; ?>
"><?php echo $this->_tpl_vars['location']['name1']; ?>
</a> &raquo; <?php echo $this->_tpl_vars['location']['name2']; ?>

			</div>
			<div id="news">
				<div id="title"><h2><?php echo $this->_tpl_vars['news']['title']; ?>
</h2></div>
				<div id="news_info"><span id="source">来源：<?php echo $this->_tpl_vars['news']['source']; ?>
</span><span id="pub_date">发布时间：<?php echo ((is_array($_tmp=$this->_tpl_vars['news']['pub_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</span><span id="author">作者：<?php echo $this->_tpl_vars['news']['author']; ?>
</span><?php if ($this->caching && !$this->_cache_including): echo '{nocache:ebc4e3b58dd9b7260586b9f1e38aacf4#0}'; endif;$this->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><span id="click">点击：<?php echo $this->_tpl_vars['news']['click']; ?>
</span><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:ebc4e3b58dd9b7260586b9f1e38aacf4#0}'; endif;?></div>
				<div id="content"><?php echo $this->_tpl_vars['news']['content']; ?>
</div>
			</div>
		<?php if ($this->caching && !$this->_cache_including): echo '{nocache:ebc4e3b58dd9b7260586b9f1e38aacf4#1}'; endif;$this->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><div class="comment"><form action="comment.php?act=send" name="comment_form" method="post">
		<div class="title1">评论</div>
		<div id="com_count"><?php if ($this->_tpl_vars['news']['comment'] == 0): ?>共有 0 条评论<?php else: ?><a href="comment.php?id=<?php echo $this->_tpl_vars['news']['id']; ?>
&type=1">共有<?php echo $this->_tpl_vars['news']['comment']; ?>
条评论</a><?php endif; ?></div>
		<div class="content1"><?php unset($this->_sections['com']);
$this->_sections['com']['name'] = 'com';
$this->_sections['com']['loop'] = is_array($_loop=$this->_tpl_vars['comment_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['com']['show'] = true;
$this->_sections['com']['max'] = $this->_sections['com']['loop'];
$this->_sections['com']['step'] = 1;
$this->_sections['com']['start'] = $this->_sections['com']['step'] > 0 ? 0 : $this->_sections['com']['loop']-1;
if ($this->_sections['com']['show']) {
    $this->_sections['com']['total'] = $this->_sections['com']['loop'];
    if ($this->_sections['com']['total'] == 0)
        $this->_sections['com']['show'] = false;
} else
    $this->_sections['com']['total'] = 0;
if ($this->_sections['com']['show']):

            for ($this->_sections['com']['index'] = $this->_sections['com']['start'], $this->_sections['com']['iteration'] = 1;
                 $this->_sections['com']['iteration'] <= $this->_sections['com']['total'];
                 $this->_sections['com']['index'] += $this->_sections['com']['step'], $this->_sections['com']['iteration']++):
$this->_sections['com']['rownum'] = $this->_sections['com']['iteration'];
$this->_sections['com']['index_prev'] = $this->_sections['com']['index'] - $this->_sections['com']['step'];
$this->_sections['com']['index_next'] = $this->_sections['com']['index'] + $this->_sections['com']['step'];
$this->_sections['com']['first']      = ($this->_sections['com']['iteration'] == 1);
$this->_sections['com']['last']       = ($this->_sections['com']['iteration'] == $this->_sections['com']['total']);
?>
				<div class="com">
					<div class="com_title"><img src="images/mood/mood-<?php echo $this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['mood']; ?>
.gif" border="0" />&nbsp;<?php if ($this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['user_id'] <> 0): ?><?php echo $this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['user_name']; ?>
<?php else: ?>游客<?php endif; ?>&nbsp;于<?php echo ((is_array($_tmp=$this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['pub_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
&nbsp;说到：</div>
						<div class="com_content" id='c_content<?php echo $this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['com_id']; ?>
' onclick="show_detail(<?php echo $this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['com_id']; ?>
);"><?php echo ((is_array($_tmp=$this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['content'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 50) : smarty_modifier_mb_substr($_tmp, 0, 50)); ?>
</div>
					<div style="display:none;cursor:hand;" class="com_content" id='com_detail<?php echo $this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['com_id']; ?>
' onclick="show_detail(<?php echo $this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['com_id']; ?>
);"><?php echo $this->_tpl_vars['comment_list'][$this->_sections['com']['index']]['content']; ?>
</div>
				</div><?php endfor; endif; ?>
			<div id="send_comment1"><?php if ($this->_tpl_vars['user_name']): ?>您好，<a href="user.php"><?php echo $this->_tpl_vars['user_name']; ?>
</a><?php else: ?>您现在是匿名发表！ <a href="user.php?act=login&from=<?php echo $this->_tpl_vars['url']; ?>
">登录</a>&nbsp;|&nbsp;<a href="user.php?act=reg&from=<?php echo $this->_tpl_vars['url']; ?>
">注册</a><?php endif; ?></div>
			<div id="mood">
				<ul>
					<li><input type="radio" name='mood' value='1'/><img src="images/mood/mood-1.gif" /></li>
					<li><input type="radio" name='mood' value='2'/><img src="images/mood/mood-2.gif" /></li>
					<li><input type="radio" name='mood' value='3'/><img src="images/mood/mood-3.gif" /></li>
					<li><input type="radio" name='mood' value='4'/><img src="images/mood/mood-4.gif" /></li>
					<li><input type="radio" name='mood' value='5'/><img src="images/mood/mood-5.gif" /></li>
					<li><input type="radio" name='mood' value='6' checked="1" /><img src="images/mood/mood-6.gif" /></li>
					<li><input type="radio" name='mood' value='7'/><img src="images/mood/mood-7.gif" /></li>
				</ul>
			</div>
			<div class="clear"></div>
			<div>
				<textarea rows="6" cols="50" name="comment"></textarea><br/><br/>
				<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['news']['id']; ?>
" />
				<input type="hidden" name="type" value="1" />
				<input type="submit" name="submit" value="提交评论" />
			</div>
		</div></form>
	</div><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:ebc4e3b58dd9b7260586b9f1e38aacf4#1}'; endif;?>
		</div>
		<div class="main_r">
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
<script type="text/javascript">
	function show_detail(i){
		$('#c_content'+i).toggle();
		$('#com_detail'+i).toggle();
	}
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
</body>
</html>