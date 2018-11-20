<?php /* Smarty version 2.6.22, created on 2018-11-20 12:14:31
         compiled from info_index.htm */

$this->_cache_serials['/www/data/compile//%%EE^EEC^EEC39276%%info_index.htm.inc'] = 'dd40bf32419e6b1234f01f54b706e70d';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'info_index.htm', 31, false),array('block', 'dynamic', 'info_index.htm', 84, false),)), $this); ?>
<?php
function _smarty_tplfunc_dd40bf32419e6b1234f01f54b706e70d_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('dynamic', array()); $_block_repeat=true;smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $_smarty->_tpl_vars['index_info']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_dynamic($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
