<?php /* Smarty version 2.6.22, created on 2018-11-20 11:05:10
         compiled from info_add1.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script src="css/jquery.js" type="text/javascript"></script>
<script src="css/publish.js" type="text/javascript"></script>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<div><?php if ($this->_tpl_vars['cat_list']): ?><?php echo $this->_tpl_vars['cat_list']; ?>
<?php else: ?>您还没有创建可用的栏目<?php endif; ?></div>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>