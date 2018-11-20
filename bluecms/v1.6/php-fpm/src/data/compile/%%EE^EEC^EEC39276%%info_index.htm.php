<?php /* Smarty version 2.6.22, created on 2018-11-20 12:14:31
         compiled from info_index.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'info_index.htm', 31, false),array('block', 'dynamic', 'info_index.htm', 84, false),)), $this); ?>
<?php $this->_cache_serials['/www/data/compile//%%EE^EEC^EEC39276%%info_index.htm.inc'] = 'dd40bf32419e6b1234f01f54b706e70d'; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<meta name="Keywords" content="<?php echo $this->_tpl_vars['cat_info']['keywords']; ?>
" />
<meta name="Description" content="<?php echo $this->_tpl_vars['cat_info']['description']; ?>
" />
<title>分类信息 - <?php echo $this->_tpl_vars['site_name']; ?>
 - Powered by BlueCMS</title>
<link href="templates/default/css/index.css" rel="stylesheet" type="text/css" />
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
	<div id="content">
		<div id="content_left">
			<div class="f_content">
				<div id="new_infos">
					<div class="title1">最新信息</div>
					<div class="content1">
						<ul><?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['info_arr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['n']['show'] = true;
$this->_sections['n']['max'] = $this->_sections['n']['loop'];
$this->_sections['n']['step'] = 1;
$this->_sections['n']['start'] = $this->_sections['n']['step'] > 0 ? 0 : $this->_sections['n']['loop']-1;
if ($this->_sections['n']['show']) {
    $this->_sections['n']['total'] = $this->_sections['n']['loop'];
    if ($this->_sections['n']['total'] == 0)
        $this->_sections['n']['show'] = false;
} else
    $this->_sections['n']['total'] = 0;
if ($this->_sections['n']['show']):

            for ($this->_sections['n']['index'] = $this->_sections['n']['start'], $this->_sections['n']['iteration'] = 1;
                 $this->_sections['n']['iteration'] <= $this->_sections['n']['total'];
                 $this->_sections['n']['index'] += $this->_sections['n']['step'], $this->_sections['n']['iteration']++):
$this->_sections['n']['rownum'] = $this->_sections['n']['iteration'];
$this->_sections['n']['index_prev'] = $this->_sections['n']['index'] - $this->_sections['n']['step'];
$this->_sections['n']['index_next'] = $this->_sections['n']['index'] + $this->_sections['n']['step'];
$this->_sections['n']['first']      = ($this->_sections['n']['iteration'] == 1);
$this->_sections['n']['last']       = ($this->_sections['n']['iteration'] == $this->_sections['n']['total']);
?>
							<li><a href="<?php echo $this->_tpl_vars['info_arr'][$this->_sections['n']['index']]['url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['info_arr'][$this->_sections['n']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 20) : smarty_modifier_mb_substr($_tmp, 0, 20)); ?>
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
				<div class="search">
					<div class="title1">搜索</div>
					<div class="s_content1">
						<form name="search_form" action="search.php" method="post">
							<div><select name="cid" id="cid" class="form"><option value="">请选择栏目</option><?php echo $this->_tpl_vars['cat_option']; ?>
</select></div>
							<div><select name="aid" id="aid" class="form"><option value="">请选择地区</option><?php echo $this->_tpl_vars['area_option']; ?>
</select></div>
							<div><input type="text" size="30" name="keywords" id="keywords" class="form" /></div>
							<div><input type="image" src="templates/default/images/btn_search.gif" class="form" value="搜索" alt="搜索" id="search_sub" /></div>
							</form>
					</div>
				</div>
			</div>
			<div class="head_line tbox">
				<div class="title1"><span class="u_rec"><a href="ann.php?cid=2">我上头条</a></span>分类头条</div>
				<div class="content1">
					<ul><?php unset($this->_sections['h']);
$this->_sections['h']['name'] = 'h';
$this->_sections['h']['loop'] = is_array($_loop=$this->_tpl_vars['head_line_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['h']['show'] = true;
$this->_sections['h']['max'] = $this->_sections['h']['loop'];
$this->_sections['h']['step'] = 1;
$this->_sections['h']['start'] = $this->_sections['h']['step'] > 0 ? 0 : $this->_sections['h']['loop']-1;
if ($this->_sections['h']['show']) {
    $this->_sections['h']['total'] = $this->_sections['h']['loop'];
    if ($this->_sections['h']['total'] == 0)
        $this->_sections['h']['show'] = false;
} else
    $this->_sections['h']['total'] = 0;
if ($this->_sections['h']['show']):

            for ($this->_sections['h']['index'] = $this->_sections['h']['start'], $this->_sections['h']['iteration'] = 1;
                 $this->_sections['h']['iteration'] <= $this->_sections['h']['total'];
                 $this->_sections['h']['index'] += $this->_sections['h']['step'], $this->_sections['h']['iteration']++):
$this->_sections['h']['rownum'] = $this->_sections['h']['iteration'];
$this->_sections['h']['index_prev'] = $this->_sections['h']['index'] - $this->_sections['h']['step'];
$this->_sections['h']['index_next'] = $this->_sections['h']['index'] + $this->_sections['h']['step'];
$this->_sections['h']['first']      = ($this->_sections['h']['iteration'] == 1);
$this->_sections['h']['last']       = ($this->_sections['h']['iteration'] == $this->_sections['h']['total']);
?>
						<li><a href="<?php echo $this->_tpl_vars['head_line_list'][$this->_sections['h']['index']]['url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['head_line_list'][$this->_sections['h']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 20) : smarty_modifier_mb_substr($_tmp, 0, 20)); ?>
</a></li>
						<?php endfor; endif; ?>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="pic_info tbox" >
				<div class="title1">
					<div>图文信息</div>
					<div></div>
				</div>
				<div class="content1">
					<div class="content_pic">
						<ul><?php unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($_loop=$this->_tpl_vars['info_pic']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['p']['show'] = true;
$this->_sections['p']['max'] = $this->_sections['p']['loop'];
$this->_sections['p']['step'] = 1;
$this->_sections['p']['start'] = $this->_sections['p']['step'] > 0 ? 0 : $this->_sections['p']['loop']-1;
if ($this->_sections['p']['show']) {
    $this->_sections['p']['total'] = $this->_sections['p']['loop'];
    if ($this->_sections['p']['total'] == 0)
        $this->_sections['p']['show'] = false;
} else
    $this->_sections['p']['total'] = 0;
if ($this->_sections['p']['show']):

            for ($this->_sections['p']['index'] = $this->_sections['p']['start'], $this->_sections['p']['iteration'] = 1;
                 $this->_sections['p']['iteration'] <= $this->_sections['p']['total'];
                 $this->_sections['p']['index'] += $this->_sections['p']['step'], $this->_sections['p']['iteration']++):
$this->_sections['p']['rownum'] = $this->_sections['p']['iteration'];
$this->_sections['p']['index_prev'] = $this->_sections['p']['index'] - $this->_sections['p']['step'];
$this->_sections['p']['index_next'] = $this->_sections['p']['index'] + $this->_sections['p']['step'];
$this->_sections['p']['first']      = ($this->_sections['p']['iteration'] == 1);
$this->_sections['p']['last']       = ($this->_sections['p']['iteration'] == $this->_sections['p']['total']);
?>
							<li><a href="<?php echo $this->_tpl_vars['info_pic'][$this->_sections['p']['index']]['url']; ?>
"><img src="<?php echo $this->_tpl_vars['info_pic'][$this->_sections['p']['index']]['lit_pic']; ?>
" border="0" /></a></li>
							<?php endfor; endif; ?>
						</ul>
					</div>
					<div class="content_title">
						<ul><?php unset($this->_sections['t']);
$this->_sections['t']['name'] = 't';
$this->_sections['t']['loop'] = is_array($_loop=$this->_tpl_vars['info_title']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['t']['show'] = true;
$this->_sections['t']['max'] = $this->_sections['t']['loop'];
$this->_sections['t']['step'] = 1;
$this->_sections['t']['start'] = $this->_sections['t']['step'] > 0 ? 0 : $this->_sections['t']['loop']-1;
if ($this->_sections['t']['show']) {
    $this->_sections['t']['total'] = $this->_sections['t']['loop'];
    if ($this->_sections['t']['total'] == 0)
        $this->_sections['t']['show'] = false;
} else
    $this->_sections['t']['total'] = 0;
if ($this->_sections['t']['show']):

            for ($this->_sections['t']['index'] = $this->_sections['t']['start'], $this->_sections['t']['iteration'] = 1;
                 $this->_sections['t']['iteration'] <= $this->_sections['t']['total'];
                 $this->_sections['t']['index'] += $this->_sections['t']['step'], $this->_sections['t']['iteration']++):
$this->_sections['t']['rownum'] = $this->_sections['t']['iteration'];
$this->_sections['t']['index_prev'] = $this->_sections['t']['index'] - $this->_sections['t']['step'];
$this->_sections['t']['index_next'] = $this->_sections['t']['index'] + $this->_sections['t']['step'];
$this->_sections['t']['first']      = ($this->_sections['t']['iteration'] == 1);
$this->_sections['t']['last']       = ($this->_sections['t']['iteration'] == $this->_sections['t']['total']);
?>
							<li><span class="date"><?php echo $this->_tpl_vars['info_title'][$this->_sections['t']['index']]['pub_date']; ?>
</span><a href="<?php echo $this->_tpl_vars['info_title'][$this->_sections['t']['index']]['url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['info_title'][$this->_sections['t']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 11) : smarty_modifier_mb_substr($_tmp, 0, 11)); ?>
</a></li>
							<?php endfor; endif; ?>
						</ul>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<?php if ($this->caching && !$this->_cache_including): echo '{nocache:dd40bf32419e6b1234f01f54b706e70d#0}'; endif;$this->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['index_info']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:dd40bf32419e6b1234f01f54b706e70d#0}'; endif;?>
			<div class="clear"></div>
			
		</div>
		<div id="content_right">
				<div id="r1">
					<div class="r1_top">免费发布您的生活信息，欢迎使用！</div>
					<div class="r1_middle">
						<div class="m1 m"><a href="publish.php">发布信息</a></div>
						<div class="m2 m"><a href="ann.php?cid=2">付费推广</a></div>
						<div class="m3 m"><a href="ann.php?cid=3">帮助中心</a></div>
						<div class="m4 m"><a href="guest_book.php">留言建议</a></div>
					</div>
					<div class="clear"></div>
					<div class="r1_bottom">
						<ul>
							<li class="p4">还不是会员？马上 <a href="user.php?act=reg">注册会员</a></li>
						</ul>
					</div>
				</div>
				<div class="rec_info tbox">
					<div class="title1"><span class="u_rec"><a href="ann.php?cid=2">我要推荐</a></span>推荐信息</div>
					<div class="content1">
						<ul><?php unset($this->_sections['info']);
$this->_sections['info']['name'] = 'info';
$this->_sections['info']['loop'] = is_array($_loop=$this->_tpl_vars['rec_info']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['info']['show'] = true;
$this->_sections['info']['max'] = $this->_sections['info']['loop'];
$this->_sections['info']['step'] = 1;
$this->_sections['info']['start'] = $this->_sections['info']['step'] > 0 ? 0 : $this->_sections['info']['loop']-1;
if ($this->_sections['info']['show']) {
    $this->_sections['info']['total'] = $this->_sections['info']['loop'];
    if ($this->_sections['info']['total'] == 0)
        $this->_sections['info']['show'] = false;
} else
    $this->_sections['info']['total'] = 0;
if ($this->_sections['info']['show']):

            for ($this->_sections['info']['index'] = $this->_sections['info']['start'], $this->_sections['info']['iteration'] = 1;
                 $this->_sections['info']['iteration'] <= $this->_sections['info']['total'];
                 $this->_sections['info']['index'] += $this->_sections['info']['step'], $this->_sections['info']['iteration']++):
$this->_sections['info']['rownum'] = $this->_sections['info']['iteration'];
$this->_sections['info']['index_prev'] = $this->_sections['info']['index'] - $this->_sections['info']['step'];
$this->_sections['info']['index_next'] = $this->_sections['info']['index'] + $this->_sections['info']['step'];
$this->_sections['info']['first']      = ($this->_sections['info']['iteration'] == 1);
$this->_sections['info']['last']       = ($this->_sections['info']['iteration'] == $this->_sections['info']['total']);
?>
							<li>
								<a href="<?php echo $this->_tpl_vars['rec_info'][$this->_sections['info']['index']]['url']; ?>
"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['rec_info'][$this->_sections['info']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 16) : smarty_modifier_mb_substr($_tmp, 0, 16)); ?>
</b></a>
								<div class="info_con"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec_info'][$this->_sections['info']['index']]['content'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 30) : smarty_modifier_mb_substr($_tmp, 0, 30)); ?>
</div>
							</li>
							<?php endfor; endif; ?>
						</ul>
					</div>
					<div class="clear"></div>
				<div class="t_l"></div>
				<div class="t_r"></div>
				<div class="b_l"></div>
				<div class="b_r"></div>
				</div>
				<div class="hot_info tbox">
					<div class="title1">热门信息</div>
					<div class="content1">
						<ul><?php unset($this->_sections['h']);
$this->_sections['h']['name'] = 'h';
$this->_sections['h']['loop'] = is_array($_loop=$this->_tpl_vars['hot_info']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['h']['show'] = true;
$this->_sections['h']['max'] = $this->_sections['h']['loop'];
$this->_sections['h']['step'] = 1;
$this->_sections['h']['start'] = $this->_sections['h']['step'] > 0 ? 0 : $this->_sections['h']['loop']-1;
if ($this->_sections['h']['show']) {
    $this->_sections['h']['total'] = $this->_sections['h']['loop'];
    if ($this->_sections['h']['total'] == 0)
        $this->_sections['h']['show'] = false;
} else
    $this->_sections['h']['total'] = 0;
if ($this->_sections['h']['show']):

            for ($this->_sections['h']['index'] = $this->_sections['h']['start'], $this->_sections['h']['iteration'] = 1;
                 $this->_sections['h']['iteration'] <= $this->_sections['h']['total'];
                 $this->_sections['h']['index'] += $this->_sections['h']['step'], $this->_sections['h']['iteration']++):
$this->_sections['h']['rownum'] = $this->_sections['h']['iteration'];
$this->_sections['h']['index_prev'] = $this->_sections['h']['index'] - $this->_sections['h']['step'];
$this->_sections['h']['index_next'] = $this->_sections['h']['index'] + $this->_sections['h']['step'];
$this->_sections['h']['first']      = ($this->_sections['h']['iteration'] == 1);
$this->_sections['h']['last']       = ($this->_sections['h']['iteration'] == $this->_sections['h']['total']);
?>
							<li><a href="<?php echo $this->_tpl_vars['hot_info'][$this->_sections['h']['index']]['url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['hot_info'][$this->_sections['h']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 20) : smarty_modifier_mb_substr($_tmp, 0, 20)); ?>
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
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
</body>
</html>