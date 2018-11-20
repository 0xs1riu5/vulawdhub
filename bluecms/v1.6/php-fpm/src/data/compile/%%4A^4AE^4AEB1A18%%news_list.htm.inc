<?php /* Smarty version 2.6.22, created on 2018-11-20 09:49:37
         compiled from news_list.htm */

$this->_cache_serials['/www/data/compile//%%4A^4AE^4AEB1A18%%news_list.htm.inc'] = '9a90be255c5638bb1f790612ebc891ee';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'news_list.htm', 31, false),array('modifier', 'date_format', 'news_list.htm', 37, false),array('block', 'dynamic', 'news_list.htm', 38, false),)), $this); ?>
<?php
function _smarty_tplfunc_9a90be255c5638bb1f790612ebc891ee_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?>╣Ц╩Вё╨<span><?php echo $_smarty->_tpl_vars['news_list'][$_smarty->_sections['news']['index']]['click']; ?>
</span>
							фюбшё╨<span><?php echo $_smarty->_tpl_vars['news_list'][$_smarty->_sections['news']['index']]['comment']; ?>
</span><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
