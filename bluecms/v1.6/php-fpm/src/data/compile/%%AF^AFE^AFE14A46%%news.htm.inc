<?php /* Smarty version 2.6.22, created on 2018-11-20 12:17:48
         compiled from news.htm */

$this->_cache_serials['/www/data/compile//%%AF^AFE^AFE14A46%%news.htm.inc'] = 'ebc4e3b58dd9b7260586b9f1e38aacf4';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'news.htm', 31, false),array('modifier', 'mb_substr', 'news.htm', 40, false),array('block', 'dynamic', 'news.htm', 31, false),)), $this); ?>
<?php
function _smarty_tplfunc_ebc4e3b58dd9b7260586b9f1e38aacf4_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?><span id="click">点击：<?php echo $_smarty->_tpl_vars['news']['click']; ?>
</span><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}


function _smarty_tplfunc_ebc4e3b58dd9b7260586b9f1e38aacf4_1(&$_smarty)
{
$_smarty->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?><div class="comment"><form action="comment.php?act=send" name="comment_form" method="post">
		<div class="title1">评论</div>
		<div id="com_count"><?php if ($_smarty->_tpl_vars['news']['comment'] == 0): ?>共有 0 条评论<?php else: ?><a href="comment.php?id=<?php echo $_smarty->_tpl_vars['news']['id']; ?>
&type=1">共有<?php echo $_smarty->_tpl_vars['news']['comment']; ?>
条评论</a><?php endif; ?></div>
		<div class="content1"><?php unset($_smarty->_sections['com']);
$_smarty->_sections['com']['name'] = 'com';
$_smarty->_sections['com']['loop'] = is_array($_loop=$_smarty->_tpl_vars['comment_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty->_sections['com']['show'] = true;
$_smarty->_sections['com']['max'] = $_smarty->_sections['com']['loop'];
$_smarty->_sections['com']['step'] = 1;
$_smarty->_sections['com']['start'] = $_smarty->_sections['com']['step'] > 0 ? 0 : $_smarty->_sections['com']['loop']-1;
if ($_smarty->_sections['com']['show']) {
    $_smarty->_sections['com']['total'] = $_smarty->_sections['com']['loop'];
    if ($_smarty->_sections['com']['total'] == 0)
        $_smarty->_sections['com']['show'] = false;
} else
    $_smarty->_sections['com']['total'] = 0;
if ($_smarty->_sections['com']['show']):

            for ($_smarty->_sections['com']['index'] = $_smarty->_sections['com']['start'], $_smarty->_sections['com']['iteration'] = 1;
                 $_smarty->_sections['com']['iteration'] <= $_smarty->_sections['com']['total'];
                 $_smarty->_sections['com']['index'] += $_smarty->_sections['com']['step'], $_smarty->_sections['com']['iteration']++):
$_smarty->_sections['com']['rownum'] = $_smarty->_sections['com']['iteration'];
$_smarty->_sections['com']['index_prev'] = $_smarty->_sections['com']['index'] - $_smarty->_sections['com']['step'];
$_smarty->_sections['com']['index_next'] = $_smarty->_sections['com']['index'] + $_smarty->_sections['com']['step'];
$_smarty->_sections['com']['first']      = ($_smarty->_sections['com']['iteration'] == 1);
$_smarty->_sections['com']['last']       = ($_smarty->_sections['com']['iteration'] == $_smarty->_sections['com']['total']);
?>
				<div class="com">
					<div class="com_title"><img src="images/mood/mood-<?php echo $_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['mood']; ?>
.gif" border="0" />&nbsp;<?php if ($_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['user_id'] <> 0): ?><?php echo $_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['user_name']; ?>
<?php else: ?>游客<?php endif; ?>&nbsp;于<?php echo ((is_array($_tmp=$_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['pub_date'])) ? $_smarty->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
&nbsp;说到：</div>
						<div class="com_content" id='c_content<?php echo $_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['com_id']; ?>
' onclick="show_detail(<?php echo $_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['com_id']; ?>
);"><?php echo ((is_array($_tmp=$_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['content'])) ? $_smarty->_run_mod_handler('mb_substr', true, $_tmp, 0, 50) : smarty_modifier_mb_substr($_tmp, 0, 50)); ?>
</div>
					<div style="display:none;cursor:hand;" class="com_content" id='com_detail<?php echo $_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['com_id']; ?>
' onclick="show_detail(<?php echo $_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['com_id']; ?>
);"><?php echo $_smarty->_tpl_vars['comment_list'][$_smarty->_sections['com']['index']]['content']; ?>
</div>
				</div><?php endfor; endif; ?>
			<div id="send_comment1"><?php if ($_smarty->_tpl_vars['user_name']): ?>您好，<a href="user.php"><?php echo $_smarty->_tpl_vars['user_name']; ?>
</a><?php else: ?>您现在是匿名发表！ <a href="user.php?act=login&from=<?php echo $_smarty->_tpl_vars['url']; ?>
">登录</a>&nbsp;|&nbsp;<a href="user.php?act=reg&from=<?php echo $_smarty->_tpl_vars['url']; ?>
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
				<input type="hidden" name="id" value="<?php echo $_smarty->_tpl_vars['news']['id']; ?>
" />
				<input type="hidden" name="type" value="1" />
				<input type="submit" name="submit" value="提交评论" />
			</div>
		</div></form>
	</div><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
