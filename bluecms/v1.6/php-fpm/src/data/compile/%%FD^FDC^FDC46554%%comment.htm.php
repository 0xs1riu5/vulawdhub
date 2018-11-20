<?php /* Smarty version 2.6.22, created on 2018-11-20 12:25:48
         compiled from comment.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'comment.htm', 25, false),array('modifier', 'mb_substr', 'comment.htm', 26, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<meta name="Keywords" content="<?php echo $this->_tpl_vars['cat_info']['keywords']; ?>
" />
<meta name="Description" content="<?php echo $this->_tpl_vars['cat_info']['description']; ?>
" />
<title><?php echo $this->_tpl_vars['title']['name']; ?>
 - <?php echo $this->_tpl_vars['site_name']; ?>
 - Powered by BlueCMS</title>
<link href="templates/default/css/category.css" rel="stylesheet" type="text/css" />
<link href="templates/default/css/common.css" rel="stylesheet" type="text/css" />
<script src="templates/default/css/jquery.js" type="text/javascript"></script>
<link rel="shortcut icon" href="images/favicon.ico" />
</head>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="wapper">
	<div class="active_act">
		您所在的位置：<a href="./">首页</a> &raquo; 评论列表
	</div>
	<div class="main">
		<h3>评论标题：<a href="<?php echo $this->_tpl_vars['title']['url']; ?>
"><?php echo $this->_tpl_vars['title']['name']; ?>
</a></h3>
		<div id="comment_list">
			<div class="title1">评论列表</div>
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
			</div>
		</div>
		<div id="send_comment">
			<div class="title1">评论</div><form action="comment.php?act=send" name="comment_form" method="post">
			<div class="content1">
				<div><?php if ($this->_tpl_vars['user_name']): ?>您好，<a href="user.php"><?php echo $this->_tpl_vars['user_name']; ?>
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
					<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['title']['post_id']; ?>
" />
					<input type="hidden" name="type" value="<?php echo $this->_tpl_vars['type']; ?>
" />
					<input type="submit" name="submit" value="提交评论" />
				</div></form>
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