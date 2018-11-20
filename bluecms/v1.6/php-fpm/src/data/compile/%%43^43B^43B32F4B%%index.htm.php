<?php /* Smarty version 2.6.22, created on 2018-11-20 09:49:30
         compiled from index.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'index.htm', 67, false),array('block', 'dynamic', 'index.htm', 94, false),)), $this); ?>
<?php $this->_cache_serials['/www/data/compile//%%43^43B^43B32F4B%%index.htm.inc'] = '48245b5c51f9cbd79fc14dfc3f32df63'; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<meta name="Keywords" content="<?php echo $this->_tpl_vars['keywords']; ?>
" />
<meta name="Description" content="<?php echo $this->_tpl_vars['description']; ?>
" />
<title><?php echo $this->_tpl_vars['site_name']; ?>
 - Powered by BlueCMS</title>
<link href="templates/default/css/index.css" rel="stylesheet" type="text/css" />
<link href="templates/default/css/common.css" rel="stylesheet" type="text/css" />
<script src="templates/default/css/jquery.js" type="text/javascript"></script>
<link rel="shortcut icon" href="favicon.ico" />
<script type="text/javascript">
$(document).ready(function(){
		$("#h1").mouseover(function(){
			$("#h1").addClass("current");
			$("#hc_1").attr("style", "display:block;");
			$("#h2").removeClass("current");
			$("#hc_2").attr("style", "display:none;");
		});
		$("#h2").mouseover(function(){
			$("#h2").addClass("current");
			$("#hc_2").attr("style", "display:block;");
			$("#h1").removeClass("current");
			$("#hc_1").attr("style", "display:none;");
		});
});

function show(e){
	var a = [<?php echo $this->_tpl_vars['news_cat_n']; ?>
];
	for(var i=0;i<a.length;i++){
		$(".news_cat"+a[i]).attr("style", "display:none;");
	}
	$(".news_cat"+e).attr("style", "display:block;");
}
</script>
</head>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="wapper">
	<div class="main">
		<div id="m_top">
			<div id="top_l">
				<div id="flash">
				<script type="text/javascript">
				<!--
					var focus_width=280
					var focus_height=190
					var text_height=0
					var swf_height = focus_height+text_height

					var pics = "<?php echo $this->_tpl_vars['pics']; ?>
"
					var links = "<?php echo $this->_tpl_vars['links']; ?>
"
					var texts = '||||'
					document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">');
					document.write('<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="images/flashplay.swf"><param name="quality" value="high"><param name="bgcolor" value="#ffffff">');
					document.write('<param name="menu" value="false"><param name="wmode" value="opaque">');
					document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'">');
					document.write('<embed src="images/flashplay.swf" wmode="opaque" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'" menu="false" bgcolor="#ffffff" quality="high" width="'+ focus_width +'" height="'+ focus_height +'" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
					document.write('</object>');
				-->
				</script>
				</div>
				<div class="news_title"><strong>新闻推荐</strong></div>
				<?php if ($this->_tpl_vars['f_rec_news']): ?>
				<p>
					<strong>
						<a target="_blank" title="<?php echo $this->_tpl_vars['f_rec_news']['title']; ?>
" href="<?php echo $this->_tpl_vars['f_rec_news']['url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['f_rec_news']['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 8) : smarty_modifier_mb_substr($_tmp, 0, 8)); ?>
</a>
					</strong><br/>
					<a target="_blank" title="<?php echo $this->_tpl_vars['f_rec_news']['title']; ?>
" href="<?php echo $this->_tpl_vars['f_rec_news']['url']; ?>
">
						<img alt="<?php echo $this->_tpl_vars['f_rec_news']['title']; ?>
" src="<?php echo $this->_tpl_vars['f_rec_news']['lit_pic']; ?>
"/>
					</a>
					<a target="_blank" title="<?php echo $this->_tpl_vars['f_rec_news']['title']; ?>
" href="<?php echo $this->_tpl_vars['f_rec_news']['url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['f_rec_news']['descript'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 40) : smarty_modifier_mb_substr($_tmp, 0, 40)); ?>
</a>
				</p><?php endif; ?>
				<div class="clear"></div>
				<ul><?php unset($this->_sections['r']);
$this->_sections['r']['name'] = 'r';
$this->_sections['r']['loop'] = is_array($_loop=$this->_tpl_vars['rec_news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['r']['show'] = true;
$this->_sections['r']['max'] = $this->_sections['r']['loop'];
$this->_sections['r']['step'] = 1;
$this->_sections['r']['start'] = $this->_sections['r']['step'] > 0 ? 0 : $this->_sections['r']['loop']-1;
if ($this->_sections['r']['show']) {
    $this->_sections['r']['total'] = $this->_sections['r']['loop'];
    if ($this->_sections['r']['total'] == 0)
        $this->_sections['r']['show'] = false;
} else
    $this->_sections['r']['total'] = 0;
if ($this->_sections['r']['show']):

            for ($this->_sections['r']['index'] = $this->_sections['r']['start'], $this->_sections['r']['iteration'] = 1;
                 $this->_sections['r']['iteration'] <= $this->_sections['r']['total'];
                 $this->_sections['r']['index'] += $this->_sections['r']['step'], $this->_sections['r']['iteration']++):
$this->_sections['r']['rownum'] = $this->_sections['r']['iteration'];
$this->_sections['r']['index_prev'] = $this->_sections['r']['index'] - $this->_sections['r']['step'];
$this->_sections['r']['index_next'] = $this->_sections['r']['index'] + $this->_sections['r']['step'];
$this->_sections['r']['first']      = ($this->_sections['r']['iteration'] == 1);
$this->_sections['r']['last']       = ($this->_sections['r']['iteration'] == $this->_sections['r']['total']);
?>
					<li><a href="<?php echo $this->_tpl_vars['rec_news'][$this->_sections['r']['index']]['url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec_news'][$this->_sections['r']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 18) : smarty_modifier_mb_substr($_tmp, 0, 18)); ?>
</a></li>
					<?php endfor; endif; ?>
				</ul>
			</div>
			<div id="top_m">
					<div class="news_title"><strong>最新新闻</strong></div>
					<a href="<?php echo $this->_tpl_vars['f_news']['url']; ?>
"><h2><?php echo ((is_array($_tmp=$this->_tpl_vars['f_news']['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 18) : smarty_modifier_mb_substr($_tmp, 0, 18)); ?>
</h2></a>
					<ul><?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['news_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<li><a href="<?php echo $this->_tpl_vars['news_list'][$this->_sections['n']['index']]['url']; ?>
"><?php echo $this->_tpl_vars['news_list'][$this->_sections['n']['index']]['title']; ?>
</a></li>
						<?php endfor; endif; ?>
					</ul>
					<div class="news_title"><strong>热点关注</strong></div>
					<ol><?php unset($this->_sections['h']);
$this->_sections['h']['name'] = 'h';
$this->_sections['h']['loop'] = is_array($_loop=$this->_tpl_vars['hot_news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<li><a href="<?php echo $this->_tpl_vars['hot_news'][$this->_sections['h']['index']]['url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['hot_news'][$this->_sections['h']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 18) : smarty_modifier_mb_substr($_tmp, 0, 18)); ?>
</a></li>
						<?php endfor; endif; ?>
					</ol>
			</div>
		<div id="top_r">
			<?php if ($this->caching && !$this->_cache_including): echo '{nocache:48245b5c51f9cbd79fc14dfc3f32df63#0}'; endif;$this->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php if ($this->_tpl_vars['user_name'] && $this->_tpl_vars['active'] == 0): ?>
			<div id="userinfo">
					<h3>用户登录</h3>
					<div class="welcome">你好：<strong><?php echo $this->_tpl_vars['user_name']; ?>
</strong>，欢迎登录 </div>
					<div class="userface">
						<a href=""><img width="64" height="64" src="<?php if ($this->_tpl_vars['face_pic']): ?><?php echo $this->_tpl_vars['face_pic']; ?>
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
			<?php elseif ($this->_tpl_vars['active'] == 1): ?>
			<div id="userinfo">
			    <div class="welcome">你好：<strong><?php echo $this->_tpl_vars['user_name']; ?>
</strong>，欢迎登录 </div>
			    <div class="userface">
			        <a href=""><img width="64" height="64" src="<?php if ($this->_tpl_vars['face_pic']): ?><?php echo $this->_tpl_vars['face_pic']; ?>
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
			<?php endif; ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:48245b5c51f9cbd79fc14dfc3f32df63#0}'; endif;?>
			<div id="ann">
				<div class="title1" ><a href="<?php echo url_rewrite('ann', array()); ?>">网站公告</a></div>
				<div class="content1">
					<ul><?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['ann_arr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<li><a href="ann.php?ann_id=<?php echo $this->_tpl_vars['ann_arr'][$this->_sections['a']['index']]['ann_id']; ?>
" <?php if ($this->_tpl_vars['ann_arr'][$this->_sections['a']['index']]['color']): ?>style="color:<?php echo $this->_tpl_vars['ann_arr'][$this->_sections['a']['index']]['color']; ?>
;"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['ann_arr'][$this->_sections['a']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 20) : smarty_modifier_mb_substr($_tmp, 0, 20)); ?>
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
			<div id="help">
				<div class="help_tab">
					<ul><li id="h1" class="current"><a href="ann.php?cid=3"><span>帮助中心</span></a></li><li id="h2" class=""><a href="ann.php?cid=2"><span>付费推广</span></a></li></ul>
				</div>
				<div class="help_content">
					<ul id="hc_1" style="display:block;"><?php unset($this->_sections['h']);
$this->_sections['h']['name'] = 'h';
$this->_sections['h']['loop'] = is_array($_loop=$this->_tpl_vars['help_arr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<li><a href="ann.php?ann_id=<?php echo $this->_tpl_vars['help_arr'][$this->_sections['h']['index']]['ann_id']; ?>
"><?php echo $this->_tpl_vars['help_arr'][$this->_sections['h']['index']]['title']; ?>
</a></li>
						<?php endfor; endif; ?>
					</ul>
					<ul id="hc_2" style="display:none;"><?php unset($this->_sections['s']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['loop'] = is_array($_loop=$this->_tpl_vars['service_arr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
    $this->_sections['s']['total'] = $this->_sections['s']['loop'];
    if ($this->_sections['s']['total'] == 0)
        $this->_sections['s']['show'] = false;
} else
    $this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

            for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
                 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
                 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']      = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']       = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>
						<li><a href="ann.php?ann_id=<?php echo $this->_tpl_vars['service_arr'][$this->_sections['s']['index']]['ann_id']; ?>
"><?php echo $this->_tpl_vars['service_arr'][$this->_sections['s']['index']]['title']; ?>
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
		<div class="clear"></div>
	</div>
	<div id="content">
		<div id="channel">
			<div id="c_l">
			<div id="news">
					<div class="channel_title">
						<span class="n_i"></span>
						<strong>新闻分类</strong>
						<?php unset($this->_sections['c']);
$this->_sections['c']['name'] = 'c';
$this->_sections['c']['loop'] = is_array($_loop=$this->_tpl_vars['news_cat']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['c']['show'] = true;
$this->_sections['c']['max'] = $this->_sections['c']['loop'];
$this->_sections['c']['step'] = 1;
$this->_sections['c']['start'] = $this->_sections['c']['step'] > 0 ? 0 : $this->_sections['c']['loop']-1;
if ($this->_sections['c']['show']) {
    $this->_sections['c']['total'] = $this->_sections['c']['loop'];
    if ($this->_sections['c']['total'] == 0)
        $this->_sections['c']['show'] = false;
} else
    $this->_sections['c']['total'] = 0;
if ($this->_sections['c']['show']):

            for ($this->_sections['c']['index'] = $this->_sections['c']['start'], $this->_sections['c']['iteration'] = 1;
                 $this->_sections['c']['iteration'] <= $this->_sections['c']['total'];
                 $this->_sections['c']['index'] += $this->_sections['c']['step'], $this->_sections['c']['iteration']++):
$this->_sections['c']['rownum'] = $this->_sections['c']['iteration'];
$this->_sections['c']['index_prev'] = $this->_sections['c']['index'] - $this->_sections['c']['step'];
$this->_sections['c']['index_next'] = $this->_sections['c']['index'] + $this->_sections['c']['step'];
$this->_sections['c']['first']      = ($this->_sections['c']['iteration'] == 1);
$this->_sections['c']['last']       = ($this->_sections['c']['iteration'] == $this->_sections['c']['total']);
?>
						<?php if ($this->_sections['c']['index'] == 0): ?>
						<a href="<?php echo $this->_tpl_vars['news_cat'][$this->_sections['c']['index']]['url']; ?>
" target="_blank" id="<?php echo $this->_tpl_vars['news_cat'][$this->_sections['c']['index']]['cat_id']; ?>
" onmouseover="show(this.id);"><?php echo $this->_tpl_vars['news_cat'][$this->_sections['c']['index']]['cat_name']; ?>
</a>
						<?php else: ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['news_cat'][$this->_sections['c']['index']]['url']; ?>
" target="_blank"  id="<?php echo $this->_tpl_vars['news_cat'][$this->_sections['c']['index']]['cat_id']; ?>
" onmouseover="show(this.id);"><?php echo $this->_tpl_vars['news_cat'][$this->_sections['c']['index']]['cat_name']; ?>
</a><?php endif; ?>
						<?php endfor; endif; ?>
					</div>
			<?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['news_arr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<div class="news_cat<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['cat_id']; ?>
" style="display:<?php if ($this->_sections['n']['index'] == 0): ?>block;<?php else: ?>none;<?php endif; ?>">
					<!--<div class="channel_title">
						<span class="n_i"></span>
						<span class="right"><a href="<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['url']; ?>
">更多</a></span>
						<strong><?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['cat_name']; ?>
</strong>
					</div>-->
					<div class="rec_news">
						<a href="<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['f_r_news']['url']; ?>
" target="_blank"><?php if ($this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['f_r_news']['lit_pic']): ?><img style="width:200px;height:115px;" src="<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['f_r_news']['photo']; ?>
<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['f_r_news']['lit_pic']; ?>
" border="0" /><?php endif; ?></a>
						<ul><?php unset($this->_sections['r']);
$this->_sections['r']['name'] = 'r';
$this->_sections['r']['loop'] = is_array($_loop=$this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['rec_news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['r']['show'] = true;
$this->_sections['r']['max'] = $this->_sections['r']['loop'];
$this->_sections['r']['step'] = 1;
$this->_sections['r']['start'] = $this->_sections['r']['step'] > 0 ? 0 : $this->_sections['r']['loop']-1;
if ($this->_sections['r']['show']) {
    $this->_sections['r']['total'] = $this->_sections['r']['loop'];
    if ($this->_sections['r']['total'] == 0)
        $this->_sections['r']['show'] = false;
} else
    $this->_sections['r']['total'] = 0;
if ($this->_sections['r']['show']):

            for ($this->_sections['r']['index'] = $this->_sections['r']['start'], $this->_sections['r']['iteration'] = 1;
                 $this->_sections['r']['iteration'] <= $this->_sections['r']['total'];
                 $this->_sections['r']['index'] += $this->_sections['r']['step'], $this->_sections['r']['iteration']++):
$this->_sections['r']['rownum'] = $this->_sections['r']['iteration'];
$this->_sections['r']['index_prev'] = $this->_sections['r']['index'] - $this->_sections['r']['step'];
$this->_sections['r']['index_next'] = $this->_sections['r']['index'] + $this->_sections['r']['step'];
$this->_sections['r']['first']      = ($this->_sections['r']['iteration'] == 1);
$this->_sections['r']['last']       = ($this->_sections['r']['iteration'] == $this->_sections['r']['total']);
?>
							<li><a href="<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['rec_news'][$this->_sections['r']['index']]['url']; ?>
" target="_blank"><?php echo ((is_array($_tmp=$this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['rec_news'][$this->_sections['r']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 17) : smarty_modifier_mb_substr($_tmp, 0, 17)); ?>
</a></li>
							<?php endfor; endif; ?>
						</ul>
					</div>
					<div class="latest_news">
						<a href="<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['f_l_news']['url']; ?>
" target="_blank"><h2><?php echo ((is_array($_tmp=$this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['f_l_news']['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 18) : smarty_modifier_mb_substr($_tmp, 0, 18)); ?>
</h2></a>
						<p><?php echo ((is_array($_tmp=$this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['f_l_news']['descript'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 70) : smarty_modifier_mb_substr($_tmp, 0, 70)); ?>
</p>
						<ul><?php unset($this->_sections['l']);
$this->_sections['l']['name'] = 'l';
$this->_sections['l']['loop'] = is_array($_loop=$this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['latest_news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['l']['show'] = true;
$this->_sections['l']['max'] = $this->_sections['l']['loop'];
$this->_sections['l']['step'] = 1;
$this->_sections['l']['start'] = $this->_sections['l']['step'] > 0 ? 0 : $this->_sections['l']['loop']-1;
if ($this->_sections['l']['show']) {
    $this->_sections['l']['total'] = $this->_sections['l']['loop'];
    if ($this->_sections['l']['total'] == 0)
        $this->_sections['l']['show'] = false;
} else
    $this->_sections['l']['total'] = 0;
if ($this->_sections['l']['show']):

            for ($this->_sections['l']['index'] = $this->_sections['l']['start'], $this->_sections['l']['iteration'] = 1;
                 $this->_sections['l']['iteration'] <= $this->_sections['l']['total'];
                 $this->_sections['l']['index'] += $this->_sections['l']['step'], $this->_sections['l']['iteration']++):
$this->_sections['l']['rownum'] = $this->_sections['l']['iteration'];
$this->_sections['l']['index_prev'] = $this->_sections['l']['index'] - $this->_sections['l']['step'];
$this->_sections['l']['index_next'] = $this->_sections['l']['index'] + $this->_sections['l']['step'];
$this->_sections['l']['first']      = ($this->_sections['l']['iteration'] == 1);
$this->_sections['l']['last']       = ($this->_sections['l']['iteration'] == $this->_sections['l']['total']);
?>
							<li><a href="<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['latest_news'][$this->_sections['l']['index']]['url']; ?>
" target="_blank"><?php echo ((is_array($_tmp=$this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['latest_news'][$this->_sections['l']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 17) : smarty_modifier_mb_substr($_tmp, 0, 17)); ?>
</a></li>
							<?php endfor; endif; ?>
						</ul>
						<ol><?php unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($_loop=$this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['photo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><li><a href="<?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['photo'][$this->_sections['p']['index']]['url']; ?>
" target="_blank"><img src="<?php if ($this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['photo'][$this->_sections['p']['index']]['lit_pic']): ?><?php echo $this->_tpl_vars['news_arr'][$this->_sections['n']['index']]['photo'][$this->_sections['p']['index']]['lit_pic']; ?>
<?php else: ?>templates/default/images/defaultpic.gif<?php endif; ?>" border="0" /></a></li><?php endfor; endif; ?></ol>
					</div>
					<div class="clear"></div>
				</div><?php endfor; endif; ?>
				</div>
				<div class="info">
					<div class="channel_title">
						<span class="n_i"></span>
						<strong>分类信息</strong>
						<?php unset($this->_sections['c']);
$this->_sections['c']['name'] = 'c';
$this->_sections['c']['loop'] = is_array($_loop=$this->_tpl_vars['info_cat']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['c']['show'] = true;
$this->_sections['c']['max'] = $this->_sections['c']['loop'];
$this->_sections['c']['step'] = 1;
$this->_sections['c']['start'] = $this->_sections['c']['step'] > 0 ? 0 : $this->_sections['c']['loop']-1;
if ($this->_sections['c']['show']) {
    $this->_sections['c']['total'] = $this->_sections['c']['loop'];
    if ($this->_sections['c']['total'] == 0)
        $this->_sections['c']['show'] = false;
} else
    $this->_sections['c']['total'] = 0;
if ($this->_sections['c']['show']):

            for ($this->_sections['c']['index'] = $this->_sections['c']['start'], $this->_sections['c']['iteration'] = 1;
                 $this->_sections['c']['iteration'] <= $this->_sections['c']['total'];
                 $this->_sections['c']['index'] += $this->_sections['c']['step'], $this->_sections['c']['iteration']++):
$this->_sections['c']['rownum'] = $this->_sections['c']['iteration'];
$this->_sections['c']['index_prev'] = $this->_sections['c']['index'] - $this->_sections['c']['step'];
$this->_sections['c']['index_next'] = $this->_sections['c']['index'] + $this->_sections['c']['step'];
$this->_sections['c']['first']      = ($this->_sections['c']['iteration'] == 1);
$this->_sections['c']['last']       = ($this->_sections['c']['iteration'] == $this->_sections['c']['total']);
?>
						<?php if ($this->_sections['c']['index'] == 0): ?>
						<a href="<?php echo $this->_tpl_vars['info_cat'][$this->_sections['c']['index']]['url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['info_cat'][$this->_sections['c']['index']]['cat_name']; ?>
</a>
						<?php else: ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['info_cat'][$this->_sections['c']['index']]['url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['info_cat'][$this->_sections['c']['index']]['cat_name']; ?>
</a><?php endif; ?>
						<?php endfor; endif; ?>
					</div>
					<div id="latest_info">
						<a href="<?php echo $this->_tpl_vars['f_info']['url']; ?>
" target="_blank">
							<h2><?php echo ((is_array($_tmp=$this->_tpl_vars['f_info']['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 13) : smarty_modifier_mb_substr($_tmp, 0, 13)); ?>
</h2>
						</a>
						<ol><?php unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($_loop=$this->_tpl_vars['info_photo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
							<li><a href="<?php echo $this->_tpl_vars['info_photo'][$this->_sections['p']['index']]['url']; ?>
" target="_blank">
									<img src="<?php if ($this->_tpl_vars['info_photo'][$this->_sections['p']['index']]['lit_pic']): ?><?php echo $this->_tpl_vars['info_photo'][$this->_sections['p']['index']]['lit_pic']; ?>

									<?php else: ?>templates/default/images/defaultpic.gif<?php endif; ?>" border="0" />
									<?php echo ((is_array($_tmp=$this->_tpl_vars['info_photo'][$this->_sections['p']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 6) : smarty_modifier_mb_substr($_tmp, 0, 6)); ?>
</a>
							</li><?php endfor; endif; ?>
						</ol>
						<ul><?php unset($this->_sections['l']);
$this->_sections['l']['name'] = 'l';
$this->_sections['l']['loop'] = is_array($_loop=$this->_tpl_vars['info_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['l']['show'] = true;
$this->_sections['l']['max'] = $this->_sections['l']['loop'];
$this->_sections['l']['step'] = 1;
$this->_sections['l']['start'] = $this->_sections['l']['step'] > 0 ? 0 : $this->_sections['l']['loop']-1;
if ($this->_sections['l']['show']) {
    $this->_sections['l']['total'] = $this->_sections['l']['loop'];
    if ($this->_sections['l']['total'] == 0)
        $this->_sections['l']['show'] = false;
} else
    $this->_sections['l']['total'] = 0;
if ($this->_sections['l']['show']):

            for ($this->_sections['l']['index'] = $this->_sections['l']['start'], $this->_sections['l']['iteration'] = 1;
                 $this->_sections['l']['iteration'] <= $this->_sections['l']['total'];
                 $this->_sections['l']['index'] += $this->_sections['l']['step'], $this->_sections['l']['iteration']++):
$this->_sections['l']['rownum'] = $this->_sections['l']['iteration'];
$this->_sections['l']['index_prev'] = $this->_sections['l']['index'] - $this->_sections['l']['step'];
$this->_sections['l']['index_next'] = $this->_sections['l']['index'] + $this->_sections['l']['step'];
$this->_sections['l']['first']      = ($this->_sections['l']['iteration'] == 1);
$this->_sections['l']['last']       = ($this->_sections['l']['iteration'] == $this->_sections['l']['total']);
?>
							<li><a href="<?php echo $this->_tpl_vars['info_list'][$this->_sections['l']['index']]['url']; ?>
" target="_blank">
							<?php echo ((is_array($_tmp=$this->_tpl_vars['info_list'][$this->_sections['l']['index']]['title'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 17) : smarty_modifier_mb_substr($_tmp, 0, 17)); ?>
</a>
							</li><?php endfor; endif; ?>
						</ul>
					</div>
					<div id="rec_info">
						<ul><?php unset($this->_sections['r']);
$this->_sections['r']['name'] = 'r';
$this->_sections['r']['loop'] = is_array($_loop=$this->_tpl_vars['rec_info_p']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['r']['show'] = true;
$this->_sections['r']['max'] = $this->_sections['r']['loop'];
$this->_sections['r']['step'] = 1;
$this->_sections['r']['start'] = $this->_sections['r']['step'] > 0 ? 0 : $this->_sections['r']['loop']-1;
if ($this->_sections['r']['show']) {
    $this->_sections['r']['total'] = $this->_sections['r']['loop'];
    if ($this->_sections['r']['total'] == 0)
        $this->_sections['r']['show'] = false;
} else
    $this->_sections['r']['total'] = 0;
if ($this->_sections['r']['show']):

            for ($this->_sections['r']['index'] = $this->_sections['r']['start'], $this->_sections['r']['iteration'] = 1;
                 $this->_sections['r']['iteration'] <= $this->_sections['r']['total'];
                 $this->_sections['r']['index'] += $this->_sections['r']['step'], $this->_sections['r']['iteration']++):
$this->_sections['r']['rownum'] = $this->_sections['r']['iteration'];
$this->_sections['r']['index_prev'] = $this->_sections['r']['index'] - $this->_sections['r']['step'];
$this->_sections['r']['index_next'] = $this->_sections['r']['index'] + $this->_sections['r']['step'];
$this->_sections['r']['first']      = ($this->_sections['r']['iteration'] == 1);
$this->_sections['r']['last']       = ($this->_sections['r']['iteration'] == $this->_sections['r']['total']);
?>
							<li><a href="<?php echo $this->_tpl_vars['rec_info_p'][$this->_sections['r']['index']]['url']; ?>
" target="_blank">
							<img src="<?php echo $this->_tpl_vars['rec_info_p'][$this->_sections['r']['index']]['lit_pic']; ?>
" border="0" /></a></li><?php endfor; endif; ?>
						</ul>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="c_r">
				<div class="rec_info">
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
				<div id="kefu">
					<span id="g_btn"><a class="guest_btn" href="guest_book.php" target="_blank">留言建议</a></span>
					<div id="kf_content">
						<?php $_from = $this->_tpl_vars['tel']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
							<img src="templates/default/images/tel.gif" border="0" />&nbsp;热线电话：<?php echo $this->_tpl_vars['t']; ?>
<br/><?php endforeach; endif; unset($_from); ?>
							<?php $_from = $this->_tpl_vars['qq']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['q1']):
?>
							<img src="templates/default/images/qq_online.gif" border="0" />&nbsp;客服QQ：<a href="tencent://message/?uin=<?php echo $this->_tpl_vars['q1']; ?>
&amp;Site=&amp;Menu=yes"><?php echo $this->_tpl_vars['q1']; ?>
</a><br/><?php endforeach; endif; unset($_from); ?>
							<?php $_from = $this->_tpl_vars['qq_group']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['q2']):
?>
							<img src="templates/default/images/qq_group.gif" border="0" />&nbsp;QQ群：<?php echo $this->_tpl_vars['q2']; ?>
<br/><?php endforeach; endif; unset($_from); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="clear"></div>
	<div class="telad">
		<div class="title">
			<dl>
			<dt>常用便民电话</dt>
			<dd>每行业限一家，欲购从速！</dd>
			</dl>
		</div>
		<div class="teladl">
			<ul>
			<?php unset($this->_sections['ad_phone']);
$this->_sections['ad_phone']['name'] = 'ad_phone';
$this->_sections['ad_phone']['loop'] = is_array($_loop=$this->_tpl_vars['ad_phone_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ad_phone']['show'] = true;
$this->_sections['ad_phone']['max'] = $this->_sections['ad_phone']['loop'];
$this->_sections['ad_phone']['step'] = 1;
$this->_sections['ad_phone']['start'] = $this->_sections['ad_phone']['step'] > 0 ? 0 : $this->_sections['ad_phone']['loop']-1;
if ($this->_sections['ad_phone']['show']) {
    $this->_sections['ad_phone']['total'] = $this->_sections['ad_phone']['loop'];
    if ($this->_sections['ad_phone']['total'] == 0)
        $this->_sections['ad_phone']['show'] = false;
} else
    $this->_sections['ad_phone']['total'] = 0;
if ($this->_sections['ad_phone']['show']):

            for ($this->_sections['ad_phone']['index'] = $this->_sections['ad_phone']['start'], $this->_sections['ad_phone']['iteration'] = 1;
                 $this->_sections['ad_phone']['iteration'] <= $this->_sections['ad_phone']['total'];
                 $this->_sections['ad_phone']['index'] += $this->_sections['ad_phone']['step'], $this->_sections['ad_phone']['iteration']++):
$this->_sections['ad_phone']['rownum'] = $this->_sections['ad_phone']['iteration'];
$this->_sections['ad_phone']['index_prev'] = $this->_sections['ad_phone']['index'] - $this->_sections['ad_phone']['step'];
$this->_sections['ad_phone']['index_next'] = $this->_sections['ad_phone']['index'] + $this->_sections['ad_phone']['step'];
$this->_sections['ad_phone']['first']      = ($this->_sections['ad_phone']['iteration'] == 1);
$this->_sections['ad_phone']['last']       = ($this->_sections['ad_phone']['iteration'] == $this->_sections['ad_phone']['total']);
?>
			<li <?php if ($this->_tpl_vars['ad_phone_list'][$this->_sections['ad_phone']['index']]['color']): ?>style="color:<?php echo $this->_tpl_vars['ad_phone_list'][$this->_sections['ad_phone']['index']]['color']; ?>
"<?php endif; ?>><a title="<?php echo $this->_tpl_vars['ad_phone_list'][$this->_sections['ad_phone']['index']]['title']; ?>
"><?php echo $this->_tpl_vars['ad_phone_list'][$this->_sections['ad_phone']['index']]['content']; ?>
</a></li>
			<?php endfor; endif; ?>
			</ul>
		</div>
	</div>
	<div class="clears"></div>
	<div class="link">
		<div class="title">
			<dl>
			<dt>友情链接</dt>
			<dd>

			</dd>
			</dl>
		</div>
		<div class="content">
			<div class="text">
				<?php unset($this->_sections['link']);
$this->_sections['link']['name'] = 'link';
$this->_sections['link']['loop'] = is_array($_loop=$this->_tpl_vars['link_list_text']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['link']['show'] = true;
$this->_sections['link']['max'] = $this->_sections['link']['loop'];
$this->_sections['link']['step'] = 1;
$this->_sections['link']['start'] = $this->_sections['link']['step'] > 0 ? 0 : $this->_sections['link']['loop']-1;
if ($this->_sections['link']['show']) {
    $this->_sections['link']['total'] = $this->_sections['link']['loop'];
    if ($this->_sections['link']['total'] == 0)
        $this->_sections['link']['show'] = false;
} else
    $this->_sections['link']['total'] = 0;
if ($this->_sections['link']['show']):

            for ($this->_sections['link']['index'] = $this->_sections['link']['start'], $this->_sections['link']['iteration'] = 1;
                 $this->_sections['link']['iteration'] <= $this->_sections['link']['total'];
                 $this->_sections['link']['index'] += $this->_sections['link']['step'], $this->_sections['link']['iteration']++):
$this->_sections['link']['rownum'] = $this->_sections['link']['iteration'];
$this->_sections['link']['index_prev'] = $this->_sections['link']['index'] - $this->_sections['link']['step'];
$this->_sections['link']['index_next'] = $this->_sections['link']['index'] + $this->_sections['link']['step'];
$this->_sections['link']['first']      = ($this->_sections['link']['iteration'] == 1);
$this->_sections['link']['last']       = ($this->_sections['link']['iteration'] == $this->_sections['link']['total']);
?>
				<a href="<?php echo $this->_tpl_vars['link_list_text'][$this->_sections['link']['index']]['linksite']; ?>
" target="_blank"><?php echo $this->_tpl_vars['link_list_text'][$this->_sections['link']['index']]['linkname']; ?>
</a>
				<?php endfor; endif; ?>
			</div>
			<div class="img"><ul>
				<?php unset($this->_sections['link']);
$this->_sections['link']['name'] = 'link';
$this->_sections['link']['loop'] = is_array($_loop=$this->_tpl_vars['link_list_img']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['link']['show'] = true;
$this->_sections['link']['max'] = $this->_sections['link']['loop'];
$this->_sections['link']['step'] = 1;
$this->_sections['link']['start'] = $this->_sections['link']['step'] > 0 ? 0 : $this->_sections['link']['loop']-1;
if ($this->_sections['link']['show']) {
    $this->_sections['link']['total'] = $this->_sections['link']['loop'];
    if ($this->_sections['link']['total'] == 0)
        $this->_sections['link']['show'] = false;
} else
    $this->_sections['link']['total'] = 0;
if ($this->_sections['link']['show']):

            for ($this->_sections['link']['index'] = $this->_sections['link']['start'], $this->_sections['link']['iteration'] = 1;
                 $this->_sections['link']['iteration'] <= $this->_sections['link']['total'];
                 $this->_sections['link']['index'] += $this->_sections['link']['step'], $this->_sections['link']['iteration']++):
$this->_sections['link']['rownum'] = $this->_sections['link']['iteration'];
$this->_sections['link']['index_prev'] = $this->_sections['link']['index'] - $this->_sections['link']['step'];
$this->_sections['link']['index_next'] = $this->_sections['link']['index'] + $this->_sections['link']['step'];
$this->_sections['link']['first']      = ($this->_sections['link']['iteration'] == 1);
$this->_sections['link']['last']       = ($this->_sections['link']['iteration'] == $this->_sections['link']['total']);
?>
				<li><a href="<?php echo $this->_tpl_vars['link_list_img'][$this->_sections['link']['index']]['linksite']; ?>
" title="<?php echo $this->_tpl_vars['link_list_img'][$this->_sections['link']['index']]['linkname']; ?>
" target="_blank" class="imglink">
				<img src="<?php echo $this->_tpl_vars['link_list_img'][$this->_sections['link']['index']]['linklogo']; ?>
" alt="<?php echo $this->_tpl_vars['link_list_img'][$this->_sections['link']['index']]['linkname']; ?>
" width="88" height="31" border="0" /></a></li>
				<?php endfor; endif; ?>
			</div>
			<div class="clear"></div>
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